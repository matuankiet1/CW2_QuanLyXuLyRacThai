@extends('layouts.admin-with-sidebar')

@section('content')
    <div class="container-fluid">
        <div class="card shadow-soft mb-4">
            <div class="card-body">
                <form method="GET" class="row g-3 align-items-end">
                    <div class="col-12 col-md-4">
                        <label class="form-label">Tìm kiếm</label>
                        <input type="text" name="search" value="{{ $search }}" class="form-control" placeholder="Tiêu đề, tác giả...">
                    </div>
                    <div class="col-6 col-md-3">
                        <label class="form-label">Danh mục</label>
                        <select name="post_categories" class="form-select">
                            <option>Tất cả danh mục</option>
                            <option>Tin tức</option>
                            <option>Kiến thức</option>
                            <option>Tuyên truyền</option>
                        </select>
                    </div>
                    <div class="col-6 col-md-3">
                        <label class="form-label">Trạng thái</label>
                        <select name="status" class="form-select">
                            <option value="all">Tất cả trạng thái</option>
                            <option value="published">Đã xuất bản</option>
                            <option value="draft">Nháp</option>
                            <option value="archived">Lưu trữ</option>
                        </select>
                    </div>
                    <div class="col-12 col-md-2 d-grid d-md-block">
                        <button class="btn btn-outline-secondary me-md-2 mb-2 mb-md-0">Lọc</button>
                        <a href="{{ route('admin.posts.create') }}" class="btn btn-admin">+ Thêm bài viết</a>
                    </div>
                </form>
            </div>
        </div>

        <div class="card shadow-soft">
            <div class="table-responsive">
                <table class="table align-middle">
                    <thead>
                        <tr>
                            <th class="text-center" style="width: 70px">STT</th>
                            <th style="width: 100px">Ảnh</th>
                            <th>Tiêu đề</th>
                            <th>Tác giả</th>
                            <th>Danh mục</th>
                            <th>Ngày xuất bản</th>
                            <th>Trạng thái</th>
                            <th class="text-end">Lượt xem</th>
                            <th class="text-end">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if ($posts->count() > 0)
                            @foreach ($posts as $index => $post)
                                <tr>
                                    <td class="text-center">{{ $posts->firstItem() + $index }}</td>
                                    <td class="text-center align-middle">
                                        @if ($post->image)
                                            <img src="{{ asset($post->image) }}" alt="{{ $post->title }}" class="img-thumbnail" style="width: 60px; height: 60px; object-fit: cover;">
                                        @else
                                            <div class="bg-light d-flex align-items-center justify-content-center text-muted small" style="width: 60px; height: 60px; border-radius: 4px;">Không có</div>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="fw-medium">{{ Str::limit($post->title, 40) }}</div>
                                        <div class="text-muted small">{{ Str::limit($post->excerpt, 60) }}</div>
                                    </td>
                                    <td>{{ $post->author }}</td>
                                    <td><span class="badge text-bg-light">{{ $post->post_categories }}</span></td>
                                    <td>{{ optional($post->published_at)->format('d/m/Y') }}</td>
                                    <td>
                                        @if ($post->status === 'published')
                                            <span class="badge text-bg-success">Đã xuất bản</span>
                                        @elseif ($post->status === 'draft')
                                            <span class="badge text-bg-secondary">Nháp</span>
                                        @else
                                            <span class="badge text-bg-warning">Lưu trữ</span>
                                        @endif
                                    </td>
                                    <td class="text-end">{{ number_format($post->views) }}</td>
                                    <td class="text-end">
                                        <div class="btn-group btn-group-sm" role="group">
                                            <a href="{{ route('admin.posts.edit', $post) }}" class="btn btn-primary">Sửa</a>
                                            <form action="{{ route('admin.posts.destroy', $post) }}" method="POST" onsubmit="return confirm('Bạn có chắc chắn muốn xóa bài viết này không?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger">Xóa</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="9" class="text-center text-muted py-4">Không có bài viết nào.</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
            <div class="card-footer bg-white">
                {{ $posts->withQueryString()->links() }}
            </div>
        </div>
    </div>
@endsection
<style>
    td img:hover {
        transform: scale(1.2);
        transition: transform 0.2s ease-in-out;
        z-index: 10;
    }
</style>