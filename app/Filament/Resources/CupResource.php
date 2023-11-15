<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CupResource\Pages;
use App\Models\Cup;
use App\Models\State;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CupResource extends Resource
{
    protected static ?string $model = Cup::class;

    protected static ?string $navigationIcon = 'heroicon-o-sparkles';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(70),
                Forms\Components\Select::make('game_id')
                    ->relationship('game', 'name')
                    ->required(),
                Forms\Components\Select::make('country_id')
                    ->relationship('state.country', 'name')
                    ->label('Country')
                    ->afterStateHydrated(function ($record, callable $set) {
                        $countryId = $record?->state?->country?->id;
                        if ($countryId) {
                            $set('country_id', $countryId);
                        }
                    })
                    ->afterStateUpdated(function (callable $set) {
                        $set('state_id', null);
                    })
                    ->searchable()
                    ->reactive()
                    ->dehydrated(false),
                Forms\Components\Select::make('state_id')
                    ->label('State')
                    ->options(function (callable $get, callable $set) {
                        $countryId = $get('country_id');

                        if (! $countryId) {
                            return [];
                        }

                        return State::where('country_id', $countryId)->pluck('name', 'id')->toArray();
                    })
                    ->required(),
                Forms\Components\TextInput::make('capacity')
                    ->required()
                    ->numeric(),
                Forms\Components\Textarea::make('description')
                    ->maxLength(65535)
                    ->columnSpanFull(),
                Forms\Components\DateTimePicker::make('end_register_at')
                    ->required(),
                Forms\Components\DateTimePicker::make('start_at')
                    ->afterOrEqual('end_register_at')
                    ->required(),
                Forms\Components\Select::make('status_id')
                    ->relationship('status', 'name')
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
                Tables\Columns\TextColumn::make('id')
                    ->searchable(),
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('capacity')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('end_register_at')
                    ->dateTime('Y-m-d')
                    ->sortable(),
                Tables\Columns\TextColumn::make('start_at')
                    ->dateTime('Y-m-d')
                    ->sortable(),
                Tables\Columns\TextColumn::make('createdByUser.name')
                    ->label('Created By')
                    ->numeric()
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
            ->defaultSort('id', 'desc')

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
            CupResource\RelationManagers\CompetitionsRelationManager::class,
            CupResource\RelationManagers\RegisterdUsersRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCups::route('/'),
            'create' => Pages\CreateCup::route('/create'),
            'edit' => Pages\EditCup::route('/{record}/edit'),
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
