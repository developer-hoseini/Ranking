<?php

namespace Database\Seeders;

use App\Enums\StatusEnum;
use App\Models\Competition;
use App\Models\Game;
use App\Models\Profile;
use App\Models\State;
use App\Models\Status;
use App\Models\Team;
use Illuminate\Database\Seeder;

class TempUserScoreSeeder extends Seeder
{
    public function run(): void
    {
        Game::limit(9)
            ->each(function (Game $game) {

                for ($i = random_int(1, 3); $i > 0; $i--) {

                    $competitionIds = Competition::factory(random_int(1, 3))->create([
                        'game_id' => $game->id,
                        'status_id' => Status::where('name', StatusEnum::FINISHED->value)->first()->id,
                        'state_id' => State::first()->id,
                    ])->pluck('id');

                    Profile::factory(7)
                        ->create([
                            'state_id' => State::first()->id,
                        ])
                        ->each(function (Profile $profile) use ($competitionIds) {
                            $profile->user->addMediaFromUrl('https://www.clipartmax.com/png/middle/319-3191274_male-avatar-admin-profile.png')
                                ->toMediaCollection('avatar');
                            $profile->user->competitions()->attach($competitionIds->take(random_int(1, $competitionIds->count()))->toArray(), [
                                'status_id' => Status::where('name', StatusEnum::GAME_RESULT_WIN->value)->first()->id,
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
