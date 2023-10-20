<?php

namespace Database\Seeders;

use App\Models\Invite;
use App\Models\status;
use Illuminate\Database\Seeder;

class StatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $statuses = [
            ['name' => 'pending'],
            ['name' => 'accepted'],
            ['name' => 'rejected'],
            ['name' => 'canceled'],
            ['name' => 'submit_result', 'model_type' => Invite::class],
            ['name' => 'wait_opponent_result', 'model_type' => Invite::class],
            ['name' => 'wait_image_verify', 'model_type' => Invite::class],
            ['name' => 'wait_club_verify', 'model_type' => Invite::class],
            ['name' => 'wait_opponent_result', 'model_type' => Invite::class],
            ['name' => 'wait_opponent_result', 'model_type' => Invite::class],
            ['name' => 'wait_opponent_result', 'model_type' => Invite::class],
        ];

        try {
            foreach ($statuses as $status) {
                status::updateOrCreate([
                    'name' => $status['name'],
                    ...isset($status['model_type']) ? ['model_type' => $status['model_type']] : [],
                ]);
            }
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
