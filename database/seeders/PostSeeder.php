<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

class PostSeeder extends Seeder
{
    public function run(): void
    {
        $articles = [
            [
                'title' => 'Giảm thiểu rác thải nhựa trong trường học',
                'slug' => Str::slug('Giảm thiểu rác thải nhựa trong trường học'),
                'excerpt' => 'Chiến dịch mới giúp học sinh và sinh viên giảm sử dụng nhựa dùng một lần.',
                'content' => 'Trong năm học mới, nhiều trường học đã phát động chiến dịch "Nói không với nhựa dùng một lần"...',
                'image' => 'articles/plastic_reduction.jpg',
                'author' => 'Admin',
                'status' => 'published',
                'published_at' => Carbon::now()->subDays(10),
            ],
            [
                'title' => 'Ngày hội tái chế xanh 2025',
                'slug' => Str::slug('Ngày hội tái chế xanh 2025'),
                'excerpt' => 'Sự kiện môi trường lớn nhất năm với hàng trăm tình nguyện viên tham gia.',
                'content' => 'Ngày hội tái chế xanh diễn ra tại công viên trung tâm đã thu hút hơn 1.000 người tham dự...',
                'image' => 'articles/recycling_festival.jpg',
                'author' => 'Nguyễn Minh Tâm',
                'status' => 'published',
                'published_at' => Carbon::now()->subDays(5),
            ],
            [
                'title' => 'Mẹo phân loại rác tại nhà hiệu quả',
                'slug' => Str::slug('Mẹo phân loại rác tại nhà hiệu quả'),
                'excerpt' => 'Phân loại rác không khó nếu bạn biết cách thực hiện đơn giản mỗi ngày.',
                'content' => 'Việc phân loại rác đúng cách giúp giảm đáng kể lượng rác thải ra môi trường...',
                'image' => 'articles/waste_sorting_tips.jpg',
                'author' => 'Lê Thị Mai',
                'status' => 'draft',
                'published_at' => null,
            ],
        ];

        DB::table('articles')->insert($articles);
    }
}
