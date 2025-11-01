@extends('layouts.admin-with-sidebar')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-newspaper mr-2"></i>
                        Báo cáo chi tiết bài viết
                    </h3>
                </div>
                <div class="card-body">
                    <!-- Bộ lọc -->
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <form method="GET" action="{{ route('admin.reports.posts') }}" class="form-inline">
                                <div class="form-group mr-2">
                                    <input type="text" name="search" class="form-control" placeholder="Tìm kiếm..." value="{{ $search }}">
                                </div>
                                <div class="form-group mr-2">
                                    <select name="status" class="form-control">
                                        <option value="">Tất cả trạng thái</option>
                                        <option value="published" {{ $status == 'published' ? 'selected' : '' }}>Đã xuất bản</option>
                                        <option value="draft" {{ $status == 'draft' ? 'selected' : '' }}>Bản nháp</option>
                                    </select>
                                </div>
                                <div class="form-group mr-2">
                                    <select name="category" class="form-control">
                                        <option value="">Tất cả danh mục</option>
                                        <option value="Tin tức" {{ $category == 'Tin tức' ? 'selected' : '' }}>Tin tức</option>
                                        <option value="Hướng dẫn" {{ $category == 'Hướng dẫn' ? 'selected' : '' }}>Hướng dẫn</option>
                                        <option value="Sự kiện" {{ $category == 'Sự kiện' ? 'selected' : '' }}>Sự kiện</option>
                                        <option value="Thông báo" {{ $category == 'Thông báo' ? 'selected' : '' }}>Thông báo</option>
                                    </select>
                                </div>
                                <div class="form-group mr-2">
                                    <input type="date" name="date_from" class="form-control" value="{{ $dateFrom }}">
                                </div>
                                <div class="form-group mr-2">
                                    <input type="date" name="date_to" class="form-control" value="{{ $dateTo }}">
                                </div>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-search"></i> Tìm kiếm
                                </button>
                                <a href="{{ route('admin.reports.posts') }}" class="btn btn-secondary ml-2">
                                    <i class="fas fa-refresh"></i> Làm mới
                                </a>
                            </form>
                        </div>
                    </div>
                    
                    <!-- Bảng dữ liệu -->
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Tiêu đề</th>
                                    <th>Tác giả</th>
                                    <th>Danh mục</th>
                                    <th>Trạng thái</th>
                                    <th>Ngày tạo</th>
                                    <th>Ngày xuất bản</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($posts as $post)
                                <tr>
                                    <td>{{ $loop->iteration + ($posts->currentPage() - 1) * $posts->perPage() }}</td>
                                    <td>
                                        <strong>{{ Str::limit($post->title, 50) }}</strong>
                                    </td>
                                    <td>{{ $post->author }}</td>
                                    <td>
                                        <span class="badge badge-secondary">{{ $post->category }}</span>
                                    </td>
                                    <td>
                                        @if($post->status == 'published')
                                            <span class="badge badge-success">Đã xuất bản</span>
                                        @else
                                            <span class="badge badge-warning">Bản nháp</span>
                                        @endif
                                    </td>
                                    <td>{{ $post->created_at->format('d/m/Y H:i') }}</td>
                                    <td>
                                        @if($post->publish_date)
                                            {{ \Carbon\Carbon::parse($post->publish_date)->format('d/m/Y') }}
                                        @else
                                            <span class="text-muted">Chưa xuất bản</span>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center">Không có dữ liệu</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Phân trang -->
                    <div class="d-flex justify-content-center">
                        {{ $posts->appends(request()->query())->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
