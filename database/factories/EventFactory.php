<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Event>
 */
class EventFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $status = $this->faker->randomElement(['upcoming', 'completed']);

        return [
            'title' => $this->faker->sentence(4),
            'description' => $this->faker->paragraph,
            'date' => $this->faker->dateTimeBetween('-1 month', '+1 month'),
            'location' => $this->faker->randomElement(['Khu A', 'Khu B', 'Hội trường', 'Công viên']),
            'participants' => $this->faker->numberBetween(20, 150),
            'waste' => $status === 'completed' ? $this->faker->numberBetween(50, 300) : 0,
            'status' => $status,
        ];
    }
}