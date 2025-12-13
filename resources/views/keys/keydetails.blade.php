<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-bold text-2xl text-gray-800 dark:text-white">Chi Tiết Key</h2>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">{{ $key->key_code }}</p>
            </div>
            <a href="{{ route('keys.index') }}" class="px-4 py-2 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 transition">
                ← Quay lại
            </a>
        </div>
    </x-slot>

    <div class="py-8 px-4 sm:px-6 lg:px-8 space-y-6">
        
        <!-- Success Message -->
        @if(session('success'))
        <div class="bg-green-50 dark:bg-green-900/20 border-l-4 border-green-500 p-4 rounded-r-xl">
            <div class="flex items-center gap-3">
                <svg class="w-6 h-6 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <p class="text-green-800 dark:text-green-200 font-medium">{{ session('success') }}</p>
            </div>
        </div>
        @endif

        <!-- Key Status Card -->
        <div class="bg-gradient-to-br from-indigo-500 to-purple-600 rounded-3xl shadow-2xl p-8 text-white">
            <div class="flex items-center justify-between mb-6">
                <div class="flex items-center gap-4">
                    <div class="w-16 h-16 bg-white/20 backdrop-blur-sm rounded-2xl flex items-center justify-center text-3xl">
                        @if($key->isActive())
                            ✅
                        @elseif($key->isExpired())
                            ⏰
                        @elseif($key->isSuspended())
                            ⏸️
                        @else
                            🔒
                        @endif
                    </div>
                    <div>
                        <p class="text-sm text-indigo-100 uppercase font-medium">Trạng thái</p>
                        <p class="text-2xl font-black">
                            @if($key->isActive())
                                Đang Hoạt Động
                            @elseif($key->isExpired())
                                Hết Hạn
                            @elseif($key->isSuspended())
                                Tạm Dừng
                            @else
                                {{ ucfirst($key->status) }}
                            @endif
                        </p>
                    </div>
                </div>

                <!-- Type Badge -->
                <span class="px-4 py-2 bg-white/20 backdrop-blur-sm rounded-full text-sm font-bold">
                    {{ $key->key_type === 'custom' ? '✨ Tùy chỉnh' : '📦 Tự động' }}
                </span>
            </div>

            <!-- Key Code -->
            <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-6 mb-6">
                <p class="text-sm text-indigo-100 uppercase font-medium mb-2">Mã Key</p>
                <div class="flex items-center justify-between gap-4">
                    <p class="text-3xl font-mono font-black tracking-wider">{{ $key->key_code }}</p>
                    <button onclick="copyToClipboard('{{ $key->key_code }}')" class="px-4 py-2 bg-white text-indigo-600 rounded-lg font-bold hover:bg-indigo-50 transition">
                        📋 Copy
                    </button>
                </div>
            </div>

            <!-- Time Info -->
            <div class="grid grid-cols-2 gap-4">
                @if($key->expires_at)
                <div class="bg-white/10 backdrop-blur-sm rounded-xl p-4">
                    <p class="text-xs text-indigo-100 uppercase mb-1">Thời hạn còn lại</p>
                    <p class="text-2xl font-black">{{ $key->getRemainingDays() ?? '∞' }} ngày</p>
                </div>
                <div class="bg-white/10 backdrop-blur-sm rounded-xl p-4">
                    <p class="text-xs text-indigo-100 uppercase mb-1">Hết hạn vào</p>
                    <p class="text-lg font-bold">{{ $key->expires_at->format('d/m/Y') }}</p>
                </div>
                @else
                <div class="col-span-2 bg-white/10 backdrop-blur-sm rounded-xl p-4 text-center">
                    <p class="text-xl font-black">♾️ Không giới hạn thời gian</p>
                </div>
                @endif
            </div>
        </div>

        <!-- Details Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            
            <!-- General Info -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700 p-6">
                <h3 class="font-bold text-xl text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                    <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Thông Tin Chung
                </h3>
                <div class="space-y-3">
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-500 dark:text-gray-400">Loại Key:</span>
                        <span class="font-semibold text-gray-900 dark:text-white">{{ $key->key_type === 'custom' ? 'Tùy chỉnh' : 'Tự động' }}</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-500 dark:text-gray-400">Thời hạn:</span>
                        <span class="font-semibold text-gray-900 dark:text-white">{{ number_format($key->duration_minutes) }} phút</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-500 dark:text-gray-400">Chi phí:</span>
                        <span class="font-semibold text-yellow-600 dark:text-yellow-400">{{ number_format($key->key_cost) }} 🪙</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-500 dark:text-gray-400">Kích hoạt lúc:</span>
                        <span class="font-semibold text-gray-900 dark:text-white">{{ $key->activated_at?->format('d/m/Y H:i') ?? 'Chưa kích hoạt' }}</span>
                    </div>
                    @if($key->assigned_to_email)
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-500 dark:text-gray-400">Gán cho:</span>
                        <span class="font-semibold text-gray-900 dark:text-white">{{ $key->assigned_to_email }}</span>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Usage Stats -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700 p-6">
                <h3 class="font-bold text-xl text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                    Thống Kê Sử Dụng
                </h3>
                <div class="space-y-3">
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-500 dark:text-gray-400">Số lần xác thực:</span>
                        <span class="font-semibold text-gray-900 dark:text-white">{{ number_format($key->validation_count) }}</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-500 dark:text-gray-400">Lần cuối xác thực:</span>
                        <span class="font-semibold text-gray-900 dark:text-white">{{ $key->last_validated_at?->diffForHumans() ?? 'Chưa bao giờ' }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Actions -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700 p-6">
            <h3 class="font-bold text-xl text-gray-900 dark:text-white mb-4">Hành Động</h3>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                
                @if($key->isActive())
                <a href="{{ route('keys.extend-form', $key->id) }}" class="px-4 py-3 bg-green-500 hover:bg-green-600 text-white rounded-xl font-bold text-center transition">
                    ⏱️ Gia Hạn
                </a>
                
                <form action="{{ route('keys.suspend', $key->id) }}" method="POST" onsubmit="return confirm('Tạm dừng key này?')">
                    @csrf
                    <button type="submit" class="w-full px-4 py-3 bg-yellow-500 hover:bg-yellow-600 text-white rounded-xl font-bold transition">
                        ⏸️ Tạm Dừng
                    </button>
                </form>
                @endif

                @if($key->isSuspended())
                <form action="{{ route('keys.activate', $key->id) }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full px-4 py-3 bg-blue-500 hover:bg-blue-600 text-white rounded-xl font-bold transition">
                        ▶️ Kích Hoạt
                    </button>
                </form>
                @endif

                @if(!$key->status === 'revoked')
                <form action="{{ route('keys.revoke', $key->id) }}" method="POST" onsubmit="return confirm('Thu hồi vĩnh viễn key này? Hành động không thể hoàn tác!')">
                    @csrf
                    <button type="submit" class="w-full px-4 py-3 bg-red-500 hover:bg-red-600 text-white rounded-xl font-bold transition">
                        🚫 Thu Hồi
                    </button>
                </form>
                @endif

                <a href="{{ route('keys.validation-logs', $key->id) }}" class="px-4 py-3 bg-gray-500 hover:bg-gray-600 text-white rounded-xl font-bold text-center transition">
                    📊 Lịch Sử
                </a>
            </div>
        </div>
    </div>

    <script>
    function copyToClipboard(text) {
        navigator.clipboard.writeText(text).then(() => {
            alert('✅ Đã copy: ' + text);
        });
    }
    </script>
</x-app-layout>