<?php

namespace App\Filament\Resources;

use App\Enums\AchievementTypeEnum;
use App\Filament\Resources\AchievementResource\Pages;
use App\Models\Achievement;
use App\Models\Competition;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AchievementResource extends Resource
{
    protected static ?string $model = Achievement::class;

    protected static ?string $navigationIcon = 'heroicon-o-gift';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('achievmentable_type')
                    ->label('Achievmentable Type')
                    ->options([
                        User::class => 'user',
                    ])
                    ->afterStateUpdated(function (callable $set) {
                        $set('achievmentable_id', null);
                    })
                    ->default(User::class)
                    ->hidden() // hidden set default value to User::class in create function
                    ->required(),
                Forms\Components\Select::make('achievmentable_id')
                    ->label('User')
                    ->options(function (callable $get) {
                        $playerType = $get('achievmentable_type');

                        $options = [];
                        if (! $playerType) {
                            return $options;
                        }

                        if ($playerType == User::class) {
                            $options = User::pluck('name', 'id')->toArray();
                        }

                        return $options;
                    })
                    ->searchable()
                    ->required(),

                Forms\Components\Select::make('type')
                    ->label('Type')
                    ->options([
                        AchievementTypeEnum::SCORE->value => AchievementTypeEnum::SCORE->value,
                        AchievementTypeEnum::COIN->value => AchievementTypeEnum::COIN->value,
                    ])
                    ->required(),
                Forms\Components\TextInput::make('count')
                    ->required()
                    ->numeric(),
                Forms\Components\Select::make('status_id')
                    ->label('Reason')
                    ->relationship('status', 'name')
                    ->getOptionLabelFromRecordUsing(fn (Model $record) => "{$record->nameWithoutModelPrefix}"),

                Forms\Components\Select::make('occurred_model_type')
                    ->options([
                        Competition::class => 'competition',
                    ])
                    ->afterStateUpdated(function (callable $set) {
                        $set('occurred_model_id', null);
                    })
                    ->default(Competition::class)
                    ->hidden(true) //  set default value to Competition::class in create function
                    ->reactive(),
                Forms\Components\Select::make('occurred_model_id')
                    ->label('competition')
                    ->options(function (callable $get) {
                        $type = $get('occurred_model_type');

                        $options = [];
                        if (! $type) {
                            return $options;
                        }

                        if ($type == Competition::class) {
                            $options = Competition::pluck('name', 'id')->toArray();
                        }

                        return $options;
                    })
                    ->searchable()
                    ->placeholder('you could leave this field empty'),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('achievmentable.name')
                    ->label('User')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('type')
                    ->sortable(),
                Tables\Columns\TextColumn::make('count')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('status.name')
                    ->label('Reason')
                    ->state(function (Model $record): string {
                        return $record->status->nameWithoutModelPrefix;
                    })
                    ->sortable(),
                Tables\Columns\TextColumn::make('occurredModel.name')
                    ->sortable(),
                Tables\Columns\TextColumn::make('createdByUser.name')
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
            ->filters([
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
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
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAchievements::route('/'),
            'create' => Pages\CreateAchievement::route('/create'),
            'edit' => Pages\EditAchievement::route('/{record}/edit'),
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
