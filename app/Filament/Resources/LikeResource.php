<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LikeResource\Pages;
use App\Models\Like;
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

class LikeResource extends Resource
{
    protected static ?string $model = Like::class;

    protected static ?string $navigationIcon = 'heroicon-o-heart';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('likeable_type')
                    ->label('Like For')
                    ->options([
                        User::class => 'User',
                        Team::class => 'team',
                    ])
                    ->afterStateUpdated(function (callable $set) {
                        $set('likeable_id', null);
                    })
                    ->reactive()
                    ->required(),
                Forms\Components\Select::make('likeable_id')
                    ->required()
                    ->options(function (callable $get) {
                        $type = $get('likeable_type');

                        $options = [];
                        if (! $type) {
                            return $options;
                        }

                        if ($type == User::class) {
                            $options = User::pluck('name', 'id')->toArray();
                        }
                        if ($type == Team::class) {
                            $options = Team::pluck('name', 'id')->toArray();
                        }

                        return $options;
                    })
                    ->searchable(),
                Forms\Components\Select::make('liked_by_user_id')
                    ->relationship('likedByUser', 'name')
                    ->searchable(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([

                Tables\Columns\TextColumn::make('likeable_type')
                    ->label('Type')
                    ->state(function (Model $record): string {
                        return class_basename($record->likeable_type ?? '');
                    })
                    ->searchable(),
                Tables\Columns\TextColumn::make('likeable.name')
                    ->label('Like for')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('deleted_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('likedByUser.name')
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
            'index' => Pages\ListLikes::route('/'),
            'create' => Pages\CreateLike::route('/create'),
            'edit' => Pages\EditLike::route('/{record}/edit'),
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
