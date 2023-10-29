<?php

namespace Database\Factories;

use App\Models\Game;
use Illuminate\Database\Eloquent\Factories\Factory;

class GameFactory extends Factory
{
    protected $model = Game::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'sort' => $this->faker->randomNumber(),
            'active' => $this->faker->boolean(),
            'is_online' => $this->faker->boolean(),
            'image_updated_at' => $this->faker->dateTime(),
        ];
    }
}
