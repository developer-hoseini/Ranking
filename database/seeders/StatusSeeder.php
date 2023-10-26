<?php

namespace Database\Seeders;

use App\Models\GameResult;
use App\Models\Invite;
use App\Models\Status;
use App\Models\Ticket;
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
            ['name' => 'win', 'model_type' => GameResult::class, 'message' => 'you won'],
            ['name' => 'lose', 'model_type' => GameResult::class, 'message' => 'you lose'],
            ['name' => 'absent', 'model_type' => GameResult::class, 'message' => 'you absent'],
            ['name' => 'pending', 'model_type' => Ticket::class, 'message' => ''],
            ['name' => 'answered', 'model_type' => Ticket::class, 'message' => ''],
            ['name' => 'closed', 'model_type' => Ticket::class, 'message' => ''],
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
