<?php

namespace Database\Factories;

use App\Models\Competition;
use App\Models\Game;
use App\Models\State;
use App\Models\Status;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class CompetitionFactory extends Factory
{
    protected $model = Competition::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'coin' => $this->faker->randomNumber(2),
            'capacity' => $this->faker->randomNumber(2),
            'description' => $this->faker->text(),
            'end_register_at' => $this->faker->dateTime,
            'start_at' => Carbon::parse($this->faker->dateTime)->subDays($this->faker->randomNumber(2)),

            'game_id' => Game::factory(),
            'state_id' => State::factory(),
            'status_id' => Status::factory(),
            'created_by_user_id' => User::factory(),
        ];
    }
}
