<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class PostHomeController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        // Query chính
        $posts = Post::where('status', 'published');

        // Tìm kiếm
        if ($search) {
            $posts->where(function ($q) use ($search) {
                $q->where('title', 'like', "%$search%")
                    ->orWhere('author', 'like', "%$search%")
                    ->orWhere('excerpt', 'like', "%$search%");
            });
        }

        // Sắp xếp + phân trang
        $posts = $posts->orderBy('published_at', 'desc')->paginate(6);

        return view('user.posts.home', compact('posts', 'search'));
    }

    public function show($slug)
    {
        // Lấy bài viết theo slug
        $post = Post::where('slug', $slug)->firstOrFail();

        // Tăng lượt xem
        $post->increment('post_views');

        return view('user.posts.show', compact('post'));
    }



}
