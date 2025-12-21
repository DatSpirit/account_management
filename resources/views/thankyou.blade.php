{{-- thankyou.blade.php - REFACTORED với METADATA làm CORE --}}

<x-app-layout>
    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            
            {{-- SUCCESS CARD --}}
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-center">
                    @if($transaction->status === 'success')
                        <div class="text-6xl mb-4">✅</div>
                        <h2 class="text-2xl font-bold text-green-600 dark:text-green-400 mb-2">
                            Thanh toán thành công!
                        </h2>
                    @elseif($transaction->status === 'pending')
                        <div class="text-6xl mb-4">⏳</div>
                        <h2 class="text-2xl font-bold text-yellow-600 dark:text-yellow-400 mb-2">
                            Đang xử lý thanh toán...
                        </h2>
                    @else
                        <div class="text-6xl mb-4">❌</div>
                        <h2 class="text-2xl font-bold text-red-600 dark:text-red-400 mb-2">
                            Thanh toán thất bại
                        </h2>
                    @endif
                    
                    <p class="text-gray-600 dark:text-gray-400">
                        Mã đơn hàng: <code class="bg-gray-100 dark:bg-gray-700 px-2 py-1 rounded font-mono">{{ $transaction->order_code }}</code>
                    </p>
                </div>
            </div>

            {{-- TRANSACTION DETAILS --}}
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100 mb-4">
                        📋 Thông tin giao dịch
                    </h3>

                    <div class="space-y-3 text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-600 dark:text-gray-400">Số tiền:</span>
                            <span class="font-bold text-green-600">
                                {{ number_format($transaction->amount) }} 
                                {{ $transaction->currency === 'COINKEY' ? 'Coin' : 'VND' }}
                            </span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600 dark:text-gray-400">Trạng thái:</span>
                            <span class="font-semibold {{ $transaction->status === 'success' ? 'text-green-600' : 'text-yellow-600' }}">
                                {{ ucfirst($transaction->status) }}
                            </span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600 dark:text-gray-400">Thời gian:</span>
                            <span class="font-semibold">
                                {{ $transaction->created_at->format('d/m/Y H:i') }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- CONTENT BY TYPE (sử dụng METADATA) --}}
            
            {{-- 1️⃣ CUSTOM EXTENSION --}}
            @if($is_custom_extension)
                <div class="bg-indigo-50 dark:bg-indigo-900/20 border border-indigo-200 dark:border-indigo-800 rounded-lg p-6 mb-6">
                    <div class="flex items-center gap-3 mb-4">
                        <span class="text-4xl">🎯</span>
                        <div>
                            <h3 class="text-xl font-bold text-indigo-700 dark:text-indigo-400">
                                Gia hạn tùy chỉnh thành công!
                            </h3>
                            <p class="text-sm text-gray-600 dark:text-gray-400">
                                Key đã được gia hạn {{ $days_added ?? 0 }} ngày
                            </p>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4 text-sm">
                        @if($key_code)
                            <div>
                                <span class="text-gray-600 dark:text-gray-400">Key Code:</span>
                                <code class="block mt-1 bg-white dark:bg-gray-700 px-3 py-2 rounded font-mono font-bold">
                                    {{ $key_code }}
                                </code>
                            </div>
                        @endif
                        
                        @if($package_name)
                            <div>
                                <span class="text-gray-600 dark:text-gray-400">Gói gia hạn:</span>
                                <span class="block mt-1 font-semibold text-indigo-600">{{ $package_name }}</span>
                            </div>
                        @endif
                        
                        @if($days_added)
                            <div>
                                <span class="text-gray-600 dark:text-gray-400">Thời gian cộng:</span>
                                <span class="block mt-1 font-bold text-green-600">+{{ $days_added }} ngày</span>
                            </div>
                        @endif

                        @if($key && $key->expires_at)
                            <div>
                                <span class="text-gray-600 dark:text-gray-400">Hết hạn mới:</span>
                                <span class="block mt-1 font-semibold text-red-600">
                                    {{ $key->expires_at->format('d/m/Y H:i') }}
                                </span>
                            </div>
                        @endif
                    </div>

                    @if($key)
                        <a href="{{ route('keys.keydetails', $key->id) }}" 
                           class="mt-4 inline-block bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2 rounded-lg transition">
                            Xem chi tiết Key →
                        </a>
                    @endif
                </div>

            {{-- 2️⃣ STANDARD EXTENSION --}}
            @elseif($is_key_extension && !$is_custom_extension)
                <div class="bg-orange-50 dark:bg-orange-900/20 border border-orange-200 dark:border-orange-800 rounded-lg p-6 mb-6">
                    <div class="flex items-center gap-3 mb-4">
                        <span class="text-4xl">⏱️</span>
                        <div>
                            <h3 class="text-xl font-bold text-orange-700 dark:text-orange-400">
                                Gia hạn Key thành công!
                            </h3>
                            <p class="text-sm text-gray-600 dark:text-gray-400">
                                Key đã được cộng thêm {{ number_format($duration_minutes ?? 0) }} phút
                            </p>
                        </div>
                    </div>

                    @if($key)
                        <div class="grid grid-cols-2 gap-4 text-sm mb-4">
                            <div>
                                <span class="text-gray-600 dark:text-gray-400">Key Code:</span>
                                <code class="block mt-1 bg-white dark:bg-gray-700 px-3 py-2 rounded font-mono font-bold">
                                    {{ $key->key_code }}
                                </code>
                            </div>
                            <div>
                                <span class="text-gray-600 dark:text-gray-400">Hết hạn mới:</span>
                                <span class="block mt-1 font-semibold text-red-600">
                                    {{ $key->expires_at ? $key->expires_at->format('d/m/Y H:i') : 'Vĩnh viễn' }}
                                </span>
                            </div>
                        </div>

                        <a href="{{ route('keys.keydetails', $key->id) }}" 
                           class="inline-block bg-orange-600 hover:bg-orange-700 text-white px-6 py-2 rounded-lg transition">
                            Xem chi tiết Key →
                        </a>
                    @endif
                </div>

            {{-- 3️⃣ CUSTOM KEY PURCHASE --}}
            @elseif($is_custom_key_purchase)
                <div class="bg-purple-50 dark:bg-purple-900/20 border border-purple-200 dark:border-purple-800 rounded-lg p-6 mb-6">
                    <div class="flex items-center gap-3 mb-4">
                        <span class="text-4xl">🎨</span>
                        <div>
                            <h3 class="text-xl font-bold text-purple-700 dark:text-purple-400">
                                Custom Key tạo thành công!
                            </h3>
                            <p class="text-sm text-gray-600 dark:text-gray-400">
                                Key tùy chỉnh của bạn đã sẵn sàng
                            </p>
                        </div>
                    </div>

                    @if($key)
                        <div class="grid grid-cols-2 gap-4 text-sm mb-4">
                            <div>
                                <span class="text-gray-600 dark:text-gray-400">Key Code (Custom):</span>
                                <code class="block mt-1 bg-purple-100 dark:bg-purple-800 px-3 py-2 rounded font-mono font-bold">
                                    {{ $key->key_code }}
                                </code>
                            </div>
                            <div>
                                <span class="text-gray-600 dark:text-gray-400">Thời hạn:</span>
                                <span class="block mt-1 font-semibold text-purple-600">
                                    {{ number_format($duration_minutes ?? 0) }} phút
                                </span>
                            </div>
                            @if($key->expires_at)
                                <div>
                                    <span class="text-gray-600 dark:text-gray-400">Hết hạn:</span>
                                    <span class="block mt-1 font-semibold text-red-600">
                                        {{ $key->expires_at->format('d/m/Y H:i') }}
                                    </span>
                                </div>
                            @endif
                        </div>

                        <a href="{{ route('keys.keydetails', $key->id) }}" 
                           class="inline-block bg-purple-600 hover:bg-purple-700 text-white px-6 py-2 rounded-lg transition">
                            Xem chi tiết Key →
                        </a>
                    @endif
                </div>

            {{-- 4️⃣ PACKAGE PURCHASE --}}
            @elseif($is_package_purchase)
                <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-6 mb-6">
                    <div class="flex items-center gap-3 mb-4">
                        <span class="text-4xl">🔑</span>
                        <div>
                            <h3 class="text-xl font-bold text-blue-700 dark:text-blue-400">
                                Key mới được tạo!
                            </h3>
                            <p class="text-sm text-gray-600 dark:text-gray-400">
                                {{ $product?->name ?? 'Package' }}
                            </p>
                        </div>
                    </div>

                    @if($key)
                        <div class="grid grid-cols-2 gap-4 text-sm mb-4">
                            <div>
                                <span class="text-gray-600 dark:text-gray-400">Key Code:</span>
                                <code class="block mt-1 bg-white dark:bg-gray-700 px-3 py-2 rounded font-mono font-bold">
                                    {{ $key->key_code }}
                                </code>
                            </div>
                            <div>
                                <span class="text-gray-600 dark:text-gray-400">Thời hạn:</span>
                                <span class="block mt-1 font-semibold text-purple-600">
                                    {{ number_format($key->duration_minutes) }} phút
                                </span>
                            </div>
                            @if($key->expires_at)
                                <div>
                                    <span class="text-gray-600 dark:text-gray-400">Hết hạn:</span>
                                    <span class="block mt-1 font-semibold text-red-600">
                                        {{ $key->expires_at->format('d/m/Y H:i') }}
                                    </span>
                                </div>
                            @endif
                        </div>

                        <a href="{{ route('keys.keydetails', $key->id) }}" 
                           class="inline-block bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg transition">
                            Xem chi tiết Key →
                        </a>
                    @endif
                </div>

            {{-- 5️⃣ COINKEY DEPOSIT --}}
            @elseif($is_coinkey_deposit)
                <div class="bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-200 dark:border-emerald-800 rounded-lg p-6 mb-6">
                    <div class="flex items-center gap-3 mb-4">
                        <span class="text-4xl">💰</span>
                        <div>
                            <h3 class="text-xl font-bold text-emerald-700 dark:text-emerald-400">
                                Nạp Coinkey thành công!
                            </h3>
                            <p class="text-sm text-gray-600 dark:text-gray-400">
                                Số dư đã được cập nhật
                            </p>
                        </div>
                    </div>

                    <div class="text-center">
                        <p class="text-3xl font-bold text-emerald-600 mb-2">
                            +{{ number_format($product?->coinkey_amount ?? 0) }} Coin
                        </p>
                        <a href="{{ route('wallet.index') }}" 
                           class="inline-block bg-emerald-600 hover:bg-emerald-700 text-white px-6 py-2 rounded-lg transition">
                            Xem ví của tôi →
                        </a>
                    </div>
                </div>
            @endif

            {{-- ACTIONS --}}
            <div class="flex gap-4">
                <a href="{{ route('dashboard') }}" 
                   class="flex-1 bg-gray-600 hover:bg-gray-700 text-white text-center py-3 rounded-lg transition">
                    ← Về Dashboard
                </a>
                <a href="{{ route('keys.index') }}" 
                   class="flex-1 bg-indigo-600 hover:bg-indigo-700 text-white text-center py-3 rounded-lg transition">
                    Xem My Keys →
                </a>
            </div>

        </div>
    </div>
</x-app-layout>