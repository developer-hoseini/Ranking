<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CompetitionResource\Pages;
use App\Filament\Resources\CompetitionResource\RelationManagers\TeamsRelationManager;
use App\Filament\Resources\CompetitionResource\RelationManagers\UsersRelationManager;
use App\Models\Competition;
use App\Models\GameResult;
use App\Models\State;
use App\Models\Status;
use App\Models\User;
use DB;
use Filament\Forms;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CompetitionResource extends Resource
{
    protected static ?string $model = Competition::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(80),
                Forms\Components\Select::make('game_id')
                    ->relationship('game', 'name')
                    ->required(),
                Forms\Components\Select::make('country_id')
                    ->relationship('state.country', 'name')
                    ->label('Country')
                    ->afterStateUpdated(function (callable $set) {
                        $set('state_id', null);
                    })
                    ->searchable()
                    ->reactive()
                    ->dehydrated(false),
                Forms\Components\Select::make('state_id')
                    ->label('State')
                    ->options(function (callable $get) {
                        $countryId = $get('country_id');
                        if (! $countryId) {
                            return [];
                        }

                        return State::where('country_id', $countryId)->pluck('name', 'id')->toArray();
                    })
                    ->required(),
                Forms\Components\Select::make('status_id')
                    ->relationship('competitionStatus', 'name')
                    ->default(2)
                    ->required(),

                Forms\Components\TextInput::make('capacity')
                    ->required()
                    ->numeric()
                    ->default(2),
                Forms\Components\Textarea::make('description')
                    ->maxLength(65535)
                    ->columnSpanFull(),
                Forms\Components\DateTimePicker::make('end_register_at')
                    ->required(),
                Forms\Components\DateTimePicker::make('start_at')
                    ->afterOrEqual('end_register_at')
                    ->required(),
                Forms\Components\SpatieMediaLibraryFileUpload::make('images')
                    ->collection('images')
                    ->multiple()
                    ->nullable(),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('game.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('state.country.name')
                    ->numeric()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('state.name')
                    ->numeric()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('end_register_at')
                    ->dateTime('Y-m-d')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('start_at')
                    ->dateTime('Y-m-d')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('competitionStatus.name')
                    ->numeric()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('capacity')
                    ->numeric()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('createdByUser.name')
                    ->label('Created By')
                    ->numeric()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

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
            ])
            ->actions([
                Tables\Actions\Action::make('game results')
                    ->color('info')
                    ->icon('heroicon-o-flag')
                    ->iconButton()
                    ->form(function (Competition $record) {
                        $users = $record->users;
                        $gameResults = $record->gameResults;
                        $statuses = Status::modelType(null)->get();
                        $gameResultstatuses = Status::modelType(GameResult::class, false)->get();

                        $sections = $users->map(function ($user, $key) use ($statuses, $gameResultstatuses, $gameResults) {
                            $gameResult = $gameResults->where('playerable_id', $user->id)->first();

                            return Section::make('player '.$key + 1)
                                ->schema([
                                    Select::make("form.$key.user_id")
                                        ->label('user')
                                        ->options([$user->id => $user->avatarName])
                                        ->default($gameResult?->playerable_id)
                                        ->required(),
                                    Select::make("form.$key.game_result_status_id")
                                        ->label('status')
                                        ->options($gameResultstatuses->pluck('name', 'id'))
                                        ->default($gameResult?->game_result_status_id)
                                        ->required(),
                                    Select::make("form.$key.user_status_id")
                                        ->label('status user')
                                        ->options($statuses->pluck('name', 'id'))
                                        ->default($gameResult?->user_status_id)
                                        ->required(),

                                ]);

                        });

                        return [
                            ...$sections,
                            ...count($sections) ? [
                                Select::make('admin_status_id')
                                    ->label('status admin')
                                    ->options($statuses->pluck('name', 'id'))
                                    ->default($gameResults->first()?->admin_status_id)
                                    ->required(),
                            ] : [],
                        ];
                    })
                    ->action(function (Competition $record, array $data, Action $action) {
                        $forms = $data['form'] ?? [];

                        $adminStatusForm = $data['admin_status_id'] ?? '';
                        $gameResults = $record->gameResults;

                        if (count($forms) == 0) {
                            Notification::make()
                                ->warning()
                                ->title('Add participates')
                                ->body('you must first add users to this competition')
                                ->send();

                            $action->cancel();

                            return;
                        }

                        DB::beginTransaction();

                        try {
                            foreach ($forms as $form) {
                                $gameResult = $gameResults->where('playerable_id', $form['user_id'])->first();

                                if ($gameResult) {
                                    //edit
                                    $gameResult->update([
                                        'game_result_status_id' => $form['game_result_status_id'],
                                        'user_status_id' => $form['user_status_id'],
                                        'admin_status_id' => $adminStatusForm,
                                    ]);

                                } else {
                                    //create
                                    $gameResult = $record->gameResults()->create([
                                        'playerable_type' => User::class,
                                        'playerable_id' => $form['user_id'],
                                        'game_result_status_id' => $form['game_result_status_id'],
                                        'user_status_id' => $form['user_status_id'],
                                        'admin_status_id' => $adminStatusForm,
                                    ]);
                                }

                            }

                            \App\Services\Actions\Achievement\GameResult\ReceiveCoin::handle($gameResults);

                            DB::commit();

                            Notification::make()
                                ->success()
                                ->title('success')
                                ->body('game results submited')
                                ->send();

                        } catch (\Throwable $th) {
                            DB::rollback();
                            throw $th;
                        }

                    }),
                Tables\Actions\EditAction::make(),

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
            TeamsRelationManager::class,
            UsersRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCompetitions::route('/'),
            'create' => Pages\CreateCompetition::route('/create'),
            'edit' => Pages\EditCompetition::route('/{record}/edit'),
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
