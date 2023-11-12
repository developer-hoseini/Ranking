<?php

namespace Database\Factories;

use App\Models\Club;
use App\Models\State;
use Illuminate\Database\Eloquent\Factories\Factory;

class ClubFactory extends Factory
{
    protected $model = Club::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'address' => $this->faker->address(),
            'tel' => $this->faker->word(),
            'is_rezvani' => $this->faker->boolean(),
            'sort' => $this->faker->randomNumber(1),
            'active' => $this->faker->boolean(),
            'state_id' => State::factory(),
        ];
    }
}
