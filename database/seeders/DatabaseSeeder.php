<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run()
    {

        $this->call([
            RoleAndPermissionSeeder::class,
            GameTypeSeeder::class,
            StatusSeeder::class,
            PolicySeeder::class,
            GallerySeeder::class,
        ]);

        if (app()->environment('local')) {
            $this->call([
                CountryStateSeeder::class,
                GameSeeder::class,
                CompetitionSeeder::class,
                TicketCategorySeeder::class,
                TempUserScoreSeeder::class,
            ]);
        }
    }
}
