<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Event;

class EventSeeder extends Seeder
{
    public function run(): void
    {
        // Tạo 20 sự kiện mẫu
        Event::factory()->count(100)->create();
    }
}
