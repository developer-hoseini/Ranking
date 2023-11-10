<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TeamResource\Pages;
use App\Models\State;
use App\Models\Team;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class TeamResource extends Resource
{
    protected static ?string $model = Team::class;

    protected static ?string $navigationIcon = 'heroicon-o-cube-transparent';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(50),
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
                Forms\Components\Select::make('users')
                    ->relationship('users', 'name')
                    ->multiple()
                    ->searchable(),
                Forms\Components\Select::make('capitan_user_id')
                    ->relationship('capitan', 'name')
                    ->searchable(),
                Forms\Components\Textarea::make('about')
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('likes')
                    ->default(0)
                    ->numeric(),
                Forms\Components\Select::make('status_id')
                    ->relationship('status', 'name')
                    ->default(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('capitan.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('status.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('state.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('likes')
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
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTeams::route('/'),
            'create' => Pages\CreateTeam::route('/create'),
            'edit' => Pages\EditTeam::route('/{record}/edit'),
        ];
    }
}
