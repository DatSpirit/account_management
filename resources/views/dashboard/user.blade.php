<x-app-layout>
    {{-- Container cho Toast Notification --}}
    <div id="toast-container" class="fixed top-4 right-4 z-[100] space-y-3"></div>
    
    <x-slot name="header">
        <div class="border-b border-gray-200 dark:border-gray-700 pb-3">
            <h2 class="font-extrabold text-3xl text-gray-900 dark:text-gray-50 leading-tight tracking-wider">
                {{ __('BẢNG ĐIỀU KHIỂN CÁ NHÂN') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-8 px-4 sm:px-6 lg:px-8 min-h-screen bg-gray-50 dark:bg-gray-900 transition-colors duration-300">
        <div class="max-w-7xl mx-auto space-y-8">

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl rounded-2xl p-6 sm:p-8 border border-gray-200 dark:border-gray-700">
                <div class="mb-6">
                    <h3 class="text-3xl sm:text-4xl font-black text-gray-900 dark:text-white mb-3">
                    Chào mừng, <span class="text-indigo-600 dark:text-indigo-400">{{ $user->name }}</span>
                    </h3>
                    
                    <div class="md:flex md:justify-between md:items-center p-5 rounded-xl border border-emerald-400 dark:border-emerald-600 bg-emerald-50 dark:bg-emerald-900/40">
                        <div class="md:w-3/4">
                            
                            {{-- THÔNG TIN LẦN ĐĂNG NHẬP GẦN NHẤT --}}
                            {{-- <p class="text-lg text-emerald-800 dark:text-emerald-200 font-bold">
                                Lần đăng nhập gần nhất: 
                                <span class="text-emerald-900 dark:text-emerald-50">
                                    @if(property_exists($user, 'last_login_at') && $user->last_login_at)
                                        {{ $user->last_login_at->format('H:i:s d/m/Y') }}
                                    @else
                                        Chưa có dữ liệu
                                    @endif
                                </span>
                            </p> --}}

                            {{-- THÊM SỐ LẦN ĐĂNG NHẬP  --}}
                            <p class="text-lg text-emerald-800 dark:text-emerald-200 font-bold mb-2">
                                Số lần đăng nhập: 
                                <span class="text-emerald-900 dark:text-emerald-50">
                                    {{ number_format($user->login_count ?? 0) }}
                                </span>
                            </p>
                            
                            {{-- THÔNG TIN CHUNG  --}}
                            <p class="text-sm text-gray-700 dark:text-gray-300">
                                Tài khoản hoạt động tốt. Được tạo {{ $user->created_at->diffForHumans() }}. Hãy kiểm tra tổng quan giao dịch.
                            </p>
                        </div>
                        <div class="mt-4 md:mt-0 md:w-1/4 md:text-right">
                            <a href="{{ route('profile.edit') }}" 
                                class="inline-flex items-center justify-center 
                                        bg-indigo-600 hover:bg-indigo-700 text-white 
                                        font-medium text-sm px-4 py-2 rounded-lg 
                                        shadow transition-all duration-300">
                                ✏️ Cập nhật Hồ sơ
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                
                <div class="lg:col-span-1 bg-gradient-to-br from-indigo-600 to-purple-700 dark:from-indigo-700 dark:to-purple-800 rounded-2xl shadow-xl p-6 text-white border border-indigo-500/50">
                    <div class="flex items-center justify-between">
                        <h4 class="text-lg font-semibold uppercase tracking-wider opacity-90">Tổng Chi Tiêu (VND)</h4>
                        <svg class="w-8 h-8 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8v8m0-8h.01M12 3v1m0 16v1m-7-5h-1M20 12h1M6.5 6.5l-.707-.707M17.5 6.5l.707-.707M6.5 17.5l-.707.707M17.5 17.5l.707.707" />
                        </svg>
                    </div>
                    <p class="mt-4 text-5xl font-extrabold">{{ number_format($stats['total_spend']) }}</p>
                    <p class="mt-3 text-sm opacity-80">Tổng chi tiêu đã được xác nhận</p>
                </div>

                <div class="lg:col-span-2 bg-white dark:bg-gray-800 rounded-2xl shadow-xl p-6 border border-gray-200 dark:border-gray-700">
                    <h4 class="text-xl font-bold text-gray-900 dark:text-white mb-4">Thống kê Đơn hàng</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 items-center">
                        {{-- Vùng Biểu đồ tròn (Sử dụng Chart.js) --}}
                        <div class="w-full h-48 flex items-center justify-center">
                            <canvas id="transactionPieChart" class="w-32 h-32"></canvas>
                            <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
                            @php
                                $successRate = $stats['total_transactions'] > 0 ? round($stats['success'] / $stats['total_transactions'] * 100, 1) : 0;
                            @endphp
                            <script>
                                document.addEventListener('DOMContentLoaded', function () {
                                    const ctx = document.getElementById('transactionPieChart').getContext('2d');
                                    new Chart(ctx, {
                                        type: 'doughnut',
                                        data: {
                                            labels: ['Đã Thanh Toán', 'Chờ Thanh Toán', 'Thất Bại/Hủy'],
                                            datasets: [{
                                                data: [{{ $stats['success'] }}, {{ $stats['pending'] }}, {{ $stats['failed'] }}],
                                                backgroundColor: ['#10B981', '#F59E0B', '#EF4444'], // green, yellow, red
                                                hoverOffset: 10
                                            }]
                                        },
                                        options: {
                                            responsive: true,
                                            maintainAspectRatio: false,
                                            plugins: {
                                                legend: {
                                                    display: false
                                                },
                                                tooltip: {
                                                    callbacks: {
                                                        label: function(context) {
                                                            let label = context.label || '';
                                                            if (label) {
                                                                label += ': ';
                                                            }
                                                            if (context.parsed !== null) {
                                                                label += context.parsed + ' GD';
                                                            }
                                                            return label;
                                                        }
                                                    }
                                                }
                                            },
                                            cutout: '70%',
                                        }
                                    });
                                });
                            </script>
                        </div>

                        {{-- Các chỉ số giao dịch --}}
                        <div class="space-y-3">
                            <div class="flex justify-between items-center pb-2 border-b border-gray-100 dark:border-gray-700">
                                <span class="text-sm font-medium text-green-600 dark:text-green-400">✅ Đã Thanh Toán</span>
                                <span class="text-lg font-bold text-gray-900 dark:text-white">
                                    {{ $stats['success'] }} GD ({{ $successRate }}%)
                                </span>
                            </div>
                            <div class="flex justify-between items-center pb-2 border-b border-gray-100 dark:border-gray-700">
                                <span class="text-sm font-medium text-yellow-600 dark:text-yellow-400">⏱ Chờ Thanh Toán</span>
                                <span class="text-lg font-bold text-gray-900 dark:text-white">
                                    {{ $stats['pending'] }} GD 
                                    ({{ $stats['total_transactions'] > 0 ? round($stats['pending'] / $stats['total_transactions'] * 100, 1) : 0 }}%)
                                </span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-sm font-medium text-red-600 dark:text-red-400">❌ Thất Bại/Hủy</span>
                                <span class="text-lg font-bold text-gray-900 dark:text-white">
                                    {{ $stats['failed'] }} GD
                                    ({{ $stats['total_transactions'] > 0 ? round($stats['failed'] / $stats['total_transactions'] * 100, 1) : 0 }}%)
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl border border-gray-200 dark:border-gray-700 p-6">
                    <h4 class="text-2xl font-bold text-gray-900 dark:text-white mb-6 border-b pb-2 border-gray-200 dark:border-gray-700">
                        🛒 Sản phẩm đã mua gần đây
                    </h4>
                    <ul class="divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse($productsBought as $transaction)
                            <li class="py-4 flex justify-between items-center group transition-colors duration-200 hover:bg-gray-50 dark:hover:bg-gray-700/50 -mx-6 px-6">
                                <div class="flex flex-col">
                                    <span class="text-sm font-semibold text-gray-900 dark:text-gray-100">
                                        {{ $transaction->product->name ?? 'Sản phẩm không xác định' }}
                                    </span>
                                    <span class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                        Đơn hàng #{{ $transaction->order_code }} - {{ $transaction->created_at->diffForHumans() }}
                                    </span>
                                </div>
                                <div class="text-right">
                                    <span class="text-base font-bold text-green-600 dark:text-green-400">
                                        {{ number_format($transaction->amount) }} VND
                                    </span>
                                    <span class="block text-xs text-gray-500 dark:text-gray-400 mt-1 uppercase">
                                        {{ ucfirst($transaction->status) }}
                                    </span>
                                </div>
                            </li>
                        @empty
                            <li class="py-10 text-center text-gray-500 dark:text-gray-400">Bạn chưa mua sản phẩm nào.</li>
                        @endforelse
                    </ul>
                    <a href="#" class="mt-4 block text-sm text-indigo-600 dark:text-indigo-400 font-medium hover:underline text-center">
                        Xem tất cả đơn hàng ({{ $stats['success'] }} giao dịch thành công)
                    </a>
                </div>
                
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl border border-gray-200 dark:border-gray-700 p-6">
                    <h4 class="text-2xl font-bold text-gray-900 dark:text-white mb-6 border-b pb-2 border-gray-200 dark:border-gray-700">
                        🔔 Lịch sử hoạt động
                    </h4>

                    <ul class="space-y-4">
                        @foreach ($activities as $activity)
                            <li class="p-4 bg-gray-50 dark:bg-gray-700/50 rounded-lg shadow-sm border border-gray-100 dark:border-gray-700 flex justify-between items-center transition-colors duration-200 hover:bg-gray-100 dark:hover:bg-gray-700">
                                <div class="flex items-center">
                                    <span class="text-{{ $activity['color'] }}-500 mr-4 text-xl">{{ $activity['icon'] }}</span>
                                    <span class="text-gray-800 dark:text-gray-200 font-medium text-sm">{{ $activity['desc'] }}</span>
                                </div>
                                <span class="text-xs text-gray-500 dark:text-gray-400" title="{{ $activity['real_time']->format('H:i:s d/m/Y') }}">
                                    {{ $activity['time'] }}
                                </span>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
            
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl border border-gray-200 dark:border-gray-700 p-6">
                <h4 class="text-2xl font-bold text-gray-900 dark:text-white mb-6 border-b pb-2 border-gray-200 dark:border-gray-700">
                    📈 Biến động Chi tiêu 7 ngày gần nhất
                </h4>
                <div class="w-full h-80">
                    {{-- Canvas để vẽ biểu đồ cột (Minh họa từ ảnh) --}}
                    <canvas id="expenseBarChart"></canvas>
                    <script>
                        document.addEventListener('DOMContentLoaded', function () {
                            const ctxBar = document.getElementById('expenseBarChart').getContext('2d');
                            new Chart(ctxBar, {
                                type: 'bar',
                                data: {
                                    labels: {!! json_encode($chartLabels) !!}, // Ngày (ví dụ: 07/11)
                                    datasets: [{
                                        label: 'Tổng Chi Tiêu (VND)',
                                        data: {!! json_encode($chartTotals) !!}, // Tổng số tiền
                                        backgroundColor: 'rgba(99, 102, 241, 0.8)', // indigo-500
                                        borderColor: 'rgba(79, 70, 229, 1)', // indigo-600
                                        borderWidth: 1,
                                        borderRadius: 6,
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
                                                    // Định dạng tiền tệ đơn giản (VD: 1.000.000)
                                                    return value.toLocaleString('vi-VN');
                                                }
                                            }
                                        }
                                    },
                                    plugins: {
                                        legend: {
                                            display: false
                                        },
                                        tooltip: {
                                            callbacks: {
                                                label: function(context) {
                                                    let label = context.dataset.label || '';
                                                    if (label) {
                                                        label += ': ';
                                                    }
                                                    if (context.parsed.y !== null) {
                                                        label += context.parsed.y.toLocaleString('vi-VN') + ' VND';
                                                    }
                                                    return label;
                                                }
                                            }
                                        }
                                    }
                                }
                            });
                        });
                    </script>
                </div>
                <p class="text-center text-sm text-gray-500 dark:text-gray-400 mt-4">
                    Thống kê chi tiêu dựa trên các giao dịch thành công trong 7 ngày gần nhất.
                </p>
            </div>
            
        </div>
    </div>
</x-app-layout>