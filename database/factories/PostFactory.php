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

        // Tên tác giả rút gọn
        $first = ucfirst($this->faker->firstName());
        $last = ucfirst($this->faker->lastName());
        $author = strtoupper(substr($last, 0, 1)) . '. ' . $first;

        // 📸 Danh sách ảnh trong thư mục public/images/posts/
        $images = [
            'images/posts/Post 1.png',
            'images/posts/Post 2.png',
            'images/posts/Post 3.png',
            'images/posts/Post 4.png',
            'images/posts/Post 5.png',
            'images/posts/Post 6.png',
        ];

        return [
            'title' => $title,
            'slug' => Str::slug($title) . '-' . Str::random(4),
            'author' => $author,
            'excerpt' => $this->faker->sentence(2),
            'content' => $this->faker->paragraphs(4, true),
            'post_categories' => $this->faker->randomElement(['Tin tức', 'Tuyên truyền', 'Kiến thức']),
            
            // 🎨 Lấy ngẫu nhiên một ảnh có sẵn
            'image' => $this->faker->randomElement($images),

            'status' => $this->faker->randomElement(['draft', 'published', 'archived']),
            'published_at' => $this->faker->dateTimeBetween('-1 month', '+1 month'),
        ];
    }
}
