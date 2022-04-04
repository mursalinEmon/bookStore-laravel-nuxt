<?php

namespace Database\Factories;
Use App\Models\Order;
Use App\Models\Link;

use Illuminate\Database\Eloquent\Factories\Factory;

class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $link = Link::inRandomOrder()->first();
        return [
            'code' => $link->code,
            'user_id' => $link->user->id,
            'ambassador_email' => $link->user->email,
            'name' => $this->faker->name,
            'email' => $this->faker->email,
            'complete' => 1
        ];
    }
}
