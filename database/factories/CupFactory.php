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
        $capacity = $this->faker->numberBetween(4, 16);
        $capacity = $this->getCapacity($capacity);

        return [
            'name' => $this->faker->name,
            'capacity' => $capacity,
            'description' => $this->faker->text(50),
        ];
    }

    private function getCapacity($capacity)
    {
        if (($capacity % 2) == 0) {
            return $capacity;
        } else {
            $capacity = $capacity + 1;
        }

        $capacity = $this->getCapacity($capacity);

        return $capacity;
    }
}
