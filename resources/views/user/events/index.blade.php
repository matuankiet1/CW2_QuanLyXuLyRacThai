{{-- 
    View: Danh sách sự kiện (Sinh viên)
    Route: GET /events
    Controller: UserEventController@index
    
    Chức năng:
    - Hiển thị danh sách sự kiện
    - Lọc theo trạng thái (upcoming, ongoing, ended)
    - Tìm kiếm sự kiện
    - Hiển thị trạng thái đăng ký (nếu đã đăng nhập)
--}}
@extends('layouts.user')

@section('title', 'Danh sách sự kiện')

@section('content')
<div class="container mx-auto px-4 py-8">
    {{-- Flash Messages --}}
    @if(session('success'))
        <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg relative" role="alert">
            <span class="block sm:inline">
                <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
            </span>
            <button type="button" class="absolute top-0 bottom-0 right-0 px-4 py-3" onclick="this.parentElement.style.display='none'">
                <i class="fas fa-times"></i>
            </button>
        </div>
    @endif

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
        <h1 class="text-4xl font-bold text-gray-900 mb-2">🎉 Sự kiện thu gom rác</h1>
        <p class="text-gray-600 text-lg">Tham gia các sự kiện thu gom rác và góp phần bảo vệ môi trường</p>
    </div>

    {{-- Filter và Search --}}
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <form method="GET" action="{{ route('user.events.index') }}" class="flex flex-col md:flex-row gap-4">
            {{-- Tìm kiếm --}}
            <div class="flex-1">
                <input type="text" 
                       name="search" 
                       value="{{ $search ?? '' }}" 
                       placeholder="Tìm kiếm sự kiện..." 
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
            </div>
            
            {{-- Lọc theo trạng thái --}}
            <div class="flex gap-2">
                <a href="{{ route('user.events.index', ['status' => 'up_coming']) }}" 
                   class="px-4 py-2 rounded-lg transition {{ ($status ?? 'up_coming') === 'up_coming' ? 'bg-green-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                    Sắp tới
                </a>
                 <a href="{{ route('user.events.index', ['status' => 'register_ended']) }}" 
                   class="px-4 py-2 rounded-lg transition {{ ($status ?? 'register_ended') === 'register_ended' ? 'bg-green-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                    Hết đăng ký
                </a>
                <a href="{{ route('user.events.index', ['status' => 'registering']) }}" 
                   class="px-4 py-2 rounded-lg transition {{ ($status ?? 'registering') === 'registering' ? 'bg-green-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                    Đang đăng ký
                </a>
                <a href="{{ route('user.events.index', ['status' => 'on_going']) }}" 
                   class="px-4 py-2 rounded-lg transition {{ ($status ?? 'on_going') === 'on_going' ? 'bg-green-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                    Đang diễn ra
                </a>
                <a href="{{ route('user.events.index', ['status' => 'ended']) }}" 
                   class="px-4 py-2 rounded-lg transition {{ ($status ?? 'ended') === 'ended' ? 'bg-green-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                    Đã kết thúc
                </a>
            </div>
            
            {{-- Nút tìm kiếm --}}
            <button type="submit" class="px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition">
                <i class="fas fa-search mr-2"></i>Tìm kiếm
            </button>
        </form>
    </div>

    {{-- Danh sách sự kiện --}}
    @if($events->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($events as $event)
                <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
                    {{-- Image placeholder --}}
                    <div class="h-48 bg-gradient-to-r from-green-400 to-green-600 flex items-center justify-center">
                        <i class="fas fa-calendar-alt text-white text-6xl"></i>
                    </div>
                    
                    {{-- Content --}}
                    <div class="p-6">
                        {{-- Title --}}
                        <h3 class="text-xl font-bold text-gray-900 mb-3 line-clamp-2">
                            <a href="{{ route('user.events.show', $event->id) }}" class="hover:text-green-600 transition">
                                {{ $event->title }}
                            </a>
                        </h3>
                        
                        {{-- Info --}}
                        <div class="space-y-2 mb-4">
                            <div class="flex items-center text-gray-600">
                                <i class="fas fa-map-marker-alt mr-2 text-green-600 w-4"></i>
                                <span class="text-sm">{{ $event->location }}</span>
                            </div>
                            <div class="flex items-center text-gray-600">
                                <i class="fas fa-calendar mr-2 text-green-600 w-4"></i>
                                <span class="text-sm"> Ngày đăng ký: {{ $event->register_date->format('d/m/Y') }} - {{ $event->register_end_date->format('d/m/Y') }}</span>
                            </div>
                            <div class="flex items-center text-gray-600">
                                <i class="fas fa-calendar mr-2 text-green-600 w-4"></i>
                                <span class="text-sm"> Ngày diễn ra: {{ $event->event_start_date->format('d/m/Y') }} - {{ $event->event_end_date->format('d/m/Y') }}</span>
                            </div>
                            @if($event->capacity)
                                <div class="flex items-center text-gray-600">
                                    <i class="fas fa-users mr-2 text-green-600 w-4"></i>
                                    <span class="text-sm">{{ $event->available_slots }} / {{ $event->capacity }} chỗ còn lại</span>
                                </div>
                            @endif
                        </div>
                        
                        {{-- Status badge --}}
                        <div class="mb-4 flex flex-wrap gap-2">
                            @php
                            $statusColors = [
                                'Sắp diễn ra'   => 'bg-purple-500',
                                'Đang đăng ký'  => 'bg-blue-500',
                                'Hết đăng ký'  => 'bg-red-500',
                                'Đang diễn ra'  => 'bg-green-500',
                                'Kết thúc'      => 'bg-gray-500',
                                'Đang xử lý'   => 'bg-gray-500',
                            ];

                            $statusText = $event->status; // Sử dụng getter status
                            $statusColor = $statusColors[$statusText] ?? 'bg-gray-500';
                            @endphp
                           <span class="px-3 py-1 rounded-full text-xs font-semibold text-white {{ $statusColor }}">
                                {{ $statusText }}
                            </span>
                            
                            {{-- Trạng thái đăng ký --}}
                            @if(auth()->check() && isset($userRegistrations[$event->id]))
                                @php
                                    $regStatus = $userRegistrations[$event->id];
                                    $regColors = [
                                        'pending' => 'bg-yellow-500',
                                        'confirmed' => 'bg-blue-500',
                                        'attended' => 'bg-green-500',
                                        'canceled' => 'bg-red-500',
                                    ];
                                    $regTexts = [
                                        'pending' => 'Chờ xác nhận',
                                        'confirmed' => 'Đã xác nhận',
                                        'attended' => 'Đã tham gia',
                                        'canceled' => 'Đã hủy',
                                    ];
                                @endphp
                                <span class="px-3 py-1 rounded-full text-xs font-semibold text-white {{ $regColors[$regStatus] ?? 'bg-gray-500' }}">
                                    {{ $regTexts[$regStatus] ?? $regStatus }}
                                </span>
                            @endif
                        </div>
                        
                        {{-- Action button --}}
                        <a href="{{ route('user.events.show', $event->id) }}" 
                           class="block w-full text-center px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition font-medium">
                            Xem chi tiết <i class="fas fa-arrow-right ml-2"></i>
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
        
        {{-- Pagination --}}
        <div class="mt-8">
            {{ $events->appends(request()->query())->links('pagination.tailwind') }}
        </div>
    @else
        {{-- Empty state --}}
        <div class="bg-white rounded-lg shadow-md p-12 text-center">
            <i class="fas fa-calendar-times text-gray-300 text-6xl mb-4"></i>
            <h3 class="text-xl font-semibold text-gray-700 mb-2">Không tìm thấy sự kiện nào</h3>
            <p class="text-gray-500">Vui lòng thử lại với từ khóa hoặc bộ lọc khác.</p>
        </div>
    @endif
</div>
@endsection

