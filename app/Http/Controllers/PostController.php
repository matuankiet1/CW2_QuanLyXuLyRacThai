<?php

namespace App\Http\Controllers;
use Illuminate\Support\Str;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PostController extends Controller
{
    /**
     * Danh sách bài viết
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $post_categories = $request->input('post_categories');
        $status = $request->input('status');

        $query = Post::query();

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%$search%")
                    ->orWhere('author', 'like', "%$search%")
                    ->orWhere('excerpt', 'like', "%$search%");
            });
        }

        if ($post_categories && $post_categories !== 'Tất cả danh mục') {
            $query->where('post_categories', $post_categories);
        }

        if ($status && $status !== 'all') {
            $query->where('status', $status);
        }

        $posts = $query->orderBy('id', 'asc')->paginate(10);

        $totalPosts = Post::count();
        $publishedPosts = Post::where('status', 'published')->count();
        $draftPosts = Post::where('status', 'draft')->count();

        return view('admin.posts.index', compact(
            'posts',
            'totalPosts',
            'publishedPosts',
            'draftPosts',
            'search',
            'post_categories',
            'status'
        ));
    }

    /**
     * Hiển thị form tạo bài viết mới
     */
    public function create()
    {
        $images = collect(\File::files(public_path('images/posts')))
            ->map(function ($file) {
                return 'images/posts/' . $file->getFilename();
            });

        return view('admin.posts.create', compact('images'));
    }

    /**
     * Lưu bài viết mới
     */
    public function store(Request $request)
    {
        // Ghi log để kiểm tra dữ liệu form gửi lên
        \Log::info('store() đang chạy', $request->all());

        // 1️⃣ Xác thực dữ liệu
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'excerpt' => 'required|string',
            'content' => 'required|string',
            'post_categories' => 'required|string',
            'status' => 'required|string|in:draft,published,archived',
            'published_at' => 'nullable|date',
            'image' => 'nullable|string|max:255', // ảnh chọn sẵn (URL hoặc path)
        ]);

        // 2️⃣ Tạo slug tự động và xử lý trùng
        $slug = \Str::slug($validated['title']);
        $originalSlug = $slug;
        $count = 1;

        while (\App\Models\Post::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $count++;
        }

        $validated['slug'] = $slug;

        // 3️⃣ Tạo bài viết
        $post = \App\Models\Post::create($validated);

        // 4️⃣ Chuyển hướng về danh sách + thông báo
        return redirect()
            ->route('admin.posts.index')
            ->with('success', 'Bài viết đã được thêm thành công!');
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
            'excerpt' => 'required|string',
            'content' => 'required|string',
            'post_categories' => 'required|string',
            'image' => 'nullable|string|max:255',
            'author' => 'required|string|max:255',
            'status' => 'required|in:draft,published,archived',
            'published_at' => 'nullable|date',
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
