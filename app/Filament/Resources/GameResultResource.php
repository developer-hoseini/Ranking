<?php

namespace App\Filament\Resources;

use App\Filament\Resources\GameResultResource\Pages;
use App\Models\GameResult;
use App\Models\Invite;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class GameResultResource extends Resource
{
    protected static ?string $model = GameResult::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('invite_id')
                    ->searchable()
                    ->options(
                        function () {
                            return Invite::query()
                                ->with([
                                    'inviterUser:id,name',
                                    'invitedUser:id,name',
                                ])
                                ->get()
                                ->map(fn ($invite) => [
                                    $invite->id => "{$invite->inviterUser->name} vs {$invite->invitedUser->name}",
                                ])
                                ->toArray();
                        }
                    )
                    ->required(),
                Forms\Components\Select::make('inviter_game_result_status_id')
                    ->relationship('inviterGameResultStatus', 'name'),
                Forms\Components\Select::make('invited_game_result_status_id')
                    ->relationship('invitedGameResultStatus', 'name'),
                Forms\Components\Select::make('club_id')
                    ->relationship('club', 'name'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('invite.id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('inviterGameResultStatus.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('invitedGameResultStatus.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('club.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('deleted_at')
                    ->dateTime()
                    ->sortable(),
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
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
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

    public static function getNavigationGroup(): ?string
    {
        return 'Games';
    }

    public static function getNavigationSort(): ?int
    {
        return 3;
    }
}
