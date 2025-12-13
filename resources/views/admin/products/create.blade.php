<x-app-layout>
    <!-- Toast Container -->
    <div id="toast-container" class="fixed top-4 right-4 z-[100] space-y-3"></div>

    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-3">
                <svg class="w-6 h-6 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                </svg>
                <h2 class="font-bold text-2xl text-gray-800 dark:text-gray-100 tracking-tight">
                    Add Product
                </h2>
            </div>
            <a href="{{ route('products') }}"
                class="px-4 py-2 bg-gray-200 hover:bg-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-800 dark:text-gray-200 font-medium rounded-lg transition-all duration-200">
                ← Back
            </a>
        </div>
    </x-slot>

    <div class="py-8 px-4 sm:px-6 lg:px-8 min-h-screen bg-gray-50 dark:bg-gray-900">
        <div class="max-w-3xl mx-auto">

            <div
                class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
                <div class="bg-gradient-to-r from-indigo-500 to-purple-600 dark:from-indigo-600 dark:to-purple-700 p-6">
                    <h3 class="text-xl font-bold text-white">Thông Tin Sản Phẩm</h3>
                    <p class="text-white/90 text-sm mt-1">Điền đầy đủ thông tin sản phẩm mới</p>
                </div>

                <form action="{{ route('admin.products.store') }}" method="POST" class="p-6 space-y-6">
                    @csrf

                    <!-- Active Checkbox -->
                    <div
                        class="flex items-center space-x-3 bg-gray-50 dark:bg-gray-700/50 p-3 rounded-xl border border-gray-200 dark:border-gray-600">
                        <div class="flex items-center h-5">
                            <input id="is_active" name="is_active" type="checkbox" value="1"
                                {{ old('is_active', true) ? 'checked' : '' }}
                                class="w-5 h-5 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600">
                        </div>
                        <label for="is_active"
                            class="font-medium text-gray-700 dark:text-gray-300 select-none cursor-pointer">
                            Kích hoạt sản phẩm (Hiển thị ngay trên cửa hàng)
                        </label>
                    </div>

                    <!-- Name -->
                    <div>
                        <label for="name"
                            class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Tên Sản Phẩm <span
                                class="text-red-500">*</span></label>
                        <input type="text" id="name" name="name" value="{{ old('name') }}" required
                            class="w-full px-4 py-3 bg-white dark:bg-gray-700 border-2 border-gray-300 dark:border-gray-600 rounded-xl text-sm font-medium text-gray-900 dark:text-gray-100 focus:border-indigo-500 transition-all">
                    </div>

                    <!-- Category -->
                    <div>
                        <label for="category"
                            class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Danh Mục <span
                                class="text-red-500">*</span></label>
                        <select id="category" name="category" required
                            class="w-full px-4 py-3 bg-white dark:bg-gray-700 border-2 border-gray-300 dark:border-gray-600 rounded-xl text-sm font-medium text-gray-900 dark:text-gray-100 focus:border-indigo-500 cursor-pointer">
                            <option value="Service" {{ old('category') == 'Service' ? 'selected' : '' }}>Service (Gói
                                dịch vụ)</option>
                            <option value="Top-up" {{ old('category') == 'Top-up' ? 'selected' : '' }}>Top-up (Nạp tiền)
                            </option>
                        </select>
                    </div>

                    <!-- Price (VND) -->
                    <div>
                        <label for="price"
                            class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Giá Tiền Mặt (VNĐ)
                            <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <input type="number" id="price" name="price" value="{{ old('price') }}" required
                                min="2000" step="1000"
                                class="w-full px-4 py-3 bg-white dark:bg-gray-700 border-2 border-gray-300 dark:border-gray-600 rounded-xl text-sm font-medium text-gray-900 dark:text-gray-100 focus:border-indigo-500 pl-10"
                                placeholder="VD: 50000">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 font-bold">₫</span>
                        </div>
                    </div>

                    <!-- TYPE CONFIGURATION -->
                    <div
                        class="grid grid-cols-1 md:grid-cols-2 gap-6 bg-indigo-50 dark:bg-indigo-900/20 p-4 rounded-xl border border-indigo-100 dark:border-indigo-800">
                        <!-- Product Type -->
                        <div class="md:col-span-2">
                            <label for="product_type"
                                class="block text-sm font-bold text-indigo-800 dark:text-indigo-300 mb-2">Loại Sản Phẩm
                                <span class="text-red-500">*</span></label>
                            <select id="product_type" name="product_type" onchange="toggleFields()"
                                class="w-full px-4 py-3 bg-white dark:bg-gray-700 border-2 border-indigo-200 dark:border-indigo-700 rounded-xl text-sm font-medium text-gray-900 dark:text-gray-100 focus:ring-indigo-500 cursor-pointer">
                                <option value="package" {{ old('product_type') == 'package' ? 'selected' : '' }}>📦 Gói
                                    Bản Quyền (Bán Key)</option>
                                <option value="coinkey" {{ old('product_type') == 'coinkey' ? 'selected' : '' }}>💰 Gói
                                    Nạp Tiền (Top-up)</option>
                            </select>
                            <p class="text-xs text-indigo-600 dark:text-indigo-400 mt-2" id="type_hint"></p>
                        </div>

                        <!-- Coinkey Amount -->
                        <div>
                            <label id="coinkey_label" for="coinkey_amount"
                                class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Giá
                                Coin</label>
                            <div class="relative">
                                <input type="number" id="coinkey_amount" name="coinkey_amount"
                                    value="{{ old('coinkey_amount', 0) }}" required min="0"
                                    class="w-full px-4 py-3 bg-white dark:bg-gray-700 border-2 border-gray-300 dark:border-gray-600 rounded-xl text-sm font-medium text-gray-900 dark:text-gray-100 focus:border-indigo-500 pl-10">
                                <span class="absolute left-3 top-1/2 -translate-y-1/2">🪙</span>
                            </div>
                            <p class="text-xs text-gray-500 mt-1" id="coinkey_hint"></p>
                        </div>

                        <!-- Duration -->
                        <div id="duration_field">
                            <label for="duration_minutes"
                                class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Thời hạn
                                (Phút)</label>
                            <input type="number" id="duration_minutes" name="duration_minutes"
                                value="{{ old('duration_minutes', 1440) }}"
                                class="w-full px-4 py-3 bg-white dark:bg-gray-700 border-2 border-gray-300 dark:border-gray-600 rounded-xl text-sm font-medium text-gray-900 dark:text-gray-100 focus:border-indigo-500">
                            <p class="text-xs text-gray-500 mt-1">1440 phút = 1 ngày</p>
                        </div>
                    </div>

                    <!-- Description -->
                    <div>
                        <label for="description"
                            class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Mô Tả</label>
                        <textarea id="description" name="description" rows="3"
                            class="w-full px-4 py-3 bg-white dark:bg-gray-700 border-2 border-gray-300 dark:border-gray-600 rounded-xl text-sm font-medium text-gray-900 dark:text-gray-100 focus:border-indigo-500 resize-none">{{ old('description') }}</textarea>
                    </div>

                    <div class="flex flex-col sm:flex-row gap-3 pt-4 border-t border-gray-200 dark:border-gray-700">
                        <button type="submit"
                            class="flex-1 px-6 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 text-white font-bold rounded-xl shadow-lg hover:shadow-xl transition-all">Lưu
                            Sản Phẩm</button>
                        <a href="{{ route('products') }}"
                            class="flex-1 px-6 py-3 bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-white font-bold rounded-xl text-center transition-all">Hủy</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function toggleFields() {
            const type = document.getElementById('product_type').value;
            const durationField = document.getElementById('duration_field');
            const coinkeyLabel = document.getElementById('coinkey_label');
            const coinkeyHint = document.getElementById('coinkey_hint');
            const typeHint = document.getElementById('type_hint');

            if (type === 'coinkey') {
                durationField.style.display = 'none';
                coinkeyLabel.innerText = "Số lượng Coinkey NHẬN ĐƯỢC";
                coinkeyHint.innerText = "Khách trả tiền mặt -> Nhận Coin vào ví.";
                typeHint.innerText = "Chế độ: Gói Nạp Tiền (Top-up).";
            } else {
                durationField.style.display = 'block';
                coinkeyLabel.innerText = "Giá bán bằng Coinkey";
                coinkeyHint.innerText = "Khách dùng Coin trong ví để mua.";
                typeHint.innerText = "Chế độ: Gói Bản Quyền (Service).";
            }
        }
        document.addEventListener('DOMContentLoaded', toggleFields);
    </script>
    <script>
        // Show flash messages
        @if (session('success'))
            setTimeout(() => {
                alert('✅ {{ session('success') }}');
            }, 100);
        @endif
        @if (session('error'))
            setTimeout(() => {
                alert('❌ {{ session('error') }}');
            }, 100);
        @endif
    </script>
</x-app-layout>
