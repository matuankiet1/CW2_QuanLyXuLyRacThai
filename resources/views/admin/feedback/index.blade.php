@extends('layouts.admin-with-sidebar')

@section('title', 'Phản hồi từ người dùng')

@section('content')
<div class="card">
    <div class="card-header">
        <h2 class="font-semibold text-lg">Danh sách phản hồi</h2>
    </div>

    <div class="card-body">
        <table class="table">
            <thead>
                <tr>
                    <th>Người gửi</th>
                    <th>Tiêu đề</th>
                    <th>Ngày gửi</th>
                    <th>Trạng thái</th>
                    <th></th>
                </tr>
            </thead>

            <tbody>
                @foreach($feedback as $item)
                <tr>
                    <td>{{ $item->user->name }}</td>
                    <td>{{ $item->subject }}</td>
                    <td>{{ $item->created_at->format('d/m/Y H:i') }}</td>
                    <td>
                        @if($item->reply)
                            <span class="badge badge-success">Đã trả lời</span>
                        @else
                            <span class="badge badge-warning">Chưa trả lời</span>
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('admin.feedback.show', $item->id) }}" class="btn-primary text-sm">
                            Xem chi tiết
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="mt-4">
            {{ $feedback->links() }}
        </div>
    </div>
</div>
@endsection
