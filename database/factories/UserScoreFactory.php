<?php

namespace Database\Factories;

use App\Models\Country;
use App\Models\Game;
use App\Models\User;
use App\Models\UserScore;
use Illuminate\Database\Eloquent\Factories\Factory;

class UserScoreFactory extends Factory
{
    protected $model = UserScore::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(['active' => true]),
            'game_id' => Game::factory(),
            'country_id' => Country::first()->id,
            'is_join' => $this->faker->boolean(),
            'in_club' => $this->faker->randomNumber(3),
            'with_image' => $this->faker->randomNumber(3),
            'team_played' => $this->faker->randomNumber(2),
            'score' => $this->faker->randomNumber(3),
            'win' => $this->faker->randomNumber(2),
        ];
    }
}
