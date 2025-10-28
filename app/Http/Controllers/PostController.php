<?php

namespace App\Http\Controllers;
use Illuminate\Support\Str;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

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
     * Hiển thị chi tiết bài viết
     */
    public function show($id)
    {
        $post = Post::where('status', 'published')->findOrFail($id);
        $relatedPosts = Post::where('status', 'published')
            ->where('id', '!=', $id)
            ->where('post_categories', $post->post_categories)
            ->limit(3)
            ->get();

        return view('posts.show', compact('post', 'relatedPosts'));
    }

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

        $posts = $query->orderBy('id', 'asc')->paginate(8);

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
        return view('admin.posts.create');
    }

    /**
     * Lưu bài viết mới
     */
    public function store(Request $request)
    {
        try {
            // Ghi log để debug (nếu cần)
            Log::info("Đang xử lý thêm bài viết", $request->all());

            // Validate dữ liệu
            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'excerpt' => 'required|string',
                'content' => 'required|string',
                'post_categories' => 'required|string',
                'image' => 'nullable|string|max:255',
                'author' => 'required|string|max:255',
                'status' => 'required|in:draft,published,archived',
                'published_at' => 'nullable|date',
            ]);

            // Tạo bài viết
            $post = Post::create($validated);

            return redirect()
                ->route('admin.posts.index')
                ->with('success', 'Thêm bài viết thành công!');
        } catch (\Exception $e) {
            Log::error("Lỗi khi thêm bài viết: " . $e->getMessage());
            return back()->withErrors(['error' => 'Không thể thêm bài viết.'])->withInput();
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
