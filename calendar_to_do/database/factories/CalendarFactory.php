<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;

class CalendarFactory extends Factory
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
            'description' => $this->faker->text(),
            'is_send' => $this->faker->boolean(),
            'start' => $this->faker->dateTime(),
            'end' => $this->faker->dateTime(),
            'user_id' => User::factory(),
        ];
    }
}
