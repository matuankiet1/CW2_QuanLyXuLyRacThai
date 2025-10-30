<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Event;

class EventFactory extends Factory
{
    protected $model = Event::class;

    public function definition()
    {
        // Tạo khoảng thời gian hợp lý
        $registerStart = $this->faker->dateTimeBetween('-1 month', '+1 month');
        $registerEnd   = (clone $registerStart)->modify('+7 days');
        $eventStart    = (clone $registerEnd)->modify('+3 days');
        $eventEnd      = (clone $eventStart)->modify('+2 days');

        return [
            'title' => ucfirst($this->faker->words(3, true)), // Tên sự kiện
            'register_date' => $registerStart,
            'register_end_date' => $registerEnd,
            'event_start_date' => $eventStart,
            'event_end_date' => $eventEnd,
            'location' => $this->faker->city(),
            'participants' => $this->faker->numberBetween(50, 500),
            'status' => $this->faker->randomElement(['upcoming', 'completed']),
            'description' => $this->faker->paragraphs(3, true),
        ];
    }
}
