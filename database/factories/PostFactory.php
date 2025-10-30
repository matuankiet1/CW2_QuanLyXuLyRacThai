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
        // Tiêu đề ngắn gọn 2 từ
        $title = ucfirst($this->faker->words(2, true));

        // Lấy họ và tên
        $first = ucfirst($this->faker->firstName());
        $last = ucfirst($this->faker->lastName());

        // Rút họ chỉ còn ký tự đầu, ví dụ: "N. An"
        $author = strtoupper(substr($last, 0, 1)) . '. ' . $first;

        return [
            'title' => $title,
            'slug' => Str::slug($title) . '-' . Str::random(4),
            'author' => $author,
            'excerpt' => $this->faker->sentence(2),
            'content' => $this->faker->paragraphs(4, true),
            'post_categories' => $this->faker->randomElement(['Tin tức', 'Tuyên truyền', 'Kiến thức']),
            'image' => $this->faker->imageUrl(800, 600, 'nature', true, 'Post'),
            'status' => $this->faker->randomElement(['draft', 'published', 'archived']),
            'published_at' => $this->faker->dateTimeBetween('-1 month', '+1 month'),
        ];
    }
}
