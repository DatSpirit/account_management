<x-app-layout>
    <div class="min-h-screen bg-gray-50 dark:bg-gray-900 py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <!-- Header Section -->
            <div class="flex flex-col md:flex-row md:items-center justify-between mb-8 gap-4">
                <div>
                    <h2 class="text-3xl font-bold text-gray-900 dark:text-white flex items-center">
                        <svg class="w-8 h-8 mr-3 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                 d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                        </svg>
                        My Wallet
                    </h2>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Quản lý số dư và lịch sử giao dịch Coinkey</p>
                </div>
                <div class="flex gap-3">
                    <a href="{{ route('products') }}"
                        class="inline-flex items-center justify-center px-5 py-2.5 border border-transparent text-sm font-medium rounded-lg text-white bg-indigo-600 hover:bg-indigo-700 focus:ring-4 focus:ring-indigo-300 transition-all shadow-sm">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                        </svg>
                        Nạp Coinkey
                    </a>
                </div>
            </div>

            <!-- Stats Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
                <!-- Main Balance Card -->
                <div
                    class="lg:col-span-2 relative overflow-hidden bg-gradient-to-r from-indigo-600 to-blue-600 rounded-2xl shadow-xl p-8 text-white">
                    <div class="absolute top-0 right-0 -mt-4 -mr-4 w-32 h-32 bg-white opacity-10 rounded-full blur-2xl">
                    </div>
                    <div
                        class="absolute bottom-0 left-0 -mb-4 -ml-4 w-32 h-32 bg-indigo-400 opacity-20 rounded-full blur-2xl">
                    </div>

                    <div class="relative z-10 flex flex-col justify-between h-full">
                        <div>
                            <p class="text-indigo-100 font-medium text-sm uppercase tracking-wider mb-2">Số dư hiện tại
                            </p>
                            <div class="flex items-baseline gap-2">
                                <span
                                    class="text-5xl font-extrabold tracking-tight">{{ number_format($wallet->balance) }}</span>
                                <span class="text-xl font-semibold text-indigo-200">Coinkey</span>
                            </div>
                        </div>

                        <div class="mt-8 grid grid-cols-2 gap-8 border-t border-white/20 pt-6">
                            <div>
                                <p class="text-indigo-200 text-xs uppercase mb-1">Tổng nạp tích lũy</p>
                                <p class="text-xl font-bold">{{ number_format($wallet->total_deposited) }} <span
                                        class="text-xs font-normal opacity-70">VND</span></p>
                            </div>
                            <div>
                                <p class="text-indigo-200 text-xs uppercase mb-1">Tổng đã chi tiêu</p>
                                <p class="text-xl font-bold">{{ number_format($wallet->total_spent) }} <span
                                        class="text-xs font-normal opacity-70">Coinkey</span></p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions / Promo -->
                <div
                    class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 flex flex-col justify-center">
                    <h3 class="font-bold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                        <span class="p-2 bg-yellow-100 text-yellow-600 rounded-lg">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 10V3L4 14h7v7l9-11h-7z" />
                            </svg>
                        </span>
                        Dịch vụ nổi bật
                    </h3>
                    <div class="space-y-3">
                        <a href="{{ route('keys.create-custom') }}"
                            class="block p-3 rounded-xl bg-gray-50 dark:bg-gray-700/50 hover:bg-gray-100 dark:hover:bg-gray-700 transition group">
                            <div class="flex justify-between items-center">
                                <div>
                                    <p class="font-semibold text-gray-900 dark:text-white text-sm">Tạo Key Tùy Chỉnh</p>
                                    <p class="text-xs text-gray-500">Tự chọn thời hạn sử dụng</p>
                                </div>
                                <span class="text-gray-400 group-hover:text-indigo-600 transition">→</span>
                            </div>
                        </a>
                        <a href="{{ route('wallet.buy-package') }}"
                            class="block p-3 rounded-xl bg-gray-50 dark:bg-gray-700/50 hover:bg-gray-100 dark:hover:bg-gray-700 transition group">
                            <div class="flex justify-between items-center">
                                <div>
                                    <p class="font-semibold text-gray-900 dark:text-white text-sm">Mua Gói Ưu Đãi</p>
                                    <p class="text-xs text-gray-500">Tiết kiệm tới 30%</p>
                                </div>
                                <span class="text-gray-400 group-hover:text-indigo-600 transition">→</span>
                            </div>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Transaction History Section -->
            <div x-data="{ activeTab: 'wallet_log' }"
                class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                <!-- Tabs -->
                <div class="border-b border-gray-200 dark:border-gray-700">
                    <nav class="flex -mb-px px-6" aria-label="Tabs">
                        <button @click="activeTab = 'wallet_log'"
                            :class="activeTab === 'wallet_log' ? 'border-indigo-500 text-indigo-600' :
                                'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                            class="whitespace-nowrap py-4 px-6 border-b-2 font-medium text-sm transition-colors flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                            Biến động số dư (Coins)
                        </button>
                        <button @click="activeTab = 'orders'"
                            :class="activeTab === 'orders' ? 'border-indigo-500 text-indigo-600' :
                                'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                            class="whitespace-nowrap py-4 px-6 border-b-2 font-medium text-sm transition-colors flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                            </svg>
                            Lịch sử đơn hàng (Tiền mặt)
                        </button>
                    </nav>
                </div>

                <!-- Tab Content: Wallet Logs (Coinkey Transactions) -->
                <div x-show="activeTab === 'wallet_log'" class="p-0">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-700/50">
                                <tr>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                        Thời gian</th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                        Nội dung</th>
                                    <th scope="col"
                                        class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                        Số lượng</th>
                                    <th scope="col"
                                        class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                        Số dư sau GD</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                {{-- 
                                    LƯU Ý: Ở Controller, bạn phải truyền biến $walletTransactions 
                                    (Lấy từ bảng coinkey_transactions) thay vì biến $transactions cũ.
                                --}}
                                @forelse($walletTransactions ?? [] as $log)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition">
                                        <td
                                            class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                            {{ $log->created_at->format('d/m/Y H:i') }}
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="flex items-center">
                                                <span
                                                    class="p-1.5 rounded-full mr-3 {{ $log->amount > 0 ? 'bg-green-100 text-green-600' : 'bg-red-100 text-red-600' }}">
                                                    @if ($log->amount > 0)
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                            viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2" d="M7 11l5-5m0 0l5 5m-5-5v12" />
                                                        </svg>
                                                    @else
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                            viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2" d="M17 13l-5 5m0 0l-5-5m5 5V6" />
                                                        </svg>
                                                    @endif
                                                </span>
                                                <span
                                                    class="text-sm font-medium text-gray-900 dark:text-white">{{ $log->description }}</span>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right">
                                            <span
                                                class="text-sm font-bold font-mono {{ $log->amount > 0 ? 'text-green-600' : 'text-red-600' }}">
                                                {{ $log->amount > 0 ? '+' : '' }}{{ number_format($log->amount) }}
                                            </span>
                                        </td>
                                        <td
                                            class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium text-gray-900 dark:text-white">
                                            {{ number_format($log->balance_after) }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4"
                                            class="px-6 py-10 text-center text-gray-500 dark:text-gray-400">
                                            Chưa có biến động số dư nào.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    @if (isset($walletTransactions) && method_exists($walletTransactions, 'links'))
                        <div class="px-6 py-3 border-t border-gray-200 dark:border-gray-700">
                            {{ $walletTransactions->appends(['tab' => 'wallet_log'])->links() }}
                        </div>
                    @endif
                </div>

                <!-- Tab Content: Orders (PayOS Transactions) -->
                <div x-show="activeTab === 'orders'" class="p-0" style="display: none;">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-700/50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Mã đơn
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Sản
                                        phẩm</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Số
                                        tiền</th>
                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Trạng
                                        thái</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @forelse($transactions ?? [] as $tx)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-mono text-gray-500">
                                            #{{ $tx->order_code }}
                                            <div class="text-xs text-gray-400">
                                                {{ $tx->created_at->format('d/m/Y H:i') }}</div>
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-900 dark:text-white">
                                            {{ $tx->description }}
                                        </td>
                                        <td
                                            class="px-6 py-4 text-right whitespace-nowrap text-sm font-bold text-gray-900 dark:text-white">
                                            {{ number_format($tx->amount) }} {{ $tx->currency }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center">
                                            @if ($tx->status == 'success')
                                                <span
                                                    class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Thành
                                                    công</span>
                                            @elseif($tx->status == 'pending')
                                                <span
                                                    class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">Chờ
                                                    xử lý</span>
                                            @else
                                                <span
                                                    class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">Thất
                                                    bại</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-6 py-10 text-center text-gray-500">Chưa có giao
                                            dịch mua hàng nào.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    @if (isset($transactions) && method_exists($transactions, 'links'))
                        <div class="px-6 py-3 border-t border-gray-200 dark:border-gray-700">
                            {{ $transactions->appends(['tab' => 'orders'])->links() }}
                        </div>
                    @endif
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
