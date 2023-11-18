<?php

namespace App\Filament\Resources;

use App\Enums\AchievementTypeEnum;
use App\Enums\CoinRequestTypeEnum;
use App\Enums\StatusEnum;
use App\Filament\Resources\CoinRequestResource\Pages;
use App\Models\CoinRequest;
use App\Models\Status;
use App\Models\User;
use Carbon\Carbon;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Filters\Indicator;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\DB;

class CoinRequestResource extends Resource
{
    protected static ?string $model = CoinRequest::class;

    protected static ?string $navigationIcon = 'heroicon-o-currency-dollar';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('type')
                    ->options(CoinRequestTypeEnum::getSelectBoxFilamentItems())
                    ->reactive()
                    ->required(),
                Forms\Components\TextInput::make('wallet_address')
                    ->nullable()
                    ->hidden(function (Get $get) {
                        $type = CoinRequestTypeEnum::tryFrom($get('type'));
                        $isHidden = $type != CoinRequestTypeEnum::SELL;

                        return $isHidden;
                    })
                    ->maxLength('255'),
                Forms\Components\TextInput::make('count')
                    ->required()
                    ->numeric(),
                Forms\Components\DateTimePicker::make('requested_at'),
                Forms\Components\Select::make('status_id')
                    ->relationship('coinRequestStatus', 'name')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        $statusAccepted = Status::nameScope(StatusEnum::ACCEPTED->value)->firstOrFail();

        return $table
            ->columns([
                Tables\Columns\TextColumn::make('createdByUser.profile.avatar_name')
                    ->sortable(),
                Tables\Columns\TextColumn::make('count')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('type')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'buy' => 'success',
                        'sell' => 'danger',
                    })
                    ->sortable(),
                Tables\Columns\TextColumn::make('requested_at')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('coinRequestStatus.name')
                    ->sortable(),
                Tables\Columns\TextColumn::make('deleted_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('id', 'desc')
            ->filters([
                Tables\Filters\TrashedFilter::make(),
                Tables\Filters\SelectFilter::make('type')
                    ->options(CoinRequestTypeEnum::getSelectBoxFilamentItems()),
                Tables\Filters\Filter::make('avatar_name')
                    ->form([TextInput::make('avatar_name')])
                    ->indicator('Administrators')
                    ->query(function (Builder $query, array $data) {
                        return $query->whereHas('createdByUser', fn ($q) => $q->searchProfileAvatarNameScope($data['avatar_name']));
                    })
                    ->indicateUsing(function (array $data): ?string {
                        if (! $data['avatar_name']) {
                            return null;
                        }

                        return 'avatar name: '.$data['avatar_name'];
                    }),
                Tables\Filters\Filter::make('requested_at')
                    ->form([
                        DatePicker::make('requested_from'),
                        DatePicker::make('requested_until'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['requested_from'],
                                fn (Builder $query, $date): Builder => $query->whereDate('requested_at', '>=', $date),
                            )
                            ->when(
                                $data['requested_until'],
                                fn (Builder $query, $date): Builder => $query->whereDate('requested_at', '<=', $date),
                            );
                    })
                    ->indicateUsing(function (array $data): array {
                        $indicators = [];
                        if ($data['requested_from'] ?? null) {
                            $indicators[] = Indicator::make('requested from '.Carbon::parse($data['requested_from'])->toFormattedDateString())
                                ->removeField('from');
                        }

                        if ($data['requested_until'] ?? null) {
                            $indicators[] = Indicator::make('requested until '.Carbon::parse($data['requested_until'])->toFormattedDateString())
                                ->removeField('until');
                        }

                        return $indicators;
                    }),
            ])
            ->actions([
                Tables\Actions\Action::make('change-status')
                    ->form([
                        Select::make('status_id')
                            ->options(function (CoinRequest $record) {
                                return Status::query()
                                    ->modelType(null, true)
                                    ->whereNot('id', $record->status_id)
                                    ->pluck('name', 'id')
                                    ->toArray();
                            })->required(),
                    ])
                    ->requiresConfirmation()
                    ->icon('heroicon-s-check-circle')
                    ->iconButton()
                    ->color('success')
                    ->requiresConfirmation()
                    ->action(function (CoinRequest $record, array $data, Action $action) use ($statusAccepted) {
                        if ($record->coinRequestStatus->isAccepted) {
                            Notification::make()
                                ->danger()
                                ->title('Forbidden')
                                ->body('You cann\'t change accepted status')
                                ->send();

                            return $action->cancel();
                        }

                        $formSelectedStatusId = $data['status_id'];

                        $requestedUser = User::where('id', $record->created_by_user_id)->firstOrFail();

                        DB::beginTransaction();
                        try {
                            if ($formSelectedStatusId == $statusAccepted->id) {
                                $isBuy = $record->type == CoinRequestTypeEnum::BUY->value;
                                $count = $isBuy ? $record->count : -$record->count;

                                $requestedUser->achievements()->create([
                                    'type' => AchievementTypeEnum::COIN->value,
                                    'count' => $count,
                                    'status_id' => Status::nameScope(StatusEnum::ACHIEVEMENT_BUY_OR_SELL_COIN->value)->firstOrFail()?->id,
                                    'created_by_user_id' => auth()->id(),
                                ]);
                            }

                            $record->update(['status_id' => $formSelectedStatusId]);

                            Notification::make()
                                ->success()
                                ->title('success')
                                ->body('status changed')
                                ->send();

                            DB::commit();
                        } catch (\Throwable $th) {
                            DB::rollBack();
                            throw $th;
                        }
                    })
                    ->hidden(function (CoinRequest $record) use ($statusAccepted) {
                        return $record->status_id == $statusAccepted->id;
                    }),
                Tables\Actions\EditAction::make()
                    ->hidden(function (CoinRequest $record) use ($statusAccepted) {
                        return $record->status_id == $statusAccepted->id;
                    }),

            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCoinRequests::route('/'),
            'create' => Pages\CreateCoinRequest::route('/create'),
            // 'edit' => Pages\EditCoinRequest::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
