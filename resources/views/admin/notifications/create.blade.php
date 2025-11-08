@extends('layouts.admin-with-sidebar')

@section('title', 'Gửi thông báo mới - Admin')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-12 col-lg-10 col-xl-8">
            <div class="card shadow-soft">
                <div class="card-body">
                    <h2 class="h4 mb-4">📢 Gửi thông báo mới</h2>
                    <form method="POST" action="{{ route('admin.notifications.store') }}" enctype="multipart/form-data">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label">Tiêu đề thông báo <span class="text-danger">*</span></label>
                            <input type="text" name="title" value="{{ old('title') }}" required class="form-control" placeholder="Nhập tiêu đề thông báo">
                            @error('title') <div class="text-danger small">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Nội dung <span class="text-danger">*</span></label>
                            <textarea name="content" required rows="6" class="form-control" placeholder="Nhập nội dung thông báo">{{ old('content') }}</textarea>
                            @error('content') <div class="text-danger small">{{ $message }}</div> @enderror
                        </div>

                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Loại thông báo <span class="text-danger">*</span></label>
                                <select name="type" required class="form-select">
                                    <option value="">-- Chọn loại --</option>
                                    <option value="announcement" {{ old('type') == 'announcement' ? 'selected' : '' }}>📢 Thông báo chung</option>
                                    <option value="academic" {{ old('type') == 'academic' ? 'selected' : '' }}>📚 Học vụ</option>
                                    <option value="event" {{ old('type') == 'event' ? 'selected' : '' }}>🎉 Sự kiện</option>
                                    <option value="urgent" {{ old('type') == 'urgent' ? 'selected' : '' }}>⚠️ Khẩn cấp</option>
                                </select>
                                @error('type') <div class="text-danger small">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">File đính kèm</label>
                                <input type="file" name="attachment" class="form-control" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
                                <small class="text-muted">Tối đa 10MB</small>
                                @error('attachment') <div class="text-danger small">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Gửi đến <span class="text-danger">*</span></label>
                            <select name="send_to_type" id="send_to_type" required class="form-select">
                                <option value="">-- Chọn đối tượng --</option>
                                <option value="all" {{ old('send_to_type') == 'all' ? 'selected' : '' }}>👥 Tất cả sinh viên</option>
                                <option value="role" {{ old('send_to_type') == 'role' ? 'selected' : '' }}>🏷️ Theo vai trò</option>
                                <option value="user" {{ old('send_to_type') == 'user' ? 'selected' : '' }}>👤 Sinh viên cụ thể</option>
                            </select>
                            @error('send_to_type') <div class="text-danger small">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-3" id="target_role_div" style="display: none;">
                            <label class="form-label">Vai trò <span class="text-danger">*</span></label>
                            <select name="target_role" id="target_role" class="form-select">
                                <option value="">-- Chọn vai trò --</option>
                                <option value="admin" {{ old('target_role') == 'admin' ? 'selected' : '' }}>Admin</option>
                                <option value="user" {{ old('target_role') == 'user' ? 'selected' : '' }}>Sinh viên</option>
                            </select>
                            @error('target_role') <div class="text-danger small">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-3" id="user_ids_div" style="display: none;">
                            <label class="form-label">Chọn sinh viên <span class="text-danger">*</span></label>
                            <div style="max-height: 200px; overflow-y: auto; border: 1px solid #dee2e6; border-radius: 0.375rem; padding: 0.75rem;">
                                @foreach($users as $user)
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="user_ids[]" value="{{ $user->user_id }}" id="user_{{ $user->user_id }}" {{ in_array($user->user_id, old('user_ids', [])) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="user_{{ $user->user_id }}">
                                            {{ $user->name }} ({{ $user->email }})
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                            @error('user_ids') <div class="text-danger small">{{ $message }}</div> @enderror
                            @error('user_ids.*') <div class="text-danger small">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Thời gian gửi</label>
                            <input type="datetime-local" name="scheduled_at" value="{{ old('scheduled_at') }}" class="form-control">
                            <small class="text-muted">Để trống để gửi ngay lập tức</small>
                            @error('scheduled_at') <div class="text-danger small">{{ $message }}</div> @enderror
                        </div>

                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('admin.notifications.index') }}" class="btn btn-outline-secondary">Hủy</a>
                            <button type="submit" class="btn btn-admin">
                                <i class="fas fa-paper-plane me-2"></i>Gửi thông báo
                            </button>
                        </div>
                    </form>
                </div>
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
        targetRoleDiv.style.display = 'none';
        userIdsDiv.style.display = 'none';
        targetRole.required = false;
        
        // Hiển thị div phù hợp
        if (sendToType === 'role') {
            targetRoleDiv.style.display = 'block';
            targetRole.required = true;
        } else if (sendToType === 'user') {
            userIdsDiv.style.display = 'block';
        }
    });
    
    // Trigger on page load nếu có old value
    document.getElementById('send_to_type').dispatchEvent(new Event('change'));
</script>
@endpush
@endsection

