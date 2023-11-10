<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CompetitionResource\Pages;
use App\Filament\Resources\CompetitionResource\RelationManagers\TeamsRelationManager;
use App\Filament\Resources\CompetitionResource\RelationManagers\UsersRelationManager;
use App\Models\Competition;
use App\Models\State;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
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
                    ->relationship('status', 'name')
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
                    ->sortable(),
                Tables\Columns\TextColumn::make('state.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('end_register_at')
                    ->dateTime('Y-m-d')
                    ->sortable(),
                Tables\Columns\TextColumn::make('start_at')
                    ->dateTime('Y-m-d')
                    ->sortable(),
                Tables\Columns\TextColumn::make('status.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('capacity')
                    ->numeric()
                    ->sortable(),

                Tables\Columns\TextColumn::make('createdByUser.name')
                    ->label('Created By')
                    ->numeric()
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
