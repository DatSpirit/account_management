<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-3xl font-bold text-gray-900 dark:text-white">Welcome Back! 👋</h2>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Here's what's happening with your account today</p>
            </div>
            <div class="hidden sm:block">
                <span class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-indigo-600 to-purple-600 text-white text-sm font-semibold rounded-xl shadow-lg">
                    🎉 {{ $user->login_count ?? 0 }} Logins
                </span>
            </div>
        </div>
    </x-slot>

    <div class="space-y-6">
        
        {{-- Welcome Banner --}}
        <div class="bg-gradient-to-r from-indigo-600 via-purple-600 to-pink-600 dark:from-indigo-700 dark:via-purple-700 dark:to-pink-700 rounded-2xl shadow-xl p-8 text-white">
            <div class="flex flex-col md:flex-row items-center justify-between">
                <div class="mb-4 md:mb-0">
                    <h3 class="text-3xl font-bold mb-2">
                        Chào mừng, <span class="text-yellow-300">{{ $user->name }}</span>!
                    </h3>
                    <p class="text-indigo-100 text-lg">
                        Số lần đăng nhập: <span class="font-bold">{{ number_format($user->login_count ?? 0) }}</span> • 
                        Tài khoản được tạo {{ $user->created_at->diffForHumans() }}
                    </p>
                    <p class="text-sm text-indigo-200 mt-2">
                        Tài khoản hoạt động tốt. Hãy kiểm tra tổng quan giao dịch bên dưới.
                    </p>
                </div>
                <a href="{{ route('profile.edit') }}" 
                   class="inline-flex items-center px-6 py-3 bg-white text-indigo-600 rounded-xl font-semibold hover:bg-gray-100 transition-all duration-200 shadow-lg transform hover:scale-105">
                    ✏️ Cập nhật Hồ sơ
                </a>
            </div>
        </div>

        {{-- Stats Overview Grid --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            
            {{-- Total Spend Card --}}
            <div class="bg-gradient-to-br from-indigo-600 to-purple-700 dark:from-indigo-700 dark:to-purple-800 rounded-2xl shadow-xl p-6 text-white transform hover:scale-105 transition-all duration-300">
                <div class="flex items-center justify-between mb-4">
                    <div class="p-3 bg-white/20 backdrop-blur-sm rounded-xl">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
                <p class="text-sm opacity-90 mb-2 uppercase tracking-wider">Tổng Chi Tiêu</p>
                <p class="text-3xl font-bold">{{ number_format($stats['total_spend']) }}</p>
                <p class="text-xs opacity-75 mt-2">VND (Đã xác nhận)</p>
            </div>

            {{-- Success Transactions --}}
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-6 border border-gray-200 dark:border-gray-700 hover:shadow-xl transition-all duration-300 group">
                <div class="flex items-center justify-between mb-4">
                    <div class="p-3 bg-green-100 dark:bg-green-900/30 rounded-xl group-hover:scale-110 transition-transform">
                        <svg class="w-8 h-8 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <span class="text-xs font-semibold text-green-600 dark:text-green-400 bg-green-100 dark:bg-green-900/30 px-3 py-1.5 rounded-full">
                        {{ $stats['total_transactions'] > 0 ? round($stats['success'] / $stats['total_transactions'] * 100, 1) : 0 }}%
                    </span>
                </div>
                <p class="text-sm text-gray-600 dark:text-gray-400 mb-2 font-medium">Thành Công</p>
                <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ number_format($stats['success']) }}</p>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">Giao dịch</p>
            </div>

            {{-- Pending Transactions --}}
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-6 border border-gray-200 dark:border-gray-700 hover:shadow-xl transition-all duration-300 group">
                <div class="flex items-center justify-between mb-4">
                    <div class="p-3 bg-yellow-100 dark:bg-yellow-900/30 rounded-xl group-hover:scale-110 transition-transform">
                        <svg class="w-8 h-8 text-yellow-600 dark:text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <span class="text-xs font-semibold text-yellow-600 dark:text-yellow-400 bg-yellow-100 dark:bg-yellow-900/30 px-3 py-1.5 rounded-full">
                        {{ $stats['total_transactions'] > 0 ? round($stats['pending'] / $stats['total_transactions'] * 100, 1) : 0 }}%
                    </span>
                </div>
                <p class="text-sm text-gray-600 dark:text-gray-400 mb-2 font-medium">Chờ Thanh Toán</p>
                <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ number_format($stats['pending']) }}</p>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">Giao dịch</p>
            </div>

            {{-- Failed/Cancelled --}}
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-6 border border-gray-200 dark:border-gray-700 hover:shadow-xl transition-all duration-300 group">
                <div class="flex items-center justify-between mb-4">
                    <div class="p-3 bg-red-100 dark:bg-red-900/30 rounded-xl group-hover:scale-110 transition-transform">
                        <svg class="w-8 h-8 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <span class="text-xs font-semibold text-red-600 dark:text-red-400 bg-red-100 dark:bg-red-900/30 px-3 py-1.5 rounded-full">
                        {{ $stats['total_transactions'] > 0 ? round($stats['failed'] / $stats['total_transactions'] * 100, 1) : 0 }}%
                    </span>
                </div>
                <p class="text-sm text-gray-600 dark:text-gray-400 mb-2 font-medium">Thất Bại/Hủy</p>
                <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ number_format($stats['failed']) }}</p>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">Giao dịch</p>
            </div>
        </div>

        {{-- Charts Section --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            
            {{-- Pie Chart --}}
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-6 border border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"/>
                    </svg>
                    Thống Kê Đơn Hàng
                </h3>
                <div class="relative h-64">
                    <canvas id="transactionPieChart"></canvas>
                </div>
                <div class="mt-6 space-y-3">
                    <div class="flex items-center justify-between p-3 rounded-lg bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800">
                        <div class="flex items-center">
                            <span class="w-3 h-3 bg-green-500 rounded-full mr-3"></span>
                            <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Thành công</span>
                        </div>
                        <span class="font-bold text-gray-900 dark:text-white">{{ $stats['success'] }}</span>
                    </div>
                    <div class="flex items-center justify-between p-3 rounded-lg bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800">
                        <div class="flex items-center">
                            <span class="w-3 h-3 bg-yellow-500 rounded-full mr-3"></span>
                            <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Chờ xử lý</span>
                        </div>
                        <span class="font-bold text-gray-900 dark:text-white">{{ $stats['pending'] }}</span>
                    </div>
                    <div class="flex items-center justify-between p-3 rounded-lg bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800">
                        <div class="flex items-center">
                            <span class="w-3 h-3 bg-red-500 rounded-full mr-3"></span>
                            <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Thất bại</span>
                        </div>
                        <span class="font-bold text-gray-900 dark:text-white">{{ $stats['failed'] }}</span>
                    </div>
                </div>
            </div>

            {{-- Bar Chart --}}
            <div class="lg:col-span-2 bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-6 border border-gray-200 dark:border-gray-700">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white flex items-center">
                        <svg class="w-5 h-5 mr-2 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                        Biến Động Chi Tiêu 7 Ngày
                    </h3>
                    <span class="text-xs font-medium text-gray-500 dark:text-gray-400 bg-gray-100 dark:bg-gray-700 px-3 py-1.5 rounded-full">
                        📅 Last 7 days
                    </span>
                </div>
                <div class="h-64">
                    <canvas id="expenseBarChart"></canvas>
                </div>
                <p class="text-center text-xs text-gray-500 dark:text-gray-400 mt-4">
                    Thống kê chi tiêu dựa trên các giao dịch thành công trong 7 ngày gần nhất
                </p>
            </div>
        </div>

        {{-- Recent Activities & Products --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            
            {{-- Recent Products --}}
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700 p-6">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white flex items-center">
                        🛒 Sản Phẩm Đã Mua
                    </h3>
                    <a href="#" class="text-sm font-medium text-indigo-600 dark:text-indigo-400 hover:underline">
                        Xem tất cả →
                    </a>
                </div>
                <div class="space-y-3">
                    @forelse($productsBought as $transaction)
                        <div class="flex items-center justify-between p-4 rounded-xl hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors duration-200 border border-gray-100 dark:border-gray-700 group">
                            <div class="flex items-center space-x-4 flex-1 min-w-0">
                                <div class="w-12 h-12 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-xl flex items-center justify-center flex-shrink-0 group-hover:scale-110 transition-transform">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                                    </svg>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-semibold text-gray-900 dark:text-white truncate">
                                        {{ $transaction->product->name ?? 'Unknown Product' }}
                                    </p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 truncate">
                                        #{{ $transaction->order_code }} • {{ $transaction->created_at->diffForHumans() }}
                                    </p>
                                </div>
                            </div>
                            <div class="text-right flex-shrink-0 ml-4">
                                <p class="text-sm font-bold text-green-600 dark:text-green-400">
                                    {{ number_format($transaction->amount) }}
                                </p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">VND</p>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-16">
                            <div class="w-20 h-20 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mx-auto mb-4">
                                <svg class="w-10 h-10 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                                </svg>
                            </div>
                            <p class="text-gray-500 dark:text-gray-400 font-medium mb-3">Bạn chưa mua sản phẩm nào</p>
                            <a href="{{ route('products') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-xl transition-all">
                                Khám phá ngay →
                            </a>
                        </div>
                    @endforelse
                </div>
            </div>

            {{-- Activity Timeline --}}
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700 p-6">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white flex items-center">
                        📋 Lịch Sử Hoạt Động
                    </h3>
                    <button class="text-sm font-medium text-indigo-600 dark:text-indigo-400 hover:underline">
                        Làm mới
                    </button>
                </div>
                <div class="space-y-3">
                    @foreach ($activities as $activity)
                        <div class="flex items-start space-x-4 p-4 rounded-xl hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors duration-200 border border-gray-100 dark:border-gray-700">
                            <div class="flex-shrink-0">
                                <div class="w-10 h-10 bg-{{ $activity['color'] }}-100 dark:bg-{{ $activity['color'] }}-900/30 rounded-full flex items-center justify-center">
                                    <span class="text-lg">{{ $activity['icon'] }}</span>
                                </div>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-gray-900 dark:text-white">
                                    {{ $activity['desc'] }}
                                </p>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1" title="{{ $activity['real_time']->format('H:i:s d/m/Y') }}">
                                    {{ $activity['time'] }}
                                </p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- Quick Actions Banner --}}
        <div class="bg-gradient-to-r from-indigo-600 to-purple-600 rounded-2xl shadow-xl p-8 text-white">
            <div class="flex flex-col md:flex-row items-center justify-between">
                <div class="mb-4 md:mb-0 text-center md:text-left">
                    <h3 class="text-3xl font-bold mb-2">Sẵn sàng khám phá? 🚀</h3>
                    <p class="text-indigo-100 text-lg">Khám phá sản phẩm và ưu đãi tuyệt vời</p>
                </div>
                <div class="flex flex-col sm:flex-row gap-3">
                    <a href="{{ route('products') }}" class="px-6 py-3 bg-white text-indigo-600 rounded-xl font-semibold hover:bg-gray-100 transition-all duration-200 shadow-lg transform hover:scale-105 text-center">
                        🛍️ Xem Sản Phẩm
                    </a>
                    <a href="{{ route('profile.edit') }}" class="px-6 py-3 bg-white/20 backdrop-blur-sm text-white rounded-xl font-semibold hover:bg-white/30 transition-all duration-200 text-center">
                        ⚙️ Cài Đặt
                    </a>
                </div>
            </div>
        </div>

    </div>

    {{-- Chart.js Script --}}
    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Pie Chart
            const successCount = {{ $stats['success'] }};
            const pendingCount = {{ $stats['pending'] }};
            const failedCount = {{ $stats['failed'] }};
           // Tính tổng số giao dịch để phục vụ tính toán phần trăm
            const totalTransactions = successCount + pendingCount + failedCount;

            const pieCtx = document.getElementById('transactionPieChart')?.getContext('2d');
            if (pieCtx) {
                new Chart(pieCtx, {
                    type: 'doughnut',
                    data: {
                        labels: ['Thành công', 'Chờ xử lý', 'Thất bại'],
                        datasets: [{
                            data: [successCount, pendingCount, failedCount],
                            backgroundColor: ['#10B981', '#F59E0B', '#EF4444'],
                            borderWidth: 2,
                            hoverOffset: 8
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: { display: false },
                            tooltip: {
                                backgroundColor: 'rgba(0, 0, 0, 0.8)',
                                padding: 12,
                                cornerRadius: 8,
                                titleFont: { size: 14, weight: 'bold' },
                                bodyFont: { size: 13 },
                                callbacks: {
                                    label: function(context) {
                                        const count = context.parsed; // Số lượng giao dịch
                                    let percentage = 0;

                                    if (totalTransactions > 0) {
                                        // Tính tỉ lệ phần trăm và làm tròn 1 chữ số thập phân
                                        percentage = ((count / totalTransactions) * 100).toFixed(1);
                                    }
                                        return context.label + ': ' + count + ' GD (' + percentage + '%)';
                                    }
                                }
                            }
                        },
                        cutout: '70%'
                    }
                });
            }

            // Bar Chart
            const barCtx = document.getElementById('expenseBarChart')?.getContext('2d');
            if (barCtx) {
                new Chart(barCtx, {
                    type: 'bar',
                    data: {
                        labels: {!! json_encode($chartLabels) !!},
                        datasets: [{
                            label: 'Tổng Chi Tiêu (VND)',
                            data: {!! json_encode($chartTotals) !!},
                            backgroundColor: 'rgba(99, 102, 241, 0.8)',
                            borderColor: 'rgba(79, 70, 229, 1)',
                            borderWidth: 2,
                            borderRadius: 6,
                            hoverBackgroundColor: 'rgba(79, 70, 229, 1)'
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    callback: function(value) {
                                        return value.toLocaleString('vi-VN');
                                    }
                                },
                                grid: {
                                    color: 'rgba(0, 0, 0, 0.05)'
                                }
                            },
                            x: {
                                grid: { display: false }
                            }
                        },
                        plugins: {
                            legend: { display: false },
                            tooltip: {
                                backgroundColor: 'rgba(0, 0, 0, 0.8)',
                                padding: 12,
                                cornerRadius: 8,
                                titleFont: { size: 14, weight: 'bold' },
                                bodyFont: { size: 13 },
                                callbacks: {
                                    label: function(context) {
                                        return 'Chi tiêu: ' + context.parsed.y.toLocaleString('vi-VN') + ' VND';
                                    }
                                }
                            }
                        }
                    }
                });
            }
        });
    </script>
    @endpush
</x-app-layout>