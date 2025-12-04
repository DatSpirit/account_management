<x-app-layout>
    <!-- Custom Scrollbar Style -->
    <style>
        .custom-scrollbar::-webkit-scrollbar { width: 6px; height: 6px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background-color: #cbd5e1; border-radius: 20px; }
        .dark .custom-scrollbar::-webkit-scrollbar-thumb { background-color: #4b5563; }
    </style>

    <!-- Toast Container -->
    <div id="toast-container" class="fixed top-20 right-4 z-[100] space-y-3 pointer-events-none">
        <!-- Toast items will be injected here via JS -->
    </div>

    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div class="flex items-center space-x-3">
                <div class="p-2 bg-indigo-600 rounded-lg shadow-lg shadow-indigo-500/30">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                    </svg>
                </div>
                <h2 class="font-bold text-2xl text-gray-800 dark:text-white tracking-tight">
                    Admin Dashboard
                </h2>
            </div>

            <!-- Period Filter -->
            {{-- <div class="flex items-center bg-white dark:bg-gray-800 rounded-lg p-1 shadow-sm border border-gray-200 dark:border-gray-700">
                <span class="text-xs font-medium text-gray-500 dark:text-gray-400 px-3 uppercase">Lọc theo:</span>
                <select id="periodFilter"
                    class="border-none bg-transparent text-sm font-semibold text-gray-700 dark:text-gray-200 focus:ring-0 cursor-pointer py-1 pl-0 pr-8"
                    onchange="window.location.href='?period='+this.value">
                    <option value="1" {{ $period == 1 ? 'selected' : '' }}>24 giờ qua</option>
                    <option value="7" {{ $period == 7 ? 'selected' : '' }}>7 ngày qua</option>
                    <option value="30" {{ $period == 30 ? 'selected' : '' }}>30 ngày qua</option>
                    <option value="90" {{ $period == 90 ? 'selected' : '' }}>90 ngày qua</option>
                </select>
            </div> --}}
        </div>
    </x-slot>

    <div class="py-8 px-4 sm:px-6 lg:px-8 min-h-screen bg-gray-50 dark:bg-gray-900 transition-colors duration-300">
        <div class="max-w-[1920px] mx-auto space-y-8">

            <!-- 1. HEADER STATS -->
            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6">
                <!-- Total Users -->
                <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-sm border border-gray-100 dark:border-gray-700 hover:shadow-md transition">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Tổng Người Dùng</p>
                            <h3 class="text-3xl font-bold text-gray-800 dark:text-white mt-2">{{ number_format($totalUsers) }}</h3>
                            <span class="flex items-center mt-2 text-xs font-medium {{ $isGrowth ? 'text-green-600' : 'text-red-600' }}">
                                {{ $isGrowth ? '↑' : '↓' }} {{ abs($growthPercentage) }}% 
                                <span class="text-gray-400 font-normal ml-1">vs tháng trước</span>
                            </span>
                        </div>
                        <div class="p-3 bg-blue-50 dark:bg-blue-900/20 rounded-xl text-blue-600 dark:text-blue-400">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20"><path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"/></svg>
                        </div>
                    </div>
                </div>

                <!-- Total Revenue -->
                <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-sm border border-gray-100 dark:border-gray-700 hover:shadow-md transition">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Doanh Thu</p>
                            <h3 class="text-3xl font-bold text-gray-800 dark:text-white mt-2">{{ number_format($transactionStats['total_revenue']) }}</h3>
                            <span class="flex items-center mt-2 text-xs text-gray-500 dark:text-gray-400">
                                Hôm nay: <b class="text-green-600 ml-1">{{ number_format($transactionStats['today_revenue']) }}đ</b>
                            </span>
                        </div>
                        <div class="p-3 bg-green-50 dark:bg-green-900/20 rounded-xl text-green-600 dark:text-green-400">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20"><path d="M8.433 7.418c.155-.103.346-.196.567-.267v1.698a2.305 2.305 0 01-.567-.267C8.07 8.34 8 8.114 8 8c0-.114.07-.34.433-.582zM11 12.849v-1.698c.22.071.412.164.567.267.364.243.433.468.433.582 0 .114-.07.34-.433.582a2.305 2.305 0 01-.567.267z"/><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-13a1 1 0 10-2 0v.092a4.535 4.535 0 00-1.676.662C6.602 6.234 6 7.009 6 8c0 .99.602 1.765 1.324 2.246.48.32 1.054.545 1.676.662v1.941c-.391-.127-.68-.317-.843-.504a1 1 0 10-1.51 1.31c.562.649 1.413 1.076 2.353 1.253V15a1 1 0 102 0v-.092a4.535 4.535 0 001.676-.662C13.398 13.766 14 12.991 14 12c0-.99-.602-1.765-1.324-2.246A4.535 4.535 0 0011 9.092V7.151c.391.127.68.317.843.504a1 1 0 101.511-1.31c-.563-.649-1.413-1.076-2.354-1.253V5z" clip-rule="evenodd"/></svg>
                        </div>
                    </div>
                </div>

                <!-- Total Orders -->
                <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-sm border border-gray-100 dark:border-gray-700 hover:shadow-md transition">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Tổng Đơn Hàng</p>
                            <h3 class="text-3xl font-bold text-gray-800 dark:text-white mt-2">{{ number_format($transactionStats['total']) }}</h3>
                            <span class="flex items-center mt-2 text-xs text-gray-500 dark:text-gray-400">
                                Thành công: <b class="text-purple-600 ml-1">{{ number_format($transactionStats['success']) }}</b>
                            </span>
                        </div>
                        <div class="p-3 bg-purple-50 dark:bg-purple-900/20 rounded-xl text-purple-600 dark:text-purple-400">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20"><path d="M3 1a1 1 0 000 2h1.22l.305 1.222a.997.997 0 00.01.042l1.358 5.43-.893.892C3.74 11.846 4.632 14 6.414 14H15a1 1 0 000-2H6.414l1-1H14a1 1 0 00.894-.553l3-6A1 1 0 0017 3H6.28l-.31-1.243A1 1 0 005 1H3zM16 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM6.5 18a1.5 1.5 0 100-3 1.5 1.5 0 000 3z"/></svg>
                        </div>
                    </div>
                </div>

                <!-- Pending Orders -->
                <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-sm border border-gray-100 dark:border-gray-700 hover:shadow-md transition">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Chờ Xử Lý</p>
                            <h3 class="text-3xl font-bold text-gray-800 dark:text-white mt-2">{{ number_format($transactionStats['pending']) }}</h3>
                            <span class="flex items-center mt-2 text-xs text-gray-500 dark:text-gray-400">
                                Thất bại: <b class="text-red-600 ml-1">{{ number_format($transactionStats['failed']) }}</b>
                            </span>
                        </div>
                        <div class="p-3 bg-amber-50 dark:bg-amber-900/20 rounded-xl text-amber-600 dark:text-amber-400">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/></svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 2. PIE CHARTS ROW -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- User Distribution Chart -->
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-6">
                    <h4 class="text-lg font-bold text-gray-800 dark:text-white mb-6 border-b pb-2 dark:border-gray-700">Phân Loại Người Dùng</h4>
                    <div class="flex flex-col md:flex-row items-center gap-6">
                        <div class="relative w-full md:w-1/2 h-64">
                            <canvas id="userDistributionChart"></canvas>
                        </div>
                        <div class="w-full md:w-1/2 space-y-3">
                            <div class="flex justify-between items-center p-2 bg-blue-50 dark:bg-blue-900/10 rounded">
                                <span class="text-sm text-gray-600 dark:text-gray-300 flex items-center gap-2"><span class="w-3 h-3 rounded-full bg-blue-500"></span> Mới</span>
                                <span class="font-bold text-blue-600">{{ number_format($userDistribution['new']) }}</span>
                            </div>
                            <div class="flex justify-between items-center p-2 bg-green-50 dark:bg-green-900/10 rounded">
                                <span class="text-sm text-gray-600 dark:text-gray-300 flex items-center gap-2"><span class="w-3 h-3 rounded-full bg-green-500"></span> Cũ</span>
                                <span class="font-bold text-green-600">{{ number_format($userDistribution['existing']) }}</span>
                            </div>
                            <div class="flex justify-between items-center p-2 bg-red-50 dark:bg-red-900/10 rounded">
                                <span class="text-sm text-gray-600 dark:text-gray-300 flex items-center gap-2"><span class="w-3 h-3 rounded-full bg-red-500"></span> Hết hạn</span>
                                <span class="font-bold text-red-600">{{ number_format($userDistribution['expired']) }}</span>
                            </div>
                            <div class="flex justify-between items-center p-2 bg-gray-50 dark:bg-gray-700/50 rounded">
                                <span class="text-sm text-gray-600 dark:text-gray-300 flex items-center gap-2"><span class="w-3 h-3 rounded-full bg-gray-500"></span> Đã xóa</span>
                                <span class="font-bold text-gray-600">{{ number_format($userDistribution['deleted']) }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Activity Status Chart -->
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-6">
                    <h4 class="text-lg font-bold text-gray-800 dark:text-white mb-6 border-b pb-2 dark:border-gray-700">Mức Độ Mua Hàng (365 ngày)</h4>
                    <div class="flex flex-col md:flex-row items-center gap-6">
                        <div class="relative w-full md:w-1/2 h-64">
                            <canvas id="activityStatusChart"></canvas>
                        </div>
                        <div class="w-full md:w-1/2 space-y-3">
                            <div class="flex justify-between items-center p-2 bg-emerald-50 dark:bg-emerald-900/10 rounded">
                                <div class="flex flex-col">
                                    <span class="text-sm font-medium text-gray-700 dark:text-gray-300 flex items-center gap-2"><span class="w-3 h-3 rounded-full bg-emerald-500"></span> Rất hoạt động</span>
                                    <span class="text-xs text-gray-400 ml-5">> 20 giao dịch</span>
                                </div>
                                <span class="font-bold text-emerald-600">{{ number_format($activityStatus['very_active']) }}</span>
                            </div>
                            <div class="flex justify-between items-center p-2 bg-teal-50 dark:bg-teal-900/10 rounded">
                                <div class="flex flex-col">
                                    <span class="text-sm font-medium text-gray-700 dark:text-gray-300 flex items-center gap-2"><span class="w-3 h-3 rounded-full bg-teal-500"></span> Hoạt động</span>
                                    <span class="text-xs text-gray-400 ml-5">5-20 giao dịch</span>
                                </div>
                                <span class="font-bold text-teal-600">{{ number_format($activityStatus['active']) }}</span>
                            </div>
                            <div class="flex justify-between items-center p-2 bg-amber-50 dark:bg-amber-900/10 rounded">
                                <div class="flex flex-col">
                                    <span class="text-sm font-medium text-gray-700 dark:text-gray-300 flex items-center gap-2"><span class="w-3 h-3 rounded-full bg-amber-500"></span> Ít mua sắm</span>
                                    <span class="text-xs text-gray-400 ml-5">1-4 giao dịch</span>
                                </div>
                                <span class="font-bold text-amber-600">{{ number_format($activityStatus['inactive']) }}</span>
                            </div>
                            <div class="flex justify-between items-center p-2 bg-gray-50 dark:bg-gray-700/50 rounded">
                                <span class="text-sm font-medium text-gray-700 dark:text-gray-300 flex items-center gap-2"><span class="w-3 h-3 rounded-full bg-gray-400"></span> Không giao dịch</span>
                                <span class="font-bold text-gray-600">{{ number_format($activityStatus['dormant']) }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 3. GROWTH CHART -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-6">
                <div class="flex items-center justify-between mb-6">
                    <h4 class="text-lg font-bold text-gray-800 dark:text-white flex items-center gap-2">
                        <span class="p-1.5 bg-indigo-100 dark:bg-indigo-900/50 rounded-md text-indigo-600 dark:text-indigo-400">
                             <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" /></svg>
                        </span>
                        Tăng Trưởng Người Dùng {{ date('Y') }}
                    </h4>
                </div>
                <div class="relative h-[350px] w-full">
                    <canvas id="userGrowthChart"></canvas>
                </div>
            </div>

            <!-- 4. TOP LISTS -->
            <div class="grid grid-cols-1 xl:grid-cols-2 gap-6">
                <!-- Top Buyers -->
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-6 flex flex-col h-full">
                    <div class="flex justify-between items-center mb-4 border-b pb-2 dark:border-gray-700">
                        <h4 class="text-lg font-bold text-gray-800 dark:text-white">Top 10 Mua Nhiều Nhất</h4>
                        <span class="text-xs bg-purple-100 text-purple-600 px-2 py-1 rounded dark:bg-purple-900/30 dark:text-purple-400">Số lượng đơn</span>
                    </div>
                    <div class="overflow-y-auto max-h-[400px] pr-2 space-y-2 custom-scrollbar">
                        @forelse($topBuyers as $index => $buyer)
                            <div class="flex items-center justify-between p-3 rounded-xl hover:bg-gray-50 dark:hover:bg-gray-700/50 transition border border-transparent hover:border-gray-100 dark:hover:border-gray-600">
                                <div class="flex items-center space-x-3">
                                    <div class="w-8 h-8 rounded-full flex items-center justify-center text-sm font-bold
                                        {{ $index == 0 ? 'bg-yellow-100 text-yellow-700' : ($index == 1 ? 'bg-gray-200 text-gray-700' : ($index == 2 ? 'bg-orange-100 text-orange-700' : 'bg-gray-100 text-gray-500')) }}">
                                        {{ $index + 1 }}
                                    </div>
                                    <div class="overflow-hidden">
                                        <p class="font-semibold text-sm text-gray-900 dark:text-gray-100 truncate w-32 md:w-48">{{ $buyer['user']->name ?? 'N/A' }}</p>
                                        <p class="text-xs text-gray-500 truncate">{{ $buyer['user']->email }}</p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <p class="font-bold text-purple-600 dark:text-purple-400">{{ $buyer['purchase_count'] }} đơn</p>
                                    <p class="text-xs text-gray-400">{{ number_format($buyer['total_spent']) }}đ</p>
                                </div>
                            </div>
                        @empty
                            <p class="text-center text-gray-400 py-4">Chưa có dữ liệu</p>
                        @endforelse
                    </div>
                </div>

                <!-- Top Spenders -->
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-6 flex flex-col h-full">
                    <div class="flex justify-between items-center mb-4 border-b pb-2 dark:border-gray-700">
                        <h4 class="text-lg font-bold text-gray-800 dark:text-white">Top 10 Tiêu Tiền</h4>
                        <span class="text-xs bg-green-100 text-green-600 px-2 py-1 rounded dark:bg-green-900/30 dark:text-green-400">Doanh số</span>
                    </div>
                    <div class="overflow-y-auto max-h-[400px] pr-2 space-y-2 custom-scrollbar">
                        @forelse($topSpenders as $index => $spender)
                            <div class="flex items-center justify-between p-3 rounded-xl hover:bg-gray-50 dark:hover:bg-gray-700/50 transition border border-transparent hover:border-gray-100 dark:hover:border-gray-600">
                                <div class="flex items-center space-x-3">
                                     <div class="w-8 h-8 rounded-full flex items-center justify-center text-sm font-bold
                                        {{ $index == 0 ? 'bg-yellow-100 text-yellow-700' : ($index == 1 ? 'bg-gray-200 text-gray-700' : ($index == 2 ? 'bg-orange-100 text-orange-700' : 'bg-gray-100 text-gray-500')) }}">
                                        {{ $index + 1 }}
                                    </div>
                                    <div class="overflow-hidden">
                                        <p class="font-semibold text-sm text-gray-900 dark:text-gray-100 truncate w-32 md:w-48">{{ $spender['user']->name ?? 'N/A' }}</p>
                                        <p class="text-xs text-gray-500 truncate">{{ $spender['user']->email }}</p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <p class="font-bold text-green-600 dark:text-green-400">{{ number_format($spender['total_spent']) }}đ</p>
                                    <p class="text-xs text-gray-400">{{ $spender['purchase_count'] }} đơn</p>
                                </div>
                            </div>
                        @empty
                            <p class="text-center text-gray-400 py-4">Chưa có dữ liệu</p>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- 5. PRODUCTS & EXPIRING USERS -->
            <div class="grid grid-cols-1 xl:grid-cols-2 gap-6">
                <!-- Top Products -->
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-6">
                    <h4 class="text-lg font-bold text-gray-800 dark:text-white mb-4 border-b pb-2 dark:border-gray-700">Sản Phẩm Bán Chạy</h4>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="text-xs text-gray-500 dark:text-gray-400 uppercase border-b dark:border-gray-700">
                                    <th class="py-2 px-1">#</th>
                                    <th class="py-2">Tên SP</th>
                                    <th class="py-2 text-center">SL</th>
                                    <th class="py-2 text-right">Doanh thu</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                                @forelse($topProducts as $index => $product)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50">
                                        <td class="py-3 px-1">
                                            <span class="inline-flex justify-center items-center w-6 h-6 rounded-full text-xs font-bold {{ $index < 3 ? 'bg-orange-100 text-orange-600' : 'bg-gray-100 text-gray-500' }}">
                                                {{ $index + 1 }}
                                            </span>
                                        </td>
                                        <td class="py-3 pr-2">
                                            <p class="text-sm font-semibold text-gray-800 dark:text-gray-200 line-clamp-1">{{ $product['product']->name ?? 'N/A' }}</p>
                                        </td>
                                        <td class="py-3 text-center">
                                            <span class="px-2 py-1 rounded text-xs bg-gray-100 dark:bg-gray-700">{{ $product['sales_count'] }}</span>
                                        </td>
                                        <td class="py-3 text-right font-bold text-gray-700 dark:text-gray-300">
                                            {{ number_format($product['revenue']) }}đ
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="4" class="text-center py-4 text-gray-400">Chưa có dữ liệu</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Expiring Users -->
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-6">
                    <div class="flex justify-between items-center mb-4 border-b pb-2 dark:border-gray-700">
                        <h4 class="text-lg font-bold text-gray-800 dark:text-white">Sắp Hết Hạn (7 ngày)</h4>
                        <span class="text-xs text-red-500 animate-pulse font-medium">Cần chú ý</span>
                    </div>
                    
                    <div class="space-y-3 overflow-y-auto max-h-[400px] custom-scrollbar">
                        @forelse($expiringUsers as $item)
                            <div class="bg-gray-50 dark:bg-gray-700/30 rounded-xl p-3 border {{ $item['days_remaining'] <= 2 ? 'border-red-200 dark:border-red-800' : 'border-gray-200 dark:border-gray-600' }}">
                                <div class="flex justify-between items-start mb-2">
                                    <div>
                                        <p class="font-bold text-sm text-gray-800 dark:text-white">{{ $item['user']->name }}</p>
                                        <p class="text-xs text-gray-500">{{ $item['user']->email }}</p>
                                    </div>
                                    <div class="text-right">
                                        <span class="text-xs font-bold {{ $item['days_remaining'] <= 2 ? 'text-red-600' : 'text-amber-600' }}">
                                            Còn {{ $item['days_remaining'] }} ngày
                                        </span>
                                        <p class="text-[10px] text-gray-400">{{ Carbon\Carbon::parse($item['expires_at'])->format('d/m/Y') }}</p>
                                    </div>
                                </div>
                                <div class="grid grid-cols-2 gap-2 mt-2">
                                     <button onclick="handleQuickExtend({{ $item['user']->id }}, 30, this)"
                                        data-user-name="{{ $item['user']->name }}"
                                        class="px-2 py-1.5 bg-white dark:bg-gray-600 border border-gray-200 dark:border-gray-500 rounded text-xs font-medium text-gray-700 dark:text-gray-200 hover:bg-emerald-50 hover:text-emerald-600 hover:border-emerald-200 transition">
                                        +30 Ngày
                                    </button>
                                     <button onclick="handleQuickExtend({{ $item['user']->id }}, 90, this)"
                                        data-user-name="{{ $item['user']->name }}"
                                        class="px-2 py-1.5 bg-white dark:bg-gray-600 border border-gray-200 dark:border-gray-500 rounded text-xs font-medium text-gray-700 dark:text-gray-200 hover:bg-blue-50 hover:text-blue-600 hover:border-blue-200 transition">
                                        +90 Ngày
                                    </button>
                                </div>
                            </div>
                        @empty
                            <div class="flex flex-col items-center justify-center h-40 text-gray-400">
                                <span class="text-sm">Không có ai sắp hết hạn</span>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- 6. REVENUE CHART -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-6">
                <div class="flex items-center justify-between mb-6">
                    <h4 class="text-lg font-bold text-gray-800 dark:text-white flex items-center gap-2">
                        <span class="p-1.5 bg-green-100 dark:bg-green-900/50 rounded-md text-green-600 dark:text-green-400">
                             <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        </span>
                        Doanh Thu 7 Ngày Qua
                    </h4>
                </div>
                <div class="relative h-[300px] w-full">
                    <canvas id="revenueChart"></canvas>
                </div>
            </div>

        </div>
    </div>

    <!-- QUICK EXTEND MODAL (Ẩn mặc định) -->
    <div id="quickExtendModal" class="fixed inset-0 z-[150] hidden items-center justify-center bg-gray-900/60 backdrop-blur-sm p-4 transition-all">
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl w-full max-w-md overflow-hidden transform transition-all scale-100">
            <div class="p-6 border-b border-gray-100 dark:border-gray-700 flex justify-between items-center bg-gray-50 dark:bg-gray-700/50">
                <h3 class="text-lg font-bold text-gray-900 dark:text-white">Gia Hạn Nhanh</h3>
                <button onclick="closeQuickExtendModal()" class="text-gray-400 hover:text-gray-500 dark:hover:text-gray-300">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                </button>
            </div>
            <form id="quickExtendForm" onsubmit="handleQuickExtend(event)" class="p-6">
                @csrf
                <input type="hidden" id="modalUserId">
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tài khoản:</label>
                        <p id="modalUserName" class="text-lg font-bold text-gray-900 dark:text-white bg-gray-50 dark:bg-gray-700 px-3 py-2 rounded-lg"></p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Hiện tại:</label>
                        <p id="modalExpiresAt" class="text-sm text-amber-600 dark:text-amber-400 font-medium"></p>
                    </div>
                    <div>
                         <label for="days" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Số ngày gia hạn thêm:</label>
                        <input type="number" id="days" name="days" value="30" min="1" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-indigo-500 focus:border-indigo-500 shadow-sm">
                        <p id="daysError" class="mt-1 text-sm text-red-600 hidden"></p>
                    </div>
                </div>
                <div class="mt-6 flex gap-3">
                    <button type="button" onclick="closeQuickExtendModal()" class="flex-1 px-4 py-2 bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-200 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-600 font-medium">Hủy</button>
                    <button type="submit" id="submitButton" class="flex-1 px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 font-medium shadow-lg shadow-indigo-500/30">Xác nhận</button>
                </div>
            </form>
        </div>
    </div>

    <!-- JAVASCRIPT LOGIC -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // 1. CẤU HÌNH CHART.JS CHUNG
        const isDarkMode = document.documentElement.classList.contains('dark');
        Chart.defaults.font.family = "'Inter', sans-serif";
        Chart.defaults.color = isDarkMode ? '#9ca3af' : '#6b7280';
        Chart.defaults.borderColor = isDarkMode ? 'rgba(255, 255, 255, 0.05)' : 'rgba(0, 0, 0, 0.05)';

        // 2. TOAST FUNCTION
        function showToast(message, type) {
            const container = document.getElementById('toast-container');
            const isSuccess = type === 'success';
            const bgColor = isSuccess ? 'bg-white dark:bg-gray-800 border-l-4 border-green-500' : 'bg-white dark:bg-gray-800 border-l-4 border-red-500';
            const iconColor = isSuccess ? 'text-green-500' : 'text-red-500';
            const iconPath = isSuccess ?
                '<path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />' :
                '<path stroke-linecap="round" stroke-linejoin="round" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />';

            const toast = document.createElement('div');
            toast.className = `pointer-events-auto flex items-center w-full max-w-sm p-4 rounded-lg shadow-xl ${bgColor} transform transition-all duration-500 translate-x-full opacity-0`;
            toast.innerHTML = `
                <div class="inline-flex items-center justify-center flex-shrink-0 w-8 h-8 ${iconColor} bg-opacity-10 rounded-lg">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">${iconPath}</svg>
                </div>
                <div class="ml-3 text-sm font-medium text-gray-800 dark:text-gray-200">${message}</div>
                <button type="button" class="ml-auto -mx-1.5 -my-1.5 rounded-lg focus:ring-2 focus:ring-gray-300 p-1.5 hover:bg-gray-100 dark:hover:bg-gray-700 inline-flex h-8 w-8 text-gray-400 hover:text-gray-900 dark:hover:text-white" onclick="this.closest('div').remove()">
                    <span class="sr-only">Close</span>
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                </button>
            `;
            container.appendChild(toast);
            requestAnimationFrame(() => toast.classList.remove('translate-x-full', 'opacity-0'));
            setTimeout(() => {
                toast.classList.add('opacity-0', 'translate-x-full');
                setTimeout(() => toast.remove(), 500);
            }, 4000);
        }

        // 3. QUICK EXTEND LOGIC
        function closeQuickExtendModal() {
            const modal = document.getElementById('quickExtendModal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }
 // Chức năng này dùng nếu muốn mở modal từ danh sách user đầy đủ - giữ lại để tái sử dụng)
        // function openQuickExtendModal(button) {
        //    
        //     document.getElementById('quickExtendModal').classList.remove('hidden');
        //     document.getElementById('quickExtendModal').classList.add('flex');
        // }

        async function handleQuickExtend(event, days = null, btn = null) {
            // Xác định gọi từ nút nhanh hay từ Form Modal
            let userId, extendDays;
            
            if (btn) {
                // Gọi từ nút nhanh (+30/+90)
                event.preventDefault(); // Ngăn sự kiện mặc định của nút
                userId = arguments[0];
                extendDays = arguments[1];
                
                // Hiệu ứng loading cho nút
                const originalText = btn.innerHTML;
                btn.innerHTML = '...';
                btn.disabled = true;
                
                await processExtend(userId, extendDays, () => {
                     btn.innerHTML = originalText;
                     btn.disabled = false;
                });
            } else {
                // Gọi từ Form Modal 
                event.preventDefault();
                userId = document.getElementById('modalUserId').value;
                extendDays = document.getElementById('days').value;
                const submitBtn = document.getElementById('submitButton');
                
                submitBtn.disabled = true;
                submitBtn.textContent = 'Đang xử lý...';
                
                await processExtend(userId, extendDays, () => {
                     submitBtn.disabled = false;
                     submitBtn.textContent = 'Xác nhận';
                     closeQuickExtendModal();
                });
            }
        }

        async function processExtend(userId, days, callback) {
            const csrfToken = document.querySelector('input[name="_token"]').value;
            // Thay thế URL PLACEHOLDER
            const url = "{{ route('admin.quick-extend', ['userId' => 'PLACEHOLDER']) }}".replace('PLACEHOLDER', userId);

            try {
                 const response = await fetch(url, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': csrfToken },
                    body: JSON.stringify({ days: days })
                });
                const data = await response.json();
                
                if(response.ok) {
                    showToast('Gia hạn thành công!', 'success');
                    setTimeout(() => location.reload(), 1000);
                } else {
                    showToast(data.message || 'Lỗi xử lý', 'error');
                }
            } catch(e) {
                console.error(e);
                showToast('Lỗi kết nối máy chủ', 'error');
            } finally {
                if(callback) callback();
            }
        }

        // 4. CHART RENDERERS
        
        // --- Pie Chart: User Distribution ---
        new Chart(document.getElementById('userDistributionChart'), {
            type: 'doughnut',
            data: {
                labels: ['Mới', 'Cũ', 'Hết hạn', 'Đã xóa'],
                datasets: [{
                    data: [{{ $userDistribution['new'] }}, {{ $userDistribution['existing'] }}, {{ $userDistribution['expired'] }}, {{ $userDistribution['deleted'] }}],
                    backgroundColor: ['#3b82f6', '#10b981', '#ef4444', '#6b7280'],
                    borderWidth: 0,
                    hoverOffset: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '75%',
                plugins: { legend: { display: false } }
            }
        });

        // --- Pie Chart: Activity Status ---
        new Chart(document.getElementById('activityStatusChart'), {
            type: 'doughnut',
            data: {
                labels: ['Rất hoạt động', 'Hoạt động', 'Ít', 'Không'],
                datasets: [{
                    data: [{{ $activityStatus['very_active'] }}, {{ $activityStatus['active'] }}, {{ $activityStatus['inactive'] }}, {{ $activityStatus['dormant'] }}],
                    backgroundColor: ['#10b981', '#14b8a6', '#f59e0b', '#9ca3af'],
                    borderWidth: 0,
                    hoverOffset: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '75%',
                plugins: { legend: { display: false } }
            }
        });

        // --- Line Chart: Growth (Gradient) ---
        const ctxGrowth = document.getElementById('userGrowthChart').getContext('2d');
        const gradientGrowth = ctxGrowth.createLinearGradient(0, 0, 0, 350);
        gradientGrowth.addColorStop(0, 'rgba(99, 102, 241, 0.4)');
        gradientGrowth.addColorStop(1, 'rgba(99, 102, 241, 0.0)');

        new Chart(ctxGrowth, {
            type: 'line',
            data: {
                labels: ['T1', 'T2', 'T3', 'T4', 'T5', 'T6', 'T7', 'T8', 'T9', 'T10', 'T11', 'T12'],
                datasets: [{
                    label: 'Người dùng mới',
                    data: @json($growthData),
                    borderColor: '#6366f1',
                    backgroundColor: gradientGrowth,
                    fill: true,
                    tension: 0.4,
                    pointRadius: 4,
                    pointHoverRadius: 6,
                    pointBackgroundColor: '#6366f1',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    y: { beginAtZero: true, grid: { borderDash: [4, 4], color: isDarkMode ? '#374151' : '#e5e7eb' } },
                    x: { grid: { display: false } }
                }
            }
        });

        // --- Bar Chart: Revenue ---
        new Chart(document.getElementById('revenueChart'), {
            type: 'bar',
            data: {
                labels: @json($revenueChart->pluck('date')->map(fn($d) => \Carbon\Carbon::parse($d)->format('d/m'))),
                datasets: [{
                    label: 'Doanh thu',
                    data: @json($revenueChart->pluck('revenue')),
                    backgroundColor: '#10b981',
                    borderRadius: 4,
                    barPercentage: 0.6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    y: { 
                        beginAtZero: true, 
                        grid: { borderDash: [4, 4], color: isDarkMode ? '#374151' : '#e5e7eb' },
                        ticks: { callback: (val) => val.toLocaleString() + 'đ' }
                    },
                    x: { grid: { display: false } }
                }
            }
        });

        // Display Session Messages if any
        @if(session('success')) showToast("{{ session('success') }}", 'success'); @endif
        @if(session('error')) showToast("{{ session('error') }}", 'error'); @endif
    </script>
</x-app-layout>