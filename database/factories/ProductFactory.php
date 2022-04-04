<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'title' => $this->faker->text(30),
            'desc' => $this->faker->text(300),
            'img' => $this->faker->imageUrl(),
            'price' => $this->faker->numberBetween(50,100)
        ];
    }
}
