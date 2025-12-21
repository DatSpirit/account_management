<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-bold text-2xl text-gray-800 dark:text-white flex items-center gap-2">
                <svg class="w-8 h-8 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
                </svg>
                Your Keys
            </h2>
            <div class="flex gap-3">
                <a href="{{ route('keys.custom-extend') }}" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl font-bold shadow-lg transition flex items-center gap-2">
                    <span>+</span> Gia Hạn Key Tùy Chỉnh
                </a>
                <a href="{{ route('products') }}"
                    class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-xl font-bold shadow-lg transition flex items-center gap-2">
                    🛒 Mua Gói Có Sẵn
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-8 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto space-y-6">
        <div class="max-w-7xl mx-auto space-y-6">
            <div class="bg-gradient-to-r from-blue-500 dark:from-blue-700 rounded-2xl shadow-xl p-6 sm:p-8 text-white">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div class="space-y-2">
                        <h3 class="text-xl sm:text-2xl font-bold text-gray-800 dark:text-white">Overview 
                        </h3>
                        <p class="text-blue-800 dark:text-white text-sm sm:text-base">Quản lý tất cả keys của bạn</p>
                    </div>
                    <div class="flex items-center space-x-6">
                        <div class="text-center">
                            <div class="text-3xl font-bold text-gray-800 dark:text-white">{{ $stats['total'] }}</div>
                            <div class="text-xs text-blue-800 dark:text-white uppercase tracking-wide">Tổng Keys
                            </div>
                        </div>
                        <div class="h-12 w-px bg-white/30"></div>
                    </div>
                </div>
            </div>

            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-3 md:gap-1">
                <div class="bg-gradient-to-br from-green-200 to-green-600 dark:bg-gray-600 overflow-hidden shadow-lg rounded-xl p-4 sm:p-6 border border-gray-200 dark:border-gray-700 hover:shadow-xl transition-all duration-300">
                    <div class="text-3xl font-bold text-white-600 dark:text-white-400">{{ $stats['active'] }}</div>
                    <div class="text-sm text-white-600 dark:text-white-400">Đang hoạt động</div>
                </div>
                <div class="bg-gradient-to-br from-yellow-200 to-yellow-600 dark:bg-gray-600 overflow-hidden shadow-lg rounded-xl p-4 sm:p-6 border border-gray-200 dark:border-gray-700 hover:shadow-xl transition-all duration-300">
                    <div class="text-3xl font-bold text-white-600 dark:text-white-400">{{ $stats['expiring_soon'] }}</div>
                    <div class="text-sm text-white-600 dark:text-white-400">Sắp hết hạn (7 ngày)</div>
                </div>
                <div class="bg-gradient-to-br from-purple-200 to-purple-600 dark:bg-gray-600 overflow-hidden shadow-lg rounded-xl p-4 sm:p-6 border border-gray-200 dark:border-gray-700 hover:shadow-xl transition-all duration-300">
                    <div class="text-3xl font-bold text-white-600 dark:text-white-400">{{ number_format($stats['total_spent']) }}</div>
                    <div class="text-sm text-white-600 dark:text-white-400">Tổng chi tiêu (1🪙 = 1000 VNG)</div>
                </div>
            </div>
            <!-- Filter Bar -->
            <div
                class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-4">
                <form method="GET" action="{{ route('keys.index') }}" class="flex flex-col md:flex-row gap-4">
                    <div class="flex-1 relative">
                        <svg class="w-5 h-5 absolute left-3 top-1/2 -translate-y-1/2 text-gray-400" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                        <input type="text" name="search" value="{{ request('search') }}"
                            placeholder="Tìm kiếm key..."
                            class="w-full pl-10 pr-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 transition">
                    </div>
                    <div class="w-full md:w-48">
                        <select name="status" onchange="this.form.submit()"
                            class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 cursor-pointer">
                            <option value="">Tất cả trạng thái</option>
                            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>🟢 Đang hoạt
                                động
                            </option>
                            <option value="expired" {{ request('status') == 'expired' ? 'selected' : '' }}>🔴 Hết hạn
                            </option>
                            <option value="suspended" {{ request('status') == 'suspended' ? 'selected' : '' }}>⚫ Bị
                                khóa
                            </option>
                        </select>
                    </div>
                    <div class="w-full md:w-48">
                        <select name="type" onchange="this.form.submit()"
                            class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 cursor-pointer">
                            <option value="">Tất cả loại</option>
                            <option value="auto_generated" {{ request('type') == 'auto_generated' ? 'selected' : '' }}>
                                AUTO
                            </option>
                            <option value="custom" {{ request('type') == 'custom' ? 'selected' : '' }}>CUSTOM</option>
                        </select>
                    </div>
                </form>
            </div>

            <!-- Keys Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
                @forelse($keys as $key)
                    <div
                        class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700 overflow-hidden hover:shadow-2xl hover:-translate-y-1 transition-all duration-300 group relative">

                        <!-- Status Badge -->
                        <div class="absolute top-4 right-4 z-10">
                            @if ($key->isActive())
                                <span
                                    class="px-2 py-1 bg-green-100 text-green-700 text-xs font-bold rounded-lg shadow-sm">ACTIVE</span>
                            @elseif($key->isExpired())
                                <span
                                    class="px-2 py-1 bg-gray-100 text-gray-600 text-xs font-bold rounded-lg shadow-sm">EXPIRED</span>
                            @else
                                <span
                                    class="px-2 py-1 bg-red-100 text-red-600 text-xs font-bold rounded-lg shadow-sm">{{ strtoupper($key->status) }}</span>
                            @endif
                        </div>

                        <!-- Type Badge -->
                        <div class="absolute top-4 left-4 z-10">
                            @if ($key->key_type == 'custom')
                                <span
                                    class="px-2 py-1 bg-purple-100 text-purple-700 text-xs font-bold rounded-lg shadow-sm">
                                    CUSTOM</span>
                            @else
                                <span
                                    class="px-2 py-1 bg-blue-100 text-blue-700 text-xs font-bold rounded-lg shadow-sm">
                                    AUTO</span>
                            @endif
                        </div>

                        <div class="p-6 pt-16">
                            <!-- Key Code -->
                            <div class="mb-4">
                                <span class="text-xs font-bold text-gray-400 uppercase tracking-wider">Mã Key</span>
                                <div
                                    class="flex items-center justify-between mt-1 bg-gray-50 dark:bg-gray-900 p-3 rounded-xl border border-gray-200 dark:border-gray-700">
                                    <code
                                        class="font-mono text-base font-black text-indigo-600 dark:text-indigo-400 tracking-wide truncate">
                                        {{ $key->key_code }}
                                    </code>
                                    <button onclick="copyToClipboard('{{ $key->key_code }}')"
                                        class="p-2 hover:bg-gray-200 dark:hover:bg-gray-700 rounded-lg transition text-gray-500"
                                        title="Sao chép">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z">
                                            </path>
                                        </svg>
                                    </button>
                                </div>
                            </div>

                            <!-- Details -->
                            <div class="space-y-3 text-sm mb-4">
                                <div class="flex justify-between items-center">
                                    <span class="text-gray-500">Tạo lúc:</span>
                                    <span
                                        class="font-semibold text-gray-900 dark:text-white">{{ $key->created_at->setTimezone('Asia/Ho_Chi_Minh')->format('d/m/Y - H:i:s') }}</span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="text-gray-500">Kích hoạt:</span>
                                    <span
                                        class="font-medium {{ $key->activated_at ? 'text-green-600' : 'text-gray-400' }}">
                                        {{ $key->activated_at ? $key->activated_at->setTimezone('Asia/Ho_Chi_Minh')->format('d/m/Y - H:i:s') : 'Chưa kích hoạt' }}
                                    </span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="text-gray-500">Tổng thời gian:</span>
                                    <span
                                        class="font-bold dark:text-gray-300">{{ number_format($key->duration_minutes) }}
                                        phút</span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="text-gray-500">Ngày hết hạn:</span>
                                    <span
                                        class="font-medium {{ $key->isExpired() ? 'text-red-500' : 'text-green-600' }}">
                                        {{ $key->expires_at ? $key->expires_at->format('d/m/Y - H:i:s') : '∞ Vĩnh viễn' }}
                                    </span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="text-gray-500">Thời hạn:</span>
                                    <span class="font-bold text-blue-600">
                                        @if ($key->getRemainingSeconds() > 86400)
                                            {{ $key->getRemainingDays() }} ngày
                                        @elseif ($key->getRemainingSeconds() > 3600)
                                            {{ $key->getRemainingMinutes() }} phút
                                        @else
                                            {{ $key->getRemainingSeconds() }} giây
                                        @endif
                                    </span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="text-gray-500">Tổng chi phí:</span>
                                    <span class="font-bold text-yellow-600">{{ number_format($key->key_cost) }}
                                        🪙</span>
                                </div>
                                {{-- <div class="flex justify-between items-center">
                                <span class="text-gray-500">Lượt xác thực:</span>
                                <span
                                    class="font-semibold text-gray-900 dark:text-white">{{ number_format($key->validation_count) }}</span>
                            </div> --}}
                            </div>

                            <!-- Action Buttons -->
                            <div class="flex gap-2 pt-3 border-t border-gray-200 dark:border-gray-700">
                                <a href="{{ route('keys.keydetails', $key->id) }}"
                                    class="flex-1 py-2 text-center bg-indigo-50 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400 rounded-xl hover:bg-indigo-100 dark:hover:bg-indigo-900/50 transition font-bold text-sm">
                                    Chi tiết
                                </a>
                                @if ($key->product_id)
                                    <a href="{{ route('keys.extend-confirm', $key->id) }}"
                                        class="flex-1 py-2 text-center bg-green-50 dark:bg-green-900/30 text-green-600 dark:text-green-400 rounded-xl hover:bg-green-100 dark:hover:bg-green-900/50 transition font-bold text-sm flex items-center justify-center gap-1">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        Gia hạn
                                    </a>
                                @endif
                                <button onclick="copyToClipboard('{{ $key->key_code }}')"
                                    class="flex-1 py-2 text-center bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-xl hover:bg-gray-200 dark:hover:bg-gray-600 transition font-bold text-sm">
                                    📋 Copy
                                </button>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full py-16 text-center">
                        <div
                            class="w-20 h-20 bg-gray-100 dark:bg-gray-800 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
                            </svg>
                        </div>
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white">Bạn chưa có Key nào</h3>
                        <p class="text-gray-500 mt-2 mb-6">Hãy tạo key đầu tiên để bắt đầu sử dụng dịch vụ.</p>
                        <a href="{{ route('products') }}"
                            class="inline-flex px-6 py-3 bg-indigo-600 text-white rounded-xl font-bold shadow-lg hover:bg-indigo-700 transition">
                            ✨ Tạo Key Ngay
                        </a>
                    </div>
                @endforelse
            </div>

            <!-- Pagination -->
            <div class="mt-6">
                {{ $keys->links() }}
            </div>
        </div>
    </div>

    <script>
        function copyToClipboard(text) {
            navigator.clipboard.writeText(text).then(() => {
                alert('✅ Đã sao chép mã Key: ' + text);
            });
        }
    </script>
</x-app-layout>
