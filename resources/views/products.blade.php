<x-app-layout>
    <!-- Toast Container -->
    <div id="toast-container" class="fixed top-4 right-4 z-[100] space-y-3"></div>

    <x-slot name="header">
        <div class="flex flex-col sm:flex-row items-center justify-center sm:justify-between space-y-2 sm:space-y-0 sm:space-x-3">
            <div class="flex items-center space-x-2">
                <svg class="w-6 h-6 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                </svg>
                <h2 class="font-bold text-2xl sm:text-3xl text-gray-800 dark:text-gray-100 tracking-tight">Product</h2>
            </div>
            <div class="text-center sm:text-left">
                <div class="text-2xl sm:text-3xl font-bold text-gray-800 dark:text-gray-100" id="total-products">{{ count($products) }}</div>
                <div class="text-xs text-gray-600 dark:text-gray-400 uppercase tracking-wide">Sản Phẩm</div>
            </div>
        </div>
    </x-slot>

    <div class="py-6 px-2 sm:px-6 lg:px-8 min-h-screen bg-gray-50 dark:bg-gray-900 transition-colors duration-300">
        <div class="max-w-7xl mx-auto space-y-6">

            <!-- Header Section -->
            <div class="bg-gradient-to-r from-indigo-500 via-purple-500 to-pink-500 dark:from-indigo-700 dark:via-purple-700 dark:to-pink-700 rounded-2xl shadow-xl p-4 sm:p-6 lg:p-8 transition-colors duration-300">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div class="space-y-2">
                        <h3 class="text-xl sm:text-2xl font-bold text-white">Cửa Hàng Sản Phẩm</h3>
                        <p class="text-white text-opacity-90 text-sm sm:text-base">Khám phá các sản phẩm chất lượng cao</p>
                    </div>
                    <div class="flex items-center space-x-4">
                        @if ($isAdmin)
                            <a href="{{ route('admin.products.create') }}"
                                class="px-3 py-2 sm:px-4 sm:py-2 bg-white hover:bg-gray-100 text-indigo-600 font-semibold rounded-lg shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-200 flex items-center space-x-2 text-sm sm:text-base">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                </svg>
                                <span>Add Product</span>
                            </a>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Search & Filter Section -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700 p-4 sm:p-6">
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-4">

                    <!-- Search Input -->
                    <div class="lg:col-span-6">
                        <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-2 uppercase tracking-wide">
                            Tìm Kiếm Sản Phẩm
                        </label>
                        <div class="relative">
                            <input type="text" id="search-input" placeholder="Nhập tên sản phẩm..."
                                class="w-full pl-4 pr-12 py-3 bg-white dark:bg-gray-700 border-2 border-gray-300 dark:border-gray-600 rounded-xl text-sm font-medium text-gray-900 dark:text-gray-100 placeholder-gray-400 dark:placeholder-gray-500 focus:border-indigo-500 dark:focus:border-indigo-400 focus:ring-4 focus:ring-indigo-500/20 transition-all duration-200">

                            <button type="button" id="search-btn"
                                class="absolute right-2 top-1/2 -translate-y-1/2 p-2 bg-indigo-600 hover:bg-indigo-700 dark:bg-indigo-500 dark:hover:bg-indigo-600 text-white rounded-lg transition-all duration-200">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    <!-- Category Filter -->
                    <div class="lg:col-span-4">
                        <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-2 uppercase tracking-wide">
                            Danh Mục
                        </label>
                        <select id="category-filter"
                            class="w-full px-4 py-3 bg-white dark:bg-gray-700 border-2 border-gray-300 dark:border-gray-600 rounded-xl text-sm font-medium text-gray-900 dark:text-gray-100 focus:border-indigo-500 dark:focus:border-indigo-400 focus:ring-4 focus:ring-indigo-500/20 transition-all duration-200 cursor-pointer">
                            <option value="">Tất cả danh mục</option>
                            <option value="laptop">💻 Laptop</option>
                            <option value="phone">📱 Điện thoại</option>
                            <option value="tablet">📲 Tablet</option>
                            <option value="accessories">🎧 Phụ kiện</option>
                            <option value="gaming">🎮 Gaming</option>
                        </select>
                    </div>

                    <!-- Price Sort -->
                    <div class="lg:col-span-2">
                        <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-2 uppercase tracking-wide">
                            Sắp Xếp
                        </label>
                        <select id="price-sort"
                            class="w-full px-4 py-3 bg-white dark:bg-gray-700 border-2 border-gray-300 dark:border-gray-600 rounded-xl text-sm font-medium text-gray-900 dark:text-gray-100 focus:border-indigo-500 dark:focus:border-indigo-400 focus:ring-4 focus:ring-indigo-500/20 transition-all duration-200 cursor-pointer">
                            <option value="">Mặc định</option>
                            <option value="price-asc">Giá tăng dần</option>
                            <option value="price-desc">Giá giảm dần</option>
                            <option value="name-asc">Tên A-Z</option>
                            <option value="name-desc">Tên Z-A</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Products Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6" id="products-grid">
                @foreach ($products as $product)
                    <div class="product-card cursor-pointer bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700 overflow-hidden transition-all duration-300 hover:shadow-2xl hover:-translate-y-2"
                        data-id="{{ $product->id }}" data-name="{{ strtolower($product['name']) }}"
                        data-price="{{ $product['price'] }}" data-category="{{ $product['category'] ?? '' }}">

                        <!-- Product Image -->
                        <div class="relative bg-gradient-to-br from-indigo-100 to-purple-100 dark:from-indigo-900 dark:to-purple-900 h-48 flex items-center justify-center p-4 sm:p-6">
                            @if (isset($product['image']))
                                <img src="{{ $product['image'] }}" alt="{{ $product['name'] }}"
                                    class="w-full h-full object-contain">
                            @else
                                <svg class="w-24 h-24 text-indigo-400 dark:text-indigo-500" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                </svg>
                            @endif

                            <!-- Category Badge -->
                            @if (isset($product['category']))
                                <span class="absolute top-3 right-3 px-2 py-1 bg-white bg-opacity-90 dark:bg-gray-800 dark:bg-opacity-90 backdrop-blur-sm text-xs font-semibold text-gray-700 dark:text-gray-300 rounded-full border border-gray-200 dark:border-gray-600">
                                    {{ $product['category'] }}
                                </span>
                            @endif
                        </div>

                        <!-- Product Info -->
                        <div class="p-4 sm:p-5 space-y-3">
                            <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100 line-clamp-2 min-h-[3.5rem]">
                                {{ $product['name'] }}
                            </h3>

                            @if (isset($product['description']))
                                <p class="text-sm text-gray-600 dark:text-gray-400 line-clamp-2">
                                    {{ $product['description'] }}
                                </p>
                            @endif

                            <div class="flex items-center justify-between pt-2">
                                <div>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">Giá</p>
                                    <p class="text-2xl font-bold text-indigo-600 dark:text-indigo-400">
                                        {{ number_format($product['price']) }}₫
                                    </p>
                                </div>
                            </div>

                            <!-- Action Buttons -->
                            <div class="flex gap-2 pt-3 flex-wrap">
                                <a href="{{ route('pay', $product['id']) }}"
                                    class="flex-1 px-4 py-2.5 bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 dark:from-indigo-500 dark:to-purple-600 dark:hover:from-indigo-600 dark:hover:to-purple-700 text-white font-semibold rounded-lg shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-200 text-center text-sm">
                                    <span class="flex items-center justify-center space-x-2">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                                        </svg>
                                        <span>Thanh toán</span>
                                    </span>
                                </a>

                                <button type="button"
                                    class="view-detail-btn p-2.5 bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 rounded-lg transition-all duration-200"
                                    title="Show Product"
                                    data-product="{{ json_encode($product) }}">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                </button>

                                @if ($isAdmin)
                                    <a href="{{ route('admin.products.edit', $product['id']) }}"
                                        class="view-detail-btn p-2.5 bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 rounded-lg transition-all duration-200"
                                        title="Edit Product">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15.232 5.232l3.536 3.536M9 11l6-6 3 3-6 6H9v-3zM4 20h16" />
                                        </svg>
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- No Results Message -->
            <div id="no-results"
                class="hidden bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700 p-12 text-center">
                <div class="flex flex-col items-center space-y-4">
                    <div class="w-24 h-24 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center">
                        <svg class="w-12 h-12 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-2">
                            Không tìm thấy sản phẩm
                        </h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400">
                            Thử thay đổi bộ lọc hoặc từ khóa tìm kiếm
                        </p>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <!-- Product Detail Modal -->
    <div id="productModal" class="hidden fixed inset-0 z-[90] flex items-center justify-center p-2 sm:p-4">
        <div class="absolute inset-0 bg-black/70 backdrop-blur-sm transition-opacity" onclick="closeModal()"></div>
        <div class="relative bg-white dark:bg-gray-800 rounded-2xl shadow-2xl w-full sm:max-w-2xl transform transition-all duration-300 scale-95 opacity-0 border border-gray-200 dark:border-gray-700 p-4 sm:p-6" id="modalContent">
            <!-- Modal Header -->
            <div class="flex items-center justify-between p-4 sm:p-6 border-b border-gray-200 dark:border-gray-700 bg-gradient-to-r from-indigo-500 to-purple-600 dark:from-indigo-600 dark:to-purple-700 rounded-t-2xl">
                <h3 class="text-xl sm:text-2xl font-bold text-white">Chi Tiết Sản Phẩm</h3>
                <button onclick="closeModal()" class="p-2 rounded-lg hover:bg-white/20 transition-colors">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <!-- Modal Body -->
            <div class="p-4 sm:p-6 space-y-4 max-h-[60vh] overflow-y-auto bg-gray-50 dark:bg-gray-900" id="modal-product-content"></div>
            <!-- Modal Footer -->
            <div class="flex items-center justify-end space-x-3 p-4 sm:p-6 border-t border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 rounded-b-2xl">
                <button onclick="closeModal()" class="px-6 py-2.5 bg-gray-200 hover:bg-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-800 dark:text-gray-200 font-semibold rounded-lg transition-all duration-200">
                    Đóng
                </button>
                <a id="modal-pay-link" href="#" class="px-6 py-2.5 bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white font-semibold rounded-lg shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-200">
                    Thanh toán ngay
                </a>
            </div>
        </div>
    </div>

    <script>
        // Toast System
        function showToast(message, type = 'success') {
            const container = document.getElementById('toast-container');
            const toast = document.createElement('div');

            const icons = {
                success: '<svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>',
                error: '<svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg>'
            };

            const colors = {
                success: 'bg-green-50 dark:bg-green-900/50 text-green-800 dark:text-green-200 border-green-200 dark:border-green-700',
                error: 'bg-red-50 dark:bg-red-900/50 text-red-800 dark:text-red-200 border-red-200 dark:border-red-700'
            };

            toast.className =
                `flex items-center space-x-3 px-6 py-4 rounded-xl shadow-2xl border-2 ${colors[type]} transform transition-all duration-300 translate-x-full opacity-0`;
            toast.innerHTML = `
                <div class="flex-shrink-0">${icons[type]}</div>
                <p class="font-medium text-sm">${message}</p>
                <button onclick="this.parentElement.remove()" class="ml-4 hover:opacity-70 transition-opacity">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                    </svg>
                </button>
            `;

            container.appendChild(toast);
            setTimeout(() => toast.classList.remove('translate-x-full', 'opacity-0'), 100);
            setTimeout(() => {
                toast.classList.add('translate-x-full', 'opacity-0');
                setTimeout(() => toast.remove(), 300);
            }, 5000);
        }

        // Modal Functions
        function closeModal() {
            const modal = document.getElementById('productModal');
            const content = document.getElementById('modalContent');
            content.classList.add('scale-95', 'opacity-0');
            setTimeout(() => modal.classList.add('hidden'), 300);
        }

        function openModal() {
            const modal = document.getElementById('productModal');
            const content = document.getElementById('modalContent');
            modal.classList.remove('hidden');
            setTimeout(() => {
                content.classList.remove('scale-95', 'opacity-0');
                content.classList.add('scale-100', 'opacity-100');
            }, 10);
        }

        // Search and Filter
        document.addEventListener('DOMContentLoaded', () => {
            const searchInput = document.getElementById('search-input');
            const searchBtn = document.getElementById('search-btn');
            const categoryFilter = document.getElementById('category-filter');
            const priceSort = document.getElementById('price-sort');
            const productsGrid = document.getElementById('products-grid');
            const noResults = document.getElementById('no-results');
            const totalProducts = document.getElementById('total-products');

            function filterProducts() {
                const searchTerm = searchInput.value.toLowerCase();
                const category = categoryFilter.value.toLowerCase();
                const sort = priceSort.value;

                let products = Array.from(document.querySelectorAll('.product-card'));
                let visibleCount = 0;

                // Filter
                products.forEach(product => {
                    const name = product.dataset.name;
                    const productCategory = product.dataset.category.toLowerCase();
                    const matchSearch = name.includes(searchTerm);
                    const matchCategory = !category || productCategory === category;

                    if (matchSearch && matchCategory) {
                        product.classList.remove('hidden');
                        visibleCount++;
                    } else {
                        product.classList.add('hidden');
                    }
                });

                // Sort
                if (sort) {
                    products.sort((a, b) => {
                        if (sort === 'price-asc') return parseFloat(a.dataset.price) - parseFloat(b.dataset
                            .price);
                        if (sort === 'price-desc') return parseFloat(b.dataset.price) - parseFloat(a.dataset
                            .price);
                        if (sort === 'name-asc') return a.dataset.name.localeCompare(b.dataset.name);
                        if (sort === 'name-desc') return b.dataset.name.localeCompare(a.dataset.name);
                    });
                    products.forEach(product => productsGrid.appendChild(product));
                }

                // Show/hide no results
                if (visibleCount === 0) {
                    noResults.classList.remove('hidden');
                } else {
                    noResults.classList.add('hidden');
                }

                totalProducts.textContent = visibleCount;
            }

            searchInput.addEventListener('input', filterProducts);
            searchBtn.addEventListener('click', filterProducts);
            categoryFilter.addEventListener('change', filterProducts);
            priceSort.addEventListener('change', filterProducts);

            // View Details
            document.querySelectorAll('.view-detail-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    const product = JSON.parse(this.dataset.product);
                    const content = document.getElementById('modal-product-content');
                    const payLink = document.getElementById('modal-pay-link');

                    content.innerHTML = `
                        <div class="space-y-4">
                            <div class="p-4 rounded-xl bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700">
                                <div class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase mb-2">Tên sản phẩm</div>
                                <div class="text-lg font-bold text-gray-900 dark:text-gray-100">${product.name}</div>
                            </div>
                            <div class="p-4 rounded-xl bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700">
                                <div class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase mb-2">Giá</div>
                                <div class="text-2xl font-bold text-indigo-600 dark:text-indigo-400">${new Intl.NumberFormat('vi-VN').format(product.price)}₫</div>
                            </div>
                            ${product.description ? `
                                <div class="p-4 rounded-xl bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700">
                                    <div class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase mb-2">Mô tả</div>
                                    <div class="text-sm text-gray-700 dark:text-gray-300">${product.description}</div>
                                </div>
                                ` : ''}
                            ${product.category ? `
                                <div class="p-4 rounded-xl bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700">
                                    <div class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase mb-2">Danh mục</div>
                                    <div class="text-sm font-semibold text-gray-900 dark:text-gray-100">${product.category}</div>
                                </div>
                                ` : ''}
                        </div>
                    `;

                    payLink.href = `/pay/${product.id}`;
                    openModal();
                });
            });

            // Close modal on ESC
            document.addEventListener('keydown', (e) => {
                if (e.key === 'Escape') closeModal();
            });
        });
    </script>
</x-app-layout>
