@extends('layouts.admin-with-sidebar')

@section('title', 'Chỉnh sửa banner - Admin')

@section('content')
<div class="p-6">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Chỉnh sửa Banner</h1>
            <p class="text-gray-600 mt-1">Cập nhật thông tin banner hiện tại</p>
        </div>
        <a href="{{ route('admin.banners.index') }}" 
           class="px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition">
            Quay lại
        </a>
    </div>

    <!-- Form -->
    <div class="bg-white rounded-lg shadow p-6 max-w-3xl mx-auto">
        <form action="{{ route('admin.banners.update', $banner->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <!-- Tiêu đề -->
            <div class="mb-5">
                <label for="title" class="block text-sm font-medium text-gray-700 mb-2">Tiêu đề Banner</label>
                <input type="text" id="title" name="title" 
                    value="{{ old('title', $banner->title) }}"
                    class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-green-500 focus:border-green-500"
                    placeholder="Nhập tiêu đề banner..." required>
                @error('title')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Hình ảnh -->
<div class="mb-6">
    <label class="block text-sm font-medium text-gray-700 mb-3">Hình ảnh Banner</label>

    <!-- Hiển thị ảnh hiện tại (nếu có) -->
    @if($banner->image)
        <div class="mb-4">
        <p class="text-sm font-medium text-gray-700 mb-2">Ảnh hiện tại:</p>
        <div class="current-image-container">
            @php
                $filename = basename($banner->image);
                $storagePath = storage_path('app/public/banners/' . $filename);
                $fileExists = file_exists($storagePath);
            @endphp
            
            @if($fileExists)
                <img src="{{ route('banner.image', $filename) }}" 
                     alt="{{ $banner->title }}" 
                     class="current-image">
                <div class="current-image-overlay">
                    <span class="current-image-text">Ảnh hiện tại</span>
                </div>
            @else
                <div class="bg-red-100 p-4 rounded-lg text-center">
                    <span class="text-red-600 text-sm">Ảnh không tồn tại</span>
                    <br>
                    <span class="text-gray-500 text-xs">{{ $filename }}</span>
                </div>
            @endif
        </div>
    </div>
    @endif
    
    <!-- Custom File Upload Area -->
    <div class="file-upload-area">
        <input type="file" id="image" name="image" accept="image/*" class="file-upload-input">
        
        <label for="image" class="file-upload-label cursor-pointer">
            <div class="file-upload-content">
                <div class="file-upload-icon">
                    <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                              d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
                <div class="file-upload-text">
                    <p class="text-lg font-medium text-gray-900">
                        @if($banner->image)
                            Thay đổi hình ảnh
                        @else
                            Chọn hình ảnh
                        @endif
                    </p>
                    <p class="text-sm text-gray-500">PNG, JPG, JPEG, GIF (Tối đa 2MB)</p>
                </div>
                <div class="file-upload-button">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                              d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                    </svg>
                    Chọn tệp
                </div>
            </div>
        </label>
        
        <!-- File Info (khi có file được chọn) -->
        <div id="fileInfo" class="file-info hidden">
            <div class="file-info-content">
                <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                          d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span id="fileName" class="file-name"></span>
                <button type="button" id="removeFile" class="remove-file-btn">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                              d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Image Preview (cho ảnh mới) -->
    <div id="imagePreview" class="mt-4 hidden">
        <p class="text-sm font-medium text-gray-700 mb-2">Ảnh mới xem trước:</p>
        <div class="preview-container">
            <img id="previewImg" src="#" alt="Preview" class="preview-image">
            <button type="button" id="removePreview" class="preview-remove-btn">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                          d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
    </div>

    @error('image')
        <p class="text-red-500 text-sm mt-2 flex items-center">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                      d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            {{ $message }}
        </p>
    @enderror
</div>


            <!-- Liên kết -->
            <div class="mb-5">
                <label for="link" class="block text-sm font-medium text-gray-700 mb-2">Liên kết</label>
                <input type="url" id="link" name="link" 
                    value="{{ old('link', $banner->link) }}"
                    class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-green-500 focus:border-green-500"
                    placeholder="https://example.com">
                @error('link')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Vị trí -->
            <div class="mb-5">
                <label for="position" class="block text-sm font-medium text-gray-700 mb-2">Vị trí hiển thị</label>
                <select id="position" name="position"
                    class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-green-500 focus:border-green-500">
                    <option value="top" {{ $banner->position == 'top' ? 'selected' : '' }}>Trang chủ - Top</option>
                    <option value="sidebar" {{ $banner->position == 'sidebar' ? 'selected' : '' }}>Sidebar</option>
                    <option value="footer" {{ $banner->position == 'footer' ? 'selected' : '' }}>Footer</option>
                </select>
                @error('position')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Trạng thái -->
            <div class="mb-5">
                <label class="block text-sm font-medium text-gray-700 mb-2">Trạng thái</label>
                <div class="flex items-center gap-4">
                    <label class="inline-flex items-center">
                        <input type="radio" name="status" value="1" 
                            {{ $banner->status ? 'checked' : '' }}
                            class="text-green-600 focus:ring-green-500 border-gray-300">
                        <span class="ml-2 text-gray-700">Hiển thị</span>
                    </label>
                    <label class="inline-flex items-center">
                        <input type="radio" name="status" value="0" 
                            {{ !$banner->status ? 'checked' : '' }}
                            class="text-red-600 focus:ring-red-500 border-gray-300">
                        <span class="ml-2 text-gray-700">Ẩn</span>
                    </label>
                </div>
            </div>

            <!-- Nút -->
            <div class="flex justify-end gap-3 mt-6">
                <a href="{{ route('admin.banners.index') }}" 
                   class="px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition">
                    Hủy
                </a>
                <button type="submit"
                    class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition">
                    Cập nhật Banner
                </button>
            </div>
        </form>
    </div>
</div>

<style>
/* File Upload Styles */
.file-upload-area {
    border: 2px dashed #d1d5db;
    border-radius: 12px;
    padding: 2rem;
    text-align: center;
    transition: all 0.3s ease;
    background: #fafafa;
    position: relative;
}

.file-upload-area:hover {
    border-color: #10b981;
    background: #f0fdf4;
}

.file-upload-area.dragover {
    border-color: #10b981;
    background: #dcfce7;
    transform: scale(1.02);
}

.file-upload-input {
    position: absolute;
    width: 100%;
    height: 100%;
    top: 0;
    left: 0;
    opacity: 0;
    cursor: pointer;
    z-index: 10;
}

.file-upload-label {
    display: block;
}

.file-upload-content {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 1rem;
}

.file-upload-icon {
    padding: 0.5rem;
}

.file-upload-text {
    text-align: center;
}

.file-upload-button {
    display: inline-flex;
    align-items: center;
    padding: 0.75rem 1.5rem;
    background: #10b981;
    color: white;
    border-radius: 8px;
    font-weight: 500;
    transition: all 0.3s ease;
}

.file-upload-button:hover {
    background: #059669;
    transform: translateY(-1px);
}

/* Current Image Styles */
.current-image-container {
    position: relative;
    display: inline-block;
}

.current-image {
    width: 300px;
    height: 200px;
    object-fit: cover;
    border-radius: 12px;
    border: 2px solid #e5e7eb;
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
}

.current-image-overlay {
    position: absolute;
    top: 8px;
    left: 8px;
    background: rgba(0, 0, 0, 0.7);
    color: white;
    padding: 0.25rem 0.75rem;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 500;
}

/* File Info Styles */
.file-info {
    margin-top: 1rem;
    padding: 1rem;
    background: #f0fdf4;
    border: 1px solid #bbf7d0;
    border-radius: 8px;
}

.file-info-content {
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.file-name {
    flex: 1;
    font-weight: 500;
    color: #065f46;
}

.remove-file-btn {
    padding: 0.25rem;
    border: none;
    background: none;
    color: #ef4444;
    cursor: pointer;
    border-radius: 4px;
    transition: background 0.2s ease;
}

.remove-file-btn:hover {
    background: #fecaca;
}

/* Preview Styles */
.preview-container {
    position: relative;
    display: inline-block;
}

.preview-image {
    width: 300px;
    height: 200px;
    object-fit: cover;
    border-radius: 12px;
    border: 2px solid #e5e7eb;
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
}

.preview-remove-btn {
    position: absolute;
    top: -8px;
    right: -8px;
    padding: 0.5rem;
    background: #ef4444;
    color: white;
    border: none;
    border-radius: 50%;
    cursor: pointer;
    transition: all 0.3s ease;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
}

.preview-remove-btn:hover {
    background: #dc2626;
    transform: scale(1.1);
}

/* Responsive */
@media (max-width: 640px) {
    .file-upload-area {
        padding: 1.5rem;
    }
    
    .current-image,
    .preview-image {
        width: 100%;
        max-width: 300px;
        height: 180px;
    }
    
    .file-upload-content {
        gap: 0.75rem;
    }
}
</style>
<!-- JS Preview ảnh mới -->
<script>
    const imageInput = document.getElementById('image');
    const previewDiv = document.getElementById('imagePreview');
    const previewImg = document.getElementById('previewImg');

    imageInput.addEventListener('change', (e) => {
        const file = e.target.files[0];
        if (file) {
            previewDiv.classList.remove('hidden');
            previewImg.src = URL.createObjectURL(file);
        } else {
            previewDiv.classList.add('hidden');
            previewImg.src = '#';
        }
    });

    
document.addEventListener('DOMContentLoaded', function() {
    const fileInput = document.getElementById('image');
    const fileInfo = document.getElementById('fileInfo');
    const fileName = document.getElementById('fileName');
    const removeFileBtn = document.getElementById('removeFile');
    const imagePreview = document.getElementById('imagePreview');
    const previewImg = document.getElementById('previewImg');
    const removePreviewBtn = document.getElementById('removePreview');
    const fileUploadArea = document.querySelector('.file-upload-area');

    // Xử lý khi chọn file
    fileInput.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            // Kiểm tra kích thước file (2MB)
            if (file.size > 2 * 1024 * 1024) {
                alert('Kích thước file không được vượt quá 2MB');
                fileInput.value = '';
                return;
            }

            // Kiểm tra loại file
            const validTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
            if (!validTypes.includes(file.type)) {
                alert('Chỉ chấp nhận file ảnh: JPG, JPEG, PNG, GIF');
                fileInput.value = '';
                return;
            }

            // Hiển thị thông tin file
            fileName.textContent = file.name;
            fileInfo.classList.remove('hidden');
            
            // Hiển thị preview ảnh
            const reader = new FileReader();
            reader.onload = function(e) {
                previewImg.src = e.target.result;
                imagePreview.classList.remove('hidden');
            }
            reader.readAsDataURL(file);
            
            // Thêm class active cho upload area
            fileUploadArea.classList.add('active');
        }
    });

    // Xóa file
    removeFileBtn.addEventListener('click', function(e) {
        e.preventDefault();
        e.stopPropagation();
        fileInput.value = '';
        fileInfo.classList.add('hidden');
        imagePreview.classList.add('hidden');
        fileUploadArea.classList.remove('active');
    });

    // Xóa preview
    removePreviewBtn.addEventListener('click', function(e) {
        e.preventDefault();
        e.stopPropagation();
        fileInput.value = '';
        fileInfo.classList.add('hidden');
        imagePreview.classList.add('hidden');
        fileUploadArea.classList.remove('active');
    });

    // Drag and drop functionality
    ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
        fileUploadArea.addEventListener(eventName, preventDefaults, false);
    });

    function preventDefaults(e) {
        e.preventDefault();
        e.stopPropagation();
    }

    ['dragenter', 'dragover'].forEach(eventName => {
        fileUploadArea.addEventListener(eventName, highlight, false);
    });

    ['dragleave', 'drop'].forEach(eventName => {
        fileUploadArea.addEventListener(eventName, unhighlight, false);
    });

    function highlight() {
        fileUploadArea.classList.add('dragover');
    }

    function unhighlight() {
        fileUploadArea.classList.remove('dragover');
    }

    fileUploadArea.addEventListener('drop', handleDrop, false);

    function handleDrop(e) {
        const dt = e.dataTransfer;
        const files = dt.files;
        fileInput.files = files;
        
        // Kích hoạt sự kiện change
        const event = new Event('change', { bubbles: true });
        fileInput.dispatchEvent(event);
    }
});
</script>
@endsection
