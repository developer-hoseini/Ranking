<?php

namespace Database\Factories;

use App\Models\Game;
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

            'capitan_user_id' => User::inRandomOrder()->first()->id,
            'state_id' => State::inRandomOrder()->first()->id,
            'status_id' => Status::query()->modelType(null)->inRandomOrder()->first()->id,
            'created_by_user_id' => User::query()->inRandomOrder()->first()->id,
            'game_id' => Game::query()->inRandomOrder()->first()->id,
        ];
    }
}
