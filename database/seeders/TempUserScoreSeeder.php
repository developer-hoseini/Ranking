<?php

namespace Database\Seeders;

use App\Enums\StatusEnum;
use App\Models\Competition;
use App\Models\Game;
use App\Models\State;
use App\Models\Status;
use App\Models\Team;
use App\Models\User;
use Illuminate\Database\Seeder;

class TempUserScoreSeeder extends Seeder
{
    public function run(): void
    {
        Game::limit(9)
            ->each(function (Game $game) {

                for ($i = random_int(1, 3); $i > 0; $i--) {

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

                    for ($j = random_int(0, 2); $j > 0; $j--) {
                        Team::factory(1)->create([
                            'state_id' => State::first()->id,
                            'status_id' => Status::where('name', StatusEnum::ACTIVE->value)->first()->id,
                        ])->each(function (Team $team) use ($competitionIds) {
                            $team->competitions()->attach($competitionIds);
                        });
                    }

                }

            });
    }
}
