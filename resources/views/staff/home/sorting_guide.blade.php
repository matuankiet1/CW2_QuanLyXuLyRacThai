@extends('layouts.user')

@section('title', 'Hướng dẫn phân loại rác')

@section('content')
    <div class="p-6 max-w-7xl mx-auto space-y-8">

        <!-- Tiêu đề -->
        <div class="text-center">
            <h1 class="text-3xl font-bold text-green-700">Hướng dẫn phân loại rác</h1>
            <p class="text-gray-600 mt-2">
                Giúp bạn phân loại rác đúng cách để bảo vệ môi trường.
            </p>
        </div>

        <!-- Grid phân loại -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

            <!-- 1. Rác tái chế -->
            <div class="p-5 bg-white shadow-md rounded-xl border border-gray-200 hover:shadow-lg transition">
                <img src="{{ asset('images/guide/recyclable.png') }}" alt="Rác tái chế"
                    class="w-full h-40 object-cover rounded-lg mb-3">
                <h3 class="font-semibold text-lg text-blue-600 mb-2 flex items-center">
                    <i class="fas fa-recycle mr-2"></i>Rác tái chế
                </h3>
                <ul class="list-disc ml-5 text-gray-700 space-y-1">
                    <li>Giấy, bìa carton</li>
                    <li>Nhựa PET, chai lọ nhựa</li>
                    <li>Lon nhôm, kim loại</li>
                    <li>Thủy tinh sạch</li>
                </ul>
                <p class="text-sm text-gray-500 mt-2">
                    *Lưu ý: làm sạch trước khi bỏ vào thùng.
                </p>
            </div>

            <!-- 2. Rác hữu cơ -->
            <div class="p-5 bg-white shadow-md rounded-xl border border-gray-200 hover:shadow-lg transition">
                <img src="{{ asset('images/guide/organic.png') }}" alt="Rác hữu cơ"
                    class="w-full h-40 object-cover rounded-lg mb-3">
                <h3 class="font-semibold text-lg text-green-600 mb-2 flex items-center">
                    <i class="fas fa-leaf mr-2"></i>Rác hữu cơ
                </h3>
                <ul class="list-disc ml-5 text-gray-700 space-y-1">
                    <li>Thức ăn thừa</li>
                    <li>Vỏ trái cây, rau củ</li>
                    <li>Bã cà phê, túi trà</li>
                    <li>Lá cây, cỏ</li>
                </ul>
                <p class="text-sm text-gray-500 mt-2">
                    *Có thể dùng để ủ phân compost.
                </p>
            </div>

            <!-- 3. Rác sinh hoạt -->
            <div class="p-5 bg-white shadow-md rounded-xl border border-gray-200 hover:shadow-lg transition">
                <img src="{{ asset('images/guide/household.png') }}" alt="Rác sinh hoạt"
                    class="w-full h-40 object-cover rounded-lg mb-3">
                <h3 class="font-semibold text-lg text-gray-700 mb-2 flex items-center">
                    <i class="fas fa-trash mr-2"></i>Rác sinh hoạt
                </h3>
                <ul class="list-disc ml-5 text-gray-700 space-y-1">
                    <li>Túi nylon bẩn</li>
                    <li>Xốp, vỏ bánh kẹo</li>
                    <li>Đồ dùng nhựa dùng một lần</li>
                    <li>Khăn giấy đã sử dụng</li>
                </ul>
            </div>

            <!-- 4. Rác nguy hại -->
            <div class="p-5 bg-white shadow-md rounded-xl border border-gray-200 hover:shadow-lg transition">
                <img src="{{ asset('images/guide/hazardous.png') }}" alt="Rác nguy hại"
                    class="w-full h-40 object-cover rounded-lg mb-3">
                <h3 class="font-semibold text-lg text-red-600 mb-2 flex items-center">
                    <i class="fas fa-skull-crossbones mr-2"></i>Rác nguy hại
                </h3>
                <ul class="list-disc ml-5 text-gray-700 space-y-1">
                    <li>Pin, ắc quy</li>
                    <li>Bóng đèn, thiết bị điện tử</li>
                    <li>Hóa chất, sơn, dầu thải</li>
                    <li>Kim tiêm, vật sắc nhọn</li>
                </ul>
                <p class="text-sm text-red-500 mt-2">
                    *Cần thu gom riêng và giao đúng điểm tiếp nhận.
                </p>
            </div>

            <!-- 5. Rác cồng kềnh -->
            <div class="p-5 bg-white shadow-md rounded-xl border border-gray-200 hover:shadow-lg transition">
                <img src="{{ asset('images/guide/bulky.png') }}" alt="Rác cồng kềnh"
                    class="w-full h-40 object-cover rounded-lg mb-3">
                <h3 class="font-semibold text-lg text-yellow-600 mb-2 flex items-center">
                    <i class="fas fa-couch mr-2"></i>Rác cồng kềnh
                </h3>
                <ul class="list-disc ml-5 text-gray-700 space-y-1">
                    <li>Bàn ghế, tủ, giường cũ</li>
                    <li>Nệm, sofa hỏng</li>
                    <li>Đồ nội thất lớn</li>
                    <li>Thiết bị gia dụng hỏng</li>
                </ul>
                <p class="text-sm text-gray-500 mt-2">
                    *Liên hệ dịch vụ thu gom cồng kềnh.
                </p>
            </div>

            <!-- 6. Rác điện tử -->
            <div class="p-5 bg-white shadow-md rounded-xl border border-gray-200 hover:shadow-lg transition">
                <img src="{{ asset('images/guide/e-waste.png') }}" alt="Rác điện tử"
                    class="w-full h-40 object-cover rounded-lg mb-3">
                <h3 class="font-semibold text-lg text-purple-600 mb-2 flex items-center">
                    <i class="fas fa-tv mr-2"></i>Rác điện tử
                </h3>
                <ul class="list-disc ml-5 text-gray-700 space-y-1">
                    <li>Điện thoại, máy tính hỏng</li>
                    <li>Linh kiện điện tử</li>
                    <li>Tivi, tủ lạnh cũ</li>
                </ul>
                <p class="text-sm text-gray-500 mt-2">
                    *Thu gom tại các điểm tái chế chuyên dụng.
                </p>
            </div>

            <!-- 7. Rác y tế / sinh học -->
            <div class="p-5 bg-white shadow-md rounded-xl border border-gray-200 hover:shadow-lg transition">
                <img src="{{ asset('images/guide/medical.png') }}" alt="Rác y tế"
                    class="w-full h-40 object-cover rounded-lg mb-3">
                <h3 class="font-semibold text-lg text-pink-600 mb-2 flex items-center">
                    <i class="fas fa-procedures mr-2"></i>Rác y tế
                </h3>
                <ul class="list-disc ml-5 text-gray-700 space-y-1">
                    <li>Kim tiêm, bông băng</li>
                    <li>Găng tay, khẩu trang đã sử dụng</li>
                    <li>Thiết bị y tế thải bỏ</li>
                </ul>
                <p class="text-sm text-pink-500 mt-2">
                    *Không vứt chung với rác sinh hoạt.
                </p>
            </div>

            <!-- 8. Rác xây dựng -->
            <div class="p-5 bg-white shadow-md rounded-xl border border-gray-200 hover:shadow-lg transition">
                <img src="{{ asset('images/guide/construction.png') }}" alt="Rác xây dựng"
                    class="w-full h-40 object-cover rounded-lg mb-3">
                <h3 class="font-semibold text-lg text-gray-800 mb-2 flex items-center">
                    <i class="fas fa-hammer mr-2"></i>Rác xây dựng
                </h3>
                <ul class="list-disc ml-5 text-gray-700 space-y-1">
                    <li>Gạch, xi măng, bê tông</li>
                    <li>Gỗ vụn, ván ép</li>
                    <li>Vật liệu thừa khác</li>
                </ul>
                <p class="text-sm text-gray-500 mt-2">
                    *Thu gom riêng, không bỏ chung với rác sinh hoạt.
                </p>
            </div>

            <!-- 9. Rác quần áo / vải -->
            <div class="p-5 bg-white shadow-md rounded-xl border border-gray-200 hover:shadow-lg transition">
                <img src="{{ asset('images/guide/textile.png') }}" alt="Rác quần áo"
                    class="w-full h-40 object-cover rounded-lg mb-3">
                <h3 class="font-semibold text-lg text-teal-600 mb-2 flex items-center">
                    <i class="fas fa-tshirt mr-2"></i>Rác quần áo / vải
                </h3>
                <ul class="list-disc ml-5 text-gray-700 space-y-1">
                    <li>Quần áo cũ, rách</li>
                    <li>Chăn, ga, gối hỏng</li>
                    <li>Giày dép hỏng</li>
                </ul>
                <p class="text-sm text-gray-500 mt-2">
                    *Có thể tái chế hoặc ủ phân.
                </p>
            </div>

        </div>

        <!-- Nút quay lại -->
        <div class="text-center">
            <a href="{{ route('staff.home.index') }}"
                class="inline-block px-5 py-2 bg-gray-200 rounded-xl hover:bg-gray-300 transition">
                ← Quay lại
            </a>
        </div>

    </div>
@endsection