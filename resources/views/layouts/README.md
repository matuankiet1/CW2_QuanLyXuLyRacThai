# Admin Layout Documentation

## Tổng quan

Layout admin đã được refactor để sử dụng **Tailwind CSS** hoàn toàn, loại bỏ Bootstrap. Layout này cung cấp các component có thể tái sử dụng và dễ dàng tùy chỉnh.

## Cấu trúc Layout

### 1. Layout chính: `admin-with-sidebar.blade.php`

Layout này bao gồm:
- Navigation bar (top bar)
- Sidebar (bên trái)
- Main content area
- Flash messages
- Responsive design với mobile sidebar toggle

### 2. Component Sidebar: `partials/admin-sidebar.blade.php`

Sidebar component với menu có thể cấu hình.

### 3. Component Page Header: `components/admin-page-header.blade.php`

Component để tạo page header nhất quán.

## Cách sử dụng

### Sử dụng layout cơ bản

```blade
@extends('layouts.admin-with-sidebar')

@section('title', 'Tiêu đề trang')

@section('content')
    <!-- Nội dung của bạn -->
@endsection
```

### Sử dụng Page Header

```blade
@section('page-header')
    <x-admin-page-header 
        title="Quản lý người dùng"
        description="Quản lý tài khoản người dùng trong hệ thống"
        :actions="[
            [
                'label' => 'Thêm người dùng',
                'url' => route('admin.users.create'),
                'icon' => 'fas fa-plus',
                'type' => 'admin'
            ]
        ]"
    />
@endsection
```

Hoặc tự tạo:

```blade
@section('page-header')
    <div class="flex justify-between items-start mb-6">
        <div>
            <h1 class="text-2xl font-semibold text-gray-900 mb-1">Tiêu đề</h1>
            <p class="text-gray-500 text-sm">Mô tả</p>
        </div>
        <a href="{{ route('admin.users.create') }}" class="btn-admin">
            <i class="fas fa-plus mr-2"></i>Thêm mới
        </a>
    </div>
@endsection
```

### Sử dụng Breadcrumb

```blade
@section('breadcrumb')
    <li class="breadcrumb-item">
        <a href="{{ route('admin.home') }}">Trang chủ</a>
    </li>
    <li class="breadcrumb-item">
        <a href="{{ route('admin.users.index') }}">Người dùng</a>
    </li>
    <li class="breadcrumb-item active">Chi tiết</li>
@endsection
```

### Tùy chỉnh Sidebar Menu

Để tùy chỉnh menu trong một view cụ thể:

```blade
@php
    $menuItems = [
        [
            'section' => 'Tổng quan',
            'items' => [
                [
                    'label' => 'Dashboard',
                    'icon' => 'fa-chart-line',
                    'route' => 'dashboard.admin',
                    'active' => request()->routeIs('dashboard.admin'),
                    'badge' => [
                        'text' => 'New',
                        'class' => 'bg-red-500 text-white'
                    ]
                ],
            ],
        ],
    ];
@endphp

@extends('layouts.admin-with-sidebar')
```

## Utility Classes

Layout cung cấp các utility classes sẵn có:

### Buttons

- `btn-admin` - Button admin (xanh lá)
- `btn-primary` - Button primary
- `btn-secondary` - Button secondary
- `btn-danger` - Button danger (đỏ)

### Cards

- `card` - Card container
- `card-header` - Card header
- `card-body` - Card body

### Tables

- `table` - Table container
- Tự động có hover effects và styling

### Forms

- `form-control` - Input field
- `form-label` - Label

### Alerts

- `alert alert-success` - Success message
- `alert alert-danger` - Error message
- `alert alert-warning` - Warning message
- `alert alert-info` - Info message

### Badges

- `badge badge-success` - Success badge
- `badge badge-danger` - Danger badge
- `badge badge-warning` - Warning badge
- `badge badge-info` - Info badge
- `badge badge-primary` - Primary badge

## Ví dụ hoàn chỉnh

```blade
@extends('layouts.admin-with-sidebar')

@section('title', 'Quản lý người dùng')

@section('page-header')
    <div class="flex justify-between items-start mb-6">
        <div>
            <h1 class="text-2xl font-semibold text-gray-900 mb-1">Quản lý người dùng</h1>
            <p class="text-gray-500 text-sm">Quản lý tài khoản người dùng trong hệ thống</p>
        </div>
        <a href="{{ route('admin.users.create') }}" class="btn-admin">
            <i class="fas fa-plus mr-2"></i>Thêm người dùng
        </a>
    </div>
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item">
        <a href="{{ route('admin.home') }}">Trang chủ</a>
    </li>
    <li class="breadcrumb-item active">Người dùng</li>
@endsection

@section('content')
    <div class="card">
        <div class="card-body">
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Tên</th>
                        <th>Email</th>
                        <th>Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Table rows -->
                </tbody>
            </table>
        </div>
    </div>
@endsection
```

## Responsive Design

Layout tự động responsive:
- Desktop: Sidebar luôn hiển thị
- Mobile: Sidebar ẩn, có nút toggle để mở/đóng
- Overlay khi mở sidebar trên mobile

## Flash Messages

Flash messages tự động hiển thị với styling Tailwind:

```php
return redirect()->route('admin.users.index')
    ->with('success', 'Thêm người dùng thành công!');
```

## Customization

### Thay đổi màu sắc

Chỉnh sửa trong `admin-with-sidebar.blade.php`:

```javascript
tailwind.config = {
    theme: {
        extend: {
            colors: {
                primary: '#10b981', // Màu chính
                secondary: '#059669', // Màu phụ
                accent: '#34d399', // Màu nhấn
            }
        }
    }
}
```

### Thay đổi sidebar width

Chỉnh sửa CSS variable:

```css
:root {
    --sidebar-width: 280px; /* Đổi thành giá trị mong muốn */
}
```

## Lưu ý

1. Layout sử dụng Tailwind CDN, nên cần kết nối internet
2. Có thể tích hợp Tailwind CSS build process để tối ưu hóa
3. Tất cả các class Bootstrap đã được thay thế bằng Tailwind
4. Component sidebar có thể được override trong từng view

