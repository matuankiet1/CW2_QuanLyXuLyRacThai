@extends('layouts.admin-with-sidebar')

@section('title', 'Gửi báo cáo thu gom')

@section('content')
<div class="p-6 max-w-4xl mx-auto space-y-6">
    <a href="{{ route('staff.collection-reports.index') }}" class="text-sm text-green-600 hover:underline">&larr; Quay lại danh sách</a>

    <div class="bg-white shadow rounded-lg p-6">
        <div class="mb-6">
            <h1 class="text-2xl font-semibold text-gray-900 mb-1">Báo cáo thu gom rác</h1>
            <p class="text-gray-500 text-sm">
                Lịch thu gom ngày {{ $schedule->scheduled_date?->format('d/m/Y') ?? '-' }} - Mã #{{ $schedule->schedule_id }}
            </p>
        </div>

        @if($errors->any())
            <div class="mb-4 p-4 bg-red-50 text-red-600 rounded-lg">
                <ul class="list-disc list-inside text-sm">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('staff.collection-reports.store', $schedule->schedule_id) }}" method="POST" enctype="multipart/form-data" class="space-y-5">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tổng khối lượng (kg) <span class="text-red-500">*</span></label>
                    <input type="number" step="0.01" min="0" name="total_weight" value="{{ old('total_weight', optional($schedule->report)->total_weight) }}"
                        class="w-full border-gray-300 rounded-lg focus:ring-green-500 focus:border-green-500" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Rác hữu cơ (kg)</label>
                    <input type="number" step="0.01" min="0" name="organic_weight" value="{{ old('organic_weight', optional($schedule->report)->organic_weight) }}"
                        class="w-full border-gray-300 rounded-lg focus:ring-green-500 focus:border-green-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Rác tái chế (kg)</label>
                    <input type="number" step="0.01" min="0" name="recyclable_weight" value="{{ old('recyclable_weight', optional($schedule->report)->recyclable_weight) }}"
                        class="w-full border-gray-300 rounded-lg focus:ring-green-500 focus:border-green-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Rác nguy hại (kg)</label>
                    <input type="number" step="0.01" min="0" name="hazardous_weight" value="{{ old('hazardous_weight', optional($schedule->report)->hazardous_weight) }}"
                        class="w-full border-gray-300 rounded-lg focus:ring-green-500 focus:border-green-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Khác (kg)</label>
                    <input type="number" step="0.01" min="0" name="other_weight" value="{{ old('other_weight', optional($schedule->report)->other_weight) }}"
                        class="w-full border-gray-300 rounded-lg focus:ring-green-500 focus:border-green-500">
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Hình ảnh minh chứng</label>
                @if(optional($schedule->report)->photo_path)
                    <div class="mb-2">
                        <a href="{{ asset('storage/' . $schedule->report->photo_path) }}" target="_blank" class="text-green-600 text-sm hover:underline">
                            Xem ảnh hiện tại
                        </a>
                    </div>
                @endif
                <input type="file" name="photo" class="w-full border-gray-300 rounded-lg focus:ring-green-500 focus:border-green-500">
                <p class="text-xs text-gray-500 mt-1">Ảnh jpg, png, tối đa 5MB.</p>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Ghi chú</label>
                <textarea name="notes" rows="4" class="w-full border-gray-300 rounded-lg focus:ring-green-500 focus:border-green-500">{{ old('notes', optional($schedule->report)->notes) }}</textarea>
            </div>

            <div class="flex justify-end gap-3">
                <a href="{{ route('staff.collection-reports.index') }}" class="px-4 py-2 rounded-lg border border-gray-300 text-gray-700 hover:bg-gray-50">Hủy</a>
                <button type="submit" class="px-4 py-2 rounded-lg bg-green-600 text-white font-semibold hover:bg-green-700">
                    {{ $schedule->report ? 'Cập nhật báo cáo' : 'Gửi báo cáo' }}
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

