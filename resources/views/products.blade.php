<x-app-layout>
    <!-- Toast Container -->
    <div id="toast-container" class="fixed top-4 right-4 z-[100] space-y-3"></div>

    <x-slot name="header">
        <div
            class="flex flex-col sm:flex-row items-center justify-center sm:justify-between space-y-2 sm:space-y-0 sm:space-x-3">
            <div class="flex items-center space-x-2">
                <svg class="w-6 h-6 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                </svg>
                <h2 class="font-bold text-2xl sm:text-3xl text-gray-800 dark:text-gray-100 tracking-tight">Product</h2>
            </div>
            <div class="text-center sm:text-left">
                <div class="text-2xl sm:text-3xl font-bold text-gray-800 dark:text-gray-100" id="total-products">
                    {{ count($products) }}</div>
                <div class="text-xs text-gray-600 dark:text-gray-400 uppercase tracking-wide">Sản Phẩm</div>
            </div>
        </div>
    </x-slot>

    <div class="py-6 px-2 sm:px-6 lg:px-8 min-h-screen bg-gray-50 dark:bg-gray-900 transition-colors duration-300">
        <div class="max-w-7xl mx-auto space-y-6">

            <!-- Header Section -->
            <div
                class="bg-gradient-to-r from-indigo-500 via-purple-500 to-pink-500 dark:from-indigo-700 dark:via-purple-700 dark:to-pink-700 rounded-2xl shadow-xl p-4 sm:p-6 lg:p-8 transition-colors duration-300">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div class="space-y-2">
                        <h3 class="text-xl sm:text-2xl font-bold text-white">Cửa Hàng Sản Phẩm</h3>
                        <p class="text-white text-opacity-90 text-sm sm:text-base">Khám phá các sản phẩm chất lượng cao
                        </p>
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
            <div
                class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700 p-4 sm:p-6">
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-4">
                    <!-- Search Input -->
                    <div class="lg:col-span-6">
                        <label
                            class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-2 uppercase tracking-wide">Tìm
                            Kiếm Sản Phẩm</label>
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
                        <label
                            class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-2 uppercase tracking-wide">Danh
                            Mục</label>
                        <select id="category-filter"
                            class="w-full px-4 py-3 bg-white dark:bg-gray-700 border-2 border-gray-300 dark:border-gray-600 rounded-xl text-sm font-medium text-gray-900 dark:text-gray-100 focus:border-indigo-500 dark:focus:border-indigo-400 focus:ring-4 focus:ring-indigo-500/20 transition-all duration-200 cursor-pointer">
                            <option value="">Tất cả danh mục</option>
                            <option value="Service">Service</option>
                            <option value="Top-up">Top-up</option>
                        </select>
                    </div>
                    <!-- Price Sort -->
                    <div class="lg:col-span-2">
                        <label
                            class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-2 uppercase tracking-wide">Sắp
                            Xếp</label>
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
                        <div
                            class="relative bg-gradient-to-br from-indigo-100 to-purple-100 dark:from-indigo-900 dark:to-purple-900 h-48 flex items-center justify-center p-4 sm:p-6">
                            @if (isset($product['image']))
                                <img src="{{ $product['image'] }}" alt="{{ $product['name'] }}"
                                    class="w-full h-full object-contain mix-blend-multiply dark:mix-blend-normal">
                            @else
                                <svg class="w-24 h-24 text-indigo-400 dark:text-indigo-500" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                </svg>
                            @endif
                            <!-- Category Badge -->
                            @if (isset($product['category']))
                                <span
                                    class="absolute top-3 right-3 px-2 py-1 bg-white/90 dark:bg-gray-800/90 backdrop-blur-sm text-xs font-semibold text-gray-700 dark:text-gray-300 rounded-full border border-gray-200 dark:border-gray-600 shadow-sm">
                                    {{ $product['category'] }}
                                </span>
                            @endif
                        </div>

                        <!-- Product Info -->
                        <div class="p-4 sm:p-5 space-y-3">
                            <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100 line-clamp-2 min-h-[3.5rem]">
                                {{ $product['name'] }}</h3>
                            @if (isset($product['description']))
                                <p class="text-sm text-gray-600 dark:text-gray-400 line-clamp-2">
                                    {{ $product['description'] }}</p>
                            @endif
                            <div class="flex flex-col pt-2">
                                <div>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">Giá tiền mặt</p>
                                    <p class="text-xl font-bold text-indigo-600 dark:text-indigo-400">
                                        {{ number_format($product['price']) }}₫</p>
                                </div>
                                @if (isset($product['product_type']) && $product['product_type'] == 'package' && $product['coinkey_amount'] > 0)
                                    <div class="mt-1">
                                        <span
                                            class="inline-flex items-center gap-1 bg-yellow-100 dark:bg-yellow-900/30 px-2 py-0.5 rounded-full border border-yellow-200 dark:border-yellow-700">
                                            <span class="text-[10px] text-yellow-700 dark:text-yellow-400">hoặc</span>
                                            <span
                                                class="text-sm font-bold text-yellow-600 dark:text-yellow-400">{{ number_format($product['coinkey_amount']) }}
                                                Coin</span>
                                        </span>
                                    </div>
                                @endif
                            </div>

                            <!-- Action Buttons -->
                            <div class="flex gap-2 pt-3 flex-wrap">
                                <button onclick="openPurchaseModal({{ json_encode($product) }})"
                                    class="flex-1 px-4 py-2.5 bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 dark:from-indigo-500 dark:to-purple-600 text-white font-semibold rounded-lg shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-200 text-center text-sm">
                                    <span class="flex items-center justify-center space-x-2">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                                        </svg>
                                        <span>{{ isset($product['product_type']) && $product['product_type'] == 'coinkey' ? 'Nạp Ngay' : 'Mua Ngay' }}</span>
                                    </span>
                                </button>

                                <!-- Nút Xem Chi Tiết -->
                                <button type="button"
                                    class="view-detail-btn p-2.5 bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 rounded-lg transition-all duration-200"
                                    title="Chi tiết sản phẩm" data-product="{{ json_encode($product) }}">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                </button>

                                <!-- Nút Edit -->
                                @if ($isAdmin)
                                    <a href="{{ route('admin.products.edit', $product['id']) }}"
                                        class="p-2.5 bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 rounded-lg transition-all duration-200"
                                        title="Chỉnh sửa">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
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
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-2">Không tìm thấy sản phẩm
                        </h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Thử thay đổi bộ lọc hoặc từ khóa tìm kiếm
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!--  PRODUCT DETAIL MODAL -->
    <div id="productModal" class="hidden fixed inset-0 z-[90] flex items-center justify-center p-4">
        <div class="absolute inset-0 bg-black/70 backdrop-blur-sm transition-opacity" onclick="closeModal()"></div>
        <div class="relative bg-white dark:bg-gray-800 rounded-2xl shadow-2xl w-full sm:max-w-3xl transform transition-all duration-300 scale-95 opacity-0 border border-gray-200 dark:border-gray-700 flex flex-col max-h-[90vh]"
            id="modalContent">

            <!-- Modal Header -->
            <div
                class="flex items-center justify-between p-6 border-b border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 rounded-t-2xl">
                <h3 class="text-2xl font-bold text-gray-900 dark:text-white">Chi Tiết Sản Phẩm</h3>
                <button onclick="closeModal()"
                    class="p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors text-gray-500">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <!-- Modal Body -->
            <div class="flex-1 overflow-y-auto p-6" id="modal-product-content">
                <!-- Nội dung sẽ được JS inject vào đây -->
            </div>

            <!-- Modal Footer -->
            <div
                class="flex items-center justify-end space-x-3 p-6 border-t border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800/50 rounded-b-2xl">
                <button onclick="closeModal()"
                    class="px-6 py-2.5 bg-white border border-gray-300 hover:bg-gray-50 dark:bg-gray-700 dark:border-gray-600 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-200 font-semibold rounded-xl transition-all duration-200 shadow-sm">
                    Đóng
                </button>
                <a id="modal-pay-link" href="#"
                    class="px-6 py-2.5 bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-200 flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                    </svg>
                    Mua Ngay
                </a>
            </div>
        </div>
    </div>

    <!--  PURCHASE MODAL (Standard & Custom) -->
    <div id="purchaseModal"
        class="hidden fixed inset-0 z-[100] flex items-center justify-center bg-black/60 backdrop-blur-sm p-4 transition-all duration-300">
        <div class="absolute inset-0" onclick="closePurchaseModal()"></div>
        <div
            class="relative bg-white dark:bg-gray-800 w-full max-w-2xl rounded-2xl shadow-2xl transform scale-100 overflow-hidden border border-gray-200 dark:border-gray-700 max-h-[90vh] overflow-y-auto">
            <!-- Header -->
            <div class="bg-gradient-to-r from-indigo-600 to-purple-600 p-6 sticky top-0 z-10 shadow-md">
                <h3 class="text-2xl font-bold text-white mb-1" id="pmTitle">Xác nhận mua hàng</h3>
                <p class="text-indigo-100 text-sm" id="pmDesc">Chọn phương thức thanh toán</p>
            </div>

            <div class="p-6">
                <!-- Tab Switcher -->
                <div id="pmTabSwitcher" class="hidden mb-6 bg-gray-100 dark:bg-gray-700 p-1 rounded-xl">
                    <div class="grid grid-cols-2 gap-2">
                        <button type="button" onclick="switchPurchaseTab('standard')" id="tab-standard"
                            class="py-2.5 px-4 rounded-lg font-bold text-sm transition-all bg-white dark:bg-gray-800 text-indigo-600 shadow-sm ring-1 ring-black/5">📦
                            Mua Gói Có Sẵn</button>
                        <button type="button" onclick="switchPurchaseTab('custom')" id="tab-custom"
                            class="py-2.5 px-4 rounded-lg font-bold text-sm transition-all text-gray-600 dark:text-gray-400 hover:text-gray-900">✨
                            Tạo Key Tùy Chỉnh</button>
                    </div>
                </div>

                <!-- FORM 1: Standard Purchase -->
                <form action="{{ route('order.process') }}" method="POST" id="purchaseForm" class="space-y-6">
                    @csrf
                    <input type="hidden" name="product_id" id="pmProductId">
                    <input type="hidden" name="payment_method" id="pmMethod">

                    <div class="space-y-4">
                        <div
                            class="bg-gradient-to-br from-indigo-50 to-purple-50 dark:from-gray-700/50 dark:to-gray-600/50 p-4 rounded-xl border border-indigo-100 dark:border-gray-600">
                            <p class="font-bold text-gray-900 dark:text-white text-lg" id="pmProductName"></p>
                            <div id="pmDurationInfo"
                                class="hidden text-sm text-gray-600 dark:text-gray-300 mt-1 flex items-center gap-2">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span id="pmDuration"></span>
                            </div>
                        </div>

                        <!-- Wallet Option -->
                        <div id="pmWalletOption" class="hidden">
                            <button type="button" onclick="selectPayment('wallet')" id="btn-wallet"
                                class="payment-btn w-full flex items-center justify-between p-4 border-2 border-gray-200 dark:border-gray-600 rounded-xl hover:border-yellow-500 transition-all group bg-white dark:bg-gray-700">
                                <div class="flex items-center gap-3">
                                    <div
                                        class="bg-yellow-100 dark:bg-yellow-900/30 p-2.5 rounded-lg text-yellow-600 dark:text-yellow-400">
                                        💰</div>
                                    <div class="text-left">
                                        <p class="font-bold text-gray-800 dark:text-gray-100">Ví Coinkey</p>
                                        <p class="text-xs text-gray-500">Số dư: <span
                                                class="font-semibold text-yellow-600">{{ number_format(auth()->user()->getOrCreateWallet()->balance ?? 0) }}</span>
                                        </p>
                                    </div>
                                </div>
                                <span class="font-bold text-yellow-600" id="pmCoinkeyPrice"></span>
                            </button>
                            <p id="pmWalletError"
                                class="text-red-500 text-xs mt-2 hidden flex items-center gap-1 justify-center font-medium">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                Số dư không đủ
                            </p>
                        </div>

                        <!-- Cash Option -->
                        <button type="button" onclick="selectPayment('cash')" id="btn-cash"
                            class="payment-btn w-full flex items-center justify-between p-4 border-2 border-gray-200 dark:border-gray-600 rounded-xl hover:border-indigo-500 transition-all group bg-white dark:bg-gray-700">
                            <div class="flex items-center gap-3">
                                <div class="bg-indigo-100 dark:bg-indigo-900/30 p-2.5 rounded-lg text-indigo-600">🏦
                                </div>
                                <div class="text-left">
                                    <p class="font-bold text-gray-800 dark:text-gray-100">Chuyển khoản / QR</p>
                                    <p class="text-xs text-gray-500">Thanh toán qua PayOS</p>
                                </div>
                            </div>
                            <span class="font-bold text-indigo-600" id="pmCashPrice"></span>
                        </button>
                    </div>

                    <div class="flex gap-3 pt-2">
                        <button type="button" onclick="closePurchaseModal()"
                            class="flex-1 py-3.5 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-200 rounded-xl font-bold hover:bg-gray-200 transition">Hủy</button>
                        <button type="submit" id="pmSubmitBtn" disabled
                            class="flex-1 py-3.5 bg-indigo-600 text-white rounded-xl font-bold hover:bg-indigo-700 shadow-lg disabled:opacity-50 disabled:cursor-not-allowed transition">Xác
                            Nhận</button>
                    </div>
                </form>

                <!-- FORM 2: Custom Key Creation -->
                <form action="{{ route('keys.create-custom') }}" method="POST" id="customKeyForm"
                    class="hidden space-y-6">
                    @csrf
                    <input type="hidden" name="product_id" id="customProductId">
                    <input type="hidden" name="payment_method" id="customPaymentMethod">

                    <div
                        class="bg-indigo-50 dark:bg-indigo-900/20 p-4 rounded-xl border border-indigo-100 dark:border-indigo-800">
                        <p class="text-xs text-indigo-600 dark:text-indigo-400 font-bold uppercase tracking-wide mb-1">
                            Gói sản phẩm</p>
                        <h4 class="text-lg font-bold text-gray-900 dark:text-white" id="customProductNameDisplay">
                            Loading...</h4>
                        <div class="flex items-center gap-4 mt-2 text-sm">
                            <span class="flex items-center gap-1 text-gray-600 dark:text-gray-300">🕒 <span
                                    id="customProductDurationDisplay">--</span></span>
                            <span class="flex items-center gap-1 font-bold text-indigo-600 dark:text-indigo-400">🏷️
                                <span id="customProductPriceDisplay">--</span></span>
                        </div>
                    </div>

                    <div class="space-y-3">
                        <label class="block text-sm font-bold text-gray-700 dark:text-gray-300">💳 Chọn nguồn
                            tiền</label>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <button type="button" onclick="selectCustomPayment('wallet')" id="btn-custom-wallet"
                                class="payment-custom-btn w-full flex flex-col items-center justify-center p-4 border-2 border-gray-200 dark:border-gray-600 rounded-xl hover:border-yellow-500 transition-all group bg-white dark:bg-gray-700">
                                <div class="bg-yellow-100 dark:bg-yellow-900/30 p-2 rounded-lg text-yellow-600 mb-2">💰
                                </div>
                                <span class="font-bold text-gray-800 dark:text-gray-100 text-sm">Ví Coinkey</span>
                            </button>
                            <button type="button" onclick="selectCustomPayment('cash')" id="btn-custom-cash"
                                class="payment-custom-btn w-full flex flex-col items-center justify-center p-4 border-2 border-gray-200 dark:border-gray-600 rounded-xl hover:border-indigo-500 transition-all group bg-white dark:bg-gray-700">
                                <div class="bg-indigo-100 dark:bg-indigo-900/30 p-2 rounded-lg text-indigo-600 mb-2">🏦
                                </div>
                                <span class="font-bold text-gray-800 dark:text-gray-100 text-sm">Tiền mặt / QR</span>
                            </button>
                        </div>
                        <p id="customWalletError" class="text-xs text-red-600 mt-1 hidden text-center font-medium">⚠️
                            Số dư ví không đủ để thanh toán gói này</p>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">🔑 Đặt tên Key của
                            bạn</label>
                        <div class="relative">
                            <input type="text" name="key_code" id="customKeyCode"
                                placeholder="VD: MY-SUPER-KEY-01 Or Random"
                                class="w-full pl-4 pr-10 py-3 bg-white dark:bg-gray-700 border-2 border-gray-300 dark:border-gray-600 rounded-xl font-medium focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/20 transition uppercase"
                                required>
                            <div class="absolute right-3 top-1/2 -translate-y-1/2" id="keyLoadingIcon"
                                style="display: none;">
                                <svg class="animate-spin h-5 w-5 text-indigo-600" xmlns="http://www.w3.org/2000/svg"
                                    fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10"
                                        stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor"
                                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                    </path>
                                </svg>

                            </div>
                            <button type="button" onclick="generateRandomKey()"
                                class="absolute right-3 top-1/2 -translate-y-1/2 text-sm bg-indigo-600 hover:bg-indigo-700 text-white px-3.5 py-1.5 rounded-lg font-semibold transition-flex items-center gap-1 flex">
                                🎲 Random
                            </button>
                        </div>
                        <div class="flex justify-between items-start mt-1">
                            <p class="text-xs text-gray-500">Mã key phải là duy nhất trong hệ thống. Chữ in hoa, số,
                                gạch ngang (-).</p>
                            <p id="keyCheckMessage" class="text-xs font-bold"></p>
                        </div>
                    </div>

                    <div class="flex gap-3 pt-2">
                        <button type="button" onclick="closePurchaseModal()"
                            class="flex-1 py-3.5 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-200 rounded-xl font-bold hover:bg-gray-200 transition">Hủy</button>
                        <button type="submit" id="customSubmitBtn" disabled
                            class="flex-1 py-3.5 bg-gradient-to-r from-indigo-600 to-purple-600 text-white rounded-xl font-bold hover:from-indigo-700 hover:to-purple-700 shadow-lg disabled:opacity-50 disabled:cursor-not-allowed transition flex items-center justify-center gap-2">
                            <span> Tạo Key Ngay</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Hàm tạo mã key ngẫu nhiên
        function generateRandomKey() {
            const chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
            let result = 'KEY-';
            for (let i = 0; i < 16; i++) {
                if (i > 0 && i % 4 === 0) result += '-';
                result += chars.charAt(Math.floor(Math.random() * chars.length));
            }
            document.getElementById('customKeyCode').value = result;
        }
        document.addEventListener('DOMContentLoaded', () => {
            const currentUserBalance = {{ auth()->check() ? auth()->user()->getOrCreateWallet()->balance : 0 }};
            const routes = {
                checkKey: "{{ route('keys.check-key-code') }}",
                createCustomKey: "{{ route('keys.create-custom') }}"
            };
            const formatMoney = (amount) => new Intl.NumberFormat('vi-VN').format(amount);

            let currentProduct = null;
            let isKeyValid = false;
            let debounceTimer = null;



            //  1. OPEN PURCHASE MODAL 
       
            window.openPurchaseModal = function(product) {
                currentProduct = product;
                isKeyValid = false;

                // Set IDs
                document.getElementById('pmProductId').value = product.id;
                document.getElementById('customProductId').value = product.id;
                document.getElementById('pmTitle').innerText = product.name;
                document.getElementById('pmProductName').innerText = product.name;
                document.getElementById('customProductNameDisplay').innerText = product.name;

                // Prices
                document.getElementById('pmCashPrice').innerText = `${formatMoney(product.price)} ₫`;
                if (product.coinkey_amount) {
                    document.getElementById('pmCoinkeyPrice').innerText =
                        `${formatMoney(product.coinkey_amount)} Coin`;
                }

                // Duration
                let durationText = 'Vĩnh viễn';
                if (product.duration_minutes && product.duration_minutes > 0) {
                    const days = Math.floor(product.duration_minutes / 1440);
                    durationText = `${days} ngày (${product.duration_minutes} phút)`;
                    document.getElementById('pmDurationInfo').classList.remove('hidden');
                } else {
                    document.getElementById('pmDurationInfo').classList.add('hidden');
                }
                document.getElementById('pmDuration').innerText = durationText;
                document.getElementById('customProductDurationDisplay').innerText = durationText;

                // Config Tabs & Wallet Option
                const isService = product.product_type === 'package';
                const tabSwitcher = document.getElementById('pmTabSwitcher');
                const walletOption = document.getElementById('pmWalletOption'); // ID của container nút Ví

                if (isService) {
                    tabSwitcher.classList.remove('hidden');
                    document.getElementById('pmDesc').innerText = 'Chọn phương thức thanh toán';

                    // --- FIX: Logic hiển thị nút Ví ở Standard Form ---
                    if (product.coinkey_amount && product.coinkey_amount > 0) {
                        walletOption.classList.remove('hidden'); // Hiện nếu có giá Coin
                    } else {
                        walletOption.classList.add('hidden'); // Ẩn nếu ko có giá Coin
                    }

                    switchPurchaseTab('standard');
                } else {
                    // Top-up (Nạp Coin)
                    tabSwitcher.classList.add('hidden');
                    walletOption.classList.add('hidden'); // Nạp coin thì ko trả bằng ví được
                    document.getElementById('pmDesc').innerText = 'Nạp Coinkey vào ví';
                    switchPurchaseTab('standard');
                }

                // Reset Forms
                document.getElementById('customKeyCode').value = '';
                document.getElementById('keyCheckMessage').innerText = '';

                // Mặc định chọn Cash cho Standard để an toàn
                selectPayment('cash');
                // Mặc định chọn Wallet cho Custom
                selectCustomPayment('wallet');

                document.getElementById('purchaseModal').classList.remove('hidden');
            };

            window.closePurchaseModal = function() {
                document.getElementById('purchaseModal').classList.add('hidden');
            };

            window.switchPurchaseTab = function(tab) {
                const standardForm = document.getElementById('purchaseForm');
                const customForm = document.getElementById('customKeyForm');
                const tabStandard = document.getElementById('tab-standard');
                const tabCustom = document.getElementById('tab-custom');

                if (tab === 'standard') {
                    standardForm.classList.remove('hidden');
                    customForm.classList.add('hidden');
                    tabStandard.classList.add('bg-white', 'text-indigo-600', 'shadow-sm', 'ring-1',
                        'ring-black/5');
                    tabStandard.classList.remove('text-gray-600', 'dark:text-gray-400');
                    tabCustom.classList.remove('bg-white', 'text-indigo-600', 'shadow-sm', 'ring-1',
                        'ring-black/5');
                    tabCustom.classList.add('text-gray-600', 'dark:text-gray-400');
                } else {
                    standardForm.classList.add('hidden');
                    customForm.classList.remove('hidden');
                    tabCustom.classList.add('bg-white', 'text-indigo-600', 'shadow-sm', 'ring-1',
                        'ring-black/5');
                    tabCustom.classList.remove('text-gray-600', 'dark:text-gray-400');
                    tabStandard.classList.remove('bg-white', 'text-indigo-600', 'shadow-sm', 'ring-1',
                        'ring-black/5');
                    tabStandard.classList.add('text-gray-600', 'dark:text-gray-400');
                }
            };

           
            //  2. STANDARD PURCHASE LOGIC

            window.selectPayment = function(method) {
                document.getElementById('pmMethod').value = method;
                const submitBtn = document.getElementById('pmSubmitBtn');
                const btnWallet = document.getElementById('btn-wallet');
                const btnCash = document.getElementById('btn-cash');
                const walletError = document.getElementById('pmWalletError');

                // Reset styles
                [btnWallet, btnCash].forEach(btn => {
                    if (btn) {
                        btn.classList.remove('border-indigo-500', 'border-yellow-500', 'bg-indigo-50',
                            'bg-yellow-50', 'ring-2', 'ring-indigo-500/50', 'ring-yellow-500/50');
                        btn.classList.add('border-gray-200');
                    }
                });

                if (method === 'wallet') {
                    if (btnWallet) btnWallet.classList.add('border-yellow-500', 'bg-yellow-50', 'ring-2',
                        'ring-yellow-500/50');

                    const coinPrice = currentProduct.coinkey_amount || 0;
                    if (currentUserBalance < coinPrice) {
                        submitBtn.disabled = true;
                        if (walletError) walletError.classList.remove('hidden');
                    } else {
                        submitBtn.disabled = false;
                        if (walletError) walletError.classList.add('hidden');
                    }
                } else {
                    if (btnCash) btnCash.classList.add('border-indigo-500', 'bg-indigo-50', 'ring-2',
                        'ring-indigo-500/50');
                    if (walletError) walletError.classList.add('hidden');
                    submitBtn.disabled = false;
                }
            };

           
            //  3. CUSTOM KEY LOGIC
          
            window.selectCustomPayment = function(method) {
                document.getElementById('customPaymentMethod').value = method;
                const form = document.getElementById('customKeyForm');
                const btnWallet = document.getElementById('btn-custom-wallet');
                const btnCash = document.getElementById('btn-custom-cash');
                const priceDisplay = document.getElementById('customProductPriceDisplay');
                const walletError = document.getElementById('customWalletError');

                // Always create custom key route
                form.action = routes.createCustomKey;

                [btnWallet, btnCash].forEach(btn => {
                    btn.classList.remove('border-indigo-500', 'border-yellow-500', 'bg-indigo-50',
                        'bg-yellow-50', 'ring-2', 'ring-indigo-500/50', 'ring-yellow-500/50');
                    btn.classList.add('border-gray-200');
                });

                if (method === 'wallet') {
                    btnWallet.classList.add('border-yellow-500', 'bg-yellow-50', 'ring-2',
                        'ring-yellow-500/50');
                    const coinPrice = currentProduct.coinkey_amount || 0;
                    priceDisplay.innerText = `${formatMoney(coinPrice)} Coin`;
                    priceDisplay.className = 'font-bold text-yellow-600';

                    if (currentUserBalance < coinPrice) {
                        walletError.classList.remove('hidden');
                    } else {
                        walletError.classList.add('hidden');
                    }
                } else {
                    btnCash.classList.add('border-indigo-500', 'bg-indigo-50', 'ring-2', 'ring-indigo-500/50');
                    priceDisplay.innerText = `${formatMoney(currentProduct.price)} ₫`;
                    priceDisplay.className = 'font-bold text-indigo-600';
                    walletError.classList.add('hidden');
                }
                validateCustomForm();
            };

            // Key Input Logic
            const customKeyInput = document.getElementById('customKeyCode');
            if (customKeyInput) {
                customKeyInput.addEventListener('input', function() {
                    clearTimeout(debounceTimer);
                    const message = document.getElementById('keyCheckMessage');
                    const loading = document.getElementById('keyLoadingIcon');

                    this.value = this.value.toUpperCase().replace(/\s+/g, '-');
                    const keyCode = this.value;

                    isKeyValid = false;
                    validateCustomForm();

                    if (keyCode.length < 3) {
                        message.textContent = 'Tối thiểu 3 ký tự';
                        message.className = 'text-xs font-bold text-gray-500';
                        return;
                    }
                    if (!/^[A-Z0-9\-]+$/.test(keyCode)) {
                        message.textContent = 'Chỉ dùng chữ in hoa, số và dấu gạch ngang';
                        message.className = 'text-xs font-bold text-red-500';
                        return;
                    }

                    loading.style.display = 'block';
                    message.textContent = 'Đang kiểm tra...';
                    message.className = 'text-xs font-bold text-gray-500';

                    debounceTimer = setTimeout(async () => {
                        try {
                            const response = await fetch(routes.checkKey, {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'Accept': 'application/json',
                                    'X-CSRF-TOKEN': document.querySelector(
                                        'meta[name="csrf-token"]').getAttribute(
                                        'content')
                                },
                                body: JSON.stringify({
                                    key_code: keyCode
                                })
                            });
                            const data = await response.json();
                            loading.style.display = 'none';

                            if (data.available) {
                                message.textContent = '✅ Key hợp lệ';
                                message.className = 'text-xs font-bold text-green-600';
                                isKeyValid = true;
                            } else {
                                message.textContent = '❌ Key đã tồn tại';
                                message.className = 'text-xs font-bold text-red-600';
                                isKeyValid = false;
                            }
                            validateCustomForm();
                        } catch (error) {
                            loading.style.display = 'none';
                            console.error('Chi tiết lỗi:',
                            error); 
                            message.textContent = 'Lỗi: ' + error.message;
                            message.className = 'text-xs font-bold text-red-500';
                        }
                    }, 500);
                });
            }

            function validateCustomForm() {
                const method = document.getElementById('customPaymentMethod').value;
                const submitBtn = document.getElementById('customSubmitBtn');
                let canAfford = true;

                if (method === 'wallet') {
                    const coinPrice = currentProduct.coinkey_amount || 0;
                    if (currentUserBalance < coinPrice) canAfford = false;
                }

                if (canAfford && isKeyValid) {
                    submitBtn.disabled = false;
                    submitBtn.classList.remove('opacity-50', 'cursor-not-allowed');
                } else {
                    submitBtn.disabled = true;
                    submitBtn.classList.add('opacity-50', 'cursor-not-allowed');
                }
            }

           
            //  4. SEARCH & FILTER
            
            const searchInput = document.getElementById('search-input');
            const categoryFilter = document.getElementById('category-filter');
            const priceSort = document.getElementById('price-sort');

            function filterProducts() {
                
                const searchTerm = searchInput.value.toLowerCase();
                const category = categoryFilter.value;
                const sort = priceSort.value;
                const productsGrid = document.getElementById('products-grid');
                const products = Array.from(document.querySelectorAll('.product-card'));
                const noResults = document.getElementById('no-results');
                let visibleCount = 0;

                products.forEach(card => {
                    const name = card.dataset.name;
                    const cardCategory = card.dataset.category || '';
                    if (name.includes(searchTerm) && (category === '' || cardCategory === category)) {
                        card.classList.remove('hidden');
                        visibleCount++;
                    } else {
                        card.classList.add('hidden');
                    }
                });

                if (sort) {
                    const visible = products.filter(p => !p.classList.contains('hidden'));
                    visible.sort((a, b) => {
                        const pA = parseFloat(a.dataset.price),
                            pB = parseFloat(b.dataset.price);
                        const nA = a.dataset.name,
                            nB = b.dataset.name;
                        if (sort === 'price-asc') return pA - pB;
                        if (sort === 'price-desc') return pB - pA;
                        if (sort === 'name-asc') return nA.localeCompare(nB);
                        if (sort === 'name-desc') return nB.localeCompare(nA);
                    });
                    visible.forEach(p => productsGrid.appendChild(p));
                }
                document.getElementById('total-products').innerText = visibleCount;
                noResults.classList.toggle('hidden', visibleCount > 0);
            }

            if (searchInput) searchInput.addEventListener('input', filterProducts);
            if (categoryFilter) categoryFilter.addEventListener('change', filterProducts);
            if (priceSort) priceSort.addEventListener('change', filterProducts);

            
            //  5. VIEW DETAIL MODAL 
            window.openModal = function() {
                const modal = document.getElementById('productModal');
                const content = document.getElementById('modalContent');
                modal.classList.remove('hidden');
                setTimeout(() => {
                    content.classList.remove('scale-95', 'opacity-0');
                    content.classList.add('scale-100', 'opacity-100');
                }, 10);
            };
            window.closeModal = function() {
                const modal = document.getElementById('productModal');
                const content = document.getElementById('modalContent');
                content.classList.remove('scale-100', 'opacity-100');
                content.classList.add('scale-95', 'opacity-0');
                setTimeout(() => modal.classList.add('hidden'), 300);
            };

            document.querySelectorAll('.view-detail-btn').forEach(btn => {
                btn.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    const product = JSON.parse(this.dataset.product);
                    const content = document.getElementById('modal-product-content');
                    const payLink = document.getElementById('modal-pay-link');

                    // --- RENDER CHI TIẾT SẢN PHẨM ĐẦY ĐỦ ---
                    const imageSrc = product.image ? product.image : '';
                    const durationText = product.duration_minutes ? Math.floor(product
                        .duration_minutes / 1440) + ' ngày' : 'Vĩnh viễn';
                    const typeText = product.product_type === 'package' ? 'Gói dịch vụ' :
                        'Nạp Coinkey';
                    const categoryText = product.category || 'Chưa phân loại';

                    content.innerHTML = `
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Cột trái: Ảnh -->
                            <div class="bg-gray-100 dark:bg-gray-700/50 rounded-xl p-4 flex items-center justify-center min-h-[200px]">
                                ${imageSrc ? `<img src="${imageSrc}" class="max-w-full max-h-[300px] object-contain rounded-lg shadow-sm">` : 
                                `<svg class="w-32 h-32 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>`}
                            </div>

                            <!-- Cột phải: Thông tin -->
                            <div class="space-y-4">
                                <div>
                                    <h4 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">${product.name}</h4>
                                    <div class="flex flex-wrap gap-2 mb-3">
                                        <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-indigo-100 text-indigo-700 dark:bg-indigo-900 dark:text-indigo-300">${typeText}</span>
                                        <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-300">${categoryText}</span>
                                    </div>
                                    <p class="text-3xl font-bold text-indigo-600 dark:text-indigo-400 mb-1">${formatMoney(product.price)}₫</p>
                                    ${product.coinkey_amount ? `<p class="text-sm font-medium text-yellow-600 dark:text-yellow-500">Hoặc ${formatMoney(product.coinkey_amount)} Coin</p>` : ''}
                                </div>

                                <div class="border-t border-gray-200 dark:border-gray-700 pt-4 space-y-2">
                                    <div class="flex justify-between">
                                        <span class="text-sm text-gray-500">Thời hạn:</span>
                                        <span class="text-sm font-medium text-gray-900 dark:text-white">${durationText}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-sm text-gray-500">Lượt bán:</span>
                                        <span class="text-sm font-medium text-gray-900 dark:text-white">${product.sold_count || 0}</span>
                                    </div>
                                </div>

                                <div class="bg-blue-50 dark:bg-gray-700/50 p-3 rounded-lg border border-blue-100 dark:border-gray-600">
                                    <h5 class="text-xs font-bold text-gray-500 uppercase mb-1">Mô tả</h5>
                                    <p class="text-sm text-gray-700 dark:text-gray-300 leading-relaxed whitespace-pre-line">${product.description || 'Chưa có mô tả.'}</p>
                                </div>
                            </div>
                        </div>
                    `;

                    payLink.onclick = function() {
                        closeModal();
                        openPurchaseModal(product);
                    };
                    payLink.removeAttribute('href');
                    openModal();
                });
            });

            document.addEventListener('keydown', (e) => {
                if (e.key === 'Escape') {
                    closeModal();
                    closePurchaseModal();
                }
            });
        });
    </script>
</x-app-layout>
