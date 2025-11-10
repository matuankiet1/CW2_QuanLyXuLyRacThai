<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
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

        // Một số ảnh mẫu
        $images = [
            'images/events/Event1.png',
            'images/events/Event2.png',
        ];

        return [
            'title' => ucfirst($this->faker->words(3, true)), // Tên sự kiện
            'register_date' => $registerStart->format('Y-m-d'),
            'register_end_date' => $registerEnd->format('Y-m-d'),
            'event_start_date' => $eventStart->format('Y-m-d'),
            'event_end_date' => $eventEnd->format('Y-m-d'),
            'location' => $this->faker->city(),
            'participants' => $this->faker->numberBetween(50, 500),
            'description' => $this->faker->paragraphs(3, true),
            'image' => $this->faker->randomElement($images),
        ];
    }
}
