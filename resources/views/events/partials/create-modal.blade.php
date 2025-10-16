{{-- resources/views/events/partials/create-modal.blade.php --}}
<dialog id="createEventModal" class="p-8 rounded-lg shadow-xl w-full max-w-md">
    <form method="POST" action="{{ route('events.store') }}">
        @csrf
        <h3 class="text-lg font-bold mb-4">Tạo sự kiện mới</h3>
        <div class="space-y-4">
            <div class="space-y-2">
                <label for="title" class="text-sm font-medium">Tên sự kiện</label>
                <input id="title" name="title" type="text" placeholder="Nhập tên sự kiện" required class="w-full px-3 py-2 border rounded-md">
            </div>
            <div class="space-y-2">
                <label for="date" class="text-sm font-medium">Ngày tổ chức</label>
                <input id="date" name="date" type="date" required class="w-full px-3 py-2 border rounded-md">
            </div>
            <div class="space-y-2">
                <label for="location" class="text-sm font-medium">Địa điểm</label>
                <input id="location" name="location" type="text" placeholder="Nhập địa điểm" required class="w-full px-3 py-2 border rounded-md">
            </div>
            <div class="space-y-2">
                <label for="description" class="text-sm font-medium">Mô tả</label>
                <textarea id="description" name="description" placeholder="Mô tả chi tiết sự kiện..." rows="4" class="w-full px-3 py-2 border rounded-md"></textarea>
            </div>
        </div>
        <div class="mt-6 flex justify-end gap-4">
            <button type="button" class="px-4 py-2 bg-gray-200 rounded-md" onclick="document.getElementById('createEventModal').close()">Hủy</button>
            <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700">Tạo sự kiện</button>
        </div>
    </form>
</dialog>