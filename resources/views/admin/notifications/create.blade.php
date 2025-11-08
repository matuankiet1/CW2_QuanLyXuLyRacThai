@extends('layouts.admin-with-sidebar')

@section('title', 'Gửi thông báo mới - Admin')

@section('content')
<div class="container mx-auto px-4">
    <div class="max-w-4xl mx-auto">
        <div class="bg-white rounded-lg shadow-md">
            <div class="p-6">
                <h2 class="text-xl font-semibold mb-4">📢 Gửi thông báo mới</h2>
                <form method="POST" action="{{ route('admin.notifications.store') }}" enctype="multipart/form-data">
                    @csrf

                    <div class="mb-4">
                        <label class="form-label">Tiêu đề thông báo <span class="text-red-500">*</span></label>
                        <input type="text" name="title" value="{{ old('title') }}" required class="form-control" placeholder="Nhập tiêu đề thông báo">
                        @error('title') <div class="text-red-500 text-sm mt-1">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-4">
                        <label class="form-label">Nội dung <span class="text-red-500">*</span></label>
                        <textarea name="content" required rows="6" class="form-control" placeholder="Nhập nội dung thông báo">{{ old('content') }}</textarea>
                        @error('content') <div class="text-red-500 text-sm mt-1">{{ $message }}</div> @enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="form-label">Loại thông báo <span class="text-red-500">*</span></label>
                            <select name="type" required class="form-control">
                                <option value="">-- Chọn loại --</option>
                                <option value="announcement" {{ old('type') == 'announcement' ? 'selected' : '' }}>📢 Thông báo chung</option>
                                <option value="academic" {{ old('type') == 'academic' ? 'selected' : '' }}>📚 Học vụ</option>
                                <option value="event" {{ old('type') == 'event' ? 'selected' : '' }}>🎉 Sự kiện</option>
                                <option value="urgent" {{ old('type') == 'urgent' ? 'selected' : '' }}>⚠️ Khẩn cấp</option>
                            </select>
                            @error('type') <div class="text-red-500 text-sm mt-1">{{ $message }}</div> @enderror
                        </div>
                        <div>
                            <label class="form-label">File đính kèm</label>
                            <input type="file" name="attachment" class="form-control" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
                            <small class="text-gray-500 text-sm">Tối đa 10MB</small>
                            @error('attachment') <div class="text-red-500 text-sm mt-1">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label">Gửi đến <span class="text-red-500">*</span></label>
                        <select name="send_to_type" id="send_to_type" required class="form-control">
                            <option value="">-- Chọn đối tượng --</option>
                            <option value="all" {{ old('send_to_type') == 'all' ? 'selected' : '' }}>👥 Tất cả sinh viên</option>
                            <option value="role" {{ old('send_to_type') == 'role' ? 'selected' : '' }}>🏷️ Theo vai trò</option>
                            <option value="user" {{ old('send_to_type') == 'user' ? 'selected' : '' }}>👤 Sinh viên cụ thể</option>
                        </select>
                        @error('send_to_type') <div class="text-red-500 text-sm mt-1">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-4 hidden" id="target_role_div">
                        <label class="form-label">Vai trò <span class="text-red-500">*</span></label>
                        <select name="target_role" id="target_role" class="form-control">
                            <option value="">-- Chọn vai trò --</option>
                            <option value="admin" {{ old('target_role') == 'admin' ? 'selected' : '' }}>Admin</option>
                            <option value="user" {{ old('target_role') == 'user' ? 'selected' : '' }}>Sinh viên</option>
                        </select>
                        @error('target_role') <div class="text-red-500 text-sm mt-1">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-4 hidden" id="user_ids_div">
                        <label class="form-label">Chọn sinh viên <span class="text-red-500">*</span></label>
                        <div class="max-h-48 overflow-y-auto border border-gray-300 rounded-md p-3 bg-gray-50">
                            @foreach($users as $user)
                                <div class="flex items-center mb-2">
                                    <input class="mr-2 h-4 w-4 text-green-600 focus:ring-green-500 border-gray-300 rounded" type="checkbox" name="user_ids[]" value="{{ $user->user_id }}" id="user_{{ $user->user_id }}" {{ in_array($user->user_id, old('user_ids', [])) ? 'checked' : '' }}>
                                    <label class="text-sm text-gray-700 cursor-pointer" for="user_{{ $user->user_id }}">
                                        {{ $user->name }} ({{ $user->email }})
                                    </label>
                                </div>
                            @endforeach
                        </div>
                        @error('user_ids') <div class="text-red-500 text-sm mt-1">{{ $message }}</div> @enderror
                        @error('user_ids.*') <div class="text-red-500 text-sm mt-1">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-4">
                        <label class="form-label">Thời gian gửi</label>
                        <input type="datetime-local" name="scheduled_at" value="{{ old('scheduled_at') }}" class="form-control">
                        <small class="text-gray-500 text-sm">Để trống để gửi ngay lập tức</small>
                        @error('scheduled_at') <div class="text-red-500 text-sm mt-1">{{ $message }}</div> @enderror
                    </div>

                    <div class="flex justify-end gap-2">
                        <a href="{{ route('admin.notifications.index') }}" class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-100 transition">Hủy</a>
                        <button type="submit" class="btn-admin">
                            <i class="fas fa-paper-plane mr-2"></i>Gửi thông báo
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.getElementById('send_to_type').addEventListener('change', function() {
        const sendToType = this.value;
        const targetRoleDiv = document.getElementById('target_role_div');
        const userIdsDiv = document.getElementById('user_ids_div');
        const targetRole = document.getElementById('target_role');
        
        // Ẩn tất cả các div
        targetRoleDiv.classList.add('hidden');
        userIdsDiv.classList.add('hidden');
        targetRole.required = false;
        
        // Hiển thị div phù hợp
        if (sendToType === 'role') {
            targetRoleDiv.classList.remove('hidden');
            targetRole.required = true;
        } else if (sendToType === 'user') {
            userIdsDiv.classList.remove('hidden');
        }
    });
    
    // Trigger on page load nếu có old value
    document.getElementById('send_to_type').dispatchEvent(new Event('change'));
</script>
@endpush
@endsection
