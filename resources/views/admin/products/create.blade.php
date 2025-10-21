<x-app-layout>
    <!-- Toast Container -->
    <div id="toast-container" class="fixed top-4 right-4 z-[100] space-y-3"></div>

    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-3">
                <svg class="w-6 h-6 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                </svg>
                <h2 class="font-bold text-2xl text-gray-800 dark:text-gray-100 tracking-tight">
                    Add Product
                </h2>
            </div>
            <a href="{{ route('products') }}" class="px-4 py-2 bg-gray-200 hover:bg-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-800 dark:text-gray-200 font-medium rounded-lg transition-all duration-200">
                ← Back
            </a>
        </div>
    </x-slot>

    <div class="py-8 px-4 sm:px-6 lg:px-8 min-h-screen bg-gray-50 dark:bg-gray-900">
        <div class="max-w-3xl mx-auto">
            
            <!-- Form Card -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
                
                <!-- Form Header -->
                <div class="bg-gradient-to-r from-indigo-500 to-purple-600 dark:from-indigo-600 dark:to-purple-700 p-6">
                    <h3 class="text-xl font-bold text-white">Thông Tin Sản Phẩm</h3>
                    <p class="text-white/90 text-sm mt-1">Điền đầy đủ thông tin sản phẩm mới</p>
                </div>

                <!-- Form Body -->
                <form action="{{ route('admin.products.store') }}" method="POST" class="p-6 space-y-6">
                    @csrf

                    <!-- Product Name -->
                    <div>
                        <label for="name" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                            Tên Sản Phẩm <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="name" name="name" value="{{ old('name') }}" required
                               class="w-full px-4 py-3 bg-white dark:bg-gray-700 border-2 border-gray-300 dark:border-gray-600 
                                      rounded-xl text-sm font-medium text-gray-900 dark:text-gray-100 placeholder-gray-400
                                      focus:border-indigo-500 dark:focus:border-indigo-400 focus:ring-4 focus:ring-indigo-500/20 
                                      transition-all duration-200"
                               placeholder="VD: iPhone 15 Pro Max">
                        @error('name')
                            <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Category -->
                    <div>
                        <label for="category" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                            Danh Mục <span class="text-red-500">*</span>
                        </label>
                        <select id="category" name="category" required
                                class="w-full px-4 py-3 bg-white dark:bg-gray-700 border-2 border-gray-300 dark:border-gray-600 
                                       rounded-xl text-sm font-medium text-gray-900 dark:text-gray-100
                                       focus:border-indigo-500 dark:focus:border-indigo-400 focus:ring-4 focus:ring-indigo-500/20 
                                       transition-all duration-200 cursor-pointer">
                            <option value="">-- Chọn danh mục --</option>
                            <option value="laptop" {{ old('category') == 'laptop' ? 'selected' : '' }}>💻 Laptop</option>
                            <option value="phone" {{ old('category') == 'phone' ? 'selected' : '' }}>📱 Điện thoại</option>
                            <option value="tablet" {{ old('category') == 'tablet' ? 'selected' : '' }}>📲 Tablet</option>
                            <option value="accessories" {{ old('category') == 'accessories' ? 'selected' : '' }}>🎧 Phụ kiện</option>
                            <option value="gaming" {{ old('category') == 'gaming' ? 'selected' : '' }}>🎮 Gaming</option>
                        </select>
                        @error('category')
                            <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Price -->
                    <div>
                        <label for="price" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                            Giá (VNĐ) <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <input type="number" id="price" name="price" value="{{ old('price') }}" required min="0" step="0.01"
                                   class="w-full px-4 py-3 bg-white dark:bg-gray-700 border-2 border-gray-300 dark:border-gray-600 
                                          rounded-xl text-sm font-medium text-gray-900 dark:text-gray-100 placeholder-gray-400
                                          focus:border-indigo-500 dark:focus:border-indigo-400 focus:ring-4 focus:ring-indigo-500/20 
                                          transition-all duration-200"
                                   placeholder="VD: 25000000">
                            <span class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-500 dark:text-gray-400 font-medium">₫</span>
                        </div>
                        @error('price')
                            <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Description -->
                    <div>
                        <label for="description" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                            Mô Tả
                        </label>
                        <textarea id="description" name="description" rows="4"
                                  class="w-full px-4 py-3 bg-white dark:bg-gray-700 border-2 border-gray-300 dark:border-gray-600 
                                         rounded-xl text-sm font-medium text-gray-900 dark:text-gray-100 placeholder-gray-400
                                         focus:border-indigo-500 dark:focus:border-indigo-400 focus:ring-4 focus:ring-indigo-500/20 
                                         transition-all duration-200 resize-none"
                                  placeholder="Nhập mô tả chi tiết về sản phẩm...">{{ old('description') }}</textarea>
                        @error('description')
                            <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex flex-col sm:flex-row gap-3 pt-4 border-t border-gray-200 dark:border-gray-700">
                        <button type="submit"
                                class="flex-1 px-6 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 
                                       text-white font-semibold rounded-xl shadow-lg hover:shadow-xl 
                                       transform hover:-translate-y-0.5 transition-all duration-200 
                                       flex items-center justify-center space-x-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            <span>Lưu Sản Phẩm</span>
                        </button>
                        <a href="{{ route('products') }}"
                           class="flex-1 px-6 py-3 bg-gray-200 hover:bg-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600 
                                  text-gray-800 dark:text-gray-200 font-semibold rounded-xl 
                                  transition-all duration-200 text-center flex items-center justify-center space-x-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                            <span>Hủy</span>
                        </a>
                    </div>
                </form>
            </div>

            <!-- Info Card -->
            <div class="mt-6 bg-blue-50 dark:bg-blue-900/20 border-2 border-blue-200 dark:border-blue-800 rounded-xl p-4">
                <div class="flex items-start space-x-3">
                    <svg class="w-6 h-6 text-blue-600 dark:text-blue-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <div class="flex-1">
                        <h4 class="text-sm font-semibold text-blue-900 dark:text-blue-300 mb-1">Lưu ý khi thêm sản phẩm:</h4>
                        <ul class="text-sm text-blue-800 dark:text-blue-400 space-y-1">
                            <li>• Tên sản phẩm phải rõ ràng và đầy đủ</li>
                            <li>• Giá tiền nhập theo đơn vị VNĐ</li>
                            <li>• Mô tả chi tiết giúp khách hàng hiểu rõ sản phẩm hơn</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Show flash messages
        @if(session('success'))
            setTimeout(() => {
                alert('✅ {{ session('success') }}');
            }, 100);
        @endif
        @if(session('error'))
            setTimeout(() => {
                alert('❌ {{ session('error') }}');
            }, 100);
        @endif
    </script>
</x-app-layout>