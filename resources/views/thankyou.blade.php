<x-app-layout>
    <div class="min-h-screen bg-gradient-to-br from-indigo-50 via-purple-50 to-pink-50 dark:from-gray-900 dark:via-purple-900/20 dark:to-pink-900/20 py-12 px-4">
        <div class="max-w-2xl mx-auto">
            
            @if($transaction->status === 'success')
                <!-- ✅ Success State -->
                <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-2xl overflow-hidden border-2 border-green-500">
                    <div class="bg-gradient-to-r from-green-500 to-emerald-600 p-8 text-center">
                        <div class="w-20 h-20 bg-white rounded-full flex items-center justify-center mx-auto mb-4 animate-bounce">
                            <svg class="w-10 h-10 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                        </div>
                        <h1 class="text-3xl font-bold text-white mb-2">Thanh Toán Thành Công!</h1>
                        <p class="text-green-100">Cảm ơn bạn đã mua hàng</p>
                    </div>

                    <div class="p-8 space-y-6">
                        <!-- Order Info -->
                        <div class="bg-gray-50 dark:bg-gray-700 rounded-xl p-6">
                            <h3 class="font-bold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                                <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                                Thông Tin Đơn Hàng
                            </h3>
                            <div class="space-y-3 text-sm">
                                <div class="flex justify-between items-center">
                                    <span class="text-gray-600 dark:text-gray-400">Mã đơn hàng:</span>
                                    <span class="font-mono font-bold text-gray-900 dark:text-white bg-gray-100 dark:bg-gray-600 px-3 py-1 rounded">
                                        {{ $transaction->order_code }}
                                    </span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600 dark:text-gray-400">Sản phẩm:</span>
                                    <span class="font-semibold text-gray-900 dark:text-white">{{ $product->name }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600 dark:text-gray-400">Số tiền:</span>
                                    <span class="font-bold text-green-600 dark:text-green-400">
                                        {{ number_format($transaction->amount) }}{{ $transaction->currency === 'VND' ? '₫' : ' Coinkey' }}
                                    </span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600 dark:text-gray-400">Thời gian:</span>
                                    <span class="text-gray-900 dark:text-white">{{ $transaction->created_at->format('d/m/Y H:i') }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Product Type Specific Info -->
                        @if($product->isCoinkeyPack())
                            <!-- Gói Nạp Coinkey -->
                            <div class="bg-gradient-to-br from-yellow-50 to-orange-50 dark:from-yellow-900/20 dark:to-orange-900/20 border-2 border-yellow-500 rounded-xl p-6 text-center">
                                <div class="text-6xl mb-4 animate-pulse">💰</div>
                                <h4 class="text-xl font-bold text-gray-900 dark:text-white mb-2">Nạp Coinkey Thành Công!</h4>
                                <p class="text-4xl font-bold text-yellow-600 dark:text-yellow-400 mb-2">
                                    +{{ number_format($product->coinkey_amount) }} Coinkey
                                </p>
                                <p class="text-sm text-gray-600 dark:text-gray-400">Đã được cộng vào ví của bạn</p>
                                <div class="mt-4 p-3 bg-white/50 dark:bg-gray-800/50 rounded-lg">
                                    <p class="text-xs text-gray-500 dark:text-gray-400">Bạn có thể sử dụng Coinkey để mua các gói dịch vụ</p>
                                </div>
                            </div>

                        @elseif($product->isServicePackage() && $key)
                            <!-- Gói Dịch Vụ - Hiển thị Key -->
                            <div class="bg-gradient-to-br from-indigo-50 to-purple-50 dark:from-indigo-900/20 dark:to-purple-900/20 border-2 border-indigo-500 rounded-xl p-6">
                                <div class="text-center mb-4">
                                    <div class="text-6xl mb-3 animate-bounce">🔑</div>
                                    <h4 class="text-xl font-bold text-gray-900 dark:text-white mb-2">Key Bản Quyền Của Bạn</h4>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">Vui lòng lưu lại key này để sử dụng</p>
                                </div>
                                
                                <div class="bg-white dark:bg-gray-800 rounded-lg p-4 mb-4 border-2 border-dashed border-indigo-300 dark:border-indigo-700">
                                    <div class="flex items-center justify-between gap-3">
                                        <code class="text-lg font-mono font-bold text-indigo-600 dark:text-indigo-400 break-all">
                                            {{ $key->key_code }}
                                        </code>
                                        <button onclick="copyKey('{{ $key->key_code }}')" 
                                                class="flex-shrink-0 p-2 bg-indigo-100 dark:bg-indigo-900/30 rounded-lg hover:bg-indigo-200 dark:hover:bg-indigo-900/50 transition group"
                                                title="Sao chép">
                                            <svg class="w-5 h-5 text-indigo-600 dark:text-indigo-400 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                                            </svg>
                                        </button>
                                    </div>
                                </div>

                                <div class="grid grid-cols-2 gap-4 text-sm">
                                    <div class="text-center p-3 bg-white/50 dark:bg-gray-800/50 rounded-lg">
                                        <p class="text-gray-600 dark:text-gray-400 mb-1">Trạng thái</p>
                                        <span class="inline-flex items-center gap-1 px-3 py-1 bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-300 rounded-full font-bold">
                                            <span class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></span>
                                            {{ ucfirst($key->status) }}
                                        </span>
                                    </div>
                                    <div class="text-center p-3 bg-white/50 dark:bg-gray-800/50 rounded-lg">
                                        <p class="text-gray-600 dark:text-gray-400 mb-1">Hết hạn</p>
                                        <p class="font-bold text-gray-900 dark:text-white">
                                            {{ $key->expires_at ? $key->expires_at->format('d/m/Y') : '∞ Vĩnh viễn' }}
                                        </p>
                                    </div>
                                </div>
                            </div>

                        @elseif($product->isServicePackage() && !$key)
                            <!-- Chờ tạo key -->
                            <div class="bg-yellow-50 dark:bg-yellow-900/20 border-2 border-yellow-500 rounded-xl p-6 text-center">
                                <div class="text-5xl mb-3">⏳</div>
                                <h4 class="font-bold text-gray-900 dark:text-white mb-2">Đang Tạo Key...</h4>
                                <p class="text-sm text-gray-600 dark:text-gray-400">Vui lòng đợi trong giây lát</p>
                                <button onclick="window.location.reload()" class="mt-4 px-4 py-2 bg-yellow-600 hover:bg-yellow-700 text-white rounded-lg transition">
                                    Làm mới trang
                                </button>
                            </div>
                        @endif

                        <!-- Action Buttons -->
                        <div class="flex flex-col sm:flex-row gap-3 pt-4">
                            @if($product->isServicePackage() && $key)
                                <a href="{{ route('keys.keydetails', $key->id) }}" 
                                   class="flex-1 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white font-bold rounded-xl text-center transition shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                                    <span class="flex items-center justify-center gap-2">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                        Xem Chi Tiết Key
                                    </span>
                                </a>
                            @else
                                <a href="{{ route('wallet.index') }}" 
                                   class="flex-1 py-3 bg-gradient-to-r from-yellow-600 to-orange-600 hover:from-yellow-700 hover:to-orange-700 text-white font-bold rounded-xl text-center transition shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                                    <span class="flex items-center justify-center gap-2">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                                        </svg>
                                        Xem Ví Của Tôi
                                    </span>
                                </a>
                            @endif
                            
                            <a href="{{ route('products') }}" 
                               class="flex-1 py-3 bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600 text-gray-800 dark:text-white font-bold rounded-xl text-center transition">
                                <span class="flex items-center justify-center gap-2">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                                    </svg>
                                    Tiếp Tục Mua Sắm
                                </span>
                            </a>
                        </div>
                    </div>
                </div>

            @elseif($transaction->status === 'cancelled')
                <!-- ❌ Cancelled State -->
                <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-2xl overflow-hidden border-2 border-red-500">
                    <div class="bg-gradient-to-r from-red-500 to-pink-600 p-8 text-center">
                        <div class="w-20 h-20 bg-white rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-10 h-10 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </div>
                        <h1 class="text-3xl font-bold text-white mb-2">Giao Dịch Đã Hủy</h1>
                        <p class="text-red-100">Thanh toán của bạn đã bị hủy</p>
                    </div>

                    <div class="p-8 text-center space-y-4">
                        <p class="text-gray-600 dark:text-gray-400">
                            Mã đơn hàng: <span class="font-mono font-bold">{{ $transaction->order_code }}</span>
                        </p>
                        <a href="{{ route('products') }}" 
                           class="inline-block px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-xl transition">
                            Thử Lại
                        </a>
                    </div>
                </div>

            @else
                <!-- ⏳ Pending/Processing State -->
                <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-2xl overflow-hidden border-2 border-yellow-500">
                    <div class="bg-gradient-to-r from-yellow-500 to-orange-600 p-8 text-center">
                        <div class="w-20 h-20 bg-white rounded-full flex items-center justify-center mx-auto mb-4 animate-spin">
                            <svg class="w-10 h-10 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                            </svg>
                        </div>
                        <h1 class="text-3xl font-bold text-white mb-2">Đang Xử Lý...</h1>
                        <p class="text-yellow-100">Vui lòng đợi trong giây lát</p>
                    </div>

                    <div class="p-8 text-center space-y-4">
                        <p class="text-gray-600 dark:text-gray-400">
                            Chúng tôi đang xác nhận thanh toán của bạn.<br>
                            Trang sẽ tự động cập nhật sau khi hoàn tất.
                        </p>
                        <button onclick="window.location.reload()" 
                                class="px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-xl transition">
                            Làm Mới Trang
                        </button>
                    </div>
                </div>
            @endif

        </div>
    </div>

    <script>
    function copyKey(keyCode) {
        navigator.clipboard.writeText(keyCode).then(() => {
            // Tạo toast notification
            const toast = document.createElement('div');
            toast.className = 'fixed top-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-xl z-50 animate-bounce';
            toast.innerHTML = '✅ Đã sao chép Key!';
            document.body.appendChild(toast);
            
            setTimeout(() => {
                toast.remove();
            }, 2000);
        }).catch(err => {
            alert('Lỗi sao chép: ' + err);
        });
    }

    // Auto refresh nếu đang pending
    @if($transaction->status === 'pending')
        let refreshCount = 0;
        const maxRefresh = 10;
        
        const refreshInterval = setInterval(() => {
            refreshCount++;
            if (refreshCount >= maxRefresh) {
                clearInterval(refreshInterval);
                alert('⚠️ Giao dịch đang được xử lý. Vui lòng kiểm tra lại sau.');
            } else {
                window.location.reload();
            }
        }, 3000);
    @endif
    </script>
</x-app-layout>