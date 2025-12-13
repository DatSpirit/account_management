<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h2 class="font-bold text-2xl text-gray-800 dark:text-white">Cửa Hàng VIP</h2>
                <p class="text-sm text-gray-500 dark:text-gray-400">Nâng cấp VIP để mở khóa ưu đãi độc quyền</p>
            </div>

            <!-- VIP Badge -->
            <div
                class="flex items-center gap-4 bg-white dark:bg-gray-800 p-3 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
                <div class="text-right">
                    <p class="text-xs text-gray-500 font-bold uppercase">Cấp độ hiện tại</p>
                    <p class="text-xl font-black text-yellow-500">VIP {{ $vipLevel }}</p>
                </div>
                <div class="w-px h-8 bg-gray-300 dark:bg-gray-600"></div>
                <div>
                    <p class="text-xs text-gray-500">Tổng đã nạp: <span
                            class="font-bold text-green-600">{{ number_format($wallet->total_deposited) }}</span>
                    </p>
                </div>
            </div>
            <a href="{{ route('wallet.index') }}"
                class="px-4 py-2 bg-gray-200 hover:bg-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-800 dark:text-gray-200 font-medium rounded-lg transition-all duration-200">
                ← Back
            </a>

        </div>
    </x-slot>

    <div class="py-8 px-4 sm:px-6 lg:px-8 min-h-screen">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">

            @forelse($vipPackages as $item)
                @php
                    $pkg = $item['product'];
                    $requiredVip = $item['required_vip'];
                    $discountPercent = $item['discount_percent'];
                    $finalPrice = $item['final_price'];
                    $maxBuy = $item['max_buy'];
                    $boughtCount = $item['bought_count'];
                    $canBuy = $item['can_buy'];
                    $isLocked = $item['is_locked'];
                @endphp

                <div
                    class="relative group bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700 overflow-hidden transition-all duration-300 {{ $isLocked ? 'opacity-70 grayscale' : 'hover:-translate-y-1 hover:shadow-2xl' }}">

                    <!-- Locked Overlay -->
                    @if ($isLocked)
                        <div
                            class="absolute inset-0 bg-black/50 z-20 flex flex-col items-center justify-center text-white backdrop-blur-[2px]">
                            <svg class="w-12 h-12 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                            </svg>
                            <span class="font-bold text-lg">Yêu cầu VIP {{ $requiredVip }}</span>
                            <span class="text-sm opacity-80">Nạp thêm để mở khóa</span>
                        </div>
                    @endif

                    <!-- Badges -->
                    <div
                        class="absolute top-0 left-0 bg-red-500 text-white text-xs font-bold px-3 py-1 rounded-br-xl z-10">
                        Giảm {{ $discountPercent }}%
                    </div>
                    <div
                        class="absolute top-0 right-0 bg-gray-900/80 text-white text-[10px] font-bold px-2 py-1 rounded-bl-xl z-10">
                        Còn lại: {{ $maxBuy - $boughtCount }}/{{ $maxBuy }}
                    </div>

                    <div class="p-6">
                        <div
                            class="w-14 h-14 bg-indigo-100 dark:bg-indigo-900/50 text-indigo-600 dark:text-indigo-400 rounded-2xl flex items-center justify-center mb-4 text-2xl shadow-inner">
                            🎁
                        </div>

                        <h4 class="text-lg font-bold text-gray-900 dark:text-white mb-1">{{ $pkg->name }}</h4>
                        <p class="text-xs text-gray-500 mb-4 line-clamp-2">{{ $pkg->description }}</p>

                        <!-- Duration Info -->
                        @if ($pkg->duration_minutes)
                            <div class="flex items-center gap-2 mb-3 text-xs text-gray-600 dark:text-gray-400">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span>{{ round($pkg->duration_minutes / 1440) }} ngày</span>
                            </div>
                        @endif

                        <!-- Pricing -->
                        <div class="flex items-center gap-2 mb-6">
                            <span
                                class="text-2xl font-black text-indigo-600 dark:text-indigo-400">{{ number_format($finalPrice) }}
                                🪙</span>
                            <span
                                class="text-sm text-gray-400 line-through">{{ number_format($pkg->coinkey_amount) }}</span>
                        </div>

                        <!-- Action -->
                        <form action="{{ route('order.process') }}" method="POST">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $pkg->id }}">
                            <input type="hidden" name="payment_method" value="wallet">

                            @if ($isLocked)
                                <button type="button" disabled
                                    class="w-full py-2.5 bg-gray-300 dark:bg-gray-700 text-gray-500 rounded-xl font-bold cursor-not-allowed">
                                    Khóa
                                </button>
                            @elseif(!$canBuy)
                                <button type="button" disabled
                                    class="w-full py-2.5 bg-gray-300 dark:bg-gray-700 text-gray-500 rounded-xl font-bold cursor-not-allowed">
                                    Hết lượt mua
                                </button>
                            @else
                                <button type="submit"
                                    class="w-full py-2.5 bg-gradient-to-r from-indigo-600 to-purple-600 text-white rounded-xl font-bold shadow-lg hover:shadow-indigo-500/50 transition-all">
                                    Mua Ngay
                                </button>
                            @endif
                        </form>
                    </div>
                </div>
            @empty
                <div class="col-span-full text-center py-16">
                    <div
                        class="w-24 h-24 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mx-auto mb-4 text-5xl">
                        📦
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">Chưa có gói ưu đãi</h3>
                    <p class="text-gray-500 dark:text-gray-400">Vui lòng quay lại sau</p>
                </div>
            @endforelse
        </div>
    </div>
</x-app-layout>
