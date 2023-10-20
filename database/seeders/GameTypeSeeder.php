<?php

namespace Database\Seeders;

use App\Models\GameType;
use Illuminate\Database\Seeder;

class GameTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $gameTypes = [
            ['name' => 'in_club'],
            ['name' => 'with_image'],
        ];

        try {
            foreach ($gameTypes as $gameType) {
                GameType::updateOrCreate([
                    'name' => $gameType['name'],
                ]);
            }
        } catch (\Throwable $th) {
            throw $th;
        }

    }
}
