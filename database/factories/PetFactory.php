<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class PetFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->name(),
            'age'  => $this->faker->randomNumber([5, 40]),
            'id_gender' => $this->faker->numberBetween(1, 2),
            'id_user' => 1
        ];
    }
}
