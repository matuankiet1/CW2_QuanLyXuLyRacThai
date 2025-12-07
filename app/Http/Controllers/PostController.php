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
        \Log::info('store() đang chạy', $request->all());
        $formToken = $request->input('form_token');
        if (session()->has('used_form_tokens.' . $formToken)) {
            return redirect()
                ->route('admin.posts.index')
                ->with('error', 'Bạn đã lưu trước đó rồi.');
        }

        session()->put('used_form_tokens.' . $formToken, true);

        $validated = $request->validate([
            'title' => [
                'required',
                'string',
                'max:255',
                'regex:/^(?!\s*$).+$/',
            ],
            'author' => [
                'required',
                'string',
                'max:255',
                'regex:/^[\pL\s\.]+$/u',
            ],
            'excerpt' => [
                'required',
                'string',
                'max:500',
                'regex:/^(?!\s*$).+$/',
            ],
            'content' => [
                'required',
                'string',
                'min:20',
                'max:5000',
            ],
            'post_categories' => [
                'required',
                'string',
                'max:100',
                'regex:/^[\pL\s,]+$/u',
            ],
            'status' => [
                'required',
                'string',
                'in:draft,published,archived',
            ],
            'published_at' => [
                'nullable',
                'date',
                'after_or_equal:today',
            ],
            'image' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,gif,webp',
                'max:2048',
            ],
        ], [
            'title.required' => 'Tiêu đề không được để trống hoặc toàn khoảng trắng.',
            'title.regex' => 'Tiêu đề không hợp lệ.',
            'author.required' => 'Tên tác giả không được để trống.',
            'author.regex' => 'Tên tác giả chỉ được chứa chữ cái, khoảng trắng hoặc dấu chấm.',
            'excerpt.required' => 'Mô tả ngắn không được để trống.',
            'excerpt.max' => 'Mô tả ngắn tối đa 500 ký tự.',
            'content.required' => 'Nội dung không được để trống.',
            'content.min' => 'Nội dung phải có ít nhất 20 ký tự.',
            'content.max' => 'Nội dung chỉ được tối đa 5000 ký tự.',
            'post_categories.required' => 'Danh mục bài viết không được để trống.',
            'post_categories.regex' => 'Danh mục không tồn tại.',
            'status.required' => 'Trạng thái là bắt buộc.',
            'status.in' => 'Trạng thái không hợp lệ.',
            'published_at.date' => 'Ngày xuất bản không hợp lệ.',
            'published_at.after_or_equal' => 'Ngày xuất bản phải bắt đầu từ hôm nay.',
            'image.image' => 'Ảnh phải là file ảnh hợp lệ.',
            'image.mimes' => 'Ảnh phải có định dạng jpg, jpeg, png, gif hoặc webp.',
            'image.max' => 'Ảnh không được vượt quá 2MB.',
        ]);


        $slug = \Str::slug($validated['title']);
        $originalSlug = $slug;
        $count = 1;

        while (\App\Models\Post::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $count++;
        }

        $validated['slug'] = $slug;

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $extension = $file->getClientOriginalExtension();
            $fileName = time() . '-' . \Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $extension;


            $file->move(public_path('images/posts'), $fileName);


            $validated['image'] = 'images/posts/' . $fileName;
        }



        $post = \App\Models\Post::create($validated);



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
    public function update(Request $request, \App\Models\Post $post)
    {

        \Log::info('update() đang chạy', $request->all());
        if ($request->updated_at != $post->updated_at) {
            return back()
                ->with('error', 'Dữ liệu sự kiện đã được cập nhật ở tab khác. Vui lòng tải lại trang trước khi cập nhật.')
                ->withInput();
        }

        $validated = $request->validate([
            'title' => [
                'required',
                'string',
                'max:255',
                'regex:/^(?!\s*$).+$/',
            ],
            'author' => [
                'required',
                'string',
                'max:255',
                'regex:/^[\pL\s\.]+$/u',
            ],
            'excerpt' => [
                'required',
                'string',
                'max:500',
                'regex:/^(?!\s*$).+$/',
            ],
            'content' => [
                'required',
                'string',
                'min:20',
                'max:5000'
            ],
            'post_categories' => [
                'required',
                'string',
                'max:100',
                'regex:/^[\pL\s,]+$/u',
            ],
            'status' => [
                'required',
                'string',
                'in:draft,published,archived',
            ],
            'published_at' => [
                'nullable',
                'date',
            ],
            'image' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,gif,webp',
                'max:2048',
            ],
        ], [
            'title.required' => 'Tiêu đề không được để trống hoặc toàn khoảng trắng.',
            'title.regex' => 'Tiêu đề không hợp lệ.',
            'author.required' => 'Tên tác giả không được để trống.',
            'author.regex' => 'Tên tác giả chỉ được chứa chữ cái, khoảng trắng hoặc dấu chấm.',
            'excerpt.required' => 'Mô tả ngắn không được để trống.',
            'excerpt.max' => 'Mô tả ngắn tối đa 500 ký tự.',
            'content.required' => 'Nội dung không được để trống.',
            'content.min' => 'Nội dung phải có ít nhất 20 ký tự.',
            'content.max' => 'Nội dung chỉ được tối đa 5000 ký tự.',
            'post_categories.required' => 'Danh mục bài viết không được để trống.',
            'post_categories.regex' => 'Danh mục không tồn tại.',
            'status.required' => 'Trạng thái là bắt buộc.',
            'status.in' => 'Trạng thái không hợp lệ.',
            'published_at.date' => 'Ngày xuất bản không hợp lệ.',
            'image.image' => 'Ảnh phải là file ảnh hợp lệ.',
            'image.mimes' => 'Ảnh phải có định dạng jpg, jpeg, png, gif hoặc webp.',
            'image.max' => 'Ảnh không được vượt quá 2MB.',
        ]);

        // 3️⃣ Cập nhật slug (nếu tiêu đề thay đổi)
        if ($validated['title'] !== $post->title) {
            $slug = \Str::slug($validated['title']);
            $originalSlug = $slug;
            $count = 1;

            while (\App\Models\Post::where('slug', $slug)->where('id', '!=', $post->id)->exists()) {
                $slug = $originalSlug . '-' . $count++;
            }

            $validated['slug'] = $slug;
        }

        // 4️⃣ Nếu có upload ảnh mới thì xử lý thay thế
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $extension = $file->getClientOriginalExtension();
            $fileName = time() . '-' . \Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $extension;

            // Tạo thư mục nếu chưa có
            $destination = public_path('images/posts');
            if (!file_exists($destination)) {
                mkdir($destination, 0755, true);
            }

            // Lưu file mới
            $file->move($destination, $fileName);
            $validated['image'] = 'images/posts/' . $fileName;

            // Xóa ảnh cũ nếu có
            if ($post->image && file_exists(public_path($post->image))) {
                unlink(public_path($post->image));
            }
        }

        // 5️⃣ Cập nhật bài viết
        $post->update($validated);

        // 6️⃣ Trả về với thông báo thành công
        return redirect()
            ->route('admin.posts.index')
            ->with('success', 'Cập nhật bài viết thành công!');

    }


    public function destroy($id)
    {
        $post = Post::find($id);

        if (!$post) {
            return redirect()->route('admin.posts.index')
                ->with('error', 'Bài viết không tồn tại hoặc đã bị xoá.');
        }
        $post->delete();

        return redirect()->route('admin.posts.index')
            ->with('success', '🗑️ Đã xóa bài viết thành công!');
    }
}
