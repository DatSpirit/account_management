<x-app-layout>
    <style>
        /* Only essential custom styles */
        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-slide-in {
            animation: slideIn 0.5s ease-out;
        }

        .custom-scrollbar::-webkit-scrollbar {
            width: 8px;
        }

        .custom-scrollbar::-webkit-scrollbar-track {
            background: #f1f5f9;
            border-radius: 10px;
        }

        .dark .custom-scrollbar::-webkit-scrollbar-track {
            background: #374151;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 10px;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }

        .dark .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #4b5563;
        }

        .dark .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: #6b7280;
        }
    </style>

    <!-- Toast Container -->
    <div id="toast-container" class="fixed top-4 right-4 z-50 space-y-3"></div>

    <!-- Header -->
    <x-slot name="header">
        <div class="flex items-center justify-between flex-wrap gap-4">
            <div class="flex items-center gap-4">
                <div
                    class="w-12 h-12 bg-gradient-to-br from-indigo-600 to-purple-600 rounded-xl flex items-center justify-center text-white font-bold text-xl shadow-lg">
                    A
                </div>
                <div>
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Admin Dashboard</h2>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Tổng quan hoạt động hệ thống</p>
                </div>
            </div>

            <div
                class="flex items-center gap-2 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl px-4 py-2 shadow-sm">
                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                <select onchange="location.href='?period='+this.value"
                    class="bg-transparent border-0 focus:outline-none focus:ring-0 font-medium text-gray-700 dark:text-gray-300 cursor-pointer">
                    <option value="1" {{ $period == 1 ? 'selected' : '' }}>24 giờ qua</option>
                    <option value="7" {{ $period == 7 ? 'selected' : '' }}>7 ngày qua</option>
                    <option value="30" {{ $period == 30 ? 'selected' : '' }}>30 ngày qua</option>
                    <option value="90" {{ $period == 90 ? 'selected' : '' }}>90 ngày qua</option>
                </select>
            </div>
        </div>
    </x-slot>

    <div
        class="py-8 px-4 sm:px-6 lg:px-8 bg-gradient-to-br from-gray-50 via-white to-gray-50 dark:from-gray-900 dark:via-gray-900 dark:to-gray-800 min-h-screen">
        <div class="max-w-7xl mx-auto space-y-8">

            <!-- Stats Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-6 gap-4 animate-slide-in">
                <!-- Card 1: Tổng Người Dùng -->
                <div
                    class="relative bg-white dark:bg-gray-800 rounded-2xl p-6 border border-gray-100 dark:border-gray-700 overflow-hidden transition-all duration-300 hover:-translate-y-1 hover:shadow-xl">
                    <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-blue-500 to-indigo-500"></div>
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400 uppercase tracking-wide">Tổng Người
                        Dùng</p>
                    <p
                        class="text-2xl font-bold bg-gradient-to-r from-blue-600 to-indigo-600 bg-clip-text text-transparent mt-2">
                        {{ number_format($totalUsers) }}</p>
                    <div class="flex items-center gap-2 mt-3">
                        <span
                            class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium {{ $isGrowth ? 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400' : 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400' }}">
                            {{ $isGrowth ? '↑' : '↓' }} {{ abs($growthPercentage) }}%
                        </span>
                        <span class="text-xs text-gray-500">so với tháng trước</span>
                    </div>
                </div>

                <!-- Card 2: Tổng Tiền Mặt -->
                <div
                    class="relative bg-white dark:bg-gray-800 rounded-2xl p-6 border border-gray-100 dark:border-gray-700 overflow-hidden transition-all duration-300 hover:-translate-y-1 hover:shadow-xl">
                    <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-green-500 to-emerald-500"></div>
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400 uppercase tracking-wide">Tổng Tiền
                        Mặt</p>
                    <p
                        class="text-2xl font-bold bg-gradient-to-r from-green-600 to-emerald-600 bg-clip-text text-transparent mt-2">
                        {{ number_format($totalCash) }}</p>
                    <p class="text-xs text-gray-800 dark:text-white mt-3">Coin :
                        <span class="font-bold text-gray-900 dark:text-white">{{ number_format($spentOnCoins) }}đ</span>
                    </p>
                    <p class="text-xs text-gray-800 dark:text-white mt-3">Key :
                        <span class="font-bold text-gray-900 dark:text-white">{{ number_format($spentOnKeys) }}đ</span>
                    </p>
                </div>

                <!-- Card 3: Tổng Coin -->
                <div
                    class="relative bg-white dark:bg-gray-800 rounded-2xl p-6 border border-gray-100 dark:border-gray-700 overflow-hidden transition-all duration-300 hover:-translate-y-1 hover:shadow-xl">
                    <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-yellow-500 to-amber-500"></div>
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400 uppercase tracking-wide">Tổng Coin
                    </p>
                    <p
                        class="text-2xl font-bold bg-gradient-to-r from-yellow-600 to-amber-600 bg-clip-text text-transparent mt-2">
                        {{ number_format($totalCoins) }}</p>
                    <p class="text-xs text-gray-500 mt-3">Đã tiêu: <span
                            class="font-semibold">{{ number_format($totalspenCoin) }}</span></p>
                    <p class="text-xs text-gray-500 mt-3">Còn lại: <span
                            class="font-semibold">{{ number_format($remainingCoins) }}</span></p>
                </div>

                <!-- Card 4: Tổng Đơn Hàng -->
                <div
                    class="relative bg-white dark:bg-gray-800 rounded-2xl p-6 border border-gray-100 dark:border-gray-700 overflow-hidden transition-all duration-300 hover:-translate-y-1 hover:shadow-xl">
                    <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-purple-500 to-pink-500"></div>
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400 uppercase tracking-wide">Tổng Đơn
                        Hàng</p>
                    <p
                        class="text-2xl font-bold bg-gradient-to-r from-purple-600 to-pink-600 bg-clip-text text-transparent mt-2">
                        {{ number_format($transactionStats['total']) }}</p>
                    <p class="text-xs text-gray-500 mt-3">Thành công: <span
                            class="font-semibold text-green-600">{{ number_format($transactionStats['success']) }}</span>
                    </p>
                </div>

                <!-- Card 5: Chờ Xử Lý -->
                <div
                    class="relative bg-white dark:bg-gray-800 rounded-2xl p-6 border border-gray-100 dark:border-gray-700 overflow-hidden transition-all duration-300 hover:-translate-y-1 hover:shadow-xl">
                    <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-orange-500 to-red-500"></div>
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400 uppercase tracking-wide">Chờ Xử Lý
                    </p>
                    <p
                        class="text-2xl font-bold bg-gradient-to-r from-orange-600 to-red-600 bg-clip-text text-transparent mt-2">
                        {{ number_format($transactionStats['pending']) }}</p>
                    <p class="text-xs text-gray-500 mt-3">Thất bại: <span
                            class="font-semibold text-red-600">{{ number_format($transactionStats['failed']) }}</span>
                    </p>
                </div>

                <!-- Card 6: Tổng Tiêu -->
                <div
                    class="relative bg-white dark:bg-gray-800 rounded-2xl p-6 border border-gray-100 dark:border-gray-700 overflow-hidden transition-all duration-300 hover:-translate-y-1 hover:shadow-xl">
                    <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-teal-500 to-cyan-500"></div>
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400 uppercase tracking-wide">Tổng Chi Tiêu</p>
                    <p
                        class="text-2xl font-bold bg-gradient-to-r from-teal-600 to-cyan-600 bg-clip-text text-transparent mt-2">
                        {{ number_format($totalAll) }}</p>
                    <p class="text-xs text-gray-500 mt-3">Tiền Mặt: <span
                            class="font-semibold text-indigo-600">{{ number_format($totalCash) }}</span></p>

                    <p class="text-xs text-gray-500 mt-3">Coin: <span
                            class="font-semibold text-indigo-600">{{ number_format($totalspenCoin) }}</span></p>
                </div>
            </div>

            <!-- Charts Row -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Chart 1: User Distribution -->
                <div
                    class="bg-white dark:bg-gray-800 rounded-2xl p-6 border border-gray-100 dark:border-gray-700 shadow-md">
                    <h3 class="text-lg font-bold mb-6 text-gray-900 dark:text-white">Phân Loại Người Dùng</h3>
                    <div class="flex flex-col lg:flex-row items-center gap-6">
                        <div class="w-48 h-48 flex-shrink-0">
                            <canvas id="userChart"></canvas>
                        </div>
                        <div class="space-y-3 w-full">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-2">
                                    <span class="w-3 h-3 rounded-full bg-blue-500"></span>
                                    <span class="text-sm text-gray-700 dark:text-gray-300">Mới</span>
                                </div>
                                <span
                                    class="font-bold text-gray-900 dark:text-white">{{ number_format($userDistribution['new']) }}</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-2">
                                    <span class="w-3 h-3 rounded-full bg-green-500"></span>
                                    <span class="text-sm text-gray-700 dark:text-gray-300">Cũ</span>
                                </div>
                                <span
                                    class="font-bold text-gray-900 dark:text-white">{{ number_format($userDistribution['existing']) }}</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-2">
                                    <span class="w-3 h-3 rounded-full bg-red-500"></span>
                                    <span class="text-sm text-gray-700 dark:text-gray-300">Hết hạn</span>
                                </div>
                                <span
                                    class="font-bold text-gray-900 dark:text-white">{{ number_format($userDistribution['expired']) }}</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-2">
                                    <span class="w-3 h-3 rounded-full bg-gray-500"></span>
                                    <span class="text-sm text-gray-700 dark:text-gray-300">Đã xóa</span>
                                </div>
                                <span
                                    class="font-bold text-gray-900 dark:text-white">{{ number_format($userDistribution['deleted']) }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Chart 2: Activity Status -->
                <div
                    class="bg-white dark:bg-gray-800 rounded-2xl p-6 border border-gray-100 dark:border-gray-700 shadow-md">
                    <h3 class="text-lg font-bold mb-6 text-gray-900 dark:text-white">Hoạt Động (365 ngày)</h3>
                    <div class="flex flex-col lg:flex-row items-center gap-6">
                        <div class="w-48 h-48 flex-shrink-0">
                            <canvas id="activityChart"></canvas>
                        </div>
                        <div class="space-y-3 w-full text-xs">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-2">
                                    <span class="w-3 h-3 rounded-full bg-emerald-500"></span>
                                    <div>
                                        <div class="text-gray-700 dark:text-gray-300">Rất hoạt động</div>
                                        <div class="text-gray-500">> 20 giao dịch</div>
                                    </div>
                                </div>
                                <span
                                    class="font-bold text-gray-900 dark:text-white">{{ number_format($activityStatus['very_active']) }}</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-2">
                                    <span class="w-3 h-3 rounded-full bg-teal-500"></span>
                                    <div>
                                        <div class="text-gray-700 dark:text-gray-300">Hoạt động</div>
                                        <div class="text-gray-500">5-20 giao dịch</div>
                                    </div>
                                </div>
                                <span
                                    class="font-bold text-gray-900 dark:text-white">{{ number_format($activityStatus['active']) }}</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-2">
                                    <span class="w-3 h-3 rounded-full bg-amber-500"></span>
                                    <div>
                                        <div class="text-gray-700 dark:text-gray-300">Ít hoạt động</div>
                                        <div class="text-gray-500">1-4 giao dịch</div>
                                    </div>
                                </div>
                                <span
                                    class="font-bold text-gray-900 dark:text-white">{{ number_format($activityStatus['inactive']) }}</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-2">
                                    <span class="w-3 h-3 rounded-full bg-gray-400"></span>
                                    <span class="text-gray-700 dark:text-gray-300">Không hoạt động</span>
                                </div>
                                <span
                                    class="font-bold text-gray-900 dark:text-white">{{ number_format($activityStatus['dormant']) }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Chart 3: Spend Distribution -->
                <div
                    class="bg-white dark:bg-gray-800 rounded-2xl p-6 border border-gray-100 dark:border-gray-700 shadow-md">
                    <h3 class="text-lg font-bold mb-6 text-gray-900 dark:text-white">Phân Bổ Chi Tiêu</h3>
                    <div class="flex flex-col lg:flex-row items-center gap-6">
                        <div class="w-48 h-48 flex-shrink-0">
                            <canvas id="spendChart"></canvas>
                        </div>
                        <div class="space-y-4 w-full">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-2">
                                    <span class="w-3 h-3 rounded-full bg-yellow-500"></span>
                                    <span class=" text-gray-700 dark:text-gray-300">Mua Coin</span>
                                </div>

                            </div>
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-2">
                                    <span class="w-3 h-3 rounded-full bg-purple-500"></span>
                                    <span class="text-gray-700 dark:text-gray-300">Mua Key/Package</span>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Combined Chart -->
            <div
                class="bg-white dark:bg-gray-800 rounded-2xl p-6 border border-gray-100 dark:border-gray-700 shadow-md">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white">Doanh Thu & Người Dùng Mới
                        ({{ now()->year }})</h3>
                    <div class="flex gap-4 text-sm">
                        <div class="flex items-center gap-2">
                            <span class="w-3 h-3 rounded bg-indigo-500"></span>
                            <span class="text-gray-600 dark:text-gray-400">Doanh thu</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="w-3 h-3 rounded-full bg-green-500"></span>
                            <span class="text-gray-600 dark:text-gray-400">Người dùng mới</span>
                        </div>
                    </div>
                </div>
                <div class="h-80">
                    <canvas id="combinedChart"></canvas>
                </div>
            </div>

            <!-- Top Lists -->
            <div class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-4 gap-6">
                <!-- Top Buyers -->
                <div
                    class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 shadow-md overflow-hidden">
                    <div
                        class="bg-gradient-to-r from-indigo-600 to-purple-600 text-white px-6 py-4 font-semibold text-lg flex items-center justify-between">
                        <span>🛒 Top Mua Hàng</span>
                        <span class="text-xs opacity-90">10 người</span>
                    </div>
                    <div class="max-h-96 overflow-y-auto custom-scrollbar">
                        @forelse($topBuyers as $item)
                            <div
                                class="flex items-center justify-between p-4 border-b border-gray-100 dark:border-gray-700 last:border-0 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors duration-200">
                                <div class="flex items-center gap-3 flex-1 min-w-0">
                                    <div
                                        class="w-12 h-12 rounded-full flex items-center justify-center text-white font-bold text-lg shadow-md bg-gradient-to-br from-indigo-500 to-purple-500">
                                        {{ strtoupper(substr($item['user']->name, 0, 1)) }}
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="font-medium text-gray-900 dark:text-white truncate">
                                            {{ $item['user']->name }}</p>
                                        <p class="text-xs text-gray-500 truncate">{{ $item['user']->email }}</p>
                                    </div>
                                </div>
                                <div class="text-right flex-shrink-0">
                                    <p class="font-bold text-indigo-600">{{ number_format($item['purchase_count']) }}
                                    </p>
                                    <p class="text-xs text-gray-500">{{ number_format($item['total_spent']) }}đ</p>
                                </div>
                            </div>
                        @empty
                            <p class="text-center text-gray-500 py-12">Chưa có dữ liệu</p>
                        @endforelse
                    </div>
                </div>

                <!-- Top Spenders -->
                <div
                    class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 shadow-md overflow-hidden">
                    <div
                        class="bg-gradient-to-r from-indigo-600 to-purple-600 text-white px-6 py-4 font-semibold text-lg flex items-center justify-between">
                        <span>💰 Top Chi Tiêu</span>
                        <span class="text-xs opacity-90">10 người</span>
                    </div>
                    <div class="max-h-96 overflow-y-auto custom-scrollbar">
                        @forelse($topSpenders as $item)
                            <div
                                class="flex items-center justify-between p-4 border-b border-gray-100 dark:border-gray-700 last:border-0 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors duration-200">
                                <div class="flex items-center gap-3 flex-1 min-w-0">
                                    <div
                                        class="w-12 h-12 rounded-full flex items-center justify-center text-white font-bold text-lg shadow-md bg-gradient-to-br from-green-500 to-emerald-500">
                                        {{ strtoupper(substr($item['user']->name, 0, 1)) }}
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="font-medium text-gray-900 dark:text-white truncate">
                                            {{ $item['user']->name }}</p>
                                        <p class="text-xs text-gray-500 truncate">{{ $item['user']->email }}</p>
                                    </div>
                                </div>
                                <div class="text-right flex-shrink-0">
                                    <p class="font-bold text-green-600">{{ number_format($item['total_spent']) }}đ</p>
                                    <p class="text-xs text-gray-500">{{ number_format($item['purchase_count']) }} đơn
                                    </p>
                                </div>
                            </div>
                        @empty
                            <p class="text-center text-gray-500 py-12">Chưa có dữ liệu</p>
                        @endforelse
                    </div>
                </div>

                <!-- Top Key Holders -->
                <div
                    class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 shadow-md overflow-hidden">
                    <div
                        class="bg-gradient-to-r from-indigo-600 to-purple-600 text-white px-6 py-4 font-semibold text-lg flex items-center justify-between">
                        <span>🔑 Top Giữ Key</span>
                        <span class="text-xs opacity-90">10 người</span>
                    </div>
                    <div class="max-h-96 overflow-y-auto custom-scrollbar">
                        @forelse($topKeyHolders as $item)
                            <div
                                class="flex items-center justify-between p-4 border-b border-gray-100 dark:border-gray-700 last:border-0 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors duration-200">
                                <div class="flex items-center gap-3 flex-1 min-w-0">
                                    <div
                                        class="w-12 h-12 rounded-full flex items-center justify-center text-white font-bold text-lg shadow-md bg-gradient-to-br from-purple-500 to-pink-500">
                                        {{ strtoupper(substr($item['user']->name, 0, 1)) }}
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="font-medium text-gray-900 dark:text-white truncate">
                                            {{ $item['user']->name }}</p>
                                        <p class="text-xs text-gray-500 truncate">{{ $item['user']->email }}</p>
                                    </div>
                                </div>
                                <div class="text-right flex-shrink-0">
                                    <p class="font-bold text-purple-600">{{ number_format($item['key_count']) }}</p>
                                    <p class="text-xs text-gray-500">keys</p>
                                </div>
                            </div>
                        @empty
                            <p class="text-center text-gray-500 py-12">Chưa có dữ liệu</p>
                        @endforelse
                    </div>
                </div>

                <!-- Top Products -->
                <div
                    class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 shadow-md overflow-hidden">
                    <div
                        class="bg-gradient-to-r from-indigo-600 to-purple-600 text-white px-6 py-4 font-semibold text-lg flex items-center justify-between">
                        <span>📦 Top Sản Phẩm</span>
                        <span class="text-xs opacity-90">10 sản phẩm</span>
                    </div>
                    <div class="max-h-96 overflow-y-auto custom-scrollbar">
                        @forelse($topProducts as $item)
                            <div
                                class="flex items-center justify-between p-4 border-b border-gray-100 dark:border-gray-700 last:border-0 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors duration-200">
                                <div class="flex-1 min-w-0">
                                    <p class="font-medium text-gray-900 dark:text-white truncate">
                                        {{ $item['product']->name ?? 'Không xác định' }}</p>
                                    <p class="text-xs text-gray-500">{{ $item['product']->category ?? '' }}</p>
                                </div>
                                <div class="text-right flex-shrink-0">
                                    <p class="font-bold text-teal-600">{{ number_format($item['sales_count']) }}</p>
                                    <p class="text-xs text-gray-500">{{ number_format($item['revenue']) }}đ</p>
                                </div>
                            </div>
                        @empty
                            <p class="text-center text-gray-500 py-12">Chưa có dữ liệu</p>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Coin & Key Products - Full Width -->
            <div
                class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 shadow-md overflow-hidden">
                <div
                    class="bg-gradient-to-r from-indigo-600 to-purple-600 text-white px-6 py-4 font-semibold text-lg flex items-center justify-between">
                    <span>💎 Top Sản Phẩm Coin & Key</span>
                </div>
                <div class="p-6">
                    <div class="flex bg-gray-100 dark:bg-gray-700 rounded-xl p-1 mb-6">
                        <button id="coinTab"
                            class="flex-1 py-2.5 px-4 text-center font-medium rounded-lg transition-all duration-200 bg-white dark:bg-gray-800 text-indigo-600 shadow-md">💰
                            Coin</button>
                        <button id="keyTab"
                            class="flex-1 py-2.5 px-4 text-center font-medium rounded-lg transition-all duration-200 text-gray-600 dark:text-gray-400 hover:text-gray-800 dark:hover:text-gray-200">🔑
                            Key/Package</button>
                    </div>

                    <div id="coinContent" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        @forelse($topCoinProducts as $item)
                            <div
                                class="flex justify-between items-center p-4 bg-yellow-50 dark:bg-yellow-900/20 rounded-xl border border-yellow-200 dark:border-yellow-800 hover:shadow-md transition-shadow">
                                <div class="flex-1 min-w-0">
                                    <p class="font-medium text-gray-900 dark:text-white truncate">
                                        {{ $item['product']->name ?? 'Không xác định' }}</p>
                                    <p class="text-xs text-gray-500 mt-1">{{ number_format($item['revenue']) }}đ</p>
                                </div>
                                <div class="text-right flex-shrink-0 ml-3">
                                    <p class="font-bold text-yellow-600 text-lg">
                                        {{ number_format($item['sales_count']) }}</p>
                                    <p class="text-xs text-gray-500">bán</p>
                                </div>
                            </div>
                        @empty
                            <div class="col-span-3 text-center py-12">
                                <p class="text-gray-500">Chưa có dữ liệu</p>
                            </div>
                        @endforelse
                    </div>

                    <div id="keyContent" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 hidden">
                        @forelse($topKeyProducts as $item)
                            <div
                                class="flex justify-between items-center p-4 bg-purple-50 dark:bg-purple-900/20 rounded-xl border border-purple-200 dark:border-purple-800 hover:shadow-md transition-shadow">
                                <div class="flex-1 min-w-0">
                                    <p class="font-medium text-gray-900 dark:text-white truncate">
                                        {{ $item['product']?->name ?? 'Không xác định' }}</p>
                                    <p class="text-xs text-gray-500 mt-1">{{ number_format($item['revenue']) }}đ</p>
                                </div>
                                <div class="text-right flex-shrink-0 ml-3">
                                    <p class="font-bold text-purple-600 text-lg">
                                        {{ number_format($item['sales_count']) }}</p>
                                    <p class="text-xs text-gray-500">bán</p>
                                </div>
                            </div>
                        @empty
                            <div class="col-span-3 text-center py-12">
                                <p class="text-gray-500">Chưa có dữ liệu</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Revenue Chart -->
            <div
                class="bg-white dark:bg-gray-800 rounded-2xl p-6 border border-gray-100 dark:border-gray-700 shadow-md">
                <div class="flex items-center justify-between mb-6">
                    <div class="flex items-center gap-3">
                        <div
                            class="w-10 h-10 rounded-xl bg-gradient-to-br from-green-500 to-emerald-500 flex items-center justify-center">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-gray-900 dark:text-white">Doanh Thu 7 Ngày Qua</h3>
                            <p class="text-sm text-gray-500">Theo dõi xu hướng doanh thu gần đây</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-2 px-3 py-1.5 bg-green-100 dark:bg-green-900/30 rounded-lg">
                        <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                        </svg>
                        <span class="text-sm font-medium text-green-700 dark:text-green-400">Xu hướng tăng
                            trưởng</span>
                    </div>
                </div>
                <div class="h-80">
                    <canvas id="revenueChart"></canvas>
                </div>
            </div>

        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Toast Notification
        function showToast(message, type = 'success') {
            const container = document.getElementById('toast-container');
            const colors = {
                success: 'from-green-500 to-emerald-500',
                error: 'from-red-500 to-pink-500'
            };
            const icons = {
                success: 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z',
                error: 'M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z'
            };

            const toast = document.createElement('div');
            toast.className =
                'flex items-center gap-3 w-full max-w-sm p-4 bg-white dark:bg-gray-800 rounded-xl shadow-2xl border border-gray-200 dark:border-gray-700 transform translate-x-full opacity-0 transition-all duration-300';
            toast.innerHTML = `
                <div class="w-10 h-10 rounded-lg bg-gradient-to-br ${colors[type]} flex items-center justify-center flex-shrink-0">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="${icons[type]}"/>
                    </svg>
                </div>
                <div class="flex-1 text-sm font-medium text-gray-900 dark:text-white">${message}</div>
                <button onclick="this.parentElement.remove()" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            `;
            container.appendChild(toast);
            setTimeout(() => toast.classList.remove('translate-x-full', 'opacity-0'), 100);
            setTimeout(() => toast.remove(), 5000);
        }

        // Tab Switching
        document.getElementById('coinTab').addEventListener('click', function() {
            this.className =
                'flex-1 py-2.5 px-4 text-center font-medium rounded-lg transition-all duration-200 bg-white dark:bg-gray-800 text-indigo-600 shadow-md';
            document.getElementById('keyTab').className =
                'flex-1 py-2.5 px-4 text-center font-medium rounded-lg transition-all duration-200 text-gray-600 dark:text-gray-400 hover:text-gray-800 dark:hover:text-gray-200';
            document.getElementById('coinContent').classList.remove('hidden');
            document.getElementById('keyContent').classList.add('hidden');
        });

        document.getElementById('keyTab').addEventListener('click', function() {
            this.className =
                'flex-1 py-2.5 px-4 text-center font-medium rounded-lg transition-all duration-200 bg-white dark:bg-gray-800 text-indigo-600 shadow-md';
            document.getElementById('coinTab').className =
                'flex-1 py-2.5 px-4 text-center font-medium rounded-lg transition-all duration-200 text-gray-600 dark:text-gray-400 hover:text-gray-800 dark:hover:text-gray-200';
            document.getElementById('keyContent').classList.remove('hidden');
            document.getElementById('coinContent').classList.add('hidden');
        });

        // Chart.js Configurations
        const chartColors = {
            blue: '#3b82f6',
            green: '#10b981',
            red: '#ef4444',
            gray: '#6b7280',
            emerald: '#10b981',
            teal: '#14b8a6',
            amber: '#f59e0b',
            yellow: '#eab308',
            purple: '#a855f7',
            indigo: '#6366f1'
        };

        // User Distribution Chart
        new Chart(document.getElementById('userChart'), {
            type: 'doughnut',
            data: {
                labels: ['Mới', 'Cũ', 'Hết hạn', 'Đã xóa'],
                datasets: [{
                    data: [
                        {{ $userDistribution['new'] }},
                        {{ $userDistribution['existing'] }},
                        {{ $userDistribution['expired'] }},
                        {{ $userDistribution['deleted'] }}
                    ],
                    backgroundColor: [chartColors.blue, chartColors.green, chartColors.red, chartColors
                        .gray
                    ],
                    borderWidth: 0
                }]
            },
            options: {
                cutout: '70%',
                plugins: {
                    legend: {
                        display: false
                    }
                }
            }
        });

        // Activity Status Chart
        new Chart(document.getElementById('activityChart'), {
            type: 'doughnut',
            data: {
                labels: ['Rất hoạt động', 'Hoạt động', 'Ít hoạt động', 'Không hoạt động'],
                datasets: [{
                    data: [
                        {{ $activityStatus['very_active'] }},
                        {{ $activityStatus['active'] }},
                        {{ $activityStatus['inactive'] }},
                        {{ $activityStatus['dormant'] }}
                    ],
                    backgroundColor: [chartColors.emerald, chartColors.teal, chartColors.amber, chartColors
                        .gray
                    ],
                    borderWidth: 0
                }]
            },
            options: {
                cutout: '70%',
                plugins: {
                    legend: {
                        display: false
                    }
                }
            }
        });

        // Spend Distribution Chart
        new Chart(document.getElementById('spendChart'), {
            type: 'doughnut',
            data: {
                labels: ['Mua Coin', 'Mua Key/Package'],
                datasets: [{
                    data: [{{ $spentOnCoins }}, {{ $spentOnKeys }}],
                    backgroundColor: [chartColors.yellow, chartColors.purple],
                    borderWidth: 0
                }]
            },
            options: {
                cutout: '70%',
                plugins: {
                    legend: {
                        display: false
                    }
                }
            }
        });

        // Combined Chart
        new Chart(document.getElementById('combinedChart'), {
            data: {
                labels: @json(array_column($combinedChartData, 'month')),
                datasets: [{
                        type: 'bar',
                        label: 'Doanh thu',
                        data: @json(array_column($combinedChartData, 'revenue')),
                        backgroundColor: chartColors.indigo,
                        yAxisID: 'y',
                        borderRadius: 8
                    },
                    {
                        type: 'line',
                        label: 'Người dùng mới',
                        data: @json(array_column($combinedChartData, 'new_users')),
                        borderColor: chartColors.green,
                        backgroundColor: 'rgba(16, 185, 129, 0.1)',
                        fill: true,
                        yAxisID: 'y1',
                        tension: 0.4
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                interaction: {
                    mode: 'index',
                    intersect: false
                },
                scales: {
                    y: {
                        type: 'linear',
                        position: 'left',
                        title: {
                            display: true,
                            text: 'Doanh thu (đ)',
                            font: {
                                weight: 'bold'
                            }
                        },
                        grid: {
                            color: 'rgba(0, 0, 0, 0.05)'
                        }
                    },
                    y1: {
                        type: 'linear',
                        position: 'right',
                        title: {
                            display: true,
                            text: 'Người dùng mới',
                            font: {
                                weight: 'bold'
                            }
                        },
                        grid: {
                            drawOnChartArea: false
                        }
                    }
                },
                plugins: {
                    legend: {
                        display: false
                    }
                }
            }
        });

        // Revenue Chart
        new Chart(document.getElementById('revenueChart'), {
            type: 'bar',
            data: {
                labels: @json($revenueChart->pluck('date')->map(fn($d) => \Carbon\Carbon::parse($d)->format('d/m'))),
                datasets: [{
                    label: 'Doanh thu',
                    data: @json($revenueChart->pluck('revenue')),
                    backgroundColor: chartColors.green,
                    borderRadius: 8
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: 'rgba(0, 0, 0, 0.05)'
                        }
                    }
                }
            }
        });

        // Session Messages
        @if (session('success'))
            showToast("{{ session('success') }}", 'success');
        @endif
        @if (session('error'))
            showToast("{{ session('error') }}", 'error');
        @endif
    </script>
</x-app-layout>
