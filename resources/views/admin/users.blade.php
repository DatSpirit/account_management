<x-app-layout>
    <!-- Toast Container -->
    <div id="toast-container" class="fixed top-4 right-4 z-[100] space-y-3"></div>

    <x-slot name="header">
        <div class="flex items-center justify-center space-x-3">
            <svg class="w-4 h-4 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
            </svg>
            <h2 class="font-bold text-2xl text-gray-800 dark:text-gray-100 tracking-tight">
                Quản Lý Người Dùng
            </h2>
        </div>
    </x-slot>

    <div class="py-8 px-4 sm:px-6 lg:px-8 min-h-screen">
        <div class="max-w-7xl mx-auto space-y-6">
            
            <!-- Header Section with Stats -->
            <div class="bg-gradient-to-r from-indigo-500 via-purple-500 to-pink-500 rounded-2xl shadow-xl p-6 sm:p-8 text-white">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div class="space-y-2">
                        <h3 class="text-xl sm:text-2xl font-bold">Dashboard Tổng Quan</h3>
                        <p class="text-indigo-100 text-sm sm:text-base">Quản lý tất cả người dùng trong hệ thống</p>
                    </div>
                    <div class="flex items-center space-x-6">
                        <div class="text-center">
                            <div class="text-3xl font-bold">{{ $users->total() }}</div>
                            <div class="text-xs text-indigo-100 uppercase tracking-wide">Tổng Users</div>
                        </div>
                        <div class="h-12 w-px bg-white/30"></div>
                        <div class="text-center">
                            <div class="text-3xl font-bold">{{ $users->currentPage() }}</div>
                            <div class="text-xs text-indigo-100 uppercase tracking-wide">Trang Hiện Tại</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Search & Filter Card -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700 p-6 transition-all duration-300 hover:shadow-xl">
                <form method="GET" action="{{ route('admin.users') }}" class="space-y-4">
                    <div class="flex flex-col lg:flex-row gap-4">
                        
                        <!-- Filter Dropdown -->
                        <div class="relative flex-shrink-0">
                            <label class="block text-xs font-semibold text-gray-600 dark:text-gray-400 mb-2 uppercase tracking-wide">
                                Lọc Theo
                            </label>
                            <select name="filter" id="filter-select"
                                class="w-full lg:w-40 px-4 py-3 bg-gray-50 dark:bg-gray-700 border-2 border-gray-200 dark:border-gray-600 
                                       rounded-xl text-sm font-medium text-gray-700 dark:text-gray-200
                                       focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/20 
                                       transition-all duration-200 cursor-pointer hover:border-indigo-400">
                                <option value="name" {{ request('filter') === 'name' ? 'selected' : '' }}>👤 Tên</option>
                                <option value="email" {{ request('filter') === 'email' ? 'selected' : '' }}>📧 Email</option>
                                <option value="id" {{ request('filter') === 'id' ? 'selected' : '' }}>🔢 ID</option>
                            </select>
                        </div>

                        <!-- Search Input -->
                        <div class="flex-1">
                            <label class="block text-xs font-semibold text-gray-600 dark:text-gray-400 mb-2 uppercase tracking-wide">
                                Tìm Kiếm
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                              d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                    </svg>
                                </div>
                                <input type="text" name="search" id="search-input" 
                                       value="{{ request('search') }}"
                                       placeholder="Nhập từ khóa tìm kiếm..."
                                       class="w-full pl-12 pr-4 py-3 bg-gray-50 dark:bg-gray-700 border-2 border-gray-200 dark:border-gray-600 
                                              rounded-xl text-sm font-medium text-gray-700 dark:text-gray-200 placeholder-gray-400
                                              focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/20 
                                              transition-all duration-200">
                                
                                <!-- Suggestions Dropdown -->
                                <ul id="suggestions" class="absolute z-50 mt-2 w-full bg-white dark:bg-gray-800 
                                    border-2 border-gray-200 dark:border-gray-700 rounded-xl shadow-2xl hidden overflow-hidden">
                                </ul>
                            </div>
                        </div>

                        <!-- Search Button -->
                        <div class="flex-shrink-0 self-end">
                            <button type="submit"
                                class="w-full lg:w-auto px-7 py-4 bg-gradient-to-r from-indigo-600 to-purple-600 
                                       hover:from-indigo-700 hover:to-purple-700 text-white font-semibold rounded-xl 
                                       shadow-lg hover:shadow-xl transform hover:-translate-y-0.5
                                       transition-all duration-200 flex items-center justify-center space-x-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                          d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                </svg>
                                <span>Search</span>
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Table Card -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="bg-gradient-to-r from-gray-50 to-gray-100 dark:from-gray-700 dark:to-gray-800 border-b-2 border-gray-200 dark:border-gray-600">
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider
                                    {{ $filter === 'id' ? 'bg-indigo-100 dark:bg-indigo-900/50' : '' }}">
                                    <div class="flex items-center space-x-2">
                                        <span>ID</span>
                                        @if($filter === 'id')
                                            <svg class="w-4 h-4 text-indigo-600" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M3 3a1 1 0 011-1h12a1 1 0 011 1v3a1 1 0 01-.293.707L12 11.414V15a1 1 0 01-.293.707l-2 2A1 1 0 018 17v-5.586L3.293 6.707A1 1 0 013 6V3z" clip-rule="evenodd"/>
                                            </svg>
                                        @endif
                                    </div>
                                </th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider
                                    {{ $filter === 'name' ? 'bg-indigo-100 dark:bg-indigo-900/50' : '' }}">
                                    <div class="flex items-center space-x-2">
                                        <span>Tên</span>
                                        @if($filter === 'name')
                                            <svg class="w-4 h-4 text-indigo-600" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M3 3a1 1 0 011-1h12a1 1 0 011 1v3a1 1 0 01-.293.707L12 11.414V15a1 1 0 01-.293.707l-2 2A1 1 0 018 17v-5.586L3.293 6.707A1 1 0 013 6V3z" clip-rule="evenodd"/>
                                            </svg>
                                        @endif
                                    </div>
                                </th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider
                                    {{ $filter === 'email' ? 'bg-indigo-100 dark:bg-indigo-900/50' : '' }}">
                                    <div class="flex items-center space-x-2">
                                        <span>Email</span>
                                        @if($filter === 'email')
                                            <svg class="w-4 h-4 text-indigo-600" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M3 3a1 1 0 011-1h12a1 1 0 011 1v3a1 1 0 01-.293.707L12 11.414V15a1 1 0 01-.293.707l-2 2A1 1 0 018 17v-5.586L3.293 6.707A1 1 0 013 6V3z" clip-rule="evenodd"/>
                                            </svg>
                                        @endif
                                    </div>
                                </th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                                    Ngày Tham Gia
                                </th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                                    Vai Trò
                                </th>
                                <th class="px-6 py-4 text-center text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                                    Hành Động
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse ($users as $user)
                                <tr class="hover:bg-indigo-50/50 dark:hover:bg-gray-700/50 transition-colors duration-150 group">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-100
                                        {{ $filter === 'id' ? 'bg-indigo-50/50 dark:bg-indigo-900/20' : '' }}">
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-lg bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 font-mono text-xs">
                                            #{{ $user->id }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap
                                        {{ $filter === 'name' ? 'bg-indigo-50/50 dark:bg-indigo-900/20' : '' }}">
                                        <div class="flex items-center space-x-3">
                                            <div class="flex-shrink-0 h-10 w-10 rounded-full bg-gradient-to-br from-indigo-400 to-purple-500 flex items-center justify-center text-white font-bold shadow-lg">
                                                {{ strtoupper(substr($user->name, 0, 1)) }}
                                            </div>
                                            <div>
                                                <div class="text-sm font-semibold text-gray-900 dark:text-gray-100">
                                                    {{ $user->name }}
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-300
                                        {{ $filter === 'email' ? 'bg-indigo-50/50 dark:bg-indigo-900/20' : '' }}">
                                        <div class="flex items-center space-x-2">
                                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                      d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                            </svg>
                                            <span class="font-medium">{{ $user->email }}</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-300">
                                        <div class="flex items-center space-x-2">
                                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                      d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                            </svg>
                                            <span>{{ $user->created_at->format('d/m/Y') }}</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if ($user->is_admin)
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold 
                                                bg-gradient-to-r from-green-400 to-emerald-500 text-white shadow-lg">
                                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                                </svg>
                                                Admin
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold 
                                                bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300">
                                                User
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        <div class="flex items-center justify-center space-x-2">
                                            <!-- View Button -->
                                            <button type="button" data-id="{{ $user->id }}" title="Xem chi tiết"
                                                class="view-btn group/btn p-2.5 rounded-lg bg-indigo-500 hover:bg-indigo-600 
                                                       text-white shadow-md hover:shadow-lg transform hover:scale-110 
                                                       transition-all duration-200">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                          d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                          d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                                </svg>
                                            </button>

                                            <!-- Edit Button -->
                                            <a href="{{ route('admin.edit', $user->id) }}" title="Chỉnh sửa"
                                                class="p-2.5 rounded-lg bg-blue-500 hover:bg-blue-600 
                                                       text-white shadow-md hover:shadow-lg transform hover:scale-110 
                                                       transition-all duration-200">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                          d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                                </svg>
                                            </a>

                                            <!-- Delete Button -->
                                            <form action="{{ route('admin.destroy', $user->id) }}" method="POST" 
                                                  onsubmit="return confirm('Bạn có chắc muốn xóa người dùng này không?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" title="Xóa"
                                                    class="p-2.5 rounded-lg bg-red-500 hover:bg-red-600 
                                                           text-white shadow-md hover:shadow-lg transform hover:scale-110 
                                                           transition-all duration-200">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                              d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                    </svg>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-16 text-center">
                                        <div class="flex flex-col items-center justify-center space-y-4">
                                            <div class="w-24 h-24 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center">
                                                <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                          d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                                                </svg>
                                            </div>
                                            <div class="space-y-2">
                                                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                                                    Không tìm thấy người dùng
                                                </h3>
                                                <p class="text-sm text-gray-500 dark:text-gray-400">
                                                    Thử thay đổi bộ lọc hoặc từ khóa tìm kiếm
                                                </p>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if($users->hasPages())
                    <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900/50">
                        {{ $users->links('vendor.pagination.tailwind') }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Enhanced Modal -->
    <div id="previewModal" class="hidden fixed inset-0 z-[90] flex items-center justify-center p-4">
        <div class="absolute inset-0 bg-black/60 backdrop-blur-sm transition-opacity" 
             onclick="closeModal()"></div>
        
        <div class="relative bg-white dark:bg-gray-800 rounded-2xl shadow-2xl w-full max-w-lg 
                    transform transition-all duration-300 scale-95 opacity-0" 
             id="modalContent">
            
            <!-- Modal Header -->
            <div class="flex items-center justify-between p-6 border-b border-gray-200 dark:border-gray-700">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 rounded-full bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                  d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 dark:text-gray-100">
                        Thông Tin Chi Tiết
                    </h3>
                </div>
                <button onclick="closeModal()" 
                    class="p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                    <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <!-- Modal Body -->
            <div class="p-6 space-y-4 max-h-[60vh] overflow-y-auto">
                <div class="space-y-3">
                    <div class="flex items-center justify-between p-3 rounded-lg bg-gray-50 dark:bg-gray-700/50">
                        <span class="text-sm font-medium text-gray-600 dark:text-gray-400">ID</span>
                        <span id="preview-id" class="text-sm font-bold text-gray-900 dark:text-gray-100"></span>
                    </div>
                    <div class="flex items-center justify-between p-3 rounded-lg bg-gray-50 dark:bg-gray-700/50">
                        <span class="text-sm font-medium text-gray-600 dark:text-gray-400">Tên</span>
                        <span id="preview-name" class="text-sm font-bold text-gray-900 dark:text-gray-100"></span>
                    </div>
                    <div class="flex items-center justify-between p-3 rounded-lg bg-gray-50 dark:bg-gray-700/50">
                        <span class="text-sm font-medium text-gray-600 dark:text-gray-400">Email</span>
                        <span id="preview-email" class="text-sm font-bold text-gray-900 dark:text-gray-100"></span>
                    </div>
                    <div class="flex items-center justify-between p-3 rounded-lg bg-gray-50 dark:bg-gray-700/50">
                        <span class="text-sm font-medium text-gray-600 dark:text-gray-400">Vai trò Admin</span>
                        <span id="preview-is-admin" class="text-sm font-bold"></span>
                    </div>
                    <div class="flex items-center justify-between p-3 rounded-lg bg-gray-50 dark:bg-gray-700/50">
                        <span class="text-sm font-medium text-gray-600 dark:text-gray-400">Ngày hết hạn TK</span>
                        <span id="preview-expires-at" class="text-sm font-bold"></span>
                    </div>
                    <div class="flex items-center justify-between p-3 rounded-lg bg-gray-50 dark:bg-gray-700/50">
                        <span class="text-sm font-medium text-gray-600 dark:text-gray-400">Xác thực 2FA</span>
                        <span id="preview-2fa" class="text-sm font-bold"></span>
                    </div>
                    <div class="flex items-center justify-between p-3 rounded-lg bg-gray-50 dark:bg-gray-700/50">
                        <span class="text-sm font-medium text-gray-600 dark:text-gray-400">Ngày tham gia</span>
                        <span id="preview-created" class="text-sm font-bold text-gray-900 dark:text-gray-100"></span>
                    </div>
                    <div class="flex items-center justify-between p-3 rounded-lg bg-gray-50 dark:bg-gray-700/50">
                        <span class="text-sm font-medium text-gray-600 dark:text-gray-400">Cập nhật gần nhất</span>
                        <span id="preview-updated" class="text-sm font-bold text-gray-900 dark:text-gray-100"></span>
                    </div>
                </div>
            </div>

            <!-- Modal Footer -->
            <div class="flex items-center justify-end space-x-3 p-6 border-t border-gray-200 dark:border-gray-700">
                <button onclick="closeModal()"
                    class="px-6 py-2.5 bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 
                           text-white font-semibold rounded-lg shadow-lg hover:shadow-xl 
                           transform hover:-translate-y-0.5 transition-all duration-200">
                    Đóng
                </button>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script>
        // Toast Notification System
        function showToast(message, type = 'success') {
            const container = document.getElementById('toast-container');
            const toast = document.createElement('div');
            
            const icons = {
                success: '<svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>',
                error: '<svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg>'
            };
            
            const colors = {
                success: 'bg-green-50 dark:bg-green-900/50 text-green-800 dark:text-green-200 border-green-200 dark:border-green-800',
                error: 'bg-red-50 dark:bg-red-900/50 text-red-800 dark:text-red-200 border-red-200 dark:border-red-800'
            };
            
            toast.className = `flex items-center space-x-3 px-6 py-4 rounded-xl shadow-2xl border-2 ${colors[type]} 
                              transform transition-all duration-300 translate-x-full opacity-0`;
            toast.innerHTML = `
                <div class="flex-shrink-0">${icons[type]}</div>
                <p class="font-medium text-sm">${message}</p>
                <button onclick="this.parentElement.remove()" class="ml-4 hover:opacity-70 transition-opacity">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                    </svg>
                </button>
            `;
            
            container.appendChild(toast);
            
            setTimeout(() => {
                toast.classList.remove('translate-x-full', 'opacity-0');
            }, 100);
            
            setTimeout(() => {
                toast.classList.add('translate-x-full', 'opacity-0');
                setTimeout(() => toast.remove(), 300);
            }, 5000);
        }

        // Show flash messages as toasts
        @if(session('success'))
            showToast("{{ session('success') }}", 'success');
        @endif
        @if(session('error'))
            showToast("{{ session('error') }}", 'error');
        @endif

        // Modal Functions
        function closeModal() {
            const modal = document.getElementById('previewModal');
            const content = document.getElementById('modalContent');
            content.classList.add('scale-95', 'opacity-0');
            setTimeout(() => modal.classList.add('hidden'), 300);
        }

        function openModal() {
            const modal = document.getElementById('previewModal');
            const content = document.getElementById('modalContent');
            modal.classList.remove('hidden');
            setTimeout(() => {
                content.classList.remove('scale-95', 'opacity-0');
                content.classList.add('scale-100', 'opacity-100');
            }, 10);
        }

        // View User Details
        document.addEventListener('DOMContentLoaded', () => {
            const buttons = document.querySelectorAll('.view-btn');

            const formatDateTime = (isoString) => {
                if (!isoString) return 'N/A';
                try {
                    const date = new Date(isoString);
                    return date.toLocaleString('vi-VN', {
                        year: 'numeric',
                        month: '2-digit',
                        day: '2-digit',
                        hour: '2-digit',
                        minute: '2-digit',
                        second: '2-digit',
                        hour12: false
                    });
                } catch (e) {
                    return isoString;
                }
            };

            buttons.forEach(btn => {
                btn.addEventListener('click', () => {
                    const id = btn.dataset.id;
                    const originalContent = btn.innerHTML;

                    btn.innerHTML = `
                        <svg class="animate-spin h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                    `;
                    btn.disabled = true;

                    fetch(`/admin/users/${id}/show`)
                        .then(res => {
                            if (!res.ok) throw new Error(`HTTP ${res.status}`);
                            return res.json();
                        })
                        .then(user => {
                            document.getElementById('preview-id').textContent = user.id || 'N/A';
                            document.getElementById('preview-name').textContent = user.name || 'N/A';
                            document.getElementById('preview-email').textContent = user.email || 'N/A';

                            const isAdmin = user.is_admin || false;
                            const isAdminSpan = document.getElementById('preview-is-admin');
                            isAdminSpan.innerHTML = isAdmin ? 
                                '<span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200">Có</span>' :
                                '<span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200">Không</span>';

                            const expiresAtValue = user.expires_at;
                            const expiresAtSpan = document.getElementById('preview-expires-at');
                            expiresAtSpan.innerHTML = expiresAtValue ? 
                                `<span class="text-yellow-600 dark:text-yellow-400">${expiresAtValue}</span>` :
                                '<span class="text-green-600 dark:text-green-400">Không giới hạn</span>';

                            const twoFaValue = user.two_factor_enabled;
                            const twoFaSpan = document.getElementById('preview-2fa');
                            twoFaSpan.innerHTML = twoFaValue ?
                                '<span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200">Có</span>' :
                                '<span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200">Không</span>';

                            document.getElementById('preview-created').textContent = formatDateTime(user.created_at);
                            document.getElementById('preview-updated').textContent = formatDateTime(user.updated_at);

                            openModal();
                            btn.innerHTML = originalContent;
                            btn.disabled = false;
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            showToast('Không thể tải thông tin người dùng', 'error');
                            btn.innerHTML = originalContent;
                            btn.disabled = false;
                        });
                });
            });

            // Close modal on ESC key
            document.addEventListener('keydown', (e) => {
                if (e.key === 'Escape') closeModal();
            });
        });

        // Autocomplete Search
        document.addEventListener('DOMContentLoaded', () => {
            const searchInput = document.getElementById('search-input');
            const suggestionsBox = document.getElementById('suggestions');
            const filterSelect = document.getElementById('filter-select');
            let debounceTimer;

            searchInput.addEventListener('input', function() {
                const query = this.value.trim();
                const filter = filterSelect.value;

                clearTimeout(debounceTimer);
                debounceTimer = setTimeout(() => {
                    if (query.length < 2) {
                        suggestionsBox.classList.add('hidden');
                        suggestionsBox.innerHTML = '';
                        return;
                    }

                    fetch(`/admin/users/suggestions?q=${encodeURIComponent(query)}&filter=${filter}`)
                        .then(response => response.json())
                        .then(data => {
                            suggestionsBox.innerHTML = '';

                            if (data.length === 0) {
                                suggestionsBox.classList.add('hidden');
                                return;
                            }

                            data.forEach(user => {
                                const li = document.createElement('li');
                                li.className = "px-4 py-3 cursor-pointer hover:bg-indigo-50 dark:hover:bg-gray-700 transition-colors border-b last:border-b-0 border-gray-100 dark:border-gray-700";
                                
                                const displayText = filter === 'email' ? user.email :
                                    filter === 'id' ? `#${user.id}` :
                                    `${user.name} — ${user.email}`;
                                
                                li.innerHTML = `
                                    <div class="flex items-center space-x-3">
                                        <div class="flex-shrink-0 w-8 h-8 rounded-full bg-gradient-to-br from-indigo-400 to-purple-500 flex items-center justify-center text-white text-xs font-bold">
                                            ${user.name ? user.name.charAt(0).toUpperCase() : '?'}
                                        </div>
                                        <span class="text-sm font-medium text-gray-700 dark:text-gray-200">${displayText}</span>
                                    </div>
                                `;

                                li.addEventListener('click', () => {
                                    searchInput.value = filter === 'email' ? user.email :
                                        filter === 'id' ? user.id : user.name;
                                    suggestionsBox.classList.add('hidden');
                                });

                                suggestionsBox.appendChild(li);
                            });

                            suggestionsBox.classList.remove('hidden');
                        })
                        .catch(() => suggestionsBox.classList.add('hidden'));
                }, 300);
            });

            document.addEventListener('click', (e) => {
                if (!suggestionsBox.contains(e.target) && e.target !== searchInput) {
                    suggestionsBox.classList.add('hidden');
                }
            });
        });
    </script>
</x-app-layout>