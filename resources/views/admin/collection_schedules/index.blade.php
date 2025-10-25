@extends('layouts.dashboard')

@section('main-content')
    {{-- @if ($collectionSchedules->isEmpty())
        <p>Không có lịch thu gom nào.</p>
    @else --}}
    <div class="max-w-7xl mx-auto">
        @if ($collectionSchedules->isEmpty() && $isSearching == false)
            <div class="bg-yellow-100 p-6 rounded-xl shadow-sm border border-gray-200">
                <p>Không có lịch thu gom nào.
                    <a type="button"
                        class="openModalBtn underline decoration-solid cursor-pointer text-blue-600 hover:text-blue-800">Thêm
                        mới
                    </a>
                </p>
            </div>
        @else
            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200">
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
                    <form action="{{ route('admin.collection-schedules.search') }}" method="GET"
                        class="relative w-full md:w-1/2">
                        <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="m21 21-4.35-4.35m1.1-4.4a7.75 7.75 0 1 1-15.5 0 7.75 7.75 0 0 1 15.5 0Z" />
                        </svg>
                        <input type="text" name="q" value="{{ request('q') }}" placeholder="Tìm kiếm..."
                            class="w-full pl-10 pr-3 py-2 rounded-lg focus:ring-2 focus:ring-green-400 focus:outline-none transition" />
                    </form>

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

                        <button
                            class="openModalBtn inline-flex items-center gap-2 bg-green-600 hover:bg-green-700 text-white font-medium py-2 px-4 rounded-lg cursor-pointer transition">
                            + Thêm lịch thu gom
                        </button>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full border-t border-gray-100">
                        <thead class="bg-green-50 text-gray-600 text-sm font-semibold">
                            <tr>
                                <th class="py-3 px-4 text-left">
                                    <input type="checkbox" id="selectAllCheckbox"
                                        class="w-4 h-4 accent-green-600 cursor-pointer">
                                </th>
                                <th class="py-3 px-4 text-left">#</th>
                                <th class="py-3 px-4 text-left">Nhân viên thực hiện</th>
                                <th class="py-3 px-4 text-left">Ngày thu gom</th>
                                <th class="py-3 px-4 text-left">Ngày hoàn thành</th>
                                <th class="py-3 px-4 text-left">Trạng thái</th>
                                <th class="py-3 px-4 text-center">Thao tác</th>
                            </tr>
                        </thead>
                        <tbody class="text-sm divide-y divide-gray-100">
                            @forelse ($collectionSchedules as $collectionSchdule)
                                <tr class="hover:bg-green-50">
                                    <td class="py-3 px-4"><input type="checkbox"
                                            class="checkbox w-4 h-4 accent-green-600 cursor-pointer"></td>
                                    <td class="py-3 px-4">{{ $collectionSchdule->schedule_id }}</td>
                                    <td class="py-3 px-4">{{ $collectionSchdule->staff->name }}</td>
                                    <td class="py-3 px-4">
                                        {{ \Carbon\Carbon::parse($collectionSchdule->scheduled_date)->format('Y-m-d') }}
                                    </td>
                                    <td class="py-3 px-4">{{ $collectionSchdule->completed_at }}</td>
                                    <td class="py-3 px-4">
                                        {{-- <span class="px-3 py-1 text-xs font-medium rounded-full bg-green-100 text-green-700">
                                    {{ $collectionSchdule->status }}
                                </span> --}}
                                        @if ($collectionSchdule->status == 'Chưa thực hiện')
                                            <span
                                                class="px-3 py-1 text-xs font-medium rounded-full bg-yellow-100 text-yellow-700">
                                                {{ $collectionSchdule->status }}
                                            </span>
                                        @elseif($collectionSchdule->status == 'Đang thực hiện')
                                            <span
                                                class="px-3 py-1 text-xs font-medium rounded-full bg-green-100 text-green-700">
                                                {{ $collectionSchdule->status }}
                                            </span>
                                        @endif
                                    </td>
                                    <td class="py-3 px-4 text-center">
                                        <div class="flex items-center justify-center">
                                            <button data-id="{{ $collectionSchdule->schedule_id }}"
                                                class="editBtn group inline-flex items-center justify-center hover:bg-amber-200 rounded-xl mx-1 p-2 transition cursor-pointer"
                                                data-modal="edit" aria-label="Chỉnh sửa">
                                                {{-- Icon chỉnh sửa --}}
                                                <svg class="w-5 h-5 group-hover:text-amber-600" viewBox="0 0 24 24"
                                                    color="#254434" fill="none" stroke="currentColor" aria-hidden="true">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M16.862 3.487a1.75 1.75 0 0 1 2.476 2.476L8.5 16.8 4 18l1.2-4.5 11.662-10.013Z" />
                                                </svg>
                                            </button>

                                            <button id="{{ $collectionSchdule->schedule_id }}"
                                                class="deleteBtn group inline-flex items-center justify-center hover:bg-red-200 rounded-xl mx-1 p-2 transition cursor-pointer"
                                                aria-label="Xóa">
                                                {{-- Icon xóa --}}
                                                <svg class="w-5 h-5 group-hover:text-red-600"
                                                    xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" color="#000"
                                                    fill="none">
                                                    <path
                                                        d="M19.5 5.5L18.8803 15.5251C18.7219 18.0864 18.6428 19.3671 18.0008 20.2879C17.6833 20.7431 17.2747 21.1273 16.8007 21.416C15.8421 22 14.559 22 11.9927 22C9.42312 22 8.1383 22 7.17905 21.4149C6.7048 21.1257 6.296 20.7408 5.97868 20.2848C5.33688 19.3626 5.25945 18.0801 5.10461 15.5152L4.5 5.5"
                                                        stroke="currentColor" stroke-width="2" stroke-linecap="round">
                                                    </path>
                                                    <path
                                                        d="M3 5.5H21M16.0557 5.5L15.3731 4.09173C14.9196 3.15626 14.6928 2.68852 14.3017 2.39681C14.215 2.3321 14.1231 2.27454 14.027 2.2247C13.5939 2 13.0741 2 12.0345 2C10.9688 2 10.436 2 9.99568 2.23412C9.8981 2.28601 9.80498 2.3459 9.71729 2.41317C9.32164 2.7167 9.10063 3.20155 8.65861 4.17126L8.05292 5.5"
                                                        stroke="currentColor" stroke-width="1.5" stroke-linecap="round">
                                                    </path>
                                                    <path d="M9.5 16.5L9.5 10.5" stroke="currentColor" stroke-width="2"
                                                        stroke-linecap="round"></path>
                                                    <path d="M14.5 16.5L14.5 10.5" stroke="currentColor" stroke-width="2"
                                                        stroke-linecap="round"></path>
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
                @if ($collectionSchedules->hasPages())
                    <div class="mt-3">
                        {{ $collectionSchedules->links() }}
                    </div>
                @endif
        @endif

        <div id="modal"
            class="hidden fixed inset-0 flex items-center justify-center z-50 bg-black/40 backdrop-blur-[2px] transition-opacity duration-300 opacity-0">
            <div id="modalBox"
                class="bg-white backdrop-blur-md border border-green-100 shadow-xl rounded-xl p-6 max-w-md w-full
              transform opacity-0 translate-y-5 scale-95 transition-all duration-300">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-lg font-semibold titleModal">Thêm lịch thu gom mới</h2>
                    <button id="closeModalBtn" class="text-gray-400 hover:text-gray-600 cursor-pointer">
                        <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <form class="space-y-4" action="{{ route('admin.collection-schedules.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="status" value="Chưa thực hiện">
                    <div class="relative">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nhân viên thực hiện: <span
                                class="text-red-500">*</span></label>
                        <input id="inputName" name="staff_id" type="text"
                            class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:border-green-500 focus:ring focus:ring-green-200 outline-none transition"
                            placeholder="Nhập tên nhân viên...">

                        <!-- Dropdown suggestions -->
                        <ul id="suggestions"
                            class="absolute top-full left-0 right-0 bg-white border border-gray-200 rounded-lg shadow-md mt-1 hidden max-h-48 overflow-y-auto z-10">
                        </ul>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Ngày thu gom: <span
                                class="text-red-500">*</span></label>
                        <input type="date" id="inputScheduledDate" name="scheduled_date"
                            class="w-full mt-1 px-3 py-2 border border-gray-300 rounded-lg focus:border-green-500 focus:ring focus:ring-green-200 outline-none">
                    </div>
                    <div class="completed-at-field">
                        <label class="block text-sm font-medium text-gray-700">Ngày hoàn thành:</label>
                        <input type="date" id="inputCompletedAt" name="completed_at"
                            class="w-full mt-1 px-3 py-2 border border-gray-300 rounded-lg focus:border-green-500 focus:ring focus:ring-green-200 outline-none">
                    </div>
                    <div class="status-field">
                        <label class="block text-sm font-medium text-gray-700">Trạng thái:</label>
                        <select id="selectStatus" name="status"
                            class="block w-full mt-1 rounded-lg border border-gray-300 bg-white px-3 py-2 focus:border-green-500 focus:ring focus:ring-green-200 outline-none transition">
                            <option value="Chưa thực hiện">Chưa thực hiện</option>
                            <option value="Đã hoàn thành">Đã hoàn thành</option>
                        </select>
                    </div>
                    <div class="flex justify-end gap-3 pt-3">
                        <button type="button" id="cancelBtn"
                            class="px-4 py-2 rounded-lg bg-gray-100 hover:bg-gray-300 cursor-pointer">Hủy</button>
                        <button type="submit"
                            class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg cursor-pointer">Lưu</button>
                    </div>
                </form>
            </div>
        </div>

        <div id="confirmModal"
            class="hidden fixed inset-0 flex items-center justify-center z-50 bg-white/5 backdrop-blur-[2px] transition-opacity duration-300 opacity-0">
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
                        <button type="submit"
                            class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg cursor-pointer">Chắc
                            chắn!</button>
                    </form>
                </div>
            </div>
        </div>

    </div>
    </div>

    <script>
        // Modal logic
        const modal = document.getElementById('modal');
        const modalBox = document.getElementById('modalBox');
        const openBtn = document.querySelectorAll('.openModalBtn');
        const closeBtn = document.getElementById('closeModalBtn');
        const cancelBtn = document.getElementById('cancelBtn');
        const saveBtn = document.getElementById('saveBtn');

        const confirmModal = document.getElementById('confirmModal');
        const confirmModalBox = document.getElementById('confirmModalBox');
        const editBtn = document.querySelectorAll('.editBtn');
        const deleteBtn = document.querySelectorAll('.deleteBtn');
        const closeConfirmModalBtn = document.getElementById('closeConfirmModalBtn');
        const cancelConfirmModalBtn = document.getElementById('cancelConfirmModalBtn');

        const form = modalBox.querySelector('form');

        const inputName = document.getElementById('inputName');
        const inputDate = document.getElementById('inputScheduledDate');

        const titleModal = document.querySelector('.titleModal');

        function openModal(modal, modalBox) {
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

        modal.addEventListener('click', e => {
            if (e.target === e.currentTarget) closeModal(modal, modalBox);
        });

        openBtn.forEach(btn => {
            btn.addEventListener('click', function() {
                titleModal.textContent = 'Thêm lịch thu gom mới';
                form.reset(); // Reset toàn bộ form
                form.action = "/collection-schedules";

                // Xóa input hidden _method nếu có
                const methodInput = form.querySelector('input[name="_method"]');
                if (methodInput) methodInput.remove();

                // displayCompletedAtField(false);
                // displayStatusField(false);
                openModal(modal, modalBox);
            });
        });

        closeBtn.addEventListener('click', function() {
            closeModal(modal, modalBox);
        });
        cancelBtn.addEventListener('click', function() {
            closeModal(modal, modalBox);
        });

        // Edit Modal logic
        editBtn.forEach(button => {
            button.addEventListener('click', async function() {
                const id = button.dataset.id;
                const res = await fetch(`/collection-schedules/${id}`);
                const data = await res.json();
                console.log(data);
                const inputCompletedAt = document.getElementById('inputCompletedAt');
                const selectStatus = document.getElementById('selectStatus');
                if (data) {
                    inputName.value = data.staff.name;
                    inputDate.value = data.scheduled_date.split(' ')[0];
                    inputCompletedAt.value = data.completed_at ? data.completed_at.split(' ')[0] : '';
                    // selectStatus.value = data.status;
                }
                titleModal.textContent = 'Chỉnh sửa lịch thu gom';
                // displayCompletedAtField(true);
                // displayStatusField(true);
                openModal(modal, modalBox);

                const form = modalBox.querySelector('form');
                form.action = `/collection-schedules/${id}`;
                if (form.querySelector('input[name="_method"]')) {
                    return; // Nếu đã có input hidden _method thì không thêm nữa
                }
                form.insertAdjacentHTML('beforeend', `
                    <input type="hidden" name="_method" value="PUT">
                `);
            });
        });

        function displayCompletedAtField(show) {
            const completedAtField = document.querySelector('.completed-at-field');
            if (show) {
                completedAtField.classList.remove('hidden');
            } else {
                completedAtField.classList.add('hidden');
            }
        }

        function displayStatusField(show) {
            const statusField = document.querySelector('.status-field');
            if (show) {
                statusField.classList.remove('hidden');
            } else {
                statusField.classList.add('hidden');
            }
        }

        // Confirm Delete Modal logic
        deleteBtn.forEach(button => {
            button.addEventListener('click', function() {
                openModal(confirmModal, confirmModalBox);
                const id = button.id;
                confirmModalBox.querySelector('form').action = `/collection-schedules/${id}`;
            });
        });

        confirmModal.addEventListener('click', e => {
            if (e.target === e.currentTarget) closeModal(confirmModal, confirmModalBox);
        });

        closeConfirmModalBtn.addEventListener('click', function() {
            closeModal(confirmModal, confirmModalBox);
        });
        cancelConfirmModalBtn.addEventListener('click', function() {
            closeModal(confirmModal, confirmModalBox);
        });

        //   Suggestions logic
        const input = document.getElementById('inputName');
        const suggestions = document.getElementById('suggestions');

        input.addEventListener('input', async function() {
            const keyword = this.value.toLowerCase().trim();
            suggestions.innerHTML = '';

            if (keyword === '') {
                suggestions.classList.add('hidden');
                return;
            }

            const res = await fetch(`/search-users?q=${encodeURIComponent(keyword)}`);
            const data = await res.json();
            if (data.length === 0) {
                suggestions.innerHTML = '<li class="px-4 py-2 text-gray-500">Không tìm thấy nhân viên</li>';
            } else {
                suggestions.innerHTML = '';
                data.forEach(name => {
                    const li = document.createElement('li');
                    li.textContent = name;
                    li.className = 'px-4 py-2 hover:bg-gray-100 cursor-pointer';
                    li.addEventListener('click', () => {
                        input.value = name;
                        suggestions.classList.add('hidden');
                    });
                    suggestions.appendChild(li);
                });
            }
            suggestions.classList.remove('hidden');
        });

        document.addEventListener('click', e => {
            if (!e.target.closest('#inputName') && !e.target.closest('#suggestions')) {
                suggestions.classList.add('hidden');
            }
        });
    </script>
    {{-- @endif --}}
@endsection
