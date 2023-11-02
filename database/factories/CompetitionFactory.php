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
            'capacity' => $this->faker->randomNumber(2),
            'description' => $this->faker->text(),
            'end_register_at' => $this->faker->dateTimeBetween('now', '+3 month', $timezone = null)->format('Y-m-d H:i:s'),
            'start_at' => Carbon::parse($this->faker->dateTimeBetween('now', '+3 month', $timezone = null))->subDays($this->faker->randomNumber(2))->format('Y-m-d H:i:s'),

            'game_id' => Game::inRandomOrder()->first()->id,
            'state_id' => State::inRandomOrder()->first()->id,
            'status_id' => Status::modelType(Competition::class, false)->inRandomOrder()->first()->id,
            'created_by_user_id' => User::inRandomOrder()->first()->id,
        ];
    }
}
