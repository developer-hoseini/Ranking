<?php

namespace Database\Seeders;

use App\Models\Game;
use App\Models\UserScore;
use Illuminate\Database\Seeder;

class TempUserScoreSeeder extends Seeder
{
    public function run(): void
    {
        Game::limit(9)
            ->each(function (Game $game) {
                UserScore::factory(7)->create(['game_id' => $game->id]);
            });
    }
}
