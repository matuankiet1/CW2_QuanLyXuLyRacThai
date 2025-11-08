@extends('layouts.dashboard')

@section('main-content')
    <div class="max-w-7xl mx-auto">
        @if ($collectionSchedules->isEmpty() && $isSearch == false)
            <div class="bg-yellow-100 p-6 rounded-xl shadow-sm border border-gray-200">
                <p>Không có báo cáo rác thải nào.</p>
            </div>
        @else
            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200">
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
                    <div class="flex flex-col flex-row items-center md:w-1/2 gap-3">
                        <button id="openOffCanvasBtn" class="p-3 rounded-xl hover:bg-gray-100 cursor-pointer">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="20" height="20"
                                color="#ffffff" fill="none">
                                <path
                                    d="M8.85746 12.5061C6.36901 10.6456 4.59564 8.59915 3.62734 7.44867C3.3276 7.09253 3.22938 6.8319 3.17033 6.3728C2.96811 4.8008 2.86701 4.0148 3.32795 3.5074C3.7889 3 4.60404 3 6.23433 3H17.7657C19.396 3 20.2111 3 20.672 3.5074C21.133 4.0148 21.0319 4.8008 20.8297 6.37281C20.7706 6.83191 20.6724 7.09254 20.3726 7.44867C19.403 8.60062 17.6261 10.6507 15.1326 12.5135C14.907 12.6821 14.7583 12.9567 14.7307 13.2614C14.4837 15.992 14.2559 17.4876 14.1141 18.2442C13.8853 19.4657 12.1532 20.2006 11.226 20.8563C10.6741 21.2466 10.0043 20.782 9.93278 20.1778C9.79643 19.0261 9.53961 16.6864 9.25927 13.2614C9.23409 12.9539 9.08486 12.6761 8.85746 12.5061Z"
                                    stroke="#141B34" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </button>

                        <form action="{{ route('admin.collection-schedules.search') }}" method="GET"
                            class="relative w-full">
                            <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="m21 21-4.35-4.35m1.1-4.4a7.75 7.75 0 1 1-15.5 0 7.75 7.75 0 0 1 15.5 0Z" />
                            </svg>
                            <input type="text" name="q" value="{{ request('q') }}" placeholder="Tìm kiếm..."
                                class="w-full pl-10 pr-3 py-2 rounded-lg focus:ring-2 focus:ring-green-400 focus:outline-none transition" />
                        </form>
                    </div>

                    <div class="flex items-center gap-3 ml-auto">
                        <button id="btnDeleteAll"
                            class="hidden items-center gap-2 bg-red-600 hover:bg-red-700 text-white font-medium py-2 px-4 rounded-lg cursor-pointer transition">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="20" height="20"
                                color="#ffffff" fill="none">
                                <path
                                    d="M19.5 5.5L18.8803 15.5251C18.7219 18.0864 18.6428 19.3671 18.0008 20.2879C17.6833 20.7431 17.2747 21.1273 16.8007 21.416C15.8421 22 14.559 22 11.9927 22C9.42312 22 8.1383 22 7.17905 21.4149C6.7048 21.1257 6.296 20.7408 5.97868 20.2848C5.33688 19.3626 5.25945 18.0801 5.10461 15.5152L4.5 5.5"
                                    stroke="#ffffff" stroke-width="2" stroke-linecap="round"></path>
                                <path
                                    d="M3 5.5H21M16.0557 5.5L15.3731 4.09173C14.9196 3.15626 14.6928 2.68852 14.3017 2.39681C14.215 2.3321 14.1231 2.27454 14.027 2.2247C13.5939 2 13.0741 2 12.0345 2C10.9688 2 10.436 2 9.99568 2.23412C9.8981 2.28601 9.80498 2.3459 9.71729 2.41317C9.32164 2.7167 9.10063 3.20155 8.65861 4.17126L8.05292 5.5"
                                    stroke="#ffffff" stroke-width="2" stroke-linecap="round"></path>
                                <path d="M9.5 16.5L9.5 10.5" stroke="#ffffff" stroke-width="2" stroke-linecap="round">
                                </path>
                                <path d="M14.5 16.5L14.5 10.5" stroke="#ffffff" stroke-width="2" stroke-linecap="round">
                                </path>
                            </svg>
                            Xóa tất cả
                        </button>
                    </div>
                </div>

                <div class="overflow-x-auto" id="tableContainer">
                    <table class="min-w-full border-t border-gray-100">
                        <thead class="bg-green-50 text-gray-600 text-sm font-semibold">
                            <tr>
                                <th class="py-3 px-4 text-left">
                                    <input type="checkbox" id="selectAllCheckbox"
                                        class="w-4 h-4 accent-green-600 cursor-pointer">
                                </th>
                                <th class="py-3 px-4 text-left">Mã lịch thu gom</th>
                                <th class="py-3 px-4 text-left">Nhân viên thực hiện</th>
                                <th class="py-3 px-4 text-left">Ngày thu gom</th>
                                <th class="py-3 px-4 text-left">Ngày hoàn thành</th>
                                <th class="py-3 px-4 text-left">Trạng thái</th>
                                <th class="py-3 px-4 text-center">Thao tác</th>
                            </tr>
                        </thead>
                        <tbody class="text-sm divide-y divide-gray-100">
                            @forelse ($collectionSchedules as $collectionSchedule)
                                <tr class="hover:bg-green-50">
                                    <td class="py-3 px-4"><input type="checkbox" name="ids[]"
                                            value="{{ $collectionSchedule->schedule_id }}"
                                            class="checkbox w-4 h-4 accent-green-600 cursor-pointer"></td>
                                    <td class="py-3 px-4">{{ $collectionSchedule->schedule_id }}</td>
                                    <td class="py-3 px-4">{{ $collectionSchedule->staff->name }}</td>
                                    <td class="py-3 px-4">
                                        {{ $collectionSchedule->scheduled_date?->format('Y-m-d') ?? '-' }}
                                    </td>
                                    <td class="py-3 px-4">{{ $collectionSchedule->completed_at?->format('Y-m-d') ?? '-' }}
                                    </td>
                                    <td class="py-3 px-4">
                                        @if ($collectionSchedule->status == 'Chưa thực hiện')
                                            <span
                                                class="px-3 py-1 text-xs font-medium rounded-full bg-yellow-100 text-yellow-700">
                                                {{ $collectionSchedule->status }}
                                            </span>
                                        @elseif($collectionSchedule->status == 'Đã hoàn thành')
                                            <span
                                                class="px-3 py-1 text-xs font-medium rounded-full bg-green-100 text-green-700">
                                                {{ $collectionSchedule->status }}
                                            </span>
                                        @endif
                                    </td>
                                    <td class="py-3 px-4 text-center">
                                        <div class="flex items-center justify-center">
                                            <button data-id="{{ $collectionSchedule->schedule_id }}"
                                                class="addWasteLogBtn group inline-flex items-center justify-center hover:bg-green-300 rounded-xl mx-1 p-2 transition cursor-pointer"
                                                data-modal="add" aria-label="Thêm">
                                                {{-- Icon thêm --}}
                                                <svg class="group-hover:text-green-700" xmlns="http://www.w3.org/2000/svg"
                                                    viewBox="0 0 24 24" width="20" height="20" color="#000000"
                                                    fill="none">
                                                    <path d="M12 4V20M20 12H4" stroke="currentColor" stroke-width="2"
                                                        stroke-linecap="round" stroke-linejoin="round"></path>
                                                </svg>
                                            </button>

                                            <button data-id="{{ $collectionSchedule->schedule_id }}"
                                                class="editWasteLogBtn group inline-flex items-center justify-center hover:bg-amber-200 rounded-xl mx-1 p-2 transition cursor-pointer"
                                                data-modal="edit" aria-label="Chỉnh sửa">
                                                {{-- Icon chỉnh sửa --}}
                                                <svg class="w-5 h-5 group-hover:text-amber-600" viewBox="0 0 24 24"
                                                    color="#254434" fill="none" stroke="currentColor"
                                                    aria-hidden="true">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M16.862 3.487a1.75 1.75 0 0 1 2.476 2.476L8.5 16.8 4 18l1.2-4.5 11.662-10.013Z" />
                                                </svg>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-4 py-6 text-center text-gray-500 italic">
                                        @if ($isSearching == true)
                                            Không có kết quả tìm kiếm cho từ khóa
                                            "<strong class="text-gray-700">{{ $q }}</strong>".
                                        @endif
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                </div>

                <!-- Phân trang -->
                {{-- <div class="flex flex-col md:flex-row items-center justify-between mt-6 text-sm text-gray-500">
                    <p>Hiển thị 1 - 5 trong tổng số 6 sự kiện</p>
                    <div class="flex items-center gap-2 mt-3 md:mt-0">
                        <button class="p-2 border rounded-lg hover:bg-green-50" aria-label="Trang trước">
                            <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m15 19-7-7 7-7" />
                            </svg>
                        </button>
                        <span>Trang 1 / 2</span>
                        <button class="p-2 border rounded-lg hover:bg-green-50" aria-label="Trang sau">
                            <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m9 5 7 7-7 7" />
                            </svg>
                        </button>
                    </div>
                </div> --}}
                @if ($wasteLogs->hasPages())
                    <div class="mt-3">
                        {{ $wasteLogs->links() }}
                    </div>
                @endif
        @endif

        <div id="modal"
            class="hidden fixed inset-0 flex items-center justify-center z-50 bg-black/40 backdrop-blur-[2px] transition-opacity duration-300 opacity-0 overflow-y-auto p-4">
            <div id="modalBox"
                class="bg-white backdrop-blur-md border border-green-100 shadow-xl rounded-xl py-6 max-w-2xl w-full
              transform opacity-0 translate-y-5 scale-95 transition-all duration-300 flex flex-col max-h-[95vh]">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-lg font-semibold px-7 titleModal">Thêm lượng rác thu gom</h2>
                    <button id="closeModalBtn" class="px-7 text-gray-400 hover:text-gray-600 cursor-pointer">
                        <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <label class="block text-sm font-medium mb-1 px-7">Không chắc loại rác? Nhập tên vật:</label>
                <input id="itemName"
                    class="border border-gray-300 rounded-lg mx-7 px-3 py-2 focus:border-green-500 focus:ring focus:ring-green-200 outline-none transition"
                    placeholder="Túi nilon, vỏ chuối, pin...">
                <div id="aiHint" class="text-sm text-gray-600 mt-1 px-7"></div>
                <div class="px-8 overflow-y-auto overscroll-contain min-h-0">
                    <form class="mt-3 space-y-4" action="{{ route('waste-logs.store') }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" value="" name="schedule_id" id="schedule_id">
                        <div id="wasteFieldsWrapper">
                            <div
                                class="grid grid-cols-3 border border-gray-300 rounded-lg p-4 gap-4 mt-3 waste-item relative">
                                <div class="flex flex-col items-start gap-1">
                                    <label class="block text-sm font-medium text-gray-700">Loại rác:</label>
                                    <select name="waste_type[]"
                                        class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:border-green-500 focus:ring focus:ring-green-200 outline-none transition">
                                        <option value="" selected disabled>-- Chọn loại rác --</option>
                                        @foreach ($wasteTypes as $id => $name)
                                            <option value="{{ $id }}">{{ $name }}</option>
                                        @endforeach
                                    </select>
                                    @error('wasteType')
                                        <p class="error-text mt-1 text-sm text-red-500">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="flex flex-col items-start gap-1">
                                    <label class="block text-sm font-medium text-gray-700">Khối lượng:</label>
                                    <input type="text" name="waste_weight[]" autocomplete="off"
                                        class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:border-green-500 focus:ring focus:ring-green-200 outline-none transition">
                                    @error('waste_weight')
                                        <p class="error-text mt-1 text-sm text-red-500">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="flex flex-col items-start gap-1">
                                    <label class="block text-sm font-medium text-gray-700">Hình ảnh:</label>
                                    <input type="file" name="waste_image[]" accept="image/*"
                                        class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:border-green-500 focus:ring focus:ring-green-200 outline-none transition">
                                    <p class="waste_image_file_name text-sm"></p>
                                    @error('waste_image')
                                        <p class="error-text mt-1 text-sm text-red-500">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Nút xoá -->
                                <button type="button"
                                    class="deleteRowBtn absolute -right-8 top-1/2 -translate-y-1/2 
                                        text-red-600 hover:text-white 
                                        bg-gray-200 hover:bg-red-500 
                                        font-bold text-lg 
                                        w-6 h-6 flex items-center justify-center 
                                        rounded-full shadow hidden transition cursor-pointer">
                                    ×
                                </button>
                            </div>
                        </div>

                    </form>
                    <button type="button" id="addWasteRowBtn"
                        class="w-full rounded-lg mt-3 py-1 text-green-700 bg-green-200 hover:bg-green-500 cursor-pointer">+</button>
                </div>

                <div class="flex justify-end gap-3 pt-3 px-7">
                    <button type="button" id="cancelModalBtn"
                        class="px-4 py-2 rounded-lg bg-gray-100 hover:bg-gray-300 cursor-pointer">Hủy</button>
                    <button type="submit"
                        class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg cursor-pointer">Lưu</button>
                </div>
            </div>
        </div>

        {{-- <div id="confirmModal"
            class="hidden fixed inset-0 flex items-center justify-center z-50 bg-black/40 backdrop-blur-[2px] transition-opacity duration-300 opacity-0">
            <div id="confirmModalBox"
                class="bg-white backdrop-blur-md border border-green-100 shadow-xl rounded-xl p-6 max-w-md w-full
              transform opacity-0 translate-y-5 scale-95 transition-all duration-300">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-lg font-semibold">Xác nhận xóa</h2>
                    <button id="closeConfirmModalBtn" class="text-gray-400 hover:text-gray-600 cursor-pointer">
                        <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                <h2 class="mb-3">Bạn có chắc chắn muốn xóa không?</h2>
                <div class="flex justify-end gap-3 pt-3">
                    <button type="button" id="cancelConfirmModalBtn"
                        class="px-4 py-2 rounded-lg bg-gray-100 hover:bg-gray-300 cursor-pointer">Hủy</button>
                    <form action="" method="POST">
                        @csrf
                        @method('DELETE')
                        <div id="idsContainer"></div>
                        <button type="submit"
                            class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg cursor-pointer">Chắc
                            chắn!</button>
                    </form>
                </div>
            </div>
        </div> --}}

        {{-- Offcanvas fillter --}}
        {{-- <div id="overlayOffCanvas"
            class="hidden fixed inset-0 bg-black/40 backdrop-blur-[2px] z-40 transition-opacity duration-300"></div> --}}

        <!-- Offcanvas panel -->
        {{-- <div id="offCanvas"
            class="fixed top-0 left-0 h-full w-80 justify-between bg-white shadow-xl transform -translate-x-full transition-transform duration-300 z-50 flex flex-col">
            <!-- Header -->
            <div class="flex items-center justify-between p-4 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-700">BỘ LỌC</h2>
                <button id="closeOffCanvasBtn" class="text-gray-500 hover:text-gray-800 cursor-pointer">
                    ✕
                </button>
            </div>

            <form action="{{ route('admin.collection-schedules.index') }}" method="GET" id="filterForm"
                class="space-y-4 flex-1 flex flex-col">
                <!-- Nội dung bộ lọc -->
                <div class="p-4 flex-1 space-y-4 overflow-y-auto">
                    <!-- Bộ lọc theo nhân viên -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nhân viên thực hiện:</label>
                        <input type="radio" class="w-4 h-4" name="radioFilterStaff" id="radioFilterStaff1"
                            value="asc">
                        <label class="form-check-label" for="radioFilterStaff1">
                            A - Z
                        </label>
                        <br>
                        <input type="radio" class="w-4 h-4" name="radioFilterStaff" id="radioFilterStaff2"
                            value="desc">
                        <label class="form-check-label" for="radioFilterStaff2">
                            Z - A
                        </label>
                    </div>

                    <!-- Bộ lọc theo ngày thu gom -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Ngày thu gom:</label>
                        <input type="radio" class="w-4 h-4" name="radioFilterScheduledDate"
                            id="radioFilterScheduledDate1" value="asc">
                        <label class="form-check-label" for="radioFilterScheduledDate1">
                            Cũ - Mới
                        </label>
                        <br>
                        <input type="radio" class="w-4 h-4" name="radioFilterScheduledDate"
                            id="radioFilterScheduledDate2" value="desc">
                        <label class="form-check-label" for="radioFilterScheduledDate2">
                            Mới - Cũ
                        </label>
                    </div>

                    <!-- Bộ lọc theo ngày hoàn thành -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Ngày hoàn thành:</label>
                        <input type="radio" class="w-4 h-4" name="radioFilterCompletedAt" id="radioFilterCompletedAt1"
                            value="old_new">
                        <label class="form-check-label" for="radioFilterCompletedAt1">
                            Cũ - Mới
                        </label>
                        <br>
                        <input type="radio" class="w-4 h-4" name="radioFilterCompletedAt" id="radioFilterCompletedAt2"
                            value="new_old">
                        <label class="form-check-label" for="radioFilterCompletedAt2">
                            Mới - Cũ
                        </label>
                    </div>

                    <!-- Bộ lọc theo trạng thái -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Trạng thái:</label>
                        <input type="radio" class="w-4 h-4" name="radioFilterStatus" id="radioFilterStatus1"
                            value="Chưa thực hiện">
                        <label class="form-check-label" for="radioFilterStatus1">
                            Chưa thực hiện
                        </label>
                        <br>
                        <input type="radio" class="w-4 h-4" name="radioFilterStatus" id="radioFilterStatus2"
                            value="Đã hoàn thành">
                        <label class="form-check-label" for="radioFilterStatus2">
                            Đã hoàn thành
                        </label>
                    </div>
                </div>

                <!-- Footer -->
                <div class="p-4 border-t border-gray-200 flex justify-end gap-3">
                    <button id="resetFilter"
                        class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-100 transition cursor-pointer">
                        Đặt lại
                    </button>
                    <button type="submit" id="applyFilter"
                        class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition cursor-pointer">
                        Áp dụng
                    </button>
                </div>
            </form>
        </div> --}}
    </div>

    <div id="imgModal" class="fixed inset-0 z-[9999] hidden items-center justify-center bg-black/60">
        <div class="relative max-w-[90vw] max-h-[90vh]">
            <img id="imgModalImg" src="" alt="Preview"
                class="max-w-[90vw] max-h-[90vh] rounded shadow-lg object-contain">
            <button type="button" id="imgModalClose"
                class="absolute -top-3 -right-3 w-8 h-8 rounded-full bg-white/90 hover:bg-white text-gray-800 shadow">
                ×
            </button>
        </div>
    </div>

    <script>
        const modal = document.getElementById('modal');
        const modalBox = document.getElementById('modalBox');
        const titleModal = document.querySelector('.titleModal');
        const addBtn = document.querySelectorAll('.addWasteLogBtn');
        const editBtn = document.querySelectorAll('.editWasteLogBtn');
        const closeBtn = document.getElementById('closeModalBtn');
        const cancelBtn = document.getElementById('cancelModalBtn');

        const form = modal.querySelector('form');
        const wrapper = document.getElementById('wasteFieldsWrapper');
        const addWasteRowBtn = document.getElementById('addWasteRowBtn');
        let originalWasteFieldsHTML = wrapper.innerHTML;

        let t;
        const $name = document.getElementById('itemName');
        const $hint = document.getElementById('aiHint');

        const imgModal = document.getElementById('imgModal');
        const imgModalImg = document.getElementById('imgModalImg');
        const imgModalClose = document.getElementById('imgModalClose');

        const previewBtn = document.querySelectorAll('.preview-btn')

        function openModal(modal, modalBox) {
            resetInputAIHint();
            modal.classList.remove('hidden');
            setTimeout(() => {
                modal.classList.remove('opacity-0');
                modal.classList.add('opacity-100');
                modalBox.classList.remove('opacity-0', 'translate-y-5', 'scale-95');
                modalBox.classList.add('opacity-100', 'translate-y-0', 'scale-100');
            }, 10);
        }

        function closeModal(modal, modalBox) {
            modalBox.classList.remove('opacity-100', 'translate-y-0', 'scale-100');
            modalBox.classList.add('opacity-0', 'translate-y-5', 'scale-95');
            modal.classList.remove('opacity-100');
            modal.classList.add('opacity-0');
            setTimeout(() => modal.classList.add('hidden'), 300);
        }

        @if (session('show_modal') || $errors->any())
            document.addEventListener('DOMContentLoaded', () => {
                openModal(modal, modalBox);
            });
        @endif

        modal.addEventListener('click', e => {
            if (e.target === e.currentTarget) closeModal(modal, modalBox);
            clearValidationState(form);
            // resetForm(form);
        });

        addBtn.forEach(btn => {
            btn.addEventListener('click', function() {
                resetForm(form);

                const scheduleId = this.getAttribute('data-id');
                document.querySelector('input#schedule_id').value = scheduleId;

                titleModal.textContent = 'Thêm lượng rác thu gom';
                wrapper.innerHTML = originalWasteFieldsHTML;
                openModal(modal, modalBox);
            });
        });

        editBtn.forEach(btn => {
            btn.addEventListener('click', function() {
                openEditWasteLogModal(this.getAttribute('data-id'));

            });
        });

        async function openEditWasteLogModal(scheduleId) {
            resetForm(form);
            clearValidationState(form);
            document.querySelector('input#schedule_id').value = scheduleId;
            titleModal.textContent = 'Chỉnh sửa lượng rác thu gom';
            wrapper.innerHTML = '';
            console.log(wrapper);


            // lấy dữ liệu từ server
            const res = await fetch(`/waste-logs/get-by-collection-schedules?schedule_id=${scheduleId}`);
            const data = await res.json();

            if (data.waste_logs?.length) {
                data.waste_logs.forEach(waste_log => {
                    const newRow = makeRow();
                    // gán giá trị
                    newRow.querySelector('select[name="waste_type[]"]').value = waste_log.waste_type_id;
                    newRow.querySelector('input[name="waste_weight[]"]').value = waste_log.waste_weight;
                    // newRow.querySelector('.waste_image_path').textContent = waste_log.waste_image;
                    const fileName = getFileNameFromPath(waste_log.waste_image);
                    const shortFileName = shortenFileName(fileName);
                    // console.log(shortFileName);
                    newRow.querySelector('.waste_image_file_name').innerHTML = `Curent: ${shortFileName} <button type="button" class="preview-btn" onclick="openImageModal('${waste_log.waste_image}')" data-url="${waste_log.waste_image}"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="20" height="20" color="#000000" fill="none">
    <path d="M21.544 11.045C21.848 11.4713 22 11.6845 22 12C22 12.3155 21.848 12.5287 21.544 12.955C20.1779 14.8706 16.6892 19 12 19C7.31078 19 3.8221 14.8706 2.45604 12.955C2.15201 12.5287 2 12.3155 2 12C2 11.6845 2.15201 11.4713 2.45604 11.045C3.8221 9.12944 7.31078 5 12 5C16.6892 5 20.1779 9.12944 21.544 11.045Z" stroke="currentColor" stroke-width="1.5"></path>
    <path d="M15 12C15 10.3431 13.6569 9 12 9C10.3431 9 9 10.3431 9 12C9 13.6569 10.3431 15 12 15C13.6569 15 15 13.6569 15 12Z" stroke="currentColor" stroke-width="1.5"></path>
</svg></button>`;
                    wrapper.appendChild(newRow);
                });
            }
            openModal(modal, modalBox);
        }

        closeBtn.addEventListener('click', function() {
            closeModal(modal, modalBox);
            clearValidationState(form);
            resetForm(form);
        });

        cancelBtn.addEventListener('click', function() {
            closeModal(modal, modalBox);
            clearValidationState(form);
            resetForm(form);
        });

        function resetForm(form) {
            if (!form) return;
            form.reset();
            form.querySelectorAll('input:not([type="hidden"]), select, textarea').forEach(el => el.value = '');
        }

        function resetInputAIHint() {
            document.getElementById('itemName').value = '';
            document.getElementById('aiHint').textContent = '';
        }

        function clearValidationState(form) {
            if (!form) return;
            form.querySelectorAll('.error-text').forEach(el => el.remove());
        }

        $name.addEventListener('input', () => {
            clearTimeout(t);
            const q = $name.value.trim();
            $hint.textContent = 'Vui lòng chờ...';
            if (!q) {
                $hint.textContent = '';
                return;
            }
            t = setTimeout(async () => {
                const res = await fetch(
                    `/waste-logs/ai-suggest-waste-classifier?q=${encodeURIComponent(q)}`);
                const j = await res.json();
                if (j.label && j.confidence >= 0.65) {
                    const pct = Math.round((j.confidence || 0) * 100);
                    const alt = (j.alternatives || []).slice(0, 2).join(' / ');
                    $hint.innerHTML = `Gợi ý: <b>${j.label}</b> (${pct}%)
                        <span class="relative group inline-flex items-center">
                            <!-- Icon -->
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-gray-500 hover:text-gray-700 cursor-pointer" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M12 2a10 10 0 1 0 10 10A10.011 10.011 0 0 0 12 2Zm0 14a1 1 0 0 1-1-1v-4a1 1 0 1 1 2 0v4a1 1 0 0 1-1 1Zm1-8h-2V6h2Z"/>
                            </svg>

                            <!-- Tooltip content -->
                            <div class="pointer-events-none absolute left-1/2 top-full z-20 w-56 -translate-x-1/2 translate-y-2
                                        rounded-md border border-gray-200 bg-white px-3 py-2 text-xs text-gray-700 shadow-xl
                                        opacity-0 scale-95 transition duration-150 ease-out
                                        group-hover:opacity-100 group-hover:scale-100">
                                ${j.reason}
                            </div>
                        </span>` +
                        (alt ? ` <br> <span class="text-gray-500"> Có thể: ${alt}</span>` : '');
                    document.getElementById('applyType')?.addEventListener('click', () => {
                        const sel = document.querySelector('select[name="waste_type"]');
                        if (sel) sel.value = j.label;
                    });
                } else {
                    $hint.textContent = `${j.reason}`;
                }
            }, 400);
        });

        addWasteRowBtn.addEventListener('click', function() {
            const newRow = makeRow();
            wrapper.appendChild(newRow);
        });

        function makeRow() {
            const temp = document.createElement('div');
            temp.innerHTML = originalWasteFieldsHTML.trim();
            const newRow = temp.firstElementChild.cloneNode(true);

            // reset input
            newRow.querySelectorAll('input').forEach(input => input.value = '');
            newRow.querySelectorAll('select').forEach(select => select.selectedIndex = 0);

            newRow.querySelector('.deleteRowBtn')?.classList.remove('hidden');
            return newRow;
        }

        // lắng nghe sự kiện xoá bằng event delegation
        document.addEventListener('click', function(e) {
            if (e.target.classList.contains('deleteRowBtn')) {
                const row = e.target.closest('.waste-item');

                // không cho xoá nếu chỉ còn 1 dòng
                if (document.querySelectorAll('.waste-item').length > 1) {
                    row.remove();
                }
            }
        });

        function getFileNameFromPath(path) {
            if (!path) return '';
            try {
                const u = new URL(path, window.location.origin);
                return u.pathname.split('/').pop() || '';
            } catch {
                // Nếu chỉ là relative path
                return String(path).split('/').pop() || '';
            }
        }

        // Rút gọn tên file
        function shortenFileName(name, head = 7, tail = 5) {
            if (!name) return '';
            const max = head + tail + 3; // + '...'
            return name.length > max ?
                name.slice(0, head) + '...' + name.slice(-tail) :
                name;
        }

        previewBtn.forEach(btn => {
            btn.addEventListener('click', function() {
                console.log('clicked');

                const url = this.dataset.url;
                openImageModal(url);
            })
        });

        imgModalClose.addEventListener('click', closeImageModal);
        imgModal.addEventListener('click', (e) => {
            if (e.target === imgModal) closeImageModal();
        });

        function openImageModal(url) {

            if (!url) return;
            if (!url.startsWith('http') && !url.startsWith('/storage/')) {
                url = '/storage/' + url.replace(/^\/?storage\//, '');
            }

            imgModalImg.src = url;
            console.log(imgModalImg.src);

            imgModal.classList.remove('hidden');
            imgModal.classList.add('flex');
            document.body.classList.add('overflow-hidden');
        }

        function closeImageModal() {
            imgModal.classList.add('hidden');
            imgModal.classList.remove('flex');
            imgModalImg.src = '';
            document.body.classList.remove('overflow-hidden');
        }
    </script>

@endsection
