<?php

namespace Database\Factories;

use App\Models\Profile;
use App\Models\State;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProfileFactory extends Factory
{
    protected $model = Profile::class;

    public function definition(): array
    {
        return [
            'fname' => $this->faker->word(),
            'lname' => $this->faker->word(),
            'bio' => $this->faker->word(),
            'birth_date' => $this->faker->dateTime(now()),
            'gender' => $this->faker->boolean(),
            'mobile' => $this->faker->randomDigit(11),
            'code_melli' => $this->faker->word(),
            'en_fullname' => $this->faker->word(),
            'bank_account' => $this->faker->word(),
            'account_holder_name' => $this->faker->name(),
            'show_mobile' => $this->faker->boolean(),
            'user_id' => User::inRandomOrder()->first()?->id,
            'state_id' => State::inRandomOrder()->first()?->id,
        ];
    }
}
