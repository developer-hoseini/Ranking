<?php

namespace Database\Seeders;

use App\Models\Country;
use Illuminate\Database\Seeder;

class CountryStateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $countries = ['IR' => 'Iran', 'US' => 'United State'];

        $states = [
            'Iran' => ['Esfahan', 'Tehran'],
            'United State' => ['Ny', 'Los'],
        ];

        if (app()->environment('local')) {
            try {
                foreach ($countries as $key => $country) {
                    $createdCountry = Country::updateOrCreate([
                        'name' => $country,
                        'sortname' => $key,
                    ]);

                    if ($createdCountry->wasRecentlyCreated) {
                        foreach ($states[$country] as $state) {
                            $createdCountry->states()->create([
                                'name' => $state,
                            ]);
                        }
                    }
                }
            } catch (\Throwable $th) {
                throw $th;
            }
        }
    }
}
