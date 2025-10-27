<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class PostController extends Controller
{
<<<<<<< Updated upstream
=======
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
>>>>>>> Stashed changes
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

    private function validateTextField($attribute, $value, $fail)
    {
        if (preg_match('/<script|<\/script>|<\?|<iframe|onerror|onload/i', $value)) {
            $fail('Trường "' . $attribute . '" chứa nội dung không hợp lệ.');
        }
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
        $post->title = $request->input('title');
        $post->content = $request->input('content');
        $post->user_id = Auth::id(); // cần 'use Illuminate\Support\Facades\Auth;'
        $post->rating = $request->input('rating', 1); // mặc định là 1 nếu không có

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

        if ($request->filled('updated_at')) {
            $clientTime = Carbon::parse($request->updated_at);
            if ($clientTime->ne($post->updated_at)) {
                return back()->withErrors([
                    'error' => 'Dữ liệu đã được chỉnh sửa bởi người khác. Vui lòng tải lại trang.'
                ])->withInput();
            }
        }

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

        $post->fill($validated);

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
            if ($post->image && file_exists(public_path($post->image))) {
                unlink(public_path($post->image));
            }

            $post->delete();
            return redirect()->route('posts.index')->with('success', 'Xoá bài viết thành công!');
        } catch (\Exception $e) {
            return redirect()->route('posts.index')->withErrors([
                'error' => 'Không thể xoá bài viết: ' . $e->getMessage()
            ]);
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
