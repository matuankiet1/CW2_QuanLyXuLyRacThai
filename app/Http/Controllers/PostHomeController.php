<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class PostHomeController extends Controller
{
    public function index()
    {
        // Lấy danh sách bài viết đã publish (trạng thái = published)
        $posts = Post::where('status', 'published')
            ->orderBy('published_at', 'desc')
            ->paginate(6); // chia trang 6 bài mỗi trang

        return view('user.posts.home', compact('posts'));
    }

    public function show($slug)
    {
        $post = Post::where('slug', $slug)->firstOrFail();
        return view('user.posts.show', compact('post'));
    }
}
