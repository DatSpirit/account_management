<x-app-layout>
    <!-- Toast Container -->
    <div id="toast-container" class="fixed top-4 right-4 z-[100] space-y-3"></div>

    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-3">
                <svg class="w-6 h-6 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                </svg>
                <h2 class="font-bold text-2xl text-gray-800 dark:text-gray-100 tracking-tight">
                    Admin Dashboard 
                </h2>
            </div>
            
            <!-- Period Filter -->
            <div class="flex items-center space-x-2">
                <label class="text-sm text-gray-600 dark:text-gray-400">Lọc:</label>
                <select id="periodFilter" class="rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 text-sm" onchange="window.location.href='?period='+this.value">
                    <option value="1" {{ $period == 1 ? 'selected' : '' }}>1 ngày</option>
                    <option value="7" {{ $period == 7 ? 'selected' : '' }}>7 ngày</option>
                    <option value="30" {{ $period == 30 ? 'selected' : '' }}>30 ngày</option>
                    <option value="90" {{ $period == 90 ? 'selected' : '' }}>90 ngày</option>
                </select>
            </div>
        </div>
    </x-slot>

    <div class="py-8 px-4 sm:px-6 lg:px-8 min-h-screen bg-gray-50 dark:bg-gray-900">
        <div class="max-w-[1800px] mx-auto space-y-6">
            
            <!-- Header Stats -->
            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6">
                <!-- Total Users -->
                <div class="bg-gradient-to-br from-blue-500 to-blue-700 rounded-2xl shadow-xl p-6 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-blue-100 text-sm font-medium">Tổng Người Dùng</p>
                            <p class="text-4xl font-bold mt-2">{{ number_format($totalUsers) }}</p>
                            <p class="text-blue-200 text-xs mt-2">
                                <span class="{{ $isGrowth ? 'text-green-300' : 'text-red-300' }}">
                                    {{ $isGrowth ? '↑' : '↓' }} {{ abs($growthPercentage) }}%
                                </span> so với tháng trước
                            </p>
                        </div>
                        <div class="w-16 h-16 bg-white/20 rounded-full flex items-center justify-center">
                            <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Total Revenue -->
                <div class="bg-gradient-to-br from-green-500 to-green-700 rounded-2xl shadow-xl p-6 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-green-100 text-sm font-medium">Tổng Doanh Thu</p>
                            <p class="text-4xl font-bold mt-2">{{ number_format($transactionStats['total_revenue']) }}</p>
                            <p class="text-green-200 text-xs mt-2">
                                Hôm nay: <span class="font-semibold">{{ number_format($transactionStats['today_revenue']) }}đ</span>
                            </p>
                        </div>
                        <div class="w-16 h-16 bg-white/20 rounded-full flex items-center justify-center">
                            <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M8.433 7.418c.155-.103.346-.196.567-.267v1.698a2.305 2.305 0 01-.567-.267C8.07 8.34 8 8.114 8 8c0-.114.07-.34.433-.582zM11 12.849v-1.698c.22.071.412.164.567.267.364.243.433.468.433.582 0 .114-.07.34-.433.582a2.305 2.305 0 01-.567.267z"/>
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-13a1 1 0 10-2 0v.092a4.535 4.535 0 00-1.676.662C6.602 6.234 6 7.009 6 8c0 .99.602 1.765 1.324 2.246.48.32 1.054.545 1.676.662v1.941c-.391-.127-.68-.317-.843-.504a1 1 0 10-1.51 1.31c.562.649 1.413 1.076 2.353 1.253V15a1 1 0 102 0v-.092a4.535 4.535 0 001.676-.662C13.398 13.766 14 12.991 14 12c0-.99-.602-1.765-1.324-2.246A4.535 4.535 0 0011 9.092V7.151c.391.127.68.317.843.504a1 1 0 101.511-1.31c-.563-.649-1.413-1.076-2.354-1.253V5z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Total Orders -->
                <div class="bg-gradient-to-br from-purple-500 to-purple-700 rounded-2xl shadow-xl p-6 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-purple-100 text-sm font-medium">Tổng Đơn Hàng</p>
                            <p class="text-4xl font-bold mt-2">{{ number_format($transactionStats['total']) }}</p>
                            <p class="text-purple-200 text-xs mt-2">
                                Thành công: <span class="font-semibold">{{ number_format($transactionStats['success']) }}</span>
                            </p>
                        </div>
                        <div class="w-16 h-16 bg-white/20 rounded-full flex items-center justify-center">
                            <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M3 1a1 1 0 000 2h1.22l.305 1.222a.997.997 0 00.01.042l1.358 5.43-.893.892C3.74 11.846 4.632 14 6.414 14H15a1 1 0 000-2H6.414l1-1H14a1 1 0 00.894-.553l3-6A1 1 0 0017 3H6.28l-.31-1.243A1 1 0 005 1H3zM16 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM6.5 18a1.5 1.5 0 100-3 1.5 1.5 0 000 3z"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Pending Orders -->
                <div class="bg-gradient-to-br from-amber-500 to-amber-700 rounded-2xl shadow-xl p-6 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-amber-100 text-sm font-medium">Đơn Chờ Xử Lý</p>
                            <p class="text-4xl font-bold mt-2">{{ number_format($transactionStats['pending']) }}</p>
                            <p class="text-amber-200 text-xs mt-2">
                                Thất bại: <span class="font-semibold">{{ number_format($transactionStats['failed']) }}</span>
                            </p>
                        </div>
                        <div class="w-16 h-16 bg-white/20 rounded-full flex items-center justify-center">
                            <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Charts Row 1: Pie Charts -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- User Distribution Chart -->
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700 p-6">
                    <h4 class="text-xl font-bold text-gray-900 dark:text-gray-100 mb-6 flex items-center space-x-2">
                        <svg class="w-5 h-5 text-indigo-600" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M2 10a8 8 0 018-8v8h8a8 8 0 11-16 0z"/>
                            <path d="M12 2.252A8.014 8.014 0 0117.748 8H12V2.252z"/>
                        </svg>
                        <span>Phân Loại Người Dùng</span>
                    </h4>
                    <div class="relative h-80">
                        <canvas id="userDistributionChart"></canvas>
                    </div>
                    <div class="mt-4 grid grid-cols-2 gap-3 text-sm">
                        <div class="flex items-center justify-between p-3 bg-blue-50 dark:bg-blue-900/20 rounded-lg">
                            <span class="text-gray-700 dark:text-gray-300">Người dùng mới</span>
                            <span class="font-bold text-blue-600 dark:text-blue-400">{{ number_format($userDistribution['new']) }}</span>
                        </div>
                        <div class="flex items-center justify-between p-3 bg-green-50 dark:bg-green-900/20 rounded-lg">
                            <span class="text-gray-700 dark:text-gray-300">Người dùng cũ</span>
                            <span class="font-bold text-green-600 dark:text-green-400">{{ number_format($userDistribution['existing']) }}</span>
                        </div>
                        <div class="flex items-center justify-between p-3 bg-red-50 dark:bg-red-900/20 rounded-lg">
                            <span class="text-gray-700 dark:text-gray-300">Đã hết hạn</span>
                            <span class="font-bold text-red-600 dark:text-red-400">{{ number_format($userDistribution['expired']) }}</span>
                        </div>
                        <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-900/20 rounded-lg">
                            <span class="text-gray-700 dark:text-gray-300">Đã xóa</span>
                            <span class="font-bold text-gray-600 dark:text-gray-400">{{ number_format($userDistribution['deleted']) }}</span>
                        </div>
                    </div>
                </div>

                <!-- Activity Status Chart -->
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700 p-6">
                    <h4 class="text-xl font-bold text-gray-900 dark:text-gray-100 mb-6 flex items-center space-x-2">
                        <svg class="w-5 h-5 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                        </svg>
                        <span>Trạng Thái Hoạt Động</span>
                    </h4>
                    <div class="relative h-80">
                        <canvas id="activityStatusChart"></canvas>
                    </div>
                    <div class="mt-4 grid grid-cols-2 gap-3 text-sm">
                        <div class="flex items-center justify-between p-3 bg-emerald-50 dark:bg-emerald-900/20 rounded-lg">
                            <span class="text-gray-700 dark:text-gray-300">Rất hoạt động</span>
                            <span class="font-bold text-emerald-600 dark:text-emerald-400">{{ number_format($activityStatus['very_active']) }}</span>
                        </div>
                        <div class="flex items-center justify-between p-3 bg-teal-50 dark:bg-teal-900/20 rounded-lg">
                            <span class="text-gray-700 dark:text-gray-300">Hoạt động</span>
                            <span class="font-bold text-teal-600 dark:text-teal-400">{{ number_format($activityStatus['active']) }}</span>
                        </div>
                        <div class="flex items-center justify-between p-3 bg-amber-50 dark:bg-amber-900/20 rounded-lg">
                            <span class="text-gray-700 dark:text-gray-300">Ít hoạt động</span>
                            <span class="font-bold text-amber-600 dark:text-amber-400">{{ number_format($activityStatus['inactive']) }}</span>
                        </div>
                        <div class="flex items-center justify-between p-3 bg-red-50 dark:bg-red-900/20 rounded-lg">
                            <span class="text-gray-700 dark:text-gray-300">Không hoạt động</span>
                            <span class="font-bold text-red-600 dark:text-red-400">{{ number_format($activityStatus['dormant']) }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Growth Chart -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700 p-6">
                <h4 class="text-xl font-bold text-gray-900 dark:text-gray-100 mb-6 flex items-center space-x-2">
                    <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                    <span>Biểu Đồ Tăng Trưởng Năm {{ date('Y') }}</span>
                </h4>
                <div class="relative" style="height: 350px;">
                    <canvas id="userGrowthChart"></canvas>
                </div>
            </div>

            <!-- Top Tables Row -->
            <div class="grid grid-cols-1 xl:grid-cols-2 gap-6">
                <!-- Top Buyers -->
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700 p-6">
                    <h4 class="text-xl font-bold text-gray-900 dark:text-gray-100 mb-6 flex items-center space-x-2">
                        <svg class="w-5 h-5 text-purple-600" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"/>
                            <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm9.707 5.707a1 1 0 00-1.414-1.414L9 12.586l-1.293-1.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        <span>Top 10 Người Mua Hàng Nhiều Nhất</span>
                    </h4>
                    <div class="space-y-3 max-h-[500px] overflow-y-auto scrollbar-thin scrollbar-thumb-gray-300 dark:scrollbar-thumb-gray-600">
                        @forelse($topBuyers as $index => $buyer)
                            <div class="flex items-center justify-between p-4 rounded-xl bg-gradient-to-r from-purple-50 to-pink-50 dark:from-purple-900/20 dark:to-pink-900/20 border border-purple-200 dark:border-purple-700 hover:shadow-md transition-all">
                                <div class="flex items-center space-x-4">
                                    <div class="w-10 h-10 rounded-full bg-gradient-to-br from-purple-500 to-pink-500 flex items-center justify-center text-white font-bold shadow-lg">
                                        {{ $index + 1 }}
                                    </div>
                                    <div>
                                        <p class="font-semibold text-gray-900 dark:text-gray-100">{{ $buyer['user']->name ?? 'N/A' }}</p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">{{ $buyer['user']->email ?? '' }}</p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <p class="text-lg font-bold text-purple-600 dark:text-purple-400">{{ $buyer['purchase_count'] }} đơn</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">{{ number_format($buyer['total_spent']) }}đ</p>
                                </div>
                            </div>
                        @empty
                            <p class="text-center text-gray-500 dark:text-gray-400 py-8">Chưa có dữ liệu</p>
                        @endforelse
                    </div>
                </div>

                <!-- Top Spenders -->
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700 p-6">
                    <h4 class="text-xl font-bold text-gray-900 dark:text-gray-100 mb-6 flex items-center space-x-2">
                        <svg class="w-5 h-5 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M8.433 7.418c.155-.103.346-.196.567-.267v1.698a2.305 2.305 0 01-.567-.267C8.07 8.34 8 8.114 8 8c0-.114.07-.34.433-.582zM11 12.849v-1.698c.22.071.412.164.567.267.364.243.433.468.433.582 0 .114-.07.34-.433.582a2.305 2.305 0 01-.567.267z"/>
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-13a1 1 0 10-2 0v.092a4.535 4.535 0 00-1.676.662C6.602 6.234 6 7.009 6 8c0 .99.602 1.765 1.324 2.246.48.32 1.054.545 1.676.662v1.941c-.391-.127-.68-.317-.843-.504a1 1 0 10-1.51 1.31c.562.649 1.413 1.076 2.353 1.253V15a1 1 0 102 0v-.092a4.535 4.535 0 001.676-.662C13.398 13.766 14 12.991 14 12c0-.99-.602-1.765-1.324-2.246A4.535 4.535 0 0011 9.092V7.151c.391.127.68.317.843.504a1 1 0 101.511-1.31c-.563-.649-1.413-1.076-2.354-1.253V5z" clip-rule="evenodd"/>
                        </svg>
                        <span>Top 10 Người Tiêu Tiền Nhiều Nhất</span>
                    </h4>
                    <div class="space-y-3 max-h-[500px] overflow-y-auto scrollbar-thin scrollbar-thumb-gray-300 dark:scrollbar-thumb-gray-600">
                        @forelse($topSpenders as $index => $spender)
                            <div class="flex items-center justify-between p-4 rounded-xl bg-gradient-to-r from-green-50 to-emerald-50 dark:from-green-900/20 dark:to-emerald-900/20 border border-green-200 dark:border-green-700 hover:shadow-md transition-all">
                                <div class="flex items-center space-x-4">
                                    <div class="w-10 h-10 rounded-full bg-gradient-to-br from-green-500 to-emerald-500 flex items-center justify-center text-white font-bold shadow-lg">
                                        {{ $index + 1 }}
                                    </div>
                                    <div>
                                        <p class="font-semibold text-gray-900 dark:text-gray-100">{{ $spender['user']->name ?? 'N/A' }}</p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">{{ $spender['user']->email ?? '' }}</p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <p class="text-lg font-bold text-green-600 dark:text-green-400">{{ number_format($spender['total_spent']) }}đ</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">{{ $spender['purchase_count'] }} đơn</p>
                                </div>
                            </div>
                        @empty
                            <p class="text-center text-gray-500 dark:text-gray-400 py-8">Chưa có dữ liệu</p>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Top Products & Expiring Users -->
            <div class="grid grid-cols-1 xl:grid-cols-2 gap-6">
                <!-- Top Products -->
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700 p-6">
                    <h4 class="text-xl font-bold text-gray-900 dark:text-gray-100 mb-6 flex items-center space-x-2">
                        <svg class="w-5 h-5 text-orange-600" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M3 1a1 1 0 000 2h1.22l.305 1.222a.997.997 0 00.01.042l1.358 5.43-.893.892C3.74 11.846 4.632 14 6.414 14H15a1 1 0 000-2H6.414l1-1H14a1 1 0 00.894-.553l3-6A1 1 0 0017 3H6.28l-.31-1.243A1 1 0 005 1H3zM16 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM6.5 18a1.5 1.5 0 100-3 1.5 1.5 0 000 3z"/>
                        </svg>
                        <span>Sản Phẩm Bán Chạy Nhất</span>
                    </h4>
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead>
                                <tr class="border-b-2 border-gray-200 dark:border-gray-700">
                                    <th class="text-left py-3 px-2 text-sm font-semibold text-gray-700 dark:text-gray-300">#</th>
                                    <th class="text-left py-3 px-2 text-sm font-semibold text-gray-700 dark:text-gray-300">Sản phẩm</th>
                                    <th class="text-center py-3 px-2 text-sm font-semibold text-gray-700 dark:text-gray-300">Đã bán</th>
                                    <th class="text-right py-3 px-2 text-sm font-semibold text-gray-700 dark:text-gray-300">Doanh thu</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                @forelse($topProducts as $index => $product)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                                        <td class="py-3 px-2">
                                            <span class="inline-flex items-center justify-center w-8 h-8 rounded-full {{ $index < 3 ? 'bg-gradient-to-br from-orange-400 to-red-500 text-white font-bold' : 'bg-gray-200 dark:bg-gray-700 text-gray-600 dark:text-gray-400' }} text-sm">
                                                {{ $index + 1 }}
                                            </span>
                                        </td>
                                        <td class="py-3 px-2">
                                            <p class="font-semibold text-gray-900 dark:text-gray-100">{{ $product['product']->name ?? 'N/A' }}</p>
                                            <p class="text-xs text-gray-500 dark:text-gray-400">{{ Str::limit($product['product']->description ?? '', 30) }}</p>
                                        </td>
                                        <td class="py-3 px-2 text-center">
                                            <span class="inline-flex px-3 py-1 rounded-full text-sm font-semibold bg-orange-100 dark:bg-orange-900/30 text-orange-700 dark:text-orange-400">
                                                {{ $product['sales_count'] }}
                                            </span>
                                        </td>
                                        <td class="py-3 px-2 text-right font-bold text-gray-900 dark:text-gray-100">
                                            {{ number_format($product['revenue']) }}đ
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center py-8 text-gray-500 dark:text-gray-400">Chưa có dữ liệu</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Expiring Users -->
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700 p-6">
                    <h4 class="text-xl font-bold text-gray-900 dark:text-gray-100 mb-6 flex items-center space-x-2">
                        <svg class="w-5 h-5 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                        </svg>
                        <span>Người Dùng Sắp Hết Hạn (7 ngày)</span>
                    </h4>
                    <div class="space-y-3 max-h-[500px] overflow-y-auto scrollbar-thin scrollbar-thumb-gray-300 dark:scrollbar-thumb-gray-600">
                        @forelse($expiringUsers as $item)
                            <div class="p-4 rounded-xl border-l-4 {{ $item['days_remaining'] <= 2 ? 'border-red-500 bg-red-50 dark:bg-red-900/20' : 'border-amber-500 bg-amber-50 dark:bg-amber-900/20' }} hover:shadow-md transition-all">
                                <div class="flex items-center justify-between mb-2">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-10 h-10 rounded-full bg-gradient-to-br from-red-400 to-pink-500 flex items-center justify-center text-white font-bold">
                                            {{ strtoupper(substr($item['user']->name, 0, 1)) }}
                                        </div>
                                        <div>
                                            <p class="font-semibold text-gray-900 dark:text-gray-100">{{ $item['user']->name }}</p>
                                            <p class="text-xs text-gray-500 dark:text-gray-400">{{ $item['user']->email }}</p>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-lg font-bold {{ $item['days_remaining'] <= 2 ? 'text-red-600 dark:text-red-400' : 'text-amber-600 dark:text-amber-400' }}">
                                            {{ $item['days_remaining'] }} ngày
                                        </p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">
                                            {{ Carbon\Carbon::parse($item['expires_at'])->format('d/m/Y') }}
                                        </p>
                                    </div>
                                </div>
                                <div class="flex space-x-2 mt-3">
                                    <button onclick="quickExtend({{ $item['user']->id }}, 30)" class="flex-1 px-3 py-2 bg-green-500 hover:bg-green-600 text-white text-xs font-semibold rounded-lg transition-colors">
                                        Gia hạn 30 ngày
                                    </button>
                                    <button onclick="quickExtend({{ $item['user']->id }}, 90)" class="flex-1 px-3 py-2 bg-blue-500 hover:bg-blue-600 text-white text-xs font-semibold rounded-lg transition-colors">
                                        Gia hạn 90 ngày
                                    </button>
                                </div>
                            </div>
                        @empty
                            <p class="text-center text-gray-500 dark:text-gray-400 py-8">Không có người dùng nào sắp hết hạn</p>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Revenue Chart -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700 p-6">
                <h4 class="text-xl font-bold text-gray-900 dark:text-gray-100 mb-6 flex items-center space-x-2">
                    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                    </svg>
                    <span>Doanh Thu 7 Ngày Gần Đây</span>
                </h4>
                <div class="relative" style="height: 300px;">
                    <canvas id="revenueChart"></canvas>
                </div>
            </div>

        </div>
    </div>

    <!-- Chart.js Script -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
            
            toast.className = `flex items-center space-x-3 px-6 py-4 rounded-xl shadow-2xl border-2 ${colors[type]} transform transition-all duration-300 translate-x-full opacity-0`;
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

        // Quick Extend Function
        async function quickExtend(userId, days) {
            try {
                const response = await fetch(`/admin/quick-extend/${userId}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({ days })
                });

                const data = await response.json();
                
                if (data.success) {
                    showToast(data.message, 'success');
                    setTimeout(() => location.reload(), 1500);
                } else {
                    showToast('Có lỗi xảy ra', 'error');
                }
            } catch (error) {
                showToast('Có lỗi xảy ra', 'error');
            }
        }

        const isDarkMode = document.documentElement.classList.contains('dark');

        // User Distribution Pie Chart
        new Chart(document.getElementById('userDistributionChart'), {
            type: 'doughnut',
            data: {
                labels: ['Người dùng mới', 'Người dùng cũ', 'Đã hết hạn', 'Đã xóa'],
                datasets: [{
                    data: [
                        {{ $userDistribution['new'] }},
                        {{ $userDistribution['existing'] }},
                        {{ $userDistribution['expired'] }},
                        {{ $userDistribution['deleted'] }}
                    ],
                    backgroundColor: ['#3b82f6', '#10b981', '#ef4444', '#6b7280'],
                    borderWidth: 3,
                    borderColor: isDarkMode ? '#1f2937' : '#ffffff'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { position: 'bottom', labels: { color: isDarkMode ? '#e5e7eb' : '#374151', padding: 15, font: { size: 12 } } },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return ' ' + context.label + ': ' + context.parsed.toLocaleString() + ' người';
                            }
                        }
                    }
                }
            }
        });

        // Activity Status Pie Chart
        new Chart(document.getElementById('activityStatusChart'), {
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
                    backgroundColor: ['#10b981', '#14b8a6', '#f59e0b', '#ef4444'],
                    borderWidth: 3,
                    borderColor: isDarkMode ? '#1f2937' : '#ffffff'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { position: 'bottom', labels: { color: isDarkMode ? '#e5e7eb' : '#374151', padding: 15, font: { size: 12 } } },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return ' ' + context.label + ': ' + context.parsed.toLocaleString() + ' người';
                            }
                        }
                    }
                }
            }
        });

        // User Growth Line Chart
        const gradient = document.getElementById('userGrowthChart').getContext('2d').createLinearGradient(0, 0, 0, 300);
        gradient.addColorStop(0, 'rgba(99, 102, 241, 0.4)');
        gradient.addColorStop(1, 'rgba(236, 72, 153, 0.05)');

        new Chart(document.getElementById('userGrowthChart'), {
            type: 'line',
            data: {
                labels: ['T1','T2','T3','T4','T5','T6','T7','T8','T9','T10','T11','T12'],
                datasets: [{
                    label: 'Người dùng mới',
                    data: @json($growthData),
                    borderColor: '#6366f1',
                    backgroundColor: gradient,
                    fill: true,
                    tension: 0.4,
                    borderWidth: 3,
                    pointRadius: 5,
                    pointHoverRadius: 8,
                    pointBackgroundColor: '#6366f1',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: true, position: 'top', labels: { color: isDarkMode ? '#e5e7eb' : '#374151' } }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { color: isDarkMode ? 'rgba(55, 65, 81, 0.5)' : 'rgba(229, 231, 235, 0.8)' },
                        ticks: { color: isDarkMode ? '#9ca3af' : '#6b7280' }
                    },
                    x: {
                        grid: { color: isDarkMode ? 'rgba(55, 65, 81, 0.3)' : 'rgba(229, 231, 235, 0.5)' },
                        ticks: { color: isDarkMode ? '#9ca3af' : '#6b7280' }
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
                    backgroundColor: 'rgba(16, 185, 129, 0.7)',
                    borderColor: '#10b981',
                    borderWidth: 2,
                    borderRadius: 8
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return ' ' + context.parsed.y.toLocaleString('vi-VN') + 'đ';
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { color: isDarkMode ? 'rgba(55, 65, 81, 0.5)' : 'rgba(229, 231, 235, 0.8)' },
                        ticks: {
                            color: isDarkMode ? '#9ca3af' : '#6b7280',
                            callback: function(value) {
                                return value.toLocaleString('vi-VN') + 'đ';
                            }
                        }
                    },
                    x: {
                        grid: { display: false },
                        ticks: { color: isDarkMode ? '#9ca3af' : '#6b7280' }
                    }
                }
            }
        });

        @if(session('success'))
            showToast("{{ session('success') }}", 'success');
        @endif
        @if(session('error'))
            showToast("{{ session('error') }}", 'error');
        @endif
    </script>
</x-app-layout>