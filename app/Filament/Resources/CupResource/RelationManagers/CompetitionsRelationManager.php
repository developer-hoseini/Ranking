<?php

namespace App\Filament\Resources\CupResource\RelationManagers;

use App\Enums\StatusEnum;
use App\Models\Competition;
use App\Models\Status;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Notifications\Notification;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Actions\AttachAction;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

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
                Tables\Actions\CreateAction::make()
                    ->form([
                        Forms\Components\TextInput::make('step')
                            ->numeric()->default(1)->required(),
                        Forms\Components\Select::make('player1')
                            ->options(function (RelationManager $livewire) {
                                $cup = $livewire->getOwnerRecord();

                                $users = User::query()
                                    ->whereHas('cups', function ($q) use ($cup) {
                                        $q->where('cups.id', $cup->id);
                                    });

                                return $users->pluck('name', 'id');

                                // return $options->pluck('id', 'name');
                            })
                            ->required(),
                        Forms\Components\Select::make('player2')
                            ->options(function (RelationManager $livewire) {
                                $cup = $livewire->getOwnerRecord();

                                $users = User::query()
                                    ->whereHas('cups', function ($q) use ($cup) {
                                        $q->where('cups.id', $cup->id);
                                    });

                                return $users->pluck('name', 'id');

                                // return $options->pluck('id', 'name');
                            })
                            ->required()
                            ->notIn(function (Get $get) {
                                return [$get('player1')];
                            }),

                    ])
                    ->afterFormValidated(function ($action, RelationManager $livewire, array $data) {

                        $cup = $livewire->getOwnerRecord();

                        $competition = Competition::query()
                            ->whereHas('cups', function ($q) use ($cup, $data) {
                                $q->where('cups.id', $cup->id)
                                    ->where('cupables.step', $data['step']);
                            })
                            ->whereHas('users', fn ($q) => $q->whereIn('users.id', [$data['player1'], $data['player2']]))
                            ->first();

                        if ($competition) {
                            $livewire->addError('palyer1', 'this competion with this step is duplicated');
                            Notification::make()
                                ->danger()
                                ->title('duplicate')
                                ->body('this competition is duplicate')
                                ->send();

                            $action->cancel();
                        }

                    })
                    ->using(function (array $data, string $model, RelationManager $livewire): Model {
                        $cup = $livewire->getOwnerRecord();
                        $user1 = User::where('id', $data['player1'])->first();
                        $user2 = User::where('id', $data['player2'])->first();

                        $createdCompetition = Competition::create([
                            'name' => "{$cup->name} - {$user1?->name} vs {$user2?->name}",
                            'capacity' => 2,
                            'description' => "create based on cup #{$cup->id}",
                            'game_id' => $cup->game_id,
                            'state_id' => $cup->state_id,
                            'status_id' => Status::where('name', StatusEnum::COMPETITION_TOURNAMENT->value)->first()?->id,
                        ]);

                        $createdCompetition->users()->attach([$user1->id, $user2->id]);
                        $createdCompetition->cups()->attach($cup->id, ['step' => $data['step']]);

                        return $createdCompetition;
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
