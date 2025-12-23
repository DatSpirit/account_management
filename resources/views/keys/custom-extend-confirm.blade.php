<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <a href="{{ route('keys.custom-extend') }}"
                    class="p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors duration-200">
                    <svg class="w-6 h-6 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                </a>
                <div>
                    <h2 class="font-bold text-2xl text-gray-800 dark:text-white">Xác nhận gia hạn</h2>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Kiểm tra thông tin trước khi thanh toán</p>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">

            {{-- Thông tin Key - MODERN CARD --}}
            <div
                class="bg-gradient-to-br from-blue-50 to-indigo-50 dark:from-blue-900/20 dark:to-indigo-900/20 border-2 border-blue-200 dark:border-blue-800 overflow-hidden shadow-xl sm:rounded-2xl mb-6">
                <div class="p-6">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="p-3 bg-blue-500 rounded-xl shadow-lg">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 dark:text-gray-100">
                         Thông tin Key
                        </h3>
                    </div>

                    <div class="space-y-4">
                        <div class="flex items-center justify-between p-4 bg-white dark:bg-gray-800 rounded-xl">
                            <span class="text-sm font-medium text-gray-600 dark:text-gray-400">Key Code:</span>
                            <code class="bg-gray-100 dark:bg-gray-700 px-4 py-2 rounded-lg font-mono font-bold text-lg">
                                {{ $key->key_code }}
                            </code>
                        </div>
                        <div class="flex items-center justify-between p-4 bg-white dark:bg-gray-800 rounded-xl">
                            <span class="text-sm font-medium text-gray-600 dark:text-gray-400">Trạng thái:</span>
                            <span
                                class="px-3 py-1 bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400 rounded-full text-sm font-semibold">
                                {{ ucfirst($key->status) }}
                            </span>
                        </div>
                        <div class="flex items-center justify-between p-4 bg-white dark:bg-gray-800 rounded-xl">
                            <span class="text-sm font-medium text-gray-600 dark:text-gray-400">Hết hạn hiện tại:</span>
                            <span class="font-bold text-red-600 dark:text-red-400">
                                {{ $current_expiry ? $current_expiry->format('d/m/Y H:i') : 'Vĩnh viễn' }}
                            </span>
                        </div>
                        @if ($key->product)
                            <div class="flex items-center justify-between p-4 bg-white dark:bg-gray-800 rounded-xl">
                                <span class="text-sm font-medium text-gray-600 dark:text-gray-400">Gói sản phẩm:</span>
                                <span
                                    class="font-semibold text-gray-900 dark:text-gray-100">{{ $key->product->name }}</span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Thông tin gói gia hạn - MODERN CARD --}}
            <div
                class="bg-gradient-to-br from-purple-50 to-pink-50 dark:from-purple-900/20 dark:to-pink-900/20 border-2 border-purple-200 dark:border-purple-800 overflow-hidden shadow-xl sm:rounded-2xl mb-6">
                <div class="p-6">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="p-3 bg-gradient-to-r from-purple-500 to-pink-500 rounded-xl shadow-lg">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 dark:text-gray-100">
                         Gói gia hạn đã chọn
                        </h3>
                    </div>

                    <div class="grid md:grid-cols-2 gap-4">
                        <div class="p-4 bg-white dark:bg-gray-800 rounded-xl">
                            <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">Tên gói</p>
                            <p class="font-bold text-lg text-indigo-600 dark:text-indigo-400">{{ $package->name }}</p>
                        </div>
                        <div class="p-4 bg-white dark:bg-gray-800 rounded-xl">
                            <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">Thời gian cộng</p>
                            <p class="font-bold text-lg text-green-600 dark:text-green-400">+{{ $package->days }} ngày
                            </p>
                        </div>
                        <div class="p-4 bg-white dark:bg-gray-800 rounded-xl">
                            <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">Giá Coinkey</p>
                            <p class="font-bold text-lg text-purple-600 dark:text-purple-400">
                                {{ number_format($package->price_coinkey) }} Coin</p>
                        </div>
                        <div class="p-4 bg-white dark:bg-gray-800 rounded-xl">
                            <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">Giá VND</p>
                            <p class="font-bold text-lg text-orange-600 dark:text-orange-400">
                                {{ number_format($package->price_vnd) }} VND</p>
                        </div>
                    </div>

                    @if ($new_expiry)
                        <div class="mt-4 p-5 bg-gradient-to-r from-green-300 to-emerald-300 dark:from-green-500 dark:to-emerald-500 rounded-xl text-gray-900 dark:text-white shadow-lg">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm opacity-90 mb-1">Hết hạn mới sau khi gia hạn</p>
                                    <p class="text-2xl font-bold">
                                        {{ $new_expiry->format('d/m/Y H:i') }}
                                    </p>
                                </div>
                                <svg class="w-12 h-12 opacity-50" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Chọn phương thức thanh toán - MODERN DESIGN --}}
            <div
                class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-2xl mb-6 border border-gray-200 dark:border-gray-700">
                <div class="p-6">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="p-3 bg-gradient-to-r from-indigo-500 to-purple-500 rounded-xl shadow-lg">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 dark:text-gray-100">
                            Chọn phương thức thanh toán
                        </h3>
                    </div>

                    <form method="POST" action="{{ route('keys.process-custom-extension') }}" id="paymentForm">
                        @csrf
                        <input type="hidden" name="key_id" value="{{ $key->id }}">
                        <input type="hidden" name="package_id" value="{{ $package->id }}">

                        <div class="space-y-4">
                            {{-- Ví Coinkey --}}
                            <label class="relative cursor-pointer group">
                                <input type="radio" name="payment_method" value="wallet" class="peer sr-only"
                                    {{ $has_sufficient_balance ? '' : 'disabled' }}>
                                <div
                                    class="border-3 border-gray-200 dark:border-gray-600 rounded-2xl p-6 hover:border-purple-400 hover:shadow-xl peer-checked:border-purple-600 peer-checked:bg-gradient-to-br peer-checked:from-purple-50 peer-checked:to-pink-50 dark:peer-checked:from-purple-900/30 dark:peer-checked:to-pink-900/30 peer-disabled:opacity-50 peer-disabled:cursor-not-allowed transition-all duration-300 transform hover:scale-[1.02]">
                                    {{-- Checkmark --}}
                                    <div
                                        class="absolute top-4 right-4 w-8 h-8 bg-purple-600 rounded-full opacity-0 peer-checked:opacity-100 transition-opacity duration-200 flex items-center justify-center shadow-lg">
                                        <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                                clip-rule="evenodd" />
                                        </svg>
                                    </div>

                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center gap-4">
                                            <div class="p-3 bg-purple-100 dark:bg-purple-900/30 rounded-xl">
                                                <svg class="w-8 h-8 text-purple-600 dark:text-purple-400"
                                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                                                </svg>
                                            </div>
                                            <div>
                                                <p class="font-bold text-lg text-gray-900 dark:text-gray-100">Ví
                                                    Coinkey</p>
                                                <p class="text-sm text-gray-600 dark:text-gray-400">
                                                    Số dư: <span
                                                        class="font-semibold">{{ number_format($wallet->balance) }}
                                                        Coin</span>
                                                </p>
                                            </div>
                                        </div>
                                        <div class="text-right">
                                            <p class="text-2xl font-bold text-purple-600 dark:text-purple-400">
                                                {{ number_format($package->price_coinkey) }}
                                            </p>
                                            <p class="text-sm text-gray-500">Coin</p>
                                        </div>
                                    </div>
                                    @if (!$has_sufficient_balance)
                                        <div
                                            class="mt-4 p-3 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg flex items-center gap-2">
                                            <svg class="w-5 h-5 text-red-500 flex-shrink-0" fill="currentColor"
                                                viewBox="0 0 20 20">
                                                <path fill-rule="evenodd"
                                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                            <span class="text-red-700 dark:text-red-400 text-sm font-medium">Số dư
                                                không đủ. Vui lòng nạp thêm Coinkey.</span>
                                        </div>
                                    @endif
                                </div>
                            </label>

                            {{-- PayOS --}}
                            <label class="relative cursor-pointer group">
                                <input type="radio" name="payment_method" value="cash" class="peer sr-only">
                                <div
                                    class="border-3 border-gray-200 dark:border-gray-600 rounded-2xl p-6 hover:border-green-400 hover:shadow-xl peer-checked:border-green-600 peer-checked:bg-gradient-to-br peer-checked:from-green-50 peer-checked:to-emerald-50 dark:peer-checked:from-green-900/30 dark:peer-checked:to-emerald-900/30 transition-all duration-300 transform hover:scale-[1.02]">
                                    {{-- Checkmark --}}
                                    <div
                                        class="absolute top-4 right-4 w-8 h-8 bg-green-600 rounded-full opacity-0 peer-checked:opacity-100 transition-opacity duration-200 flex items-center justify-center shadow-lg">
                                        <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                                clip-rule="evenodd" />
                                        </svg>
                                    </div>

                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center gap-4">
                                            <div class="p-3 bg-green-100 dark:bg-green-900/30 rounded-xl">
                                                <svg class="w-8 h-8 text-green-600 dark:text-green-400" fill="none"
                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                            </div>
                                            <div>
                                                <p class="font-bold text-lg text-gray-900 dark:text-gray-100">Chuyển
                                                    khoản / QR</p>
                                                <p class="text-sm text-gray-600 dark:text-gray-400">Thanh toán qua
                                                    PayOS</p>
                                            </div>
                                        </div>
                                        <div class="text-right">
                                            <p class="text-2xl font-bold text-green-600 dark:text-green-400">
                                                {{ number_format($package->price_vnd / 1000) }}K
                                            </p>
                                            <p class="text-sm text-gray-500">VND</p>
                                        </div>
                                    </div>
                                </div>
                            </label>
                        </div>

                        @error('payment_method')
                            <p class="text-red-500 text-sm mt-3 flex items-center gap-1">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                        clip-rule="evenodd" />
                                </svg>
                                {{ $message }}
                            </p>
                        @enderror

                        {{-- Submit Buttons --}}
                        <div class="mt-8 flex gap-4">
                            <a href="{{ route('keys.custom-extend') }}"
                                class="flex-1 bg-gray-200 hover:bg-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-800 dark:text-gray-200 font-bold py-4 px-6 rounded-xl text-center transition-all duration-200 transform hover:scale-[1.02] flex items-center justify-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                                </svg>
                                <span>Quay lại</span>
                            </a>
                            <button type="submit"
                                class="flex-1 bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white font-bold py-4 px-6 rounded-xl transition-all duration-200 transform hover:scale-[1.02] hover:shadow-2xl flex items-center justify-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span>Xác nhận thanh toán</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>

    <script>
        // Form submission handling
        document.getElementById('paymentForm').addEventListener('submit', function(e) {
            const submitBtn = this.querySelector('button[type="submit"]');
            submitBtn.innerHTML = `
                <svg class="animate-spin h-5 w-5" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <span>Đang xử lý...</span>
            `;
            submitBtn.disabled = true;
        });
    </script>
</x-app-layout>
