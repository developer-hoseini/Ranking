<?php

namespace Database\Factories;

use App\Models\Game;
use App\Models\State;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Cup>
 */
class CupFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $allowCapacities = [4, 8, 16];
        $randomId = $this->faker->numberBetween(0, 2);
        $capacity = $allowCapacities[$randomId];

        return [
            'name' => $this->faker->name,
            'capacity' => $capacity,
            'description' => $this->faker->text(50),
            'register_cost_coin' => $this->faker->numberBetween(1, 1000),

            'end_register_at' => $this->faker->dateTimeBetween('now', '+3 month', $timezone = null)->format('Y-m-d H:i:s'),
            'start_at' => Carbon::parse($this->faker->dateTimeBetween('now', '+3 month', $timezone = null))->subDays($this->faker->randomNumber(2))->format('Y-m-d H:i:s'),

            'created_by_user_id' => User::inRandomOrder()->first()->id,
            'game_id' => Game::inRandomOrder()->first()->id,
            'state_id' => State::inRandomOrder()->first()->id,
        ];
    }
}
