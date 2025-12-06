{{-- 
    View: Cập nhật nhiệm vụ (Staff)
    Route: GET /staff/trash-requests/{id}/edit
    Controller: TrashRequestController@staffEdit
--}}
@extends('layouts.user')

@section('title', 'Cập nhật nhiệm vụ')

@section('content')
<div class="container mx-auto px-4 py-8 max-w-3xl">
    {{-- Flash Messages --}}
    @if(session('error'))
        <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg relative" role="alert">
            <span class="block sm:inline">
                <i class="fas fa-exclamation-circle mr-2"></i>{{ session('error') }}
            </span>
            <button type="button" class="absolute top-0 bottom-0 right-0 px-4 py-3" onclick="this.parentElement.style.display='none'">
                <i class="fas fa-times"></i>
            </button>
        </div>
    @endif

    {{-- Back Button --}}
    <div class="mb-6">
        <a href="{{ route('staff.trash-requests.show', $trashRequest->request_id) }}" 
           class="text-green-600 hover:text-green-700 transition">
            <i class="fas fa-arrow-left mr-2"></i>Quay lại chi tiết
        </a>
    </div>

    {{-- Header --}}
    <div class="mb-8 text-center">
        <h1 class="text-4xl font-bold text-gray-900 mb-2">✏️ Cập nhật nhiệm vụ</h1>
        <p class="text-gray-600 text-lg">Cập nhật thông tin hoàn thành nhiệm vụ thu gom rác</p>
    </div>

    {{-- Info Card --}}
    <div class="bg-blue-50 border border-blue-200 rounded-lg p-6 mb-6">
        <h3 class="font-semibold text-gray-900 mb-2">Thông tin yêu cầu</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
            <div>
                <span class="text-gray-600">Địa điểm:</span>
                <span class="font-medium text-gray-900">{{ $trashRequest->location }}</span>
            </div>
            <div>
                <span class="text-gray-600">Loại rác:</span>
                <span class="font-medium text-gray-900">{{ $trashRequest->type }}</span>
            </div>
            <div>
                <span class="text-gray-600">Người gửi:</span>
                <span class="font-medium text-gray-900">{{ $trashRequest->student->name }}</span>
            </div>
        </div>
    </div>

    {{-- Form --}}
    <div class="bg-white rounded-lg shadow-md p-8">
        <form action="{{ route('staff.trash-requests.update', $trashRequest->request_id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            {{-- Proof Image --}}
            <div class="mb-6">
                <label for="proof_image" class="block text-sm font-medium text-gray-700 mb-2">
                    Ảnh minh chứng <span class="text-red-500">*</span>
                </label>
                @if($trashRequest->proof_image)
                    <div class="mb-4">
                        <label class="text-sm font-medium text-gray-500 block mb-2">Ảnh hiện tại</label>
                        <img src="{{ asset('storage/' . $trashRequest->proof_image) }}" 
                             alt="Ảnh hiện tại" 
                             class="max-w-full h-auto rounded-lg shadow-md max-h-64 mb-2">
                        <p class="text-sm text-gray-500">Upload ảnh mới để thay thế</p>
                    </div>
                @endif
                <input type="file" 
                       id="proof_image" 
                       name="proof_image" 
                       accept="image/*"
                       {{ !$trashRequest->proof_image ? 'required' : '' }}
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent @error('proof_image') border-red-500 @enderror"
                       onchange="previewImage(this)">
                @error('proof_image')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
                <p class="mt-2 text-sm text-gray-500">
                    <i class="fas fa-info-circle mr-1"></i>
                    Chấp nhận: JPG, PNG, WEBP. Tối đa 5MB
                </p>
                
                {{-- Image Preview --}}
                <div id="imagePreview" class="mt-4 hidden">
                    <img id="previewImg" src="" alt="Preview" class="max-w-full h-auto rounded-lg shadow-md max-h-64">
                </div>
            </div>

            {{-- Staff Notes --}}
            <div class="mb-6">
                <label for="staff_notes" class="block text-sm font-medium text-gray-700 mb-2">
                    Ghi chú
                </label>
                <textarea id="staff_notes" 
                          name="staff_notes" 
                          rows="5"
                          class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent @error('staff_notes') border-red-500 @enderror"
                          placeholder="Ghi chú về quá trình thu gom, số lượng, tình trạng...">{{ old('staff_notes', $trashRequest->staff_notes) }}</textarea>
                @error('staff_notes')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Info Alert --}}
            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-6">
                <p class="text-sm text-yellow-800">
                    <i class="fas fa-info-circle mr-2"></i>
                    Sau khi cập nhật, nhiệm vụ sẽ tự động chuyển sang trạng thái "Chờ duyệt" và chờ quản trị viên xem xét.
                </p>
            </div>

            {{-- Buttons --}}
            <div class="flex gap-4 justify-end">
                <a href="{{ route('staff.trash-requests.show', $trashRequest->request_id) }}" 
                   class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition">
                    <i class="fas fa-times mr-2"></i>Hủy
                </a>
                <button type="submit" 
                        class="px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition font-medium">
                    <i class="fas fa-check mr-2"></i>Hoàn thành nhiệm vụ
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    function previewImage(input) {
        const preview = document.getElementById('imagePreview');
        const previewImg = document.getElementById('previewImg');
        
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            
            reader.onload = function(e) {
                previewImg.src = e.target.result;
                preview.classList.remove('hidden');
            }
            
            reader.readAsDataURL(input.files[0]);
        } else {
            preview.classList.add('hidden');
        }
    }
</script>
@endpush
@endsection

