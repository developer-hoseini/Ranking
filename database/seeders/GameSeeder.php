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
                'name' => 'Ping Pong',
                'sort' => 1,
                'active' => 1,
            ],
            [
                'name' => 'Table Football',
                'sort' => 2,
                'active' => 1,
            ],
            [
                'name' => 'Billiard',
                'sort' => 3,
                'active' => 1,
            ],
            [
                'name' => 'Shooting',
                'sort' => 4,
                'active' => 1,
            ],
            [
                'name' => 'Bowling',
                'sort' => 7,
                'active' => 1,
            ],
            [
                'name' => 'Chess',
                'sort' => 6,
                'active' => 1,
            ],
            [
                'name' => 'Air Hockey',
                'sort' => 5,
                'active' => 1,
            ],
            [
                'name' => 'PES',
                'sort' => 8,
                'active' => 1,
            ],
            [
                'name' => 'FIFA',
                'sort' => 9,
                'active' => 1,
            ],
            [
                'name' => 'Counter-Strike',
                'sort' => 10,
                'active' => 1,
            ],
            [
                'name' => 'Dota',
                'sort' => 12,
                'active' => 1,
            ],
            [
                'name' => 'Arm wrestling',
                'sort' => 17,
                'active' => 1,
            ],
            [
                'name' => 'Call of Duty',
                'sort' => 11,
                'active' => 1,
            ],
            [
                'name' => 'Blur',
                'sort' => 13,
                'active' => 1,
            ],
            [
                'name' => 'General',
                'sort' => 14,
                'active' => 1,
            ],
            [
                'name' => 'Left 4 Dead',
                'sort' => 15,
                'active' => 1,
            ],
            [
                'name' => 'Beat Saber (VR)',
                'sort' => 16,
                'active' => 1,
            ],
            [
                'name' => 'Online-Hextris',
                'sort' => 18,
                'active' => 1,
            ],
            [
                'name' => 'Online-DuckHunt',
                'sort' => 19,
                'active' => 1,
            ],
            [
                'name' => 'Online-Simon',
                'sort' => 20,
                'active' => 1,
            ],
            [
                'name' => 'Online-Chess',
                'sort' => 21,
                'active' => 1,
            ],
            [
                'name' => 'Online-8ball',
                'sort' => 32,
                'active' => 1,
            ],
            [
                'name' => 'Online-Foosball',
                'sort' => 26,
                'active' => 1,
            ],
            [
                'name' => 'Online-Dooz',
                'sort' => 24,
                'active' => 1,
            ],
            [
                'name' => 'Online-Mench',
                'sort' => 25,
                'active' => 1,
            ],
            [
                'name' => 'Online-Mar-Pelleh',
                'sort' => 23,
                'active' => 1,
            ],
            [
                'name' => 'Online-Battleship-War',
                'sort' => 27,
                'active' => 1,
            ],
            [
                'name' => 'Online-Battle-Strike',
                'sort' => 28,
                'active' => 1,
            ],
            [
                'name' => 'Online-Bowling',
                'sort' => 29,
                'active' => 1,
            ],
            [
                'name' => 'Online-Bow-Master',
                'sort' => 30,
                'active' => 1,
            ],
            [
                'name' => 'Online-Checkers',
                'sort' => 31,
                'active' => 1,
            ],
            [
                'name' => 'Online-Dart',
                'sort' => 22,
                'active' => 1,
            ],
            [
                'name' => 'Online-Dead-City',
                'sort' => 33,
                'active' => 1,
            ],
            [
                'name' => 'Online-Domino',
                'sort' => 34,
                'active' => 1,
            ],
            [
                'name' => 'Online-Fireboy-and-Watergirl',
                'sort' => 35,
                'active' => 1,
            ],
            [
                'name' => 'Online-Freekick-Training',
                'sort' => 36,
                'active' => 1,
            ],
            [
                'name' => 'Online-Golf',
                'sort' => 37,
                'active' => 1,
            ],
            [
                'name' => 'Online-Goose-Game',
                'sort' => 38,
                'active' => 1,
            ],
            [
                'name' => 'Online-Lights',
                'sort' => 39,
                'active' => 1,
            ],
            [
                'name' => 'Online-Othello',
                'sort' => 40,
                'active' => 1,
            ],
            [
                'name' => 'Online-Puzzle',
                'sort' => 41,
                'active' => 1,
            ],
            [
                'name' => 'Online-Super-Jesse-Pink',
                'sort' => 42,
                'active' => 1,
            ],
            [
                'name' => 'Online-Tennis',
                'sort' => 43,
                'active' => 1,
            ],
        ];

        foreach ($games as $game) {
            Game::updateOrCreate($game);
        }
    }
}
