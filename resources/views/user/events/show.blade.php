{{-- 
    View: Chi tiết sự kiện (Sinh viên)
    Route: GET /events/{id}
    Controller: UserEventController@show
    
    Chức năng:
    - Hiển thị chi tiết sự kiện
    - Hiển thị trạng thái đăng ký
    - Nút đăng ký / hủy đăng ký
--}}
@extends('layouts.user')

@section('title', $event->title)

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

    {{-- Breadcrumb --}}
    <nav class="mb-6" aria-label="Breadcrumb">
        <ol class="flex items-center space-x-2 text-sm text-gray-600">
            <li><a href="{{ route('home') }}" class="hover:text-green-600 transition">Trang chủ</a></li>
            <li><i class="fas fa-chevron-right text-xs"></i></li>
            <li><a href="{{ route('user.events.index') }}" class="hover:text-green-600 transition">Sự kiện</a></li>
            <li><i class="fas fa-chevron-right text-xs"></i></li>
            <li class="text-gray-900 font-medium">{{ Str::limit($event->title, 30) }}</li>
        </ol>
    </nav>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Main Content --}}
        <div class="lg:col-span-2">
            {{-- Event Header --}}
            <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                <h1 class="text-3xl font-bold text-gray-900 mb-4">{{ $event->title }}</h1>
                
                {{-- Status Badge --}}
                <div class="mb-6 flex flex-wrap gap-2">
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
                    
                    {{-- Registration Status --}}
                    @if($isRegistered && $userRegistration)
                        @php
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
                        <span class="px-4 py-2 rounded-full text-sm font-semibold text-white {{ $regColors[$userRegistration->status] ?? 'bg-gray-500' }}">
                            {{ $regTexts[$userRegistration->status] ?? $userRegistration->status }}
                        </span>
                    @endif
                </div>
                
                {{-- Event Info --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                    <div class="flex items-start space-x-3">
                        <div class="flex-shrink-0 w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-map-marker-alt text-green-600"></i>
                        </div>
                        <div>
                            <div class="font-semibold text-gray-900 mb-1">Địa điểm</div>
                            <div class="text-gray-600">{{ $event->location }}</div>
                        </div>
                    </div>
                    
                    <div class="flex items-start space-x-3">
                        <div class="flex-shrink-0 w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-calendar text-green-600"></i>
                        </div>
                        <div>
                            <div class="font-semibold text-gray-900 mb-1">Ngày diễn ra</div>
                            <div class="text-gray-600">{{ $event->event_start_date->format('d/m/Y') }}</div>
                        </div>
                    </div>
                    
                    <div class="flex items-start space-x-3">
                        <div class="flex-shrink-0 w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-clock text-green-600"></i>
                        </div>
                        <div>
                            <div class="font-semibold text-gray-900 mb-1">Thời gian</div>
                            <div class="text-gray-600">
                                {{ $event->event_start_date->format('H:i') }} - 
                                {{ $event->event_end_date->format('H:i') }}
                            </div>
                        </div>
                    </div>
                    
                    <div class="flex items-start space-x-3">
                        <div class="flex-shrink-0 w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-calendar-check text-green-600"></i>
                        </div>
                        <div>
                            <div class="font-semibold text-gray-900 mb-1">Đăng ký từ</div>
                            <div class="text-gray-600">
                                {{ $event->register_date->format('d/m/Y') }} - 
                                {{ $event->register_end_date->format('d/m/Y') }}
                            </div>
                        </div>
                    </div>
                    
                    @if($event->capacity)
                        <div class="flex items-start space-x-3">
                            <div class="flex-shrink-0 w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                                <i class="fas fa-users text-green-600"></i>
                            </div>
                            <div>
                                <div class="font-semibold text-gray-900 mb-1">Số lượng</div>
                                <div class="text-gray-600">
                                    {{ $event->available_slots }} / {{ $event->capacity }} chỗ còn lại
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
                
                {{-- Description --}}
                @if($event->description)
                    <div class="border-t pt-6">
                        <h5 class="text-lg font-semibold text-gray-900 mb-3">Mô tả sự kiện</h5>
                        <div class="text-gray-700 whitespace-pre-wrap leading-relaxed">
                            {{ $event->description }}
                        </div>
                    </div>
                @endif
            </div>
        </div>
        
        {{-- Sidebar --}}
        <div class="lg:col-span-1">
            {{-- Action Card --}}
            <div class="bg-white rounded-lg shadow-md p-6 mb-6 sticky top-4">
                <h5 class="text-lg font-semibold text-gray-900 mb-4">Tham gia sự kiện</h5>
                
                {{-- Registration Stats --}}
                <div class="mb-6 space-y-3">
                    <div class="flex justify-between items-center pb-2 border-b">
                        <span class="text-sm text-gray-600">Chờ xác nhận:</span>
                        <strong class="text-gray-900">{{ $registrationStats['pending'] }}</strong>
                    </div>
                    <div class="flex justify-between items-center pb-2 border-b">
                        <span class="text-sm text-gray-600">Đã xác nhận:</span>
                        <strong class="text-gray-900">{{ $registrationStats['confirmed'] }}</strong>
                    </div>
                    <div class="flex justify-between items-center pb-2 border-b">
                        <span class="text-sm text-gray-600">Đã tham gia:</span>
                        <strong class="text-gray-900">{{ $registrationStats['attended'] }}</strong>
                    </div>
                    <div class="flex justify-between items-center pt-2">
                        <strong class="text-gray-900">Tổng cộng:</strong>
                        <strong class="text-lg text-green-600">{{ $registrationStats['total'] }}</strong>
                    </div>
                </div>
                
                {{-- Action Buttons --}}
                @auth
                    @if($isRegistered)
                        @if(in_array($userRegistration->status, ['pending', 'confirmed']))
                            {{-- Hủy đăng ký --}}
                            <form action="{{ route('user.events.cancel', $event->id) }}" method="POST" onsubmit="return confirm('Bạn có chắc chắn muốn hủy đăng ký tham gia sự kiện này?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="w-full px-4 py-3 bg-red-600 text-white rounded-lg hover:bg-red-700 transition font-medium">
                                    <i class="fas fa-times mr-2"></i>Hủy đăng ký
                                </button>
                            </form>
                            @if($userRegistration->registered_at)
                                <p class="text-xs text-gray-500 mt-3 text-center">
                                    Đăng ký lúc: {{ $userRegistration->registered_at->format('H:i d/m/Y') }}
                                </p>
                            @endif
                        @elseif($userRegistration->status === 'attended')
                            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg text-center">
                                <i class="fas fa-check-circle mr-2"></i>Bạn đã tham gia sự kiện này.
                            </div>
                        @else
                            <div class="bg-gray-100 border border-gray-400 text-gray-700 px-4 py-3 rounded-lg text-center">
                                Đăng ký của bạn đã bị hủy.
                            </div>
                        @endif
                    @else
                        {{-- Đăng ký --}}
                        @if($event->canRegister() && $event->status === "Đang đăng ký")
                            <form action="{{ route('user.events.register', $event->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="w-full px-4 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 transition font-medium">
                                    <i class="fas fa-user-plus mr-2"></i>Đăng ký tham gia
                                </button>
                            </form>
                        @else
                            <div class="bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded-lg text-center">
                                @if(!$event->hasAvailableSlots())
                                    <i class="fas fa-exclamation-triangle mr-2"></i>Sự kiện đã đầy.
                                @elseif($event->status === "Sắp diễn ra")
                                    <i class="fas fa-clock mr-2"></i>Chưa đến thời gian đăng ký.
                                @else
                                    <i class="fas fa-times mr-2"></i>Đã hết thời gian đăng ký.
                                @endif
                            </div>
                        @endif
                    @endif
                @else
                    <div class="bg-blue-100 border border-blue-400 text-blue-700 px-4 py-3 rounded-lg text-center">
                        <i class="fas fa-info-circle mr-2"></i>Vui lòng <a href="{{ route('login') }}" class="underline font-semibold">đăng nhập</a> để đăng ký tham gia sự kiện.
                    </div>
                @endauth
            </div>
            
            {{-- Creator Info --}}
            @if($event->createdBy)
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h6 class="text-lg font-semibold text-gray-900 mb-4">Người tổ chức</h6>
                    <div class="flex items-center space-x-3">
                        <div class="w-12 h-12 bg-green-600 text-white rounded-full flex items-center justify-center font-bold text-lg">
                            {{ strtoupper(substr($event->createdBy->name, 0, 1)) }}
                        </div>
                        <div>
                            <div class="font-semibold text-gray-900">{{ $event->createdBy->name }}</div>
                            <div class="text-sm text-gray-600">{{ $event->createdBy->email }}</div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
