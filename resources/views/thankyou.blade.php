<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-center space-x-3">
            <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <h2 class="font-bold text-2xl text-gray-800 dark:text-gray-100 tracking-tight">
                Thanh Toán Thành Công
            </h2>
        </div>
    </x-slot>

    <div class="py-12 px-4 sm:px-6 lg:px-8 min-h-screen bg-gray-50 dark:bg-gray-900">
        <div class="max-w-4xl mx-auto">
            
            <!-- Success Card -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl border border-gray-200 dark:border-gray-700 overflow-hidden">
                
                <!-- Success Icon Section -->
                <div class="bg-gradient-to-r from-green-500 via-emerald-500 to-teal-500 dark:from-green-600 dark:via-emerald-600 dark:to-teal-600 p-12 text-center">
                    <div class="inline-flex items-center justify-center w-24 h-24 bg-white/20 backdrop-blur-sm rounded-full mb-6 animate-bounce">
                        <svg class="w-16 h-16 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                    </div>
                    <h1 class="text-4xl sm:text-5xl font-bold text-white mb-4">
                        🎉 Thanh Toán Thành Công!
                    </h1>
                    <p class="text-xl text-white/90">
                        Đơn hàng của bạn đã được xác nhận
                    </p>
                </div>

                <!-- Content Section -->
                <div class="p-8 sm:p-12 space-y-8">
                    
                    <!-- Thank You Message -->
                    <div class="text-center space-y-4">
                        <div class="inline-flex items-center justify-center w-16 h-16 bg-green-100 dark:bg-green-900/30 rounded-full">
                            <svg class="w-8 h-8 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <h2 class="text-2xl font-bold text-gray-900 dark:text-gray-100">
                            Cảm ơn bạn đã mua hàng!
                        </h2>
                        <p class="text-gray-600 dark:text-gray-400 max-w-md mx-auto">
                            Giao dịch qua <span class="font-semibold text-indigo-600 dark:text-indigo-400">payOS</span> đã được xử lý thành công. Chúng tôi sẽ gửi email xác nhận đến bạn trong giây lát.
                        </p>
                    </div>

                    <!-- Order Info -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="p-6 bg-gray-50 dark:bg-gray-900/50 rounded-xl border border-gray-200 dark:border-gray-700 text-center">
                            <svg class="w-10 h-10 text-indigo-600 dark:text-indigo-400 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                            </svg>
                            <p class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-1">Mã đơn hàng</p>
                            <p class="text-sm font-bold text-gray-900 dark:text-gray-100">#{{ request('orderCode', 'N/A') }}</p>
                        </div>

                        <div class="p-6 bg-gray-50 dark:bg-gray-900/50 rounded-xl border border-gray-200 dark:border-gray-700 text-center">
                            <svg class="w-10 h-10 text-green-600 dark:text-green-400 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <p class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-1">Trạng thái</p>
                            <p class="text-sm font-bold text-green-600 dark:text-green-400">Đã thanh toán</p>
                        </div>

                        <div class="p-6 bg-gray-50 dark:bg-gray-900/50 rounded-xl border border-gray-200 dark:border-gray-700 text-center">
                            <svg class="w-10 h-10 text-purple-600 dark:text-purple-400 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            <p class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-1">Ngày</p>
                            <p class="text-sm font-bold text-gray-900 dark:text-gray-100">{{ now()->format('d/m/Y') }}</p>
                        </div>
                    </div>

                    <!-- Next Steps -->
                    <div class="bg-blue-50 dark:bg-blue-900/20 border-2 border-blue-200 dark:border-blue-800 rounded-xl p-6">
                        <div class="flex items-start space-x-3">
                            <svg class="w-6 h-6 text-blue-600 dark:text-blue-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <div class="flex-1">
                                <h3 class="text-sm font-bold text-blue-900 dark:text-blue-300 mb-2">Bước tiếp theo:</h3>
                                <ul class="space-y-2 text-sm text-blue-800 dark:text-blue-400">
                                    <li class="flex items-center space-x-2">
                                        <svg class="w-4 h-4 text-blue-600 dark:text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                        </svg>
                                        <span>Email xác nhận đã được gửi đến hộp thư của bạn</span>
                                    </li>
                                    <li class="flex items-center space-x-2">
                                        <svg class="w-4 h-4 text-blue-600 dark:text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                        </svg>
                                        <span>Đơn hàng sẽ được xử lý và giao trong 2-3 ngày làm việc</span>
                                    </li>
                                    <li class="flex items-center space-x-2">
                                        <svg class="w-4 h-4 text-blue-600 dark:text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                        </svg>
                                        <span>Bạn có thể theo dõi đơn hàng qua dashboard</span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex flex-col sm:flex-row gap-4 pt-4">
                        <a href="{{ route('products') }}" 
                           class="flex-1 px-8 py-4 bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 dark:from-indigo-500 dark:to-purple-600 dark:hover:from-indigo-600 dark:hover:to-purple-700 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-200 text-center">
                            <span class="flex items-center justify-center space-x-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                                </svg>
                                <span>Tiếp tục mua sắm</span>
                            </span>
                        </a>
                        <a href="{{ route('dashboard') }}" 
                           class="flex-1 px-8 py-4 bg-gray-200 hover:bg-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-800 dark:text-gray-200 font-semibold rounded-xl shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-200 text-center">
                            <span class="flex items-center justify-center space-x-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                                </svg>
                                <span>Về trang chủ</span>
                            </span>
                        </a>
                    </div>

                    <!-- Support Section -->
                    <div class="text-center pt-6 border-t border-gray-200 dark:border-gray-700">
                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-3">
                            Cần hỗ trợ? Liên hệ với chúng tôi
                        </p>
                        <div class="flex items-center justify-center space-x-6 text-sm">
                            <a href="mailto:support@example.com" class="flex items-center space-x-2 text-indigo-600 dark:text-indigo-400 hover:text-indigo-700 dark:hover:text-indigo-300 transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                </svg>
                                <span class="font-medium">Email</span>
                            </a>
                            <a href="tel:+84123456789" class="flex items-center space-x-2 text-indigo-600 dark:text-indigo-400 hover:text-indigo-700 dark:hover:text-indigo-300 transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                </svg>
                                <span class="font-medium">Hotline</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Footer Note -->
            <div class="text-center mt-8">
                <p class="text-sm text-gray-500 dark:text-gray-400">
                    Powered by <span class="font-semibold text-indigo-600 dark:text-indigo-400">payOS</span> • 
                    Giao dịch được bảo mật và mã hóa
                </p>
            </div>
        </div>
    </div>

    <script>
        // Confetti animation (optional)
        document.addEventListener('DOMContentLoaded', () => {
            // Show success toast if needed
            console.log('✅ Payment successful!');
        });
    </script>
</x-app-layout>