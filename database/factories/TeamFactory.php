<?php

namespace Database\Factories;

use App\Models\State;
use App\Models\Status;
use App\Models\Team;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class TeamFactory extends Factory
{
    protected $model = Team::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'about' => $this->faker->word(),
            'likes' => $this->faker->randomNumber(3),

            'status_id' => Status::factory(),
            'capitan_user_id' => User::factory(),
            'state_id' => State::factory(),
        ];
    }
}
