@extends('layouts.admin')

@section('title', 'Xác nhận sinh viên đăng ký - ' . $event->title)

@section('content')
    <h1 class="text-2xl font-bold mb-4">Xác nhận sinh viên đăng ký - {{ $event->title }}</h1>

    <table class="w-full table-auto bg-white rounded-lg shadow overflow-hidden">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-4 py-2">STT</th>
                <th class="px-4 py-2">Tên</th>
                <th class="px-4 py-2">Email</th>
                <th class="px-4 py-2">Ngày đăng ký</th>
                <th class="px-4 py-2">Hành động</th>
            </tr>
        </thead>
        <tbody>
            @forelse($participants as $index => $participant)
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-2">{{ $participants->firstItem() + $index }}</td>
                    <td class="px-4 py-2">{{ $participant->name }}</td>
                    <td class="px-4 py-2">{{ $participant->email }}</td>
                    <td class="px-4 py-2">
                        {{ $participant->registered_at ? $participant->registered_at->format('d/m/Y H:i') : '-' }}
                    </td>

                    <td class="px-4 py-2">
                        <form action="{{ route('admin.events.participants.confirm', [$event->id, $participant->user_id]) }}"
                            method="POST">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="px-3 py-1 bg-blue-600 text-white rounded hover:bg-blue-700">Xác
                                nhận</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="px-4 py-6 text-center text-gray-500">Không có sinh viên nào đang chờ xác nhận.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="mt-4">
        {{ $participants->links('pagination.tailwind') }}
    </div>
@endsection