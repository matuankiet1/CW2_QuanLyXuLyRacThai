@extends('layouts.staff')

@section('content')
    <style>
        .ai-hint .ai-tooltip {
            position: absolute;
            top: 100%;
            left: 100%;
            margin-left: 6px;
            min-width: 14rem;

            padding: 6px 10px;
            border-radius: 0.375rem;
            border: 1px solid #dee2e6;
            background: #fff;
            font-size: 0.75rem;
            color: #374151;
            box-shadow: 0 10px 15px rgba(0, 0, 0, .1);

            opacity: 0;
            pointer-events: none;
            transition: opacity .15s ease-out, transform .15s ease-out;
            z-index: 1050;
        }

        .ai-hint:hover .ai-tooltip {
            opacity: 1;
            transition: all .15s ease-out;
        }
    </style>

    <div class="container max-w-7xl mx-auto pt-5">
        @if ($collectionSchedules->isEmpty() && $isSearch == false)
            <div class="bg-yellow-100 p-6 rounded-xl shadow-sm border border-gray-200">
                <p>Không có lịch thu gom nào cần báo cáo.</p>
            </div>
        @else
            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200">
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
                    <div class="flex flex-col flex-row items-center md:w-1/2 gap-3">
                        <form action="{{ route('admin.collection-schedules.search') }}" method="GET" class="relative w-full">
                            <div class="relative w-full flex items-center">
                                <svg class="absolute left-3 w-5 h-5 text-gray-400 pointer-events-none" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="m21 21-4.35-4.35m1.1-4.4a7.75 7.75 0 1 1-15.5 0 7.75 7.75 0 0 1 15.5 0Z" />
                                </svg>

                                <input type="text" name="q" value="{{ request('q') }}" placeholder="Tìm kiếm..."
                                    class="w-full pl-11 pr-3 py-2 rounded-xl border border-gray-300
               focus:ring-2 focus:ring-green-400 focus:outline-none transition" />
                            </div>
                        </form>
                    </div>
                </div>

                <div class="overflow-x-auto" id="tableContainer">
                    <table class="min-w-full border-t border-gray-100">
                        <thead class="bg-green-50 text-gray-600 text-sm font-semibold">
                            <tr>
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
                                                class="addWasteLogBtn group inline-flex items-center justify-center hover:bg-amber-200 rounded-xl mx-1 p-2 transition cursor-pointer"
                                                data-modal="add" aria-label="Thêm">
                                                <svg class="w-5 h-5 group-hover:text-amber-600" viewBox="0 0 24 24"
                                                    color="#254434" fill="none" stroke="currentColor" aria-hidden="true">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M16.862 3.487a1.75 1.75 0 0 1 2.476 2.476L8.5 16.8 4 18l1.2-4.5 11.662-10.013Z" />
                                                </svg>
                                            </button>

                                            {{-- <button data-id="{{ $collectionSchedule->schedule_id }}"
                                                class="editWasteLogBtn group inline-flex items-center justify-center hover:bg-amber-200 rounded-xl mx-1 p-2 transition cursor-pointer"
                                                data-modal="edit" aria-label="Chỉnh sửa">
                                                <svg class="w-5 h-5 group-hover:text-amber-600" viewBox="0 0 24 24"
                                                    color="#254434" fill="none" stroke="currentColor"
                                                    aria-hidden="true">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M16.862 3.487a1.75 1.75 0 0 1 2.476 2.476L8.5 16.8 4 18l1.2-4.5 11.662-10.013Z" />
                                                </svg>
                                            </button> --}}
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
                class="bg-white backdrop-blur-md border border-green-100 shadow-xl rounded-xl py-6 max-w-3xl w-full
              transform opacity-0 translate-y-5 scale-95 transition-all duration-300 flex flex-col max-h-[95vh]">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-lg font-semibold px-7 titleModal">Báo cáo thu gom rác</h2>
                    <button id="closeModalBtn" class="px-7 text-gray-400 hover:text-gray-600 cursor-pointer">
                        <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <label class="block text-sm font-medium mb-1 px-7">Không chắc loại rác? Nhập tên vật:</label>
                <div class="relative mx-7">
                    <input id="itemName"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:border-green-500 focus:ring focus:ring-green-200 outline-none transition"
                        placeholder="Túi nilon, vỏ chuối, pin...">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="20" height="20"
                        color="#000000" fill="none"
                        class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-500 pointer-events-none">
                        <path
                            d="M10 7L9.48415 8.39405C8.80774 10.222 8.46953 11.136 7.80278 11.8028C7.13603 12.4695 6.22204 12.8077 4.39405 13.4842L3 14L4.39405 14.5158C6.22204 15.1923 7.13603 15.5305 7.80278 16.1972C8.46953 16.864 8.80774 17.778 9.48415 19.6059L10 21L10.5158 19.6059C11.1923 17.778 11.5305 16.864 12.1972 16.1972C12.864 15.5305 13.778 15.1923 15.6059 14.5158L17 14L15.6059 13.4842C13.778 12.8077 12.864 12.4695 12.1972 11.8028C11.5305 11.136 11.1923 10.222 10.5158 8.39405L10 7Z"
                            stroke="currentColor" stroke-width="1.5" stroke-linejoin="round"></path>
                        <path
                            d="M18 3L17.7789 3.59745C17.489 4.38087 17.3441 4.77259 17.0583 5.05833C16.7726 5.34408 16.3809 5.48903 15.5975 5.77892L15 6L15.5975 6.22108C16.3809 6.51097 16.7726 6.65592 17.0583 6.94167C17.3441 7.22741 17.489 7.61913 17.7789 8.40255L18 9L18.2211 8.40255C18.511 7.61913 18.6559 7.22741 18.9417 6.94166C19.2274 6.65592 19.6191 6.51097 20.4025 6.22108L21 6L20.4025 5.77892C19.6191 5.48903 19.2274 5.34408 18.9417 5.05833C18.6559 4.77259 18.511 4.38087 18.2211 3.59745L18 3Z"
                            stroke="currentColor" stroke-width="1.5" stroke-linejoin="round"></path>
                    </svg>
                </div>
                <div id="aiHint" class="text-sm text-gray-600 mt-1 px-7"></div>
                <div class="px-8 overflow-y-auto overscroll-contain min-h-0">
                    <form id="formModal" class="mt-3 space-y-4" action="{{ route('waste-logs.store') }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" value="" name="schedule_id" id="schedule_id">
                        <div id="wasteFieldsWrapper">
                            <div
                                class="grid grid-cols-3 border border-gray-300 rounded-lg p-4 gap-4 mt-3 waste-item relative">
                                {{-- Input ẩn dùng để lưu image_path cũ --}}
                                <input type="hidden" value="" name="old_waste_image[]">
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
                                    <p class="waste_image_file_name text-sm flex justify-center items-center"
                                        title=""></p>
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
                    <button type="submit" form="formModal"
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
                class="absolute -top-3 -right-3 w-8 h-8 rounded-full bg-white/90 hover:bg-white text-gray-800 shadow cursor-pointer">
                x
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
        const baseSelect = wrapper.querySelector('.waste-item select[name="waste_type[]"]');
        const baseOptionsHtml = baseSelect.innerHTML;

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
            btn.addEventListener('click', async function() {
                resetForm(form);

                const scheduleId = this.getAttribute('data-id');
                document.querySelector('input#schedule_id').value = scheduleId;

                // lấy dữ liệu từ server
                const res = await fetch(
                    `/waste-logs/get-by-collection-schedules?schedule_id=${scheduleId}`);
                const data = await res.json();

                if (data.waste_logs?.length) {
                    wrapper.innerHTML = '';
                    data.waste_logs.forEach(waste_log => {
                        const newRow = makeRow();
                        // gán giá trị
                        newRow.querySelector('select[name="waste_type[]"]').value = waste_log
                            .waste_type_id;
                        newRow.querySelector('input[name="waste_weight[]"]').value = waste_log
                            .waste_weight;
                        if (waste_log
                            .waste_image) {
                            newRow.querySelector('input[name="old_waste_image[]"]').value =
                                waste_log
                                .waste_image;
                            const fileName = getFileNameFromPath(waste_log.waste_image);
                            const shortFileName = shortenFileName(fileName);
                            // console.log(shortFileName);
                            newRow.querySelector('.waste_image_file_name').innerHTML = `Curent: ${shortFileName} <button type="button" class="preview-btn hover:bg-gray-200 p-1 rounded-xl transition cursor-pointer" onclick="openImageModal('${waste_log.waste_image}')" data-url="${waste_log.waste_image}"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="20" height="20" color="#000000" fill="none">
    <path d="M21.544 11.045C21.848 11.4713 22 11.6845 22 12C22 12.3155 21.848 12.5287 21.544 12.955C20.1779 14.8706 16.6892 19 12 19C7.31078 19 3.8221 14.8706 2.45604 12.955C2.15201 12.5287 2 12.3155 2 12C2 11.6845 2.15201 11.4713 2.45604 11.045C3.8221 9.12944 7.31078 5 12 5C16.6892 5 20.1779 9.12944 21.544 11.045Z" stroke="currentColor" stroke-width="1.5"></path>
    <path d="M15 12C15 10.3431 13.6569 9 12 9C10.3431 9 9 10.3431 9 12C9 13.6569 10.3431 15 12 15C13.6569 15 15 13.6569 15 12Z" stroke="currentColor" stroke-width="1.5"></path>
</svg></button>`;
                            newRow.querySelector('.waste_image_file_name').title = fileName;
                        }
                        wrapper.appendChild(newRow);

                    });
                } else {
                    wrapper.innerHTML = originalWasteFieldsHTML;
                }
                openModal(modal, modalBox);
            });
        });

        editBtn.forEach(btn => {
            btn.addEventListener('click', function() {
                openEditWasteLogModal(this.getAttribute('data-id'));

            });
        });

        //         async function openEditWasteLogModal(scheduleId) {
        //             resetForm(form);
        //             clearValidationState(form);
        //             document.querySelector('input#schedule_id').value = scheduleId;
        //             titleModal.textContent = 'Chỉnh sửa lượng rác thu gom';
        //             wrapper.innerHTML = '';
        //             addWasteRowBtn.classList.add('hidden');

        //             // lấy dữ liệu từ server
        //             const res = await fetch(`/waste-logs/get-by-collection-schedules?schedule_id=${scheduleId}`);
        //             const data = await res.json();

        //             if (data.waste_logs?.length) {
        //                 data.waste_logs.forEach(waste_log => {
        //                     const newRow = makeRow();

        //                     // gán giá trị
        //                     newRow.querySelector('select[name="waste_type[]"]').value = waste_log.waste_type_id;
        //                     newRow.querySelector('input[name="waste_weight[]"]').value = waste_log.waste_weight;
        //                     // newRow.querySelector('.waste_image_path').textContent = waste_log.waste_image;
        //                     const fileName = getFileNameFromPath(waste_log.waste_image);
        //                     const shortFileName = shortenFileName(fileName);
        //                     // console.log(shortFileName);
        //                     newRow.querySelector('.waste_image_file_name').innerHTML = `Curent: ${shortFileName} <button type="button" class="preview-btn" onclick="openImageModal('${waste_log.waste_image}')" data-url="${waste_log.waste_image}"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="20" height="20" color="#000000" fill="none">
    //     <path d="M21.544 11.045C21.848 11.4713 22 11.6845 22 12C22 12.3155 21.848 12.5287 21.544 12.955C20.1779 14.8706 16.6892 19 12 19C7.31078 19 3.8221 14.8706 2.45604 12.955C2.15201 12.5287 2 12.3155 2 12C2 11.6845 2.15201 11.4713 2.45604 11.045C3.8221 9.12944 7.31078 5 12 5C16.6892 5 20.1779 9.12944 21.544 11.045Z" stroke="currentColor" stroke-width="1.5"></path>
    //     <path d="M15 12C15 10.3431 13.6569 9 12 9C10.3431 9 9 10.3431 9 12C9 13.6569 10.3431 15 12 15C13.6569 15 15 13.6569 15 12Z" stroke="currentColor" stroke-width="1.5"></path>
    // </svg></button>`;
        //                     wrapper.appendChild(newRow);
        //                 });
        //             }
        //             openModal(modal, modalBox);
        //         }

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
            if (q == '') {
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
                            <span class="ai-hint position-relative d-inline-flex align-items-center">
                                <!-- Icon -->
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-gray-500 hover:text-gray-700 cursor-pointer" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M12 2a10 10 0 1 0 10 10A10.011 10.011 0 0 0 12 2Zm0 14a1 1 0 0 1-1-1v-4a1 1 0 1 1 2 0v4a1 1 0 0 1-1 1Zm1-8h-2V6h2Z"/>
                                </svg>

                                <!-- Tooltip content -->
                                <div class="ai-tooltip">
                                    ${j.reason}
                                </div>
                            </span>` +
                        (alt ? ` <br> <span class="text-gray-500"> Có thể: ${alt}</span>` : '');
                } else {
                    $hint.textContent = `${j.reason}`;
                }
            }, 400);
        });

        addWasteRowBtn.addEventListener('click', function() {
            const newRow = makeRow(false);
            wrapper.appendChild(newRow);
            refreshWasteTypeOptions();
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
                row.remove();
                refreshWasteTypeOptions();
            }
        });

        document.addEventListener('change', (e) => {
            if (e.target.matches('select[name="wasteType[]"]')) {
                refreshWasteTypeOptions();
            }
        });

        function getUsedWasteTypes() {
            const used = new Set();
            wrapper.querySelectorAll('select[name="waste_type[]"]').forEach(sel => {
                const val = sel.value;
                if (val) used.add(val);
            });
            return used;
        }

        function refreshWasteTypeOptions() {
            const used = getUsedWasteTypes();
            wrapper.querySelectorAll('select[name="waste_type[]"]').forEach(sel => {
                const current = sel.value; // giữ lựa chọn hiện tại

                // reset lại toàn bộ options về như ban đầu
                sel.innerHTML = baseOptionsHtml;

                // khôi phục lại option đang chọn (nếu có)
                if (current) {
                    sel.value = current;
                } else {
                    sel.selectedIndex = 0;
                }

                // Disable các option đã được dùng ở select khác
                sel.querySelectorAll('option').forEach(opt => {
                    if (!opt.value) return; // bỏ placeholder
                    if (used.has(opt.value) && opt.value !== String(current)) {
                        opt.disabled = true;
                    }
                });
            });
        }

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
        function shortenFileName(name, head = 5, tail = 5) {
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
