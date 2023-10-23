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
        $games = ['footbal', 'valibol'];

        foreach ($games as $game) {
            Game::updateOrCreate([
                'name' => $game,
                'sort' => 10,
            ]);
        }
    }
}
