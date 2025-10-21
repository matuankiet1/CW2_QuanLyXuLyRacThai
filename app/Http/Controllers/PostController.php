<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;


class PostController extends Controller
{
    public function index(Request $request)
    {
        // Lấy các filter từ request
        $search = $request->input('search');
        $category = $request->input('category');
        $status = $request->input('status');

        // Query cơ bản
        $query = Post::query();

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%$search%")
                  ->orWhere('author', 'like', "%$search%")
                  ->orWhere('excerpt', 'like', "%$search%");
            });
        }

        if ($category && $category !== 'Tất cả danh mục') {
            $query->where('category', $category);
        }

        if ($status && $status !== 'all') {
            $query->where('status', $status);
        }

        // Phân trang
        $posts = $query->orderBy('publish_date', 'desc')->paginate(8);

        // Thống kê
        $totalPosts = Post::count();
        $publishedPosts = Post::where('status', 'published')->count();
        $draftPosts = Post::where('status', 'draft')->count();

        return view('admin.posts.index', compact(
            'posts',
            'totalPosts',
            'publishedPosts',
            'draftPosts',
            'search',
            'category',
            'status'
        ));
    }

    public function create()
    {
        return view('posts.form', ['post' => new Post()]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'category' => 'required|string|max:100',
            'publish_date' => 'required|date',
            'status' => 'required|in:published,draft,archived',
            'excerpt' => 'required|string|max:500',
            'content' => 'required|string',
            'image_url' => 'nullable|url'
        ]);

        Post::create($data);

        return redirect()->route('admin.posts.index')->with('success', 'Đã thêm bài viết mới!');
    }

    public function edit(Post $post)
    {
        return view('posts.form', compact('post'));
    }

    public function update(Request $request, Post $post)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'category' => 'required|string|max:100',
            'publish_date' => 'required|date',
            'status' => 'required|in:published,draft,archived',
            'excerpt' => 'required|string|max:500',
            'content' => 'required|string',
            'image_url' => 'nullable|url'
        ]);

        $post->update($data);

        return redirect()->route('admin.posts.index')->with('success', 'Cập nhật bài viết thành công!');
    }
}
