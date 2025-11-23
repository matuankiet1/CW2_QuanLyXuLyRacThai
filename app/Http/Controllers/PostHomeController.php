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
        // Lấy bài viết theo slug
        $post = Post::where('slug', $slug)->firstOrFail();

        // Tăng lượt xem
        $post->increment('post_views');

        return view('user.posts.show', compact('post'));
    }

     public function indexForStaff()
    {
        // Lấy danh sách bài viết đã publish (trạng thái = published)
        $posts = Post::where('status', 'published')
            ->orderBy('published_at', 'desc')
            ->paginate(6); // chia trang 6 bài mỗi trang

        return view('staff.posts.home', compact('posts'));
    }
    public function showForStaff($slug)
    {
        // Lấy bài viết theo slug
        $post = Post::where('slug', $slug)->firstOrFail();

        // Tăng lượt xem
        $post->increment('post_views');

        return view('staff.posts.show', compact('post'));
    }

}
