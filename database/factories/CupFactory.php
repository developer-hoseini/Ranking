<?php

namespace Database\Factories;

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
        ];
    }
}
