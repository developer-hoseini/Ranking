<?php

namespace App\Filament\Resources;

use App\Filament\Resources\GameResultResource\Pages;
use App\Models\Competition;
use App\Models\GameResult;
use App\Models\Team;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class GameResultResource extends Resource
{
    protected static ?string $model = GameResult::class;

    protected static ?string $navigationIcon = 'heroicon-o-flag';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([

                Forms\Components\Select::make('playerable_type')
                    ->label('Player Type')
                    ->options([
                        User::class => 'user',
                        Team::class => 'team',
                    ])
                    ->afterStateUpdated(function (callable $set) {
                        $set('playerable_id', null);
                    })
                    ->reactive()
                    ->required(),
                Forms\Components\Select::make('playerable_id')
                    ->label('Player')
                    ->reactive()
                    ->options(function (callable $get) {
                        $playerType = $get('playerable_type');

                        $options = [];
                        if (! $playerType) {
                            return $options;
                        }

                        if ($playerType == User::class) {
                            $options = User::pluck('name', 'id')->toArray();
                        }

                        if ($playerType == Team::class) {
                            $options = Team::pluck('name', 'id')->toArray();
                        }

                        return $options;
                    })
                    ->searchable()
                    ->required(),
                Forms\Components\Select::make('gameresultable_type')
                    ->label('For ')
                    ->options([
                        Competition::class => 'Competition',
                    ])
                    ->afterStateUpdated(function (callable $set) {
                        $set('gameresultable_id', null);
                    })
                    ->reactive()
                    ->required(),

                Forms\Components\Select::make('gameresultable_id')
                    ->options(function (callable $get) {
                        $type = $get('gameresultable_type');

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
                    ->required(),
                Forms\Components\Select::make('game_result_status_id')
                    ->required()
                    ->relationship('gameResultStatus', 'name'),
                Forms\Components\Select::make('status_id')
                    ->relationship('status', 'name'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('gameResultStatus.name')
                    ->sortable(),

                Tables\Columns\TextColumn::make('playerable_type')
                    ->label('Player Type')
                    ->state(function (Model $record): string {
                        return class_basename($record->playerable_type ?? '');
                    })
                    ->searchable(),
                Tables\Columns\TextColumn::make('playerable.name')
                    ->label('Player ')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('gameresultable_type')
                    ->state(function (Model $record): string {
                        return class_basename($record->gameresultable_type ?? '');
                    })
                    ->searchable(),
                Tables\Columns\TextColumn::make('gameresultable.name')
                    ->label('name')
                    ->sortable(),
                Tables\Columns\TextColumn::make('status.name')
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
            'index' => Pages\ListGameResults::route('/'),
            'create' => Pages\CreateGameResult::route('/create'),
            'edit' => Pages\EditGameResult::route('/{record}/edit'),
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
