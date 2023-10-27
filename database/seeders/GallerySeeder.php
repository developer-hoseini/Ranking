<?php

namespace Database\Seeders;

use App\Models\Gallery;
use Illuminate\Database\Seeder;

class GallerySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $imageTypes = [
            'slider' => [
                [
                    'path' => 'assets/images/sliders/48.jpg',
                ],
                [
                    'path' => 'assets/images/sliders/49.jpg',
                ],
                [
                    'path' => 'assets/images/sliders/50.jpg',
                ],
                [
                    'path' => 'assets/images/sliders/51.jpg',
                ],
            ],
        ];

        try {
            foreach ($imageTypes as $type => $images) {
                foreach ($images as $image) {
                    Gallery::updateOrCreate([
                        ...$image,
                        'type' => $type,
                    ]);
                }
            }
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
