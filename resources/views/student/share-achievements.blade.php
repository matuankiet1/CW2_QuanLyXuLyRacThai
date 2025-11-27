@extends('layouts.user')

@section('title', 'Chia sẻ thành tích')

@section('content')
<div class="container mx-auto px-4 py-8 mt-20">
    <div class="max-w-4xl mx-auto">
        {{-- Header --}}
        <div class="mb-8 text-center">
            <h1 class="text-4xl font-bold text-gray-900 mb-2">
                <i class="fas fa-share-alt mr-3 text-green-600"></i>
                Chia sẻ thành tích của bạn
            </h1>
            <p class="text-gray-600 text-lg">Lan tỏa tinh thần xanh đến cộng đồng!</p>
        </div>

        {{-- Card thành tích --}}
        <div class="bg-gradient-to-br from-green-50 to-emerald-50 rounded-2xl shadow-xl p-8 mb-8 border-2 border-green-200">
            <div class="text-center mb-6">
                <div class="inline-block bg-green-100 rounded-full p-4 mb-4">
                    <i class="fas fa-trophy text-5xl text-green-600"></i>
                </div>
                <h2 class="text-2xl font-bold text-gray-900 mb-2">
                    Thành tích của {{ $user->name }}
                </h2>
                <p class="text-gray-600">Cùng xem bạn đã đóng góp gì cho môi trường!</p>
            </div>

            {{-- Thống kê thành tích --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
                {{-- Tổng số báo cáo --}}
                <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-blue-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-600 text-sm font-medium mb-1">Báo cáo đã gửi</p>
                            <p class="text-3xl font-bold text-gray-900">{{ $achievements['total_reports'] }}</p>
                        </div>
                        <div class="bg-blue-100 rounded-full p-3">
                            <i class="fas fa-flag text-blue-600 text-2xl"></i>
                        </div>
                    </div>
                </div>

                {{-- Báo cáo đã giải quyết --}}
                <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-green-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-600 text-sm font-medium mb-1">Đã giải quyết</p>
                            <p class="text-3xl font-bold text-gray-900">{{ $achievements['resolved_reports'] }}</p>
                        </div>
                        <div class="bg-green-100 rounded-full p-3">
                            <i class="fas fa-check-circle text-green-600 text-2xl"></i>
                        </div>
                    </div>
                </div>

                {{-- Lần phân loại rác --}}
                <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-purple-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-600 text-sm font-medium mb-1">Lần phân loại</p>
                            <p class="text-3xl font-bold text-gray-900">{{ $achievements['total_waste_logs'] }}</p>
                        </div>
                        <div class="bg-purple-100 rounded-full p-3">
                            <i class="fas fa-recycle text-purple-600 text-2xl"></i>
                        </div>
                    </div>
                </div>

                {{-- Tổng trọng lượng rác --}}
                <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-orange-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-600 text-sm font-medium mb-1">Rác đã phân loại</p>
                            <p class="text-2xl font-bold text-gray-900">
                                {{ number_format($achievements['total_waste_weight'], 2) }} kg
                            </p>
                        </div>
                        <div class="bg-orange-100 rounded-full p-3">
                            <i class="fas fa-weight text-orange-600 text-2xl"></i>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Điểm thành tích --}}
            <div class="bg-white rounded-xl shadow-md p-6 text-center">
                <p class="text-gray-600 text-sm font-medium mb-2">Điểm thành tích</p>
                <p class="text-4xl font-bold text-green-600 mb-2">
                    {{ number_format($achievements['achievement_score']) }} điểm
                </p>
                <p class="text-sm text-gray-500">Cảm ơn bạn đã đóng góp cho môi trường!</p>
            </div>
        </div>

        {{-- Nội dung chia sẻ --}}
        <div class="bg-white rounded-xl shadow-md p-6 mb-8">
            <h3 class="text-xl font-bold text-gray-900 mb-4">
                <i class="fas fa-quote-left mr-2 text-green-600"></i>
                Nội dung chia sẻ
            </h3>
            <div class="bg-gray-50 rounded-lg p-4 mb-4">
                <p class="text-gray-700 whitespace-pre-line" id="shareContent">{{ $facebookContent }}</p>
            </div>
            <button 
                onclick="copyToClipboard()" 
                class="inline-flex items-center px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition"
            >
                <i class="fas fa-copy mr-2"></i>
                Sao chép nội dung
            </button>
        </div>

        {{-- Nút chia sẻ --}}
        <div class="bg-white rounded-xl shadow-md p-8">
            <h3 class="text-xl font-bold text-gray-900 mb-6 text-center">
                <i class="fas fa-share-alt mr-2 text-green-600"></i>
                Chia sẻ lên mạng xã hội
            </h3>
            
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
                {{-- Facebook --}}
                <a 
                    href="{{ $shareUrls['facebook'] }}" 
                    target="_blank"
                    rel="noopener noreferrer"
                    class="flex flex-col items-center justify-center p-6 bg-blue-600 text-white rounded-xl hover:bg-blue-700 transition transform hover:scale-105 shadow-md"
                >
                    <i class="fab fa-facebook-f text-3xl mb-2"></i>
                    <span class="text-sm font-medium">Facebook</span>
                </a>

                {{-- Twitter/X --}}
                <a 
                    href="{{ $shareUrls['twitter'] }}" 
                    target="_blank"
                    rel="noopener noreferrer"
                    class="flex flex-col items-center justify-center p-6 bg-black text-white rounded-xl hover:bg-gray-800 transition transform hover:scale-105 shadow-md"
                >
                    <i class="fab fa-x-twitter text-3xl mb-2"></i>
                    <span class="text-sm font-medium">Twitter/X</span>
                </a>

                {{-- LinkedIn --}}
                <a 
                    href="{{ $shareUrls['linkedin'] }}" 
                    target="_blank"
                    rel="noopener noreferrer"
                    class="flex flex-col items-center justify-center p-6 bg-blue-700 text-white rounded-xl hover:bg-blue-800 transition transform hover:scale-105 shadow-md"
                >
                    <i class="fab fa-linkedin-in text-3xl mb-2"></i>
                    <span class="text-sm font-medium">LinkedIn</span>
                </a>

                {{-- Zalo --}}
                <a 
                    href="{{ $shareUrls['zalo'] }}" 
                    target="_blank"
                    rel="noopener noreferrer"
                    class="flex flex-col items-center justify-center p-6 bg-blue-500 text-white rounded-xl hover:bg-blue-600 transition transform hover:scale-105 shadow-md"
                >
                    <svg class="w-8 h-8 mb-2" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path d="M12 2C6.48 2 2 5.58 2 10c0 2.5 1.5 4.75 3.85 6.25L5 22l6.25-1.15C11.5 21.5 11.75 21.5 12 21.5c5.52 0 10-3.58 10-8s-4.48-8-10-8zm0 14.5c-.25 0-.5 0-.75-.05L7.5 17.5l1.05-3.75C7.5 12.5 6.5 11.25 6.5 10c0-2.75 2.25-5 5-5s5 2.25 5 5-2.25 5-5 5z"/>
                    </svg>
                    <span class="text-sm font-medium">Zalo</span>
                </a>

                {{-- WhatsApp --}}
                <a 
                    href="{{ $shareUrls['whatsapp'] }}" 
                    target="_blank"
                    rel="noopener noreferrer"
                    class="flex flex-col items-center justify-center p-6 bg-green-500 text-white rounded-xl hover:bg-green-600 transition transform hover:scale-105 shadow-md"
                >
                    <i class="fab fa-whatsapp text-3xl mb-2"></i>
                    <span class="text-sm font-medium">WhatsApp</span>
                </a>

                {{-- Copy Link --}}
                <button 
                    onclick="copyLink()"
                    class="flex flex-col items-center justify-center p-6 bg-gray-600 text-white rounded-xl hover:bg-gray-700 transition transform hover:scale-105 shadow-md"
                >
                    <i class="fas fa-link text-3xl mb-2"></i>
                    <span class="text-sm font-medium">Copy Link</span>
                </button>
            </div>
        </div>

        {{-- Nút quay lại --}}
        <div class="mt-8 text-center">
            <a href="{{ route('user.statistics.index') }}" class="inline-flex items-center px-6 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 transition">
                <i class="fas fa-arrow-left mr-2"></i>
                Quay lại thống kê
            </a>
        </div>
    </div>
</div>

<script>
function copyToClipboard() {
    const content = document.getElementById('shareContent').textContent;
    navigator.clipboard.writeText(content).then(() => {
        alert('Đã sao chép nội dung vào clipboard!');
    }).catch(err => {
        console.error('Lỗi khi sao chép:', err);
        alert('Không thể sao chép. Vui lòng thử lại.');
    });
}

function copyLink() {
    const link = '{{ $shareUrls["copy_link"] }}';
    navigator.clipboard.writeText(link).then(() => {
        alert('Đã sao chép link vào clipboard!');
    }).catch(err => {
        console.error('Lỗi khi sao chép:', err);
        alert('Không thể sao chép. Vui lòng thử lại.');
    });
}
</script>
@endsection

