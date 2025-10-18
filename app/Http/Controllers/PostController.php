<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    /**
     * Hiển thị danh sách bài viết
     */
    public function index(Request $request)
    {
        $query = Post::with('user');

        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        $posts = $query->orderBy('post_id', 'asc')->paginate(6);
        return view('admin.posts.index', compact('posts'));
    }

    public function create()
    {
        return view('admin.posts.create');
    }

    private function handleImageUpload(Request $request, $folder = 'assets/posts')
    {
        $file = $request->file('image');
        $ext = strtolower($file->getClientOriginalExtension());

        if ($ext === 'pdf') {
            return ['error' => 'File PDF không được phép tải lên.'];
        }

        $filename = time() . '_' . $file->getClientOriginalName();
        $destination = public_path($folder);

        if (!file_exists($destination)) {
            mkdir($destination, 0755, true);
        }

        $file->move($destination, $filename);
        return ['path' => $folder . '/' . $filename];
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => [
                'required',
                'string',
                'max:255',
                function ($attribute, $value, $fail) {
                    $this->validateTextField($attribute, $value, $fail);
                }
            ],
            'content' => [
                'required',
                'string',
                function ($attribute, $value, $fail) {
                    $this->validateTextField($attribute, $value, $fail);
                }
            ],
            'image' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
        ]);

        $post = new Post();
        $post->title = $request->title;
        $post->content = $request->content;
        $post->user_id = Auth::id();
        $post->rating = $request->rating ?? 1;

        if ($request->hasFile('image')) {
            $upload = $this->handleImageUpload($request);
            if (isset($upload['error'])) {
                return back()->withErrors(['image' => $upload['error']])->withInput();
            }
            $post->image = $upload['path'];
        }

        $post->save();
        return redirect()->route('posts.index')->with('success', 'Thêm bài viết thành công!');
    }

    public function edit($post_id)
    {
        $post = Post::findOrFail($post_id);
        return view('admin.posts.edit', compact('post'));
    }

    public function update(Request $request, $post_id)
    {
        $post = Post::findOrFail($post_id);

        // Kiểm tra xung đột cập nhật
        if ($request->filled('updated_at')) {
            $clientTime = \Carbon\Carbon::parse($request->updated_at);
            if ($clientTime->ne($post->updated_at)) {
                return back()
                    ->withErrors(['error' => 'Dữ liệu đã được chỉnh sửa bởi người khác. Vui lòng tải lại trang.'])
                    ->withInput();
            }
        }

        // Validation
        $validated = $request->validate([
            'title' => [
                'required',
                'string',
                'max:255',
                function ($attribute, $value, $fail) {
                    $this->validateTextField($attribute, $value, $fail);
                }
            ],
            'content' => [
                'required',
                'string',
                function ($attribute, $value, $fail) {
                    $this->validateTextField($attribute, $value, $fail);
                }
            ],
            'image' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
            'rating' => 'nullable|integer|min:1|max:5',
        ]);

        // Cập nhật dữ liệu
        $post->title = $validated['title'];
        $post->content = $validated['content'];
        $post->rating = $validated['rating'] ?? $post->rating;

        if ($request->hasFile('image')) {
            if ($post->image && file_exists(public_path($post->image))) {
                unlink(public_path($post->image));
            }

            $upload = $this->handleImageUpload($request);
            if (isset($upload['error'])) {
                return back()->withErrors(['image' => $upload['error']])->withInput();
            }

            $post->image = $upload['path'];
        }

        $post->save();
        return redirect()->route('posts.index')->with('success', 'Cập nhật bài viết thành công!');
    }

    public function destroy(Post $post)
    {
        try {
            // Xoá ảnh nếu tồn tại và hợp lệ
            if ($post->image) {
                $imagePath = public_path($post->image);
                if (file_exists($imagePath) && is_file($imagePath)) {
                    unlink($imagePath);
                }
            }

            // Xoá bài viết
            $post->delete();

            return redirect()->route('posts.index')->with('success', 'Xoá bài viết thành công!');
        } catch (\Exception $e) {
            return redirect()->route('posts.index')->withErrors(['error' => 'Không thể xoá bài viết: ' . $e->getMessage()]);
        }
    }

    public function showAll()
    {
        $posts = Post::latest()->paginate(6);
        return view('posts.home', compact('posts'));
    }

    public function show($id)
    {
        $post = Post::with('user')->findOrFail($id);
        return view('posts.show', compact('post'));
    }


}