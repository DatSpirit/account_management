<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <a href="{{ route('keys.index') }}" 
                   class="p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors duration-200">
                    <svg class="w-6 h-6 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                </a>
                <div>
                    <h2 class="font-bold text-2xl text-gray-800 dark:text-white">
                        Gia hạn Key tùy chỉnh
                    </h2>
                    <p class="text-sm text-gray-500 dark:text-gray-400">
                        Chọn gói thời gian linh hoạt cho Key của bạn
                    </p>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            
            {{-- Card nhập Key Code - MODERN DESIGN --}}
            <div class="bg-gradient-to-br from-white to-gray-50 dark:from-gray-800 dark:to-gray-900 overflow-hidden shadow-xl sm:rounded-2xl mb-8 border border-gray-100 dark:border-gray-700">
                <div class="p-8">
                    {{-- Header với icon --}}
                    <div class="flex items-center gap-4 mb-6">
                        <div class="p-4 bg-indigo-100 dark:bg-indigo-900/30 rounded-xl">
                            <svg class="w-8 h-8 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-2xl font-bold text-gray-900 dark:text-gray-100">
                                Nhập mã Key
                            </h3>
                            <p class="text-sm text-gray-600 dark:text-gray-400">
                                Key của bạn cần được gia hạn
                            </p>
                        </div>
                    </div>

                    <form method="POST" action="{{ route('keys.custom-extend-confirm') }}" id="extendForm">
                        @csrf
                        
                        {{-- Key Code Input với autocomplete --}}
                        <div class="mb-8">
                            <label for="key_code" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3">
                                Mã Key <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <input 
                                    type="text" 
                                    id="key_code" 
                                    name="key_code" 
                                    value="{{ old('key_code') }}"
                                    class="w-full px-5 py-4 border-2 border-gray-200 dark:border-gray-600 rounded-xl focus:ring-4 focus:ring-indigo-500/20 focus:border-indigo-500 dark:bg-gray-700 dark:text-gray-100 uppercase font-mono text-lg transition-all duration-200"
                                    placeholder="Ví dụ: PK-ABCD-1234-EFGH"
                                    list="user_keys"
                                    autocomplete="off"
                                    required
                                >
                                {{-- Icon search --}}
                                <div class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                    </svg>
                                </div>
                            </div>

                            {{-- Datalist gợi ý Key của user --}}
                            <datalist id="user_keys">
                                @foreach(auth()->user()->productKeys()->where('status', '!=', 'revoked')->latest()->take(10)->get() as $userKey)
                                    <option value="{{ $userKey->key_code }}">
                                        {{ $userKey->key_code }} - {{ $userKey->product?->name ?? 'Custom Key' }}
                                    </option>
                                @endforeach
                            </datalist>

                            {{-- Gợi ý visual --}}
                            <div class="mt-3 flex items-start gap-2 text-sm text-gray-600 dark:text-gray-400">
                                <svg class="w-5 h-5 text-blue-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <span>Bắt đầu nhập để xem gợi ý Key của bạn. Hệ thống sẽ tự động tìm kiếm.</span>
                            </div>

                            @error('key_code')
                                <p class="text-red-500 text-sm mt-2 flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                    </svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        {{-- Chọn gói gia hạn - MODERN GRID --}}
                        <div class="mb-8">
                            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-4">
                                Chọn gói gia hạn <span class="text-red-500">*</span>
                            </label>

                            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
                                @foreach($packages as $package)
                                    <label class="relative cursor-pointer group">
                                        <input 
                                            type="radio" 
                                            name="package_id" 
                                            value="{{ $package->id }}"
                                            class="peer sr-only"
                                            required
                                        >
                                        <div class="relative border-2 border-gray-200 dark:border-gray-600 rounded-2xl p-5 hover:border-indigo-400 hover:shadow-lg peer-checked:border-indigo-600 peer-checked:bg-gradient-to-br peer-checked:from-indigo-50 peer-checked:to-purple-50 dark:peer-checked:from-indigo-900/30 dark:peer-checked:to-purple-900/30 transition-all duration-300 transform hover:scale-105">
                                            {{-- Badge "Popular" cho gói 30 ngày --}}
                                            @if($package->days === 30)
                                                <div class="absolute -top-2 -right-2 bg-gradient-to-r from-orange-500 to-pink-500 text-white text-xs font-bold px-2 py-1 rounded-full shadow-lg">
                                                    HOT
                                                </div>
                                            @endif

                                            {{-- Checkmark khi selected --}}
                                            <div class="absolute top-2 right-2 w-6 h-6 bg-indigo-600 rounded-full opacity-0 peer-checked:opacity-100 transition-opacity duration-200 flex items-center justify-center">
                                                <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                                </svg>
                                            </div>

                                            <div class="text-center">
                                                <p class="font-bold text-3xl text-gray-900 dark:text-gray-100 mb-1">
                                                    {{ $package->days }}
                                                </p>
                                                <p class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-3">
                                                    ngày
                                                </p>
                                                <div class="space-y-1">
                                                    <p class="text-sm font-bold text-indigo-600 dark:text-indigo-400">
                                                        {{ number_format($package->price_coinkey) }} Coin
                                                    </p>
                                                    <p class="text-xs text-gray-500 dark:text-gray-400">
                                                        {{ number_format($package->price_vnd / 1000) }}K
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </label>
                                @endforeach
                            </div>
                            @error('package_id')
                                <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Hiển thị số dư - MODERN CARD --}}
                        <div class="bg-gradient-to-r from-indigo-500 to-purple-600 rounded-2xl p-6 mb-8 text-white shadow-xl">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <div class="p-3 bg-white/20 backdrop-blur-sm rounded-xl">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-sm opacity-90">Số dư ví hiện tại</p>
                                        <p class="text-2xl font-bold">
                                            {{ number_format($wallet->balance) }} Coinkey
                                        </p>
                                    </div>
                                </div>
                                <a href="{{ route('wallet.index') }}" 
                                   class="px-4 py-2 bg-white/20 hover:bg-white/30 backdrop-blur-sm rounded-xl transition-colors duration-200 text-sm font-semibold">
                                    Nạp thêm →
                                </a>
                            </div>
                        </div>

                        {{-- Submit Button - MODERN GRADIENT --}}
                        <button type="submit" 
                                class="w-full bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white font-bold py-4 px-6 rounded-xl transition-all duration-200 transform hover:scale-[1.02] hover:shadow-2xl flex items-center justify-center gap-2 text-lg">
                            <span>Tiếp tục</span>
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                            </svg>
                        </button>
                    </form>
                </div>
            </div>

        </div>
    </div>

    {{-- Enhanced JS for better UX --}}
    <script>
        // Auto uppercase Key Code input
        document.getElementById('key_code').addEventListener('input', function(e) {
            this.value = this.value.toUpperCase();
        });

        // Form validation feedback
        document.getElementById('extendForm').addEventListener('submit', function(e) {
            const submitBtn = this.querySelector('button[type="submit"]');
            submitBtn.innerHTML = `
                <svg class="animate-spin h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                Đang xử lý...
            `;
            submitBtn.disabled = true;
        });
    </script>
</x-app-layout>