<?php

namespace Database\Seeders;

use App\Models\Game;
use Illuminate\Database\Seeder;

class GameSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $games = [
            [
                'name' => 'PES',
                'score' => 2,
                'coin' => 50,
                'sort' => 10,
            ],
            [
                'name' => 'Ping Pong',
                'score' => 1,
                'coin' => 20,
                'sort' => 10,
            ],
        ];

        foreach ($games as $game) {
            Game::updateOrCreate($game);
        }
    }
}
