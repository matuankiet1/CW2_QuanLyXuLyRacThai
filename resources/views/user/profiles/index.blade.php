@extends('layouts.user')

@section('content')
    <div class="container max-w-7xl mx-auto pt-5">
        <div class="border border-gray-300 rounded-xl p-5 bg-white shadow-md relative">
            <button id="btnOpenModalAvatar"
                class="absolute top-3 right-3 px-4 py-2 border border-gray-300 rounded-3xl text-sm hover:bg-gray-200">
                Chỉnh sửa <i class="fa-regular fa-pen-to-square ms-1"></i>
            </button>
            <div class="flex items-center justify-center">
                @if ($user->avatar)
                    <img class="w-36 h-36 object-cover rounded-full" src="{{ asset('storage/' . $user->avatar) }}"
                        alt="Avatar">
                @else
                    <div class="w-36 h-36 bg-[#f0d364] text-white text-5xl rounded-full flex items-center justify-center">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </div>
                @endif
            </div>
        </div>

        <div class="border border-gray-300 rounded-xl mt-5 p-5 bg-white shadow-md relative">
            <button id="btnEditInfo"
                class="absolute top-3 right-3 px-4 py-2 border border-gray-300 rounded-3xl text-sm hover:bg-gray-200 {{ $errors->form_profile->any() ? 'hidden' : '' }}">
                Chỉnh sửa <i class="fa-regular fa-pen-to-square ms-1"></i>
            </button>
            <h1 class="font-semibold">Thông tin cá nhân</h1>
            <form action="{{ route('profile.update') }}" method="POST" class="profileForm mt-5">
                @csrf
                <div class="mt-5">
                    <label for="name" class="text-gray-500">Họ và tên</label>
                    <span class="field-view block mb-4 {{ $errors->form_profile->any() ? 'hidden' : '' }}"
                        data-field="name">{{ $user->name }}</span>
                    <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" data-original="{{ $user->name }}"
                        class="field-edit w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 {{ $errors->form_profile->any() ? '' : 'hidden' }}" />
                    @error('name', 'form_profile')
                        <p class="text-red-500 text-sm mt-1 profile-error">{{ $message }}</p>
                    @enderror
                </div>
                <div class="mt-5">
                    <label for="email" class="text-gray-500">Email</label>
                    <span class="field-view block mb-4 {{ $errors->form_profile->any() ? 'hidden' : '' }}"
                        data-field="email">{{ $user->email }}</span>
                    <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" data-original="{{ $user->email }}"
                        class="field-edit w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 {{ $errors->form_profile->any() ? '' : 'hidden' }}" />
                    @error('email', 'form_profile')
                        <p class="text-red-500 text-sm mt-1 profile-error">{{ $message }}</p>
                    @enderror
                </div>
                <div class="mt-5">
                    <label for="phone" class="text-gray-500">Số điện thoại</label>
                    <span class="field-view block mb-4 {{ $errors->form_profile->any() ? 'hidden' : '' }}"
                        data-field="phone">{{ $user->phone }}</span>
                    <input type="text" id="phone" name="phone" value="{{ old('phone', $user->phone) }}" data-original="{{ $user->phone }}"
                        class="field-edit w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 {{ $errors->form_profile->any() ? '' : 'hidden' }}" />
                    @error('phone', 'form_profile')
                        <p class="text-red-500 text-sm mt-1 profile-error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="btnActions flex mt-5 gap-3 {{ $errors->form_profile->any() ? '' : 'hidden' }}">
                    <button type="submit" id="btnSave"
                        class="inline-flex items-center gap-2 bg-green-600 hover:bg-green-700 text-white font-medium py-2 px-4 rounded-lg cursor-pointer transition">
                        Lưu
                    </button>

                    <button type="button" id="btnCancel"
                        class="inline-flex items-center gap-2 bg-gray-100 hover:bg-gray-300 py-2 px-4 rounded-lg cursor-pointer transition">
                        Hủy
                    </button>
                </div>
            </form>

        </div>

        <div class="border border-gray-300 rounded-xl mt-5 p-5 bg-white">
            <h1 class="font-semibold mb-5">Bảo mật</h1>
            @if ($user->auth_provider != 'local')
                <p class="">Tài khoản của bạn đang đăng nhập bằng <span
                        class="font-bold">{{ ucfirst($user->auth_provider) }}</span>.
                    Mật khẩu được quản lý bởi <span class="font-bold">{{ ucfirst($user->auth_provider) }}</span>, vì vậy
                    không
                    thể đổi mật khẩu tại đây.</p>
            @else
                <button type="button" id="btnShowFormChangePassword"
                    class="text-blue-600 underline {{ $errors->form_change_password->any() ? 'hidden' : '' }}">Đổi mật
                    khẩu</button>
            @endif

            <form action="{{ route('change_password') }}" method="POST" id="formChangePassword"
                class="{{ $errors->form_change_password->any() ? '' : 'hidden' }}">
                @csrf
                <div class="mt-5">
                    <label for="current_password" class="text-sm font-medium text-gray-700">Mật khẩu hiện tại: <span
                            class="text-red-500">*</span></label>
                    <div class="relative">
                        <input type="password" id="current_password" name="current_password" placeholder="••••••••" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500" />
                        @error('current_password', 'form_change_password')
                            <p class="text-red-500 text-sm mt-1 password-error">{{ $message }}</p>
                        @enderror
                        <button type="button"
                            class="btnTogglePassword absolute inset-y-0 right-3 flex items-center text-gray-500 hover:text-gray-700">
                            <i class="fa-regular fa-eye"></i>
                        </button>
                    </div>
                </div>

                <div class="mt-5">
                    <label for="new_password" class="text-sm font-medium text-gray-700">Mật khẩu mới: <span
                            class="text-red-500">*</span></label>
                    <div class="relative">
                        <input type="password" id="new_password" name="new_password" placeholder="••••••••" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500" />
                        @error('new_password', 'form_change_password')
                            <p class="text-red-500 text-sm mt-1 password-error">{{ $message }}</p>
                        @enderror
                        <button type="button"
                            class="btnTogglePassword absolute inset-y-0 right-3 flex items-center text-gray-500 hover:text-gray-700">
                            <i class="fa-regular fa-eye"></i>
                        </button>
                    </div>
                </div>

                <div class="mt-5">
                    <label for="renew_password" class="text-sm font-medium text-gray-700">Xác nhận mật khẩu mới: <span
                            class="text-red-500">*</span></label>
                    <div class="relative">
                        <input type="password" id="renew_password" name="new_password_confirmation"
                            placeholder="••••••••" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500" />
                        @error('new_password_confirmation', 'form_change_password')
                            <p class="text-red-500 text-sm mt-1 password-error">{{ $message }}</p>
                        @enderror
                        <button type="button"
                            class="btnTogglePassword absolute inset-y-0 right-3 flex items-center text-gray-500 hover:text-gray-700">
                            <i class="fa-regular fa-eye"></i>
                        </button>
                    </div>
                </div>

                <div class="btnActionsPassword flex mt-5 gap-3">
                    <button type="submit"
                        class="inline-flex items-center gap-2 bg-green-600 hover:bg-green-700 text-white font-medium py-2 px-4 rounded-lg cursor-pointer transition">
                        Lưu
                    </button>

                    <button type="button" id="btnCancelChangePassword"
                        class="inline-flex items-center gap-2 bg-gray-100 hover:bg-gray-300 py-2 px-4 rounded-lg cursor-pointer transition">
                        Hủy
                    </button>
                </div>
            </form>
        </div>

        {{-- Modal chọn chức năng avatar --}}
        <div id="avatarActionModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/40">
            <div id="avatarActionPanel"
                class="bg-white rounded-2xl shadow-lg w-full max-w-sm p-6 space-y-5 transform transition-all duration-300 ease-out
                opacity-0 translate-y-4 scale-95">

                {{-- Header --}}
                <div class="flex items-start justify-between">
                    <div>
                        <h2 class="text-lg font-semibold text-gray-800">Chỉnh sửa ảnh đại diện</h2>
                        <p class="text-sm text-gray-500 mt-1">
                            Chọn một thao tác bên dưới để cập nhật ảnh đại diện của bạn.
                        </p>
                    </div>
                    <button type="button" id="btnX" class="text-gray-400 hover:text-gray-600">
                        ✕
                    </button>
                </div>

                {{-- Các nút chức năng --}}
                <div class="space-y-3">
                    <button type="button" id="btnPickNewAvatar"
                        class="w-full inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-lg
                           border border-green-500 text-green-600 text-sm font-medium
                           hover:bg-green-50">
                        <span>Chọn ảnh từ thiết bị</span>
                    </button>

                    <button type="button" disabled
                        class="w-full inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-lg
                           border border-blue-500 text-blue-600 text-sm font-medium
                           hover:bg-blue-50 opacity-50 cursor-not-allowed pointer-events-none">
                        <span>Chụp ảnh mới bằng camera</span>
                    </button>

                    @if (auth()->user()->avatar)
                        <form action="{{ route('profile.delete-avatar') }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                class="w-full inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-lg
                                   border border-red-500 text-red-600 text-sm font-medium
                                   hover:bg-red-50">
                                <span>Xóa ảnh đại diện hiện tại</span>
                            </button>
                        </form>
                    @endif

                    <p class="textPreview text-center text-sm italic text-gray-600"></p>
                    <!-- Vùng preview -->

                    <img id="avatarPreview" src="{{ auth()->user()->avatar_url }}"
                        class="w-28 h-28 rounded-full object-cover border mx-auto hidden">
                </div>

                {{-- Actions --}}
                <div class="btnActionsAvatar flex justify-end gap-3 pt-3 hidden">
                    <button type="button" id="btnCancelModalAvatar"
                        class="px-4 py-2 rounded-lg bg-gray-100 hover:bg-gray-300 cursor-pointer">Hủy</button>
                    <button type="button" id="saveAvatarBtn"
                        class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg cursor-pointer">Lưu</button>
                </div>
            </div>
        </div>

        {{-- Input file ẩn để chọn ảnh mới --}}
        <form id="avatarUploadForm" action="{{ route('profile.update-avatar') }}" method="POST"
            enctype="multipart/form-data" class="hidden">
            @csrf
            <input type="file" id="avatarFileInput" name="avatar" accept="image/*">
        </form>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Ẩn/hiện các trường chỉnh sửa thông tin cá nhân
            const profileForm = document.querySelector('.profileForm');
            const btnEditInfo = document.querySelector('#btnEditInfo');
            const btnCancel = document.querySelector('#btnCancel');
            const btnActions = document.querySelector('.btnActions');
            const arrFieldView = document.querySelectorAll('.field-view');
            const arrFieldEdit = document.querySelectorAll('.field-edit');

            btnEditInfo.addEventListener('click', function() {
                resetToOriginalValues();
                arrFieldView.forEach(el => {
                    el.classList.add('hidden');
                });
                arrFieldEdit.forEach(el => {
                    el.classList.remove('hidden');
                });
                btnActions.classList.remove('hidden');
                this.classList.add('hidden');
            });

            btnCancel.addEventListener('click', function() {
                profileForm.reset();

                arrFieldView.forEach(el => {
                    el.classList.remove('hidden');
                });
                arrFieldEdit.forEach(el => {
                    el.classList.add('hidden');
                });
                btnActions.classList.add('hidden');
                btnEditInfo.classList.remove('hidden');

                // Ẩn các errors message của validator
                document.querySelectorAll('.profile-error').forEach(el => {
                    el.classList.add('hidden');
                });
            });

            function resetToOriginalValues() {
                profileForm.querySelectorAll('[data-original]').forEach(input => {
                    input.value = input.dataset.original;
                });
            }

            // Mở modal chỉnh sửa avatar
            const btnOpenModalAvatar = document.getElementById('btnOpenModalAvatar');
            const btnPickNewAvatar = document.getElementById('btnPickNewAvatar');
            const btnX = document.getElementById('btnX');
            const btnCancelModalAvatar = document.getElementById('btnCancelModalAvatar');
            const btnActionsAvatar = document.querySelector('.btnActionsAvatar');

            const avatarActionModal = document.getElementById('avatarActionModal');
            const avatarActionPanel = document.getElementById('avatarActionPanel');
            const avatarFileInput = document.getElementById('avatarFileInput');
            const avatarUploadForm = document.getElementById('avatarUploadForm');
            const avatarPreview = document.getElementById("avatarPreview");

            btnOpenModalAvatar.addEventListener('click', openAvatarActionModal);
            btnX.addEventListener('click', closeAvatarActionModal);
            btnCancelModalAvatar.addEventListener('click', closeAvatarActionModal);

            function openAvatarActionModal() {
                avatarActionModal.classList.remove('hidden');
                avatarActionModal.classList.add('flex');

                requestAnimationFrame(() => {
                    avatarActionPanel.classList.remove('opacity-0', 'translate-y-4', 'scale-95');
                    avatarActionPanel.classList.add('opacity-100', 'translate-y-0', 'scale-100');
                });
            }

            function closeAvatarActionModal() {
                avatarActionPanel.classList.remove('opacity-100', 'translate-y-0', 'scale-100');
                avatarActionPanel.classList.add('opacity-0', 'translate-y-4', 'scale-95');

                // Sau khi animation xong mới ẩn hẳn modal
                setTimeout(() => {
                    avatarActionModal.classList.add('hidden');
                    avatarActionModal.classList.remove('flex');
                }, 300);

                resetModalAvatar();
            }

            // Click ra ngoài card để đóng modal
            avatarActionModal.addEventListener('click', function(e) {
                if (e.target === avatarActionModal) {
                    closeAvatarActionModal();
                }
            });

            // Khi bấm "Chọn ảnh mới từ máy" -> mở input file
            btnPickNewAvatar.addEventListener('click', function() {
                avatarFileInput.click();
            });

            // Khi user chọn file xong -> Hiển thị ảnh preview
            avatarFileInput.addEventListener('change', function() {
                const file = avatarFileInput.files[0];

                if (!file) return;

                const reader = new FileReader();

                reader.onload = function(e) {
                    avatarPreview.src = e.target.result; // Hiển thị ảnh preview
                    avatarPreview.classList.remove("hidden"); // Hiện preview
                };

                reader.readAsDataURL(file);

                document.querySelector('.textPreview').innerText = "Xem trước:";
                btnActionsAvatar.classList.remove("hidden");
            });

            btnActionsAvatar.querySelector('#saveAvatarBtn').addEventListener('click', function() {
                avatarUploadForm.submit();
            });

            function resetModalAvatar() {
                avatarPreview.src = "";
                avatarPreview.classList.add("hidden");
                avatarFileInput.value = "";
                document.querySelector('.textPreview').innerText = "";
                btnActionsAvatar.classList.add("hidden");
            }

            // Icon ẩn/hiện mật khẩu
            const btnsTogglePassword = document.querySelectorAll('.btnTogglePassword');
            btnsTogglePassword.forEach(btn => {
                btn.addEventListener('click', function() {
                    const input = this.previousElementSibling;
                    const icon = this.querySelector('i');

                    if (input.type === "password") {
                        input.type = "text";
                        icon.classList.remove('fa-eye');
                        icon.classList.add('fa-eye-slash');
                    } else {
                        input.type = "password";
                        icon.classList.remove('fa-eye-slash');
                        icon.classList.add('fa-eye');
                    }
                });
            });

            const btnShowFormChangePassword = document.getElementById("btnShowFormChangePassword");
            const btnCancelChangePassword = document.getElementById("btnCancelChangePassword");
            const formChangePassword = document.getElementById("formChangePassword");

            if (btnShowFormChangePassword) {
                btnShowFormChangePassword.addEventListener("click", function() {
                    formChangePassword.classList.remove("hidden");
                    this.classList.add("hidden");
                });
            }

            if (btnCancelChangePassword) {
                btnCancelChangePassword.addEventListener("click", function() {
                    formChangePassword.reset();
                    formChangePassword.classList.add("hidden");
                    btnShowFormChangePassword.classList.remove("hidden");

                    // Ẩn các errors message của validator
                    document.querySelectorAll('.password-error').forEach(el => {
                        el.classList.add('hidden');
                    });
                });
            }

        });
    </script>
@endsection
