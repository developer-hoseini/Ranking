<?php

namespace App\Filament\Resources\CupResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Actions\AttachAction;
use Filament\Tables\Table;

class CompetitionsRelationManager extends RelationManager
{
    protected static string $relationship = 'competitions';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('competitions.name')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                Tables\Columns\TextColumn::make('name'),
                Tables\Columns\TextColumn::make('step'),
            ])
            ->defaultSort('step', 'desc')
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\AttachAction::make()
                    ->form(fn (AttachAction $action): array => [
                        $action->getRecordSelect()
                            ->required()
                            ->rules([]),
                        Forms\Components\TextInput::make('step')
                            ->numeric()->default(1)->required(),
                    ])
                    ->beforeFormValidated(function (AttachAction $action, RelationManager $livewire) {
                        $cup = $livewire->getOwnerRecord();
                        if ($cup->competitions()->count() >= $cup->capacity) {
                            $livewire->addError('name', 'capacity this cup is full');
                            Notification::make()
                                ->danger()
                                ->title('Capacity')
                                ->body('Capacity this cup is full')
                                ->send();

                            $action->cancel();
                        }
                    }),
            ])
            ->actions([
                Tables\Actions\DetachAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DetachBulkAction::make(),
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
