<?php

namespace Database\Seeders;

use App\Enums\StatusEnum;
use App\Models\Competition;
use App\Models\Game;
use App\Models\State;
use App\Models\Status;
use App\Models\User;
use Illuminate\Database\Seeder;

class TempUserScoreSeeder extends Seeder
{
    public function run(): void
    {
        Game::limit(9)
            ->each(function (Game $game) {
                $competitionIds = Competition::factory(1)->create([
                    'game_id' => $game->id,
                    'status_id' => Status::where('name', StatusEnum::FINISHED->value)->first()->id,
                    'state_id' => State::first()->id,
                ])->pluck('id')->toArray();
                User::factory(7)->create()->each(function (User $user) use ($competitionIds) {
                    $user->competitions()->attach($competitionIds, [
                        'status_id' => Status::where('name', StatusEnum::WIN->value)->first()->id,
                    ]);
                });
            });
    }
}
