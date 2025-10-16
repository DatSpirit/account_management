<x-app-layout>
    <x-slot name="header">
        <!-- HEADER: Tăng cường độ đậm và thêm viền dưới tinh tế -->
        <div class="border-b border-gray-200 dark:border-gray-700 pb-3">
            <h2 class="font-extrabold text-3xl text-gray-900 dark:text-gray-50 leading-tight tracking-wider">
                {{ __('PERSONAL OVERVIEW') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-12 bg-gray-100 dark:bg-gray-900 transition-colors duration-300 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <!-- CARD CHÍNH: Nền trắng sạch, đổ bóng mềm mại, bo tròn hiện đại -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl rounded-2xl p-6 sm:p-10 border border-gray-200 dark:border-gray-700">
                @php $user = Auth::user(); @endphp

                <!-- PHẦN CHÀO MỪNG & THÔNG BÁO -->
                <div class="mb-10">
                    <h3 class="text-3xl sm:text-4xl font-black text-gray-900 dark:text-white mb-3">
                        👋 Welcome, <span class="text-indigo-600 dark:text-indigo-400">{{ $user->name }}</span>
                    </h3>
                    
                    <!-- Khối thông báo/Hành động chính -->
                    <div class="md:flex md:justify-between md:items-center p-5 rounded-xl border border-emerald-400 dark:border-emerald-600 bg-emerald-50 dark:bg-emerald-900/40">
                        <div class="md:w-3/4">
                             <p class="text-lg text-emerald-800 dark:text-emerald-200 font-bold mb-1">
                                Trạng thái tài khoản: Đang hoạt động
                            </p>
                            <p class="text-sm text-gray-700 dark:text-gray-300">
                                Tài khoản của bạn đã được kích hoạt từ {{ $user->created_at->diffForHumans() }}. Hãy kiểm tra thông tin dưới đây!
                            </p>
                        </div>
                        <div class="mt-4 md:mt-0 md:w-1/4 md:text-right">
                             <a href="{{ route('profile.edit') }}" 
                                class="inline-flex items-center justify-center 
                                        bg-indigo-600 hover:bg-indigo-700 text-white 
                                        font-medium text-sm px-4 py-2 rounded-lg 
                                        shadow transition-all duration-300">
                                ✏️ Chỉnh sửa hồ sơ
                            </a>
                        </div>
                    </div>
                </div>

                <!-- PHẦN THỐNG KÊ (STATISTICS) -->
                <h4 class="text-2xl font-bold text-gray-900 dark:text-white mb-6 border-b pb-2 border-gray-200 dark:border-gray-700">
                    Chỉ số hoạt động
                </h4>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">

                    <!-- Thẻ 1: Tổng bài viết -->
                    <div class="stat-card group bg-white dark:bg-gray-700 p-6 rounded-xl shadow-lg border-l-4 border-blue-500 hover:shadow-xl transition-all duration-300 transform hover:-translate-y-0.5">
                        <div class="flex justify-between items-start">
                            <p class="text-lg font-semibold text-gray-700 dark:text-gray-300">Tổng bài viết</p>
                            <svg class="w-6 h-6 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        </div>
                        <p class="mt-4 text-4xl font-extrabold text-gray-900 dark:text-white transition-colors duration-300 group-hover:text-blue-600">12</p>
                        <p class="text-sm text-green-600 dark:text-green-400 mt-2 font-medium">+8% so với tháng trước</p>
                    </div>

                    <!-- Thẻ 2: Bình luận -->
                    <div class="stat-card group bg-white dark:bg-gray-700 p-6 rounded-xl shadow-lg border-l-4 border-purple-500 hover:shadow-xl transition-all duration-300 transform hover:-translate-y-0.5">
                        <div class="flex justify-between items-start">
                            <p class="text-lg font-semibold text-gray-700 dark:text-gray-300">Bình luận</p>
                            <svg class="w-6 h-6 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path></svg>
                        </div>
                        <p class="mt-4 text-4xl font-extrabold text-gray-900 dark:text-white transition-colors duration-300 group-hover:text-purple-600">34</p>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-2">Tương tác cao trong tuần này</p>
                    </div>

                    <!-- Thẻ 3: Lượt truy cập -->
                    <div class="stat-card group bg-white dark:bg-gray-700 p-6 rounded-xl shadow-lg border-l-4 border-amber-500 hover:shadow-xl transition-all duration-300 transform hover:-translate-y-0.5">
                        <div class="flex justify-between items-start">
                            <p class="text-lg font-semibold text-gray-700 dark:text-gray-300">Lượt truy cập</p>
                            <svg class="w-6 h-6 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                        </div>
                        <p class="mt-4 text-4xl font-extrabold text-gray-900 dark:text-white transition-colors duration-300 group-hover:text-amber-600">1,245</p>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-2">Thống kê tháng {{ now()->format('m/Y') }}</p>
                    </div>

                </div>

                <!-- PHẦN HOẠT ĐỘNG GẦN ĐÂY -->
                <div class="mt-12">
                    <h4 class="text-2xl font-bold text-gray-900 dark:text-white mb-6 border-b pb-2 border-gray-200 dark:border-gray-700">
                        Hoạt động gần đây của bạn
                    </h4>

                    <ul class="space-y-4">
                        <!-- Hoạt động 1 -->
                        <li class="p-4 bg-gray-50 dark:bg-gray-700/50 rounded-lg shadow-sm border border-gray-100 dark:border-gray-700 flex justify-between items-center transition-colors duration-200 hover:bg-gray-100 dark:hover:bg-gray-700">
                            <div class="flex items-center">
                                <span class="text-indigo-500 mr-4">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                </span>
                                <span class="text-gray-800 dark:text-gray-200 font-medium">Bạn đã cập nhật hồ sơ cá nhân.</span>
                            </div>
                            <span class="text-xs text-gray-500 dark:text-gray-400">2 giờ trước</span>
                        </li>
                        
                        <!-- Hoạt động 2 -->
                        <li class="p-4 bg-gray-50 dark:bg-gray-700/50 rounded-lg shadow-sm border border-gray-100 dark:border-gray-700 flex justify-between items-center transition-colors duration-200 hover:bg-gray-100 dark:hover:bg-gray-700">
                            <div class="flex items-center">
                                <span class="text-green-500 mr-4">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.25 9 11.603 5.176-1.353 9-6.012 9-11.603 0-1.213-.24-2.387-.682-3.467z"></path></svg>
                                </span>
                                <span class="text-gray-800 dark:text-gray-200 font-medium">Xác minh email thành công.</span>
                            </div>
                            <span class="text-xs text-gray-500 dark:text-gray-400">1 ngày trước</span>
                        </li>
                        
                        <!-- Hoạt động 3 -->
                        <li class="p-4 bg-gray-50 dark:bg-gray-700/50 rounded-lg shadow-sm border border-gray-100 dark:border-gray-700 flex justify-between items-center transition-colors duration-200 hover:bg-gray-100 dark:hover:bg-gray-700">
                            <div class="flex items-center">
                                <span class="text-yellow-500 mr-4">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                                </span>
                                <span class="text-gray-800 dark:text-gray-200 font-medium">Đăng nhập từ thiết bị mới.</span>
                            </div>
                            <span class="text-xs text-gray-500 dark:text-gray-400">3 ngày trước</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
