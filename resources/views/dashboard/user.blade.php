<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Bảng Điều Khiển Cá Nhân') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Card chính -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg p-6 lg:p-8">
                @php $user = Auth::user(); @endphp

                <h3 class="text-3xl font-bold text-gray-900 dark:text-gray-100 mb-6">
                    Chào mừng trở lại, {{ $user->name }}!
                </h3>

                <!-- Thông tin tài khoản -->
                <div class="bg-green-100 dark:bg-green-900/50 p-6 rounded-xl shadow">
                    <p class="text-green-800 dark:text-green-200 font-semibold">
                        Tài khoản của bạn đã hoạt động {{ $user->created_at->diffForHumans() }}.
                    </p>
                    <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                        Cảm ơn bạn đã đồng hành cùng hệ thống!
                    </p>
                </div>

                <!-- Các nút hành động -->
                <div class="mt-8 flex gap-4 flex-wrap">
                    <a href="{{ route('profile.edit') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg shadow">
                        ✏️ Chỉnh sửa hồ sơ
                    </a>
                    {{-- <a href="{{ route('support.contact') }}" class="bg-teal-600 hover:bg-teal-700 text-white px-4 py-2 rounded-lg shadow">
                        💬 Liên hệ hỗ trợ
                    </a> --}}
                </div>

                <!-- Thẻ thống kê (ví dụ) -->
                <div class="mt-10 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    <!-- Thẻ 1: Tổng bài viết -->
                    <div class="bg-blue-100 dark:bg-blue-900/50 p-6 rounded-xl shadow">
                        <p class="text-xl font-semibold text-blue-800 dark:text-blue-200">Tổng bài viết</p>
                        <p class="mt-2 text-3xl font-bold text-gray-900 dark:text-gray-100">12</p>
                    </div>

                    <!-- Thẻ 2: Bình luận -->
                    <div class="bg-purple-100 dark:bg-purple-900/50 p-6 rounded-xl shadow">
                        <p class="text-xl font-semibold text-purple-800 dark:text-purple-200">Bình luận</p>
                        <p class="mt-2 text-3xl font-bold text-gray-900 dark:text-gray-100">34</p>
                    </div>

                    <!-- Thẻ 3: Lượt truy cập -->
                    <div class="bg-yellow-100 dark:bg-yellow-900/50 p-6 rounded-xl shadow">
                        <p class="text-xl font-semibold text-yellow-800 dark:text-yellow-200">Lượt truy cập</p>
                        <p class="mt-2 text-3xl font-bold text-gray-900 dark:text-gray-100">1,245</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
