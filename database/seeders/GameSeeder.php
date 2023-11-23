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
                'name' => 'Hextris',
                'sort' => 18,
                'active' => 1,
            ],
            [
                'name' => 'DuckHunt',
                'sort' => 19,
                'active' => 1,
            ],
            [
                'name' => 'Simon',
                'sort' => 20,
                'active' => 1,
            ],
            [
                'name' => 'Chess',
                'sort' => 21,
                'active' => 1,
            ],
            [
                'name' => '8ball',
                'sort' => 32,
                'active' => 1,
            ],
            [
                'name' => 'Foosball',
                'sort' => 26,
                'active' => 1,
            ],
            [
                'name' => 'Dooz',
                'sort' => 24,
                'active' => 1,
            ],
            [
                'name' => 'Mench',
                'sort' => 25,
                'active' => 1,
            ],
            [
                'name' => 'Mar-Pelleh',
                'sort' => 23,
                'active' => 1,
            ],
            [
                'name' => 'Battleship-War',
                'sort' => 27,
                'active' => 1,
            ],
            [
                'name' => 'Battle-Strike',
                'sort' => 28,
                'active' => 1,
            ],
            [
                'name' => 'Bowling',
                'sort' => 29,
                'active' => 1,
            ],
            [
                'name' => 'Bow-Master',
                'sort' => 30,
                'active' => 1,
            ],
            [
                'name' => 'Checkers',
                'sort' => 31,
                'active' => 1,
            ],
            [
                'name' => 'Dart',
                'sort' => 22,
                'active' => 1,
            ],
            [
                'name' => 'Dead-City',
                'sort' => 33,
                'active' => 1,
            ],
            [
                'name' => 'Domino',
                'sort' => 34,
                'active' => 1,
            ],
            [
                'name' => 'Fireboy-and-Watergirl',
                'sort' => 35,
                'active' => 1,
            ],
            [
                'name' => 'Freekick-Training',
                'sort' => 36,
                'active' => 1,
            ],
            [
                'name' => 'Golf',
                'sort' => 37,
                'active' => 1,
            ],
            [
                'name' => 'Goose-Game',
                'sort' => 38,
                'active' => 1,
            ],
            [
                'name' => 'Lights',
                'sort' => 39,
                'active' => 1,
            ],
            [
                'name' => 'Othello',
                'sort' => 40,
                'active' => 1,
            ],
            [
                'name' => 'Puzzle',
                'sort' => 41,
                'active' => 1,
            ],
            [
                'name' => 'Super-Jesse-Pink',
                'sort' => 42,
                'active' => 1,
            ],
            [
                'name' => 'Tennis',
                'sort' => 43,
                'active' => 1,
            ],
        ];

        foreach ($games as $game) {
            Game::updateOrCreate($game);
        }
    }
}
