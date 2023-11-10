<?php

namespace App\Filament\Resources;

use App\Filament\Resources\InviteResource\Pages;
use App\Models\Invite;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class InviteResource extends Resource
{
    protected static ?string $model = Invite::class;

    protected static ?string $navigationIcon = 'heroicon-o-inbox-arrow-down';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('inviter_user_id')
                    ->relationship('inviterUser', 'name')
                    ->searchable()
                    ->required(),
                Forms\Components\Select::make('invited_user_id')
                    ->relationship('invitedUser', 'name')
                    ->searchable()
                    ->required(),
                Forms\Components\Select::make('game_id')
                    ->relationship('game', 'name')
                    ->searchable()
                    ->required(),
                Forms\Components\Select::make('game_type_id')
                    ->relationship('gameType', 'name')
                    ->searchable(),
                Forms\Components\Select::make('club_id')
                    ->relationship('club', 'name')
                    ->searchable(),
                Forms\Components\Select::make('game_status_id')
                    ->relationship('gameStatus', 'name'),
                Forms\Components\Select::make('confirm_status_id')
                    ->relationship('confirmStatus', 'name'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('inviterUser.name')
                    ->sortable(),
                Tables\Columns\TextColumn::make('invitedUser.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('game.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('gameType.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('club.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('gameStatus.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('confirmStatus.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('deleted_at')
                    ->dateTime()
                    ->toggleable(isToggledHiddenByDefault: true)
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
            ->defaultSort('id', 'desc')

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
            'index' => Pages\ListInvites::route('/'),
            'create' => Pages\CreateInvite::route('/create'),
            'edit' => Pages\EditInvite::route('/{record}/edit'),
        ];
    }
}
