<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;


class BannerController extends Controller
{
    /**
     * Hiển thị danh sách banners
     */
    public function index(Request $request)
    {
        $sort = $request->get('sort', 'newest'); // default sort
        $search = $request->get('search');

        $query = Banner::query();


        // 🔍 Nếu có từ khóa tìm kiếm
        if ($search) {
            $query->where('title', 'like', "%{$search}%");
        }

        switch ($sort) {
            case 'oldest':
                $query->orderBy('created_at', 'asc');
                break;
            case 'active':
                $query->where('status', 1)->orderBy('created_at', 'desc');
                break;
            case 'inactive':
                $query->where('status', 0)->orderBy('created_at', 'desc');
                break;
            case 'position':
                $query->orderBy('position', 'asc');
                break;
            default:
            $query->orderBy('created_at', 'desc');
    }
        $banners = $query->paginate(4);
        
        return view('admin.banners.index', compact('banners', 'sort', 'search'));
    }

    /**
     * Hiển thị form tạo banner mới
     */
    public function create()
    {
        return view('admin.banners.create');
    }

    /**
     * Lưu banner mới
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'position' => 'required|in:top,sidebar,footer',
            'status' => 'boolean',
            'link' => 'nullable|url',
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('banners', 'public');
        }

        Banner::create($validated);

        return redirect()->route('admin.banners.index')
            ->with('success', 'Banner đã được tạo thành công!');
    }

    /**
     * Hiển thị form chỉnh sửa banner
     */
    public function edit(Banner $banner)
    {
        return view('admin.banners.edit', compact('banner'));
    }

    /**
     * Cập nhật banner
     */
    public function update(Request $request, Banner $banner)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'position' => 'required|in:top,sidebar,footer',
            'status' => 'boolean',
            'link' => 'nullable|url',
        ]);

        if ($request->hasFile('image')) {
            // Xóa ảnh cũ nếu có
            if ($banner->image) {
                Storage::disk('public')->delete($banner->image);
            }
            $validated['image'] = $request->file('image')->store('banners', 'public');
        }

        $banner->update($validated);

        return redirect()->route('admin.banners.index')
            ->with('success', 'Banner đã được cập nhật thành công!');
    }

    /**
     * Xóa banner
     */
    public function destroy(Banner $banner)
    {
        // Xóa ảnh nếu có
        if ($banner->image) {
            Storage::disk('public')->delete($banner->image);
        }

        $banner->delete();

        return redirect()->route('admin.banners.index')
            ->with('success', 'Banner đã được xóa thành công!');
    }

    public function confirmDelete(Banner $banner)
{
    return view('admin.banners.confirm-delete', compact('banner'));
}

}