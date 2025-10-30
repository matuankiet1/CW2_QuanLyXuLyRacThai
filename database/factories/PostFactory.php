<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Post;

class PostFactory extends Factory
{
    protected $model = Post::class;

    public function definition()
    {
        // Tạo tiêu đề ngắn 2–4 từ
        $title = ucfirst($this->faker->words(rand(2, 3), true));

        return [
            'title' => $title,
            'slug' => Str::slug($title) . '-' . Str::random(5),
            'author' => $this->faker->name(),
            'excerpt' => $this->faker->sentence(10),
            'content' => $this->faker->paragraphs(5, true),
            'post_categories' => $this->faker->randomElement(['Tin tức', 'Sự kiện', 'Bài viết môi trường', 'Thông báo']),
            'image' => $this->faker->imageUrl(800, 600, 'nature', true, 'Post Image'),
            'status' => $this->faker->randomElement(['draft', 'published', 'archived']),
            'published_at' => $this->faker->optional()->dateTimeBetween('-1 month', '+1 month'),
        ];
    }
}
