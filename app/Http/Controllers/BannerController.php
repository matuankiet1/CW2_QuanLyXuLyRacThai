<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BannerController extends Controller
{
    // Danh sách banner
    public function index(Request $request)
    {
        $search = $request->input('search');

    $banners = \App\Models\Banner::when($search, function ($query, $search) {
        return $query->where('name', 'like', "%{$search}%")
                     ->orWhere('description', 'like', "%{$search}%");
    })
    ->orderBy('created_at', 'desc')
    ->paginate(3);

    return view('banners.index', compact('banners', 'search'));
    }

    // Hiển thị form thêm
    public function create()
    {
        return view('banners.create');
    }

    // Lưu banner
    public function store(Request $request)
    {
        // dd('Đã vào được store()', $request->all());
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'link' => 'nullable|url',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'description' => 'nullable|string',
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('banners', 'public');
        }

        Banner::create($validated);

        return redirect()->route('banners.index')->with('success', 'Thêm banner thành công!');
    }

    // Hiển thị form sửa
    public function edit(Banner $banner)
    {
        return view('banners.edit', compact('banner'));
    }

    // Cập nhật
    public function update(Request $request, Banner $banner)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'link' => 'nullable|url',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'description' => 'nullable|string',
        ]);

        if ($request->hasFile('image')) {
            // Xóa ảnh cũ
            if ($banner->image) {
                Storage::disk('public')->delete($banner->image);
            }
            $validated['image'] = $request->file('image')->store('banners', 'public');
        }

        $banner->update($validated);

        return redirect()->route('banners.index')->with('success', 'Cập nhật banner thành công!');
    }

    // Xóa
    public function destroy(Banner $banner)
    {
        if ($banner->image) {
            Storage::disk('public')->delete($banner->image);
        }

        $banner->delete();
        return redirect()->route('banners.index')->with('success', 'Xóa banner thành công!');
    }
}


