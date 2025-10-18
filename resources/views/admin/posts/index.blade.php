@extends('dashboard.app')

@section('content')

@if ($errors->has('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ $errors->first('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<div class="container py-4">
    <h1 class="text-2xl font-semibold mb-4">Post Management</h1>

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="mb-3 d-flex justify-content-between align-items-center">
        <form action="{{ route('posts.index') }}" method="GET" class="row g-2">
            <div class="col position-relative">
                <input type="text" name="search" id="search" class="form-control" placeholder="Tìm tiêu đề bài viết..." value="{{ request('search') }}" autocomplete="off">
                <ul id="search-suggestions" class="list-group position-absolute" style="z-index: 999; width: 100%; max-height: 200px; overflow-y: auto;"></ul>
            </div>
            <div class="col">
                <button type="submit" class="btn btn-primary">Tìm kiếm</button>
                <a href="{{ route('posts.index') }}" class="btn btn-secondary">Xoá lọc</a>
            </div>
        </form>

        <a href="{{ route('posts.create') }}" class="btn btn-success">Add New Post</a>
    </div>

    <div class="table-responsive shadow-sm">
        <table class="table table-bordered align-middle">
            <thead class="table-light">
                <tr>
                    <th>ID</th>
                    <th>Title</th>
                    <th>Image</th>
                    <th>Author</th>
                    <th>Created At</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($posts as $post)
                    <tr>
                        <td>{{ $post->post_id }}</td>
                        <td>{{ $post->title }}</td>
                        <td>
                            @if ($post->image)
                                <img src="{{ asset($post->image) }}" alt="Post Image" width="100">
                            @else
                                <span class="text-muted fst-italic">No image</span>
                            @endif
                        </td>
                        <td>{{ $post->user->full_name ?? 'N/A' }}</td>
                        <td>{{ $post->created_at->format('d/m/Y H:i') }}</td>
                        <td class="text-end">
                            <a href="{{ route('posts.edit', $post->post_id) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                            <form action="{{ route('posts.destroy', $post->post_id) }}" method="POST" class="d-inline delete-form">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted">Không có bài viết nào.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-3">
        {{ $posts->links('pagination::bootstrap-5') }}
    </div>
</div>

@endsection

@push('scripts')
<script>
    document.querySelectorAll('.delete-form').forEach(form => {
        form.addEventListener('submit', function (e) {
            if (!confirm('Bạn có chắc muốn xoá bài viết này không?')) {
                e.preventDefault();
            }
        });
    });

</script>
@endpush
