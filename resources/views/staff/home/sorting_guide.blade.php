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
            <div x-data="{ open: false }"
                class="p-5 bg-white shadow-md rounded-xl border border-gray-200 hover:shadow-lg transition">
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
                <p class="text-sm text-blue-500 mt-2">*Lưu ý: làm sạch trước khi bỏ vào thùng.</p>

                <!-- Nút mở modal -->
                <button @click="open = true" class="mt-3 px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600">
                    Xem chi tiết
                </button>

                <!-- Modal -->
                <div x-show="open" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
                    <div class="bg-white rounded-xl shadow-lg p-6 w-11/12 md:w-2/3 lg:w-1/2 relative">
                        <button @click="open = false" class="absolute top-3 right-3 text-gray-500 hover:text-gray-800">
                            ✕
                        </button>
                        <h2 class="text-2xl font-bold text-blue-600 mb-4">Rác tái chế - Chi tiết</h2>
                        <p class="text-gray-700 mb-3">
                            Rác tái chế gồm giấy, bìa carton, nhựa PET, chai lọ nhựa, lon nhôm, kim loại và thủy tinh sạch.
                            Trước khi bỏ vào thùng, cần làm sạch để tái chế hiệu quả.
                        </p>
                        <img src="{{ asset('images/guide/recyclable.png') }}" alt="Rác tái chế chi tiết"
                            class="w-full h-64 object-cover rounded-lg">
                    </div>
                </div>
            </div>


            <!-- 2. Rác hữu cơ -->
            <div x-data="{ open: false }"
                class="p-5 bg-white shadow-md rounded-xl border border-gray-200 hover:shadow-lg transition">
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
                <p class="text-sm text-green-500 mt-2">*Có thể dùng để ủ phân compost.</p>

                <button @click="open = true" class="mt-3 px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600">
                    Xem chi tiết
                </button>

                <!-- Modal -->
                <div x-show="open" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
                    <div class="bg-white rounded-xl shadow-lg p-6 w-11/12 md:w-2/3 lg:w-1/2 relative">
                        <button @click="open = false"
                            class="absolute top-3 right-3 text-gray-500 hover:text-gray-800">✕</button>
                        <h2 class="text-2xl font-bold text-green-600 mb-4">Rác hữu cơ - Chi tiết</h2>
                        <p class="text-gray-700 mb-3">
                            Rác hữu cơ bao gồm thức ăn thừa, vỏ trái cây, rau củ, bã cà phê, túi trà, lá cây và cỏ.
                            Có thể ủ làm phân compost để tái sử dụng cho cây trồng.
                        </p>
                        <img src="{{ asset('images/guide/organic.png') }}" alt="Rác hữu cơ chi tiết"
                            class="w-full h-64 object-cover rounded-lg">
                    </div>
                </div>
            </div>

            <!-- 3. Rác sinh hoạt -->
            <div x-data="{ open: false }"
                class="p-5 bg-white shadow-md rounded-xl border border-gray-200 hover:shadow-lg transition">
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
                <p class="text-sm text-gray-500 mt-2">*Chôn lấp hợp vệ sinh, đốt rác phát điện, tái chế và ủ phân hữu cơ
                    tùy thuộc vào loại rác</p>
                <button @click="open = true" class="mt-3 px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600">
                    Xem chi tiết
                </button>

                <div x-show="open" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
                    <div class="bg-white rounded-xl shadow-lg p-6 w-11/12 md:w-2/3 lg:w-1/2 relative">
                        <button @click="open = false"
                            class="absolute top-3 right-3 text-gray-500 hover:text-gray-800">✕</button>
                        <h2 class="text-2xl font-bold text-gray-700 mb-4">Rác sinh hoạt - Chi tiết</h2>
                        <p class="text-gray-700 mb-3">
                            Rác sinh hoạt bao gồm túi nylon bẩn, xốp, vỏ bánh kẹo, đồ nhựa dùng một lần và khăn giấy đã sử
                            dụng.
                            Nên bỏ vào thùng rác sinh hoạt thông thường.
                        </p>
                        <img src="{{ asset('images/guide/household.png') }}" alt="Rác sinh hoạt chi tiết"
                            class="w-full h-64 object-cover rounded-lg">
                    </div>
                </div>
            </div>

            <!-- 4. Rác nguy hại -->
            <div x-data="{ open: false }"
                class="p-5 bg-white shadow-md rounded-xl border border-gray-200 hover:shadow-lg transition">
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
                <p class="text-sm text-red-500 mt-2">*Cần thu gom riêng và giao đúng điểm tiếp nhận.</p>

                <button @click="open = true" class="mt-3 px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600">
                    Xem chi tiết
                </button>

                <div x-show="open" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
                    <div class="bg-white rounded-xl shadow-lg p-6 w-11/12 md:w-2/3 lg:w-1/2 relative">
                        <button @click="open = false"
                            class="absolute top-3 right-3 text-gray-500 hover:text-gray-800">✕</button>
                        <h2 class="text-2xl font-bold text-red-600 mb-4">Rác nguy hại - Chi tiết</h2>
                        <p class="text-gray-700 mb-3">
                            Rác nguy hại gồm pin, ắc quy, bóng đèn, thiết bị điện tử, hóa chất, sơn, dầu thải, kim tiêm và
                            vật sắc nhọn.
                            Cần thu gom riêng và đưa đến các điểm tiếp nhận an toàn.
                        </p>
                        <img src="{{ asset('images/guide/hazardous.png') }}" alt="Rác nguy hại chi tiết"
                            class="w-full h-64 object-cover rounded-lg">
                    </div>
                </div>
            </div>

            <!-- 5. Rác cồng kềnh -->
            <div x-data="{ open: false }"
                class="p-5 bg-white shadow-md rounded-xl border border-gray-200 hover:shadow-lg transition">
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
                <p class="text-sm text-yellow-500 mt-2">*Liên hệ dịch vụ thu gom cồng kềnh.</p>

                <button @click="open = true" class="mt-3 px-4 py-2 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600">
                    Xem chi tiết
                </button>

                <div x-show="open" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
                    <div class="bg-white rounded-xl shadow-lg p-6 w-11/12 md:w-2/3 lg:w-1/2 relative">
                        <button @click="open = false"
                            class="absolute top-3 right-3 text-gray-500 hover:text-gray-800">✕</button>
                        <h2 class="text-2xl font-bold text-yellow-600 mb-4">Rác cồng kềnh - Chi tiết</h2>
                        <p class="text-gray-700 mb-3">
                            Rác cồng kềnh bao gồm bàn ghế, tủ, giường, nệm, sofa và các thiết bị gia dụng hỏng.
                            Cần liên hệ dịch vụ thu gom chuyên dụng.
                        </p>
                        <img src="{{ asset('images/guide/bulky.png') }}" alt="Rác cồng kềnh chi tiết"
                            class="w-full h-64 object-cover rounded-lg">
                    </div>
                </div>
            </div>

            <!-- 6. Rác điện tử -->
            <div x-data="{ open: false }"
                class="p-5 bg-white shadow-md rounded-xl border border-gray-200 hover:shadow-lg transition">
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
                <p class="text-sm text-purple-500 mt-2">*Thu gom tại các điểm tái chế chuyên dụng.</p>

                <button @click="open = true" class="mt-3 px-4 py-2 bg-purple-500 text-white rounded-lg hover:bg-purple-600">
                    Xem chi tiết
                </button>

                <div x-show="open" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
                    <div class="bg-white rounded-xl shadow-lg p-6 w-11/12 md:w-2/3 lg:w-1/2 relative">
                        <button @click="open = false"
                            class="absolute top-3 right-3 text-gray-500 hover:text-gray-800">✕</button>
                        <h2 class="text-2xl font-bold text-purple-600 mb-4">Rác điện tử - Chi tiết</h2>
                        <p class="text-gray-700 mb-3">
                            Rác điện tử gồm điện thoại, máy tính, linh kiện điện tử, tivi, tủ lạnh cũ.
                            Nên thu gom tại các điểm tái chế chuyên dụng.
                        </p>
                        <img src="{{ asset('images/guide/e-waste.png') }}" alt="Rác điện tử chi tiết"
                            class="w-full h-64 object-cover rounded-lg">
                    </div>
                </div>
            </div>

            <!-- 7. Rác y tế -->
            <div x-data="{ open: false }"
                class="p-5 bg-white shadow-md rounded-xl border border-gray-200 hover:shadow-lg transition">
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
                <p class="text-sm text-pink-500 mt-2">*Không vứt chung với rác sinh hoạt.</p>

                <button @click="open = true" class="mt-3 px-4 py-2 bg-pink-500 text-white rounded-lg hover:bg-pink-600">
                    Xem chi tiết
                </button>

                <div x-show="open" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
                    <div class="bg-white rounded-xl shadow-lg p-6 w-11/12 md:w-2/3 lg:w-1/2 relative">
                        <button @click="open = false"
                            class="absolute top-3 right-3 text-gray-500 hover:text-gray-800">✕</button>
                        <h2 class="text-2xl font-bold text-pink-600 mb-4">Rác y tế - Chi tiết</h2>
                        <p class="text-gray-700 mb-3">
                            Rác y tế gồm kim tiêm, bông băng, găng tay, khẩu trang đã sử dụng, thiết bị y tế thải bỏ.
                            Cần thu gom riêng và không vứt chung với rác sinh hoạt.
                        </p>
                        <img src="{{ asset('images/guide/medical.png') }}" alt="Rác y tế chi tiết"
                            class="w-full h-64 object-cover rounded-lg">
                    </div>
                </div>
            </div>

            <!-- 8. Rác xây dựng -->
            <div x-data="{ open: false }"
                class="p-5 bg-white shadow-md rounded-xl border border-gray-200 hover:shadow-lg transition">
                <img src="{{ asset('images/guide/construction.png') }}" alt="Rác xây dựng"
                    class="w-full h-40 object-cover rounded-lg mb-3">
                <h3 class="font-semibold text-lg text-orange-800 mb-2 flex items-center">
                    <i class="fas fa-hammer mr-2"></i>Rác xây dựng
                </h3>
                <ul class="list-disc ml-5 text-gray-700 space-y-1">
                    <li>Gạch, xi măng, bê tông</li>
                    <li>Gỗ vụn, ván ép</li>
                    <li>Vật liệu thừa khác</li>
                </ul>
                <p class="text-sm text-orange-500 mt-2">*Thu gom riêng, không bỏ chung với rác sinh hoạt.</p>

                <button @click="open = true" class="mt-3 px-4 py-2 bg-orange-500 text-white rounded-lg hover:bg-orange-600">
                    Xem chi tiết
                </button>

                <div x-show="open" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
                    <div class="bg-white rounded-xl shadow-lg p-6 w-11/12 md:w-2/3 lg:w-1/2 relative">
                        <button @click="open = false"
                            class="absolute top-3 right-3 text-gray-500 hover:text-gray-800">✕</button>
                        <h2 class="text-2xl font-bold text-orange-800 mb-4">Rác xây dựng - Chi tiết</h2>
                        <p class="text-gray-700 mb-3">
                            Rác xây dựng gồm gạch, xi măng, bê tông, gỗ vụn, ván ép và các vật liệu thừa khác.
                            Cần thu gom riêng, không bỏ chung với rác sinh hoạt.
                        </p>
                        <img src="{{ asset('images/guide/construction.png') }}" alt="Rác xây dựng chi tiết"
                            class="w-full h-64 object-cover rounded-lg">
                    </div>
                </div>
            </div>

            <!-- 9. Rác quần áo / vải -->
            <div x-data="{ open: false }"
                class="p-5 bg-white shadow-md rounded-xl border border-gray-200 hover:shadow-lg transition">
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
                <p class="text-sm text-teal-500 mt-2">*Có thể tái chế hoặc ủ phân nếu là vải sợi tự nhiên.</p>

                <button @click="open = true" class="mt-3 px-4 py-2 bg-teal-500 text-white rounded-lg hover:bg-teal-600">
                    Xem chi tiết
                </button>

                <div x-show="open" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
                    <div class="bg-white rounded-xl shadow-lg p-6 w-11/12 md:w-2/3 lg:w-1/2 relative">
                        <button @click="open = false"
                            class="absolute top-3 right-3 text-gray-500 hover:text-gray-800">✕</button>
                        <h2 class="text-2xl font-bold text-teal-600 mb-4">Rác quần áo / vải - Chi tiết</h2>
                        <p class="text-gray-700 mb-3">
                            Rác quần áo / vải gồm quần áo cũ, rách, chăn, ga, gối và giày dép hỏng.
                            Có thể tái chế hoặc ủ phân.
                        </p>
                        <img src="{{ asset('images/guide/textile.png') }}" alt="Rác quần áo / vải chi tiết"
                            class="w-full h-64 object-cover rounded-lg">
                    </div>
                </div>
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
    <script src="//unpkg.com/alpinejs" defer></script>
@endsection