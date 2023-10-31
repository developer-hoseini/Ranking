<?php

namespace Database\Seeders;

use App\Enums\StatusEnum;
use App\Models\Status;
use Illuminate\Database\Seeder;

class StatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        try {

            collect(StatusEnum::cases())->each(function ($item) {
                status::updateOrCreate([
                    'name' => $item->value,
                    ...$item->getModelType() ? ['model_type' => $item->getModelType()] : [],
                    ...$item->getMessage() ? ['message' => $item->getMessage()] : [],
                ]);
            });
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
