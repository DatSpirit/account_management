<x-app-layout>
    <!-- Toast Container -->
    <div id="toast-container" class="fixed top-4 right-4 z-[100] space-y-3"></div>

    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-3">
                <svg class="w-6 h-6 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                </svg>
                <h2 class="font-bold text-2xl text-gray-800 dark:text-gray-100 tracking-tight">
                    Edit Product
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
                <div class="bg-gradient-to-r from-blue-500 to-cyan-600 dark:from-blue-600 dark:to-cyan-700 p-6">
                    <h3 class="text-xl font-bold text-white">Cập Nhật Thông Tin</h3>
                    <p class="text-white/90 text-sm mt-1">Chỉnh sửa thông tin sản phẩm: {{ $product->name }}</p>
                </div>

                <!-- Form Body -->
                <form action="{{ route('admin.products.update', $product) }}" method="POST" class="p-6 space-y-6">
                    @csrf
                    @method('PUT')

                    <!-- Product Name -->
                    <div>
                        <label for="name" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                            Tên Sản Phẩm <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="name" name="name" value="{{ old('name', $product->name) }}" required
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
                            <option value="laptop" {{ old('category', $product->category) == 'laptop' ? 'selected' : '' }}>💻 Laptop</option>
                            <option value="phone" {{ old('category', $product->category) == 'phone' ? 'selected' : '' }}>📱 Điện thoại</option>
                            <option value="tablet" {{ old('category', $product->category) == 'tablet' ? 'selected' : '' }}>📲 Tablet</option>
                            <option value="accessories" {{ old('category', $product->category) == 'accessories' ? 'selected' : '' }}>🎧 Phụ kiện</option>
                            <option value="gaming" {{ old('category', $product->category) == 'gaming' ? 'selected' : '' }}>🎮 Gaming</option>
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
                            <input type="number" id="price" name="price" value="{{ old('price', $product->price) }}" required min="0" step="0.01"
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
                                  placeholder="Nhập mô tả chi tiết về sản phẩm...">{{ old('description', $product->description) }}</textarea>
                        @error('description')
                            <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex flex-col sm:flex-row gap-3 pt-4 border-t border-gray-200 dark:border-gray-700">
                        <button type="submit"
                                class="flex-1 px-6 py-3 bg-gray-200 hover:bg-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600 
                                  text-gray-800 dark:text-gray-200 font-semibold rounded-xl 
                                  transition-all duration-200 text-center flex items-center justify-center space-x-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            <span>Cập Nhật</span>
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

            <!-- Product Info -->
            <div class="mt-6 bg-gradient-to-r from-blue-50 to-cyan-50 dark:from-blue-400/20 dark:to-cyan-600/20 border-2 border-blue-100 dark:border-blue-400 rounded-xl p-6">
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div>
                        <p class="text-xs font-semibold text-blue-900 dark:text-blue-600 uppercase tracking-wide mb-1">ID Sản Phẩm</p>
                        <p class="text-sm font-bold text-blue-800 dark:text-blue-400">#{{ $product->id }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-semibold text-blue-900 dark:text-blue-600 uppercase tracking-wide mb-1">Ngày Tạo</p>
                        <p class="text-sm font-bold text-blue-800 dark:text-blue-400">{{ $product->created_at->format('d/m/Y') }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-semibold text-blue-900 dark:text-blue-600 uppercase tracking-wide mb-1">Cập Nhật Lần Cuối</p>
                        <p class="text-sm font-bold text-blue-800 dark:text-blue-400">{{ $product->updated_at->diffForHumans() }}</p>
                    </div>
                </div>
            </div>

            <!-- Delete Section -->
            <div class="mt-6 bg-red-50 dark:bg-red-900/20 border-2 border-red-200 dark:border-red-800 rounded-xl p-6">
                <div class="flex items-start space-x-4">
                    <div class="flex-shrink-0">
                        <svg class="w-8 h-8 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                    </div>
                    <div class="flex-1">
                        <h4 class="text-sm font-bold text-red-900 dark:text-red-300 mb-2">Xóa Sản Phẩm</h4>
                        <p class="text-sm text-red-800 dark:text-red-400 mb-4">
                            Hành động này không thể hoàn tác. Sản phẩm sẽ bị xóa vĩnh viễn khỏi hệ thống.
                        </p>
                        <form action="{{ route('admin.products.destroy', $product) }}" method="POST" 
                              onsubmit="return confirm('⚠️ Bạn có chắc chắn muốn xóa sản phẩm này?\n\nHành động này không thể hoàn tác!');">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                    class="px-6 py-2.5 bg-red-600 hover:bg-red-700 dark:bg-red-700 dark:hover:bg-red-800 
                                           text-white font-semibold rounded-lg shadow-lg hover:shadow-xl 
                                           transform hover:-translate-y-0.5 transition-all duration-200 
                                           flex items-center space-x-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                                <span>Xóa Sản Phẩm</span>
                            </button>
                        </form>
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