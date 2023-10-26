<?php

namespace Database\Seeders;

use App\Models\TicketCategory;
use Illuminate\Database\Seeder;

class TicketCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            'competition',
            'finance',
        ];

        try {
            //code...
            foreach ($categories as $category) {
                TicketCategory::updateOrCreate(['name' => $category]);
            }
        } catch (\Throwable $th) {
            //throw $th;
        }
    }
}
