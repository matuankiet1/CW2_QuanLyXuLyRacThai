<?php

namespace App\Http\Controllers;
use Illuminate\Support\Str;
use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    /**
     * Hiển thị danh sách bài viết cho người dùng thường
     */
    public function showAll(Request $request)
    {
        $posts = Post::where('status', 'published')->orderBy('publish_date', 'desc')->paginate(9);
        return view('posts.home', compact('posts'));
    }

    /**
     * Danh sách bài viết
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $category = $request->input('category');
        $status = $request->input('status');

        $query = Post::query();

        if ($search) {
            $query->where(function ($q) use ($search) {
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

        $posts = $query->orderBy('publish_date', 'desc')->paginate(8);

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

    /**
     * Hiển thị form tạo bài viết mới
     */
    public function create()
    {
        return view('admin.posts.create');
    }

    /**
     * Lưu bài viết mới
     */
    public function store(Request $request)
    {
        // Log request để debug
        \Log::info('store đang chạy', $request->all());

        // Validate dữ liệu
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'excerpt' => 'nullable|string',
            'content' => 'required|string',
            'image' => 'nullable|string|max:255',
            'author' => 'required|string|max:255',
            'published_at' => 'nullable|date',
            'status' => 'required|in:draft,published',
        ]);

        try {
            // Tạo slug cơ bản
            $baseSlug = \Illuminate\Support\Str::slug($data['title']);
            $slug = $baseSlug;
            $counter = 1;

            // Kiểm tra slug trùng lặp trong DB
            while (\App\Models\Post::where('slug', $slug)->exists()) {
                $slug = $baseSlug . '-' . $counter;
                $counter++;
            }
            $data['slug'] = $slug;

            // Gán giá trị mặc định
            $data['category'] = $data['category'] ?? 'Tin tức';
            $data['image_url'] = $data['image_url'] ?? $data['image'] ?? null;
            $data['publish_date'] = $data['publish_date'] ?? now()->toDateString();

            // Thêm bài viết
            Post::create($data);

            return redirect()->route('admin.posts.index')
                ->with('success', 'Đã thêm bài viết mới!');

        } catch (\Exception $e) {
            // Log lỗi chi tiết
            \Log::error('Lỗi khi tạo bài viết: ' . $e->getMessage(), $data);

            return back()
                ->withInput()
                ->withErrors('Có lỗi xảy ra khi thêm bài viết: ' . $e->getMessage());
        }
    }



    /**
     * Hiển thị form chỉnh sửa bài viết
     */
    public function edit(Post $post)
    {
        return view('admin.posts.edit', compact('post'));
    }

    /**
     * Cập nhật bài viết
     */
    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'category' => 'required|string|max:100',
            'publish_date' => 'required|date',
            'status' => 'required|in:published,draft,archived',
            'excerpt' => 'required|string|max:500',
            'content' => 'required|string',
            'image_url' => 'nullable|url',
        ]);

        $post = Post::findOrFail($id);
        $post->update($data);

        return redirect()->route('admin.posts.index')
            ->with('success', 'Cập nhật bài viết thành công!');
    }

    public function destroy(Post $post)
    {
        $post->delete();

        return redirect()->route('admin.posts.index')
            ->with('success', '🗑️ Đã xóa bài viết thành công!');
    }
}
