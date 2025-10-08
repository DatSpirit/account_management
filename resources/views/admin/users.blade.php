<x-app-layout>
    <!-- Flash Messages -->
    <x-flash-message type="success" :message="session('success')" />
    <x-flash-message type="error" :message="session('error')" />

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight text-center">
            {{ __('Admin - User Management Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Header + Search -->
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6">
                <div class="text-center sm:text-left">
                    <h3 class="text-2xl font-bold text-gray-900 dark:text-gray-100">
                        {{ __('Danh sách người dùng') }}
                    </h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400">
                        {{ __('Quản lý tất cả người dùng đã đăng ký trong hệ thống.') }}
                    </p>
                </div>

                <!-- Search bar -->
                <form method="GET" action="{{ route('admin.users') }}" class="mt-4 sm:mt-0 flex items-center space-x-2">
                    <div class="relative w-64">
                        <!-- Icon kính lúp -->
                        <svg xmlns="http://www.w3.org/2000/svg" class="absolute left-3 top-2.5 h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-4.35-4.35m0 0A7.5 7.5 0 1116.65 16.65z" />
                        </svg>
                        <input type="text" name="search" placeholder="Tìm kiếm tên hoặc email"
                            value="{{ request('search') }}"
                            class="pl-10 pr-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-indigo-400 w-full placeholder-gray-400 dark:placeholder-gray-500">
                    </div>
                    <button type="submit"
                        class="px-5 py-2 font-semibold text-black bg-gradient-to-r from-gray-900 to-gray-700 
               hover:from-gray-800 hover:to-gray-600 rounded-md shadow-md 
               transition-all duration-200 transform hover:scale-[1.03]">
                        <span>{{ __('Search') }}</span>
                    </button>
                </form>
            </div>

            <!-- Bảng danh sách -->
            <div class="overflow-x-auto bg-white dark:bg-gray-800 rounded-xl shadow-lg ring-1 ring-gray-200 dark:ring-gray-700">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 text-center">
                    <thead class="bg-indigo-100 dark:bg-gray-700">
                        <tr>
                            <th class="px-6 py-3 text-xs font-semibold text-gray-700 dark:text-gray-200 uppercase tracking-wider">ID</th>
                            <th class="px-6 py-3 text-xs font-semibold text-gray-700 dark:text-gray-200 uppercase tracking-wider">Tên</th>
                            <th class="px-6 py-3 text-xs font-semibold text-gray-700 dark:text-gray-200 uppercase tracking-wider">Email</th>
                            <th class="px-6 py-3 text-xs font-semibold text-gray-700 dark:text-gray-200 uppercase tracking-wider">Ngày tham gia</th>
                            <th class="px-6 py-3 text-xs font-semibold text-gray-700 dark:text-gray-200 uppercase tracking-wider">Vai trò</th>
                            <th class="px-6 py-3 text-xs font-semibold text-gray-700 dark:text-gray-200 uppercase tracking-wider">Hành động</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse ($users as $user)
                            <tr class="hover:bg-indigo-50 dark:hover:bg-gray-700 transition">
                                <td class="px-6 py-4 text-sm font-medium text-gray-900 dark:text-gray-100">{{ $user->id }}</td>
                                <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-100">{{ $user->name }}</td>
                                <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-300">{{ $user->email }}</td>
                                <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-300">{{ $user->created_at->format('d/m/Y') }}</td>
                                <td class="px-6 py-4">
                                    @if ($user->is_admin)
                                        <span class="px-3 py-1 inline-flex text-xs font-semibold rounded-full bg-green-100 text-green-800 dark:bg-green-700 dark:text-green-100">
                                            Admin
                                        </span>
                                    @else
                                        <span class="px-3 py-1 inline-flex text-xs font-semibold rounded-full bg-gray-100 text-gray-800 dark:bg-gray-600 dark:text-gray-100">
                                            User
                                        </span>
                                    @endif
                                </td>

                                <td class="px-6 py-4">
                                    <div class="flex justify-center space-x-2">
                                        <!-- Nút Edit -->
                                        <a href="{{ route('admin.edit', $user->id) }}" 
                                           class="px-3 py-1 text-xs font-semibold rounded-md text-white bg-blue-600 hover:bg-blue-700 shadow-sm transition">
                                            {{ __('Edit') }}
                                        </a>

                                        <!-- Nút Delete -->
                                        <form action="{{ route('admin.destroy', $user->id) }}" method="POST" 
                                              onsubmit="return confirm('Bạn có chắc muốn xóa người dùng này không?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="px-3 py-1 text-xs font-semibold rounded-md text-white bg-red-600 hover:bg-red-700 shadow-sm transition">
                                                {{ __('Delete') }}
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-4 text-center text-gray-500 dark:text-gray-300">
                                    {{ __('Không có người dùng nào được tìm thấy.') }}
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Phân trang -->
            <div class="mt-6 flex justify-center">
                {{ $users->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
