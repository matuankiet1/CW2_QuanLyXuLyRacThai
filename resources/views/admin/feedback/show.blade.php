@extends('layouts.admin-with-sidebar')

@section('title', 'Chi tiết phản hồi')

@section('content')
<div class="card">
    <div class="card-header">
        <h2 class="font-semibold text-lg">Chi tiết phản hồi</h2>
    </div>

    <div class="card-body space-y-4">
        <div>
            <strong>Người gửi:</strong> {{ $feedback->user->name }} ({{ $feedback->user->email }})
        </div>

        <div>
            <strong>Tiêu đề:</strong> {{ $feedback->subject }}
        </div>

        <div>
            <strong>Nội dung:</strong>
            <p class="mt-2 bg-gray-100 p-4 rounded">{{ $feedback->message }}</p>
        </div>

        @if($feedback->reply)
        <div class="bg-green-50 p-4 rounded">
            <strong>Phản hồi của admin:</strong>
            <p class="mt-2">{{ $feedback->reply }}</p>
            <small class="text-gray-600">Vào lúc: {{ $feedback->reply_at }}</small>
        </div>
        @endif

        <form action="{{ route('admin.feedback.reply', $feedback->id) }}" method="POST">
            @csrf
            <label class="form-label">Phản hồi của admin</label>
            <textarea name="reply" class="form-control" rows="4">{{ old('reply', $feedback->reply) }}</textarea>

            <button class="btn-primary mt-3">Gửi phản hồi</button>
        </form>
    </div>
</div>
@endsection
