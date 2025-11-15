@extends('layouts.user')

@section('content')
<div class="max-w-xl mx-auto bg-white p-6 rounded-xl shadow-lg mt-10">
    <h2 class="text-2xl font-bold mb-4">Gửi phản hồi</h2>

    <form method="POST" action="{{ route('user.feedback.store') }}">
        @csrf

        <div class="mb-4">
            <label class="font-semibold">Họ tên</label>
            <input type="text" value="{{ auth()->user()->name }}" 
                   disabled
                   class="w-full mt-1 p-3 border rounded-lg bg-gray-100">
        </div>

        <div class="mb-4">
            <label class="font-semibold">Email</label>
            <input type="email" value="{{ auth()->user()->email }}" 
                   disabled
                   class="w-full mt-1 p-3 border rounded-lg bg-gray-100">
        </div>

        <div class="mb-4">
            <label class="font-semibold">Tiêu đề</label>
            <input type="text" name="subject"
                   class="w-full mt-1 p-3 border rounded-lg">
        </div>

        <div class="mb-4">
            <label class="font-semibold">Nội dung</label>
            <textarea name="message"
                      class="w-full mt-1 p-3 border rounded-lg h-32"></textarea>
        </div>

        <button class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700">
            Gửi phản hồi
        </button>
    </form>
</div>
@endsection
