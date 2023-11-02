<?php

namespace Database\Seeders;

use App\Models\Competition;
use Illuminate\Database\Seeder;

class CompetitionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // $competitions = [
        //     [
        //         'name' => 'Footbale Sery A 2023',

        //         'game_id' => 1,
        //         'status_id' => 2,
        //         'state_id' => 1,
        //         'created_by_user_id' => auth()->id(),
        //     ],
        //     [
        //         'name' => 'Footbale Sery B 2023',

        //         'game_id' => 1,
        //         'status_id' => 1,
        //         'state_id' => 1,
        //         'created_by_user_id' => auth()->id(),
        //     ],
        //     [
        //         'name' => 'Valibal Sery A 2023',

        //         'game_id' => 2,
        //         'status_id' => 1,
        //         'state_id' => 1,
        //         'created_by_user_id' => auth()->id(),
        //     ],
        //     [
        //         'name' => 'Valibal Sery B 2023',

        //         'game_id' => 2,
        //         'status_id' => 2,
        //         'state_id' => 1,
        //         'created_by_user_id' => auth()->id(),
        //     ],
        // ];

        // try {
        //     foreach ($competitions as $competition) {
        //         // code...
        //         Competition::updateOrCreate($competition);
        //     }
        // } catch (\Throwable $th) {
        //     throw $th;
        // }
    }
}
