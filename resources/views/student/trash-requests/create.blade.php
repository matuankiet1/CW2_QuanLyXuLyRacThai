{{-- 
    View: Tạo yêu cầu thu gom rác mới (Student)
    Route: GET /student/trash-requests/create
    Controller: TrashRequestController@create
--}}
@extends('layouts.user')

@section('title', 'Tạo yêu cầu thu gom rác')

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

    {{-- Header --}}
    <div class="mb-8 text-center">
        <h1 class="text-4xl font-bold text-gray-900 mb-2">🗑️ Tạo yêu cầu thu gom rác</h1>
        <p class="text-gray-600 text-lg">Điền thông tin để gửi yêu cầu thu gom rác</p>
    </div>

    {{-- Form --}}
    <div class="bg-white rounded-lg shadow-md p-8">
        <form action="{{ route('student.trash-requests.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            {{-- Location --}}
            <div class="mb-6">
                <label for="location" class="block text-sm font-medium text-gray-700 mb-2">
                    Địa điểm thu gom <span class="text-red-500">*</span>
                </label>
                <input type="text" 
                       id="location" 
                       name="location" 
                       value="{{ old('location') }}" 
                       required
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent @error('location') border-red-500 @enderror"
                       placeholder="Ví dụ: Khu A, Tòa nhà B, Phòng 101">
                @error('location')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Type --}}
            <div class="mb-6">
                <label for="type" class="block text-sm font-medium text-gray-700 mb-2">
                    Loại rác <span class="text-red-500">*</span>
                </label>
                <input type="text" 
                       id="type" 
                       name="type" 
                       value="{{ old('type') }}" 
                       required
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent @error('type') border-red-500 @enderror"
                       placeholder="Ví dụ: Rác tái chế, Rác hữu cơ, Rác thải điện tử...">
                @error('type')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Description --}}
            <div class="mb-6">
                <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                    Mô tả chi tiết
                </label>
                <textarea id="description" 
                          name="description" 
                          rows="4"
                          class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent @error('description') border-red-500 @enderror"
                          placeholder="Mô tả chi tiết về loại rác, số lượng, tình trạng...">{{ old('description') }}</textarea>
                @error('description')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Image --}}
            <div class="mb-6">
                <label for="image" class="block text-sm font-medium text-gray-700 mb-2">
                    Ảnh minh chứng
                </label>
                <input type="file" 
                       id="image" 
                       name="image" 
                       accept="image/*"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent @error('image') border-red-500 @enderror"
                       onchange="previewImage(this)">
                @error('image')
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

            {{-- Buttons --}}
            <div class="flex gap-4 justify-end">
                <a href="{{ route('student.trash-requests.index') }}" 
                   class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition">
                    <i class="fas fa-times mr-2"></i>Hủy
                </a>
                <button type="submit" 
                        class="px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition font-medium">
                    <i class="fas fa-paper-plane mr-2"></i>Gửi yêu cầu
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

