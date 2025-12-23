<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div class="flex items-center space-x-3">
                <div class="p-2 bg-indigo-600 rounded-lg shadow-lg shadow-indigo-500/30">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
                    </svg>
                </div>
                <h2 class="font-bold text-2xl text-gray-800 dark:text-white tracking-tight">
                    Keys Management
                </h2>
            </div>
        </div>
    </x-slot>

    <div class="py-8 px-4 sm:px-6 lg:px-8 min-h-screen bg-gray-50 dark:bg-gray-900">
        <div class="max-w-7xl mx-auto space-y-6">
            <div class="bg-gradient-to-r from-blue-500 dark:from-blue-700 rounded-2xl shadow-xl p-6 sm:p-8 text-white">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div class="space-y-2">
                        <h3 class="text-xl sm:text-2xl font-bold text-gray-800 dark:text-white">Keys Overview
                        </h3>
                        <p class="text-blue-800 dark:text-white text-sm sm:text-base">Quản lý tất cả keys</p>
                    </div>
                    <div class="flex items-center space-x-6">
                        <div class="text-center">
                            <div class="text-3xl font-bold text-gray-800 dark:text-white">{{ $stats['total'] }}</div>
                            <div class="text-xs text-blue-800 dark:text-white uppercase tracking-wide">Tổng Keys
                            </div>
                        </div>
                        <div class="h-12 w-px bg-white/30"></div>
                    </div>
                </div>
            </div>

            <!-- Stats Cards -->
            <div class="grid grid-cols-2 md:grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4">

                <div
                    class="bg-gradient-to-br from-green-200 to-green-600 dark:bg-gray-800 overflow-hidden shadow-lg rounded-xl p-4 sm:p-6 border border-gray-200 dark:border-gray-700 hover:shadow-xl transition-all duration-300">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs sm:text-sm text-gray-600 dark:text-gray-400 font-medium">Active</p>
                            <p class="text-2xl sm:text-2xl font-bold text-green-600 dark:text-green-400 mt-1 ">
                                {{ number_format($stats['active']) }} Keys</p>
                        </div>
                        <div class="bg-green-100 dark:bg-green-900/30 p-3 rounded-full">
                            <svg class="w-6 h-6 sm:w-8 sm:h-8 text-green-600 dark:text-green-400" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z " />
                            </svg>
                        </div>
                    </div>
                </div>

                <div
                    class="bg-gradient-to-br from-gray-200 to-gray-600 dark:bg-gray-800 overflow-hidden shadow-lg rounded-xl p-4 sm:p-6 border border-gray-200 dark:border-gray-700 hover:shadow-xl transition-all duration-300">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs sm:text-sm text-gray-600 dark:text-gray-400 font-medium">Expired</p>
                            <p class="text-2xl sm:text-2xl font-bold text-gray-900 dark:text-gray-100 mt-1">
                                {{ number_format($stats['expired']) }} Keys</p>
                        </div>
                        <div class="bg-gray-100 dark:bg-gray-900/30 p-3 rounded-full">
                            <svg class="w-6 h-6 sm:w-8 sm:h-8 text-gray-600 dark:text-gray-400" fill="none"
                                stroke="currentColor" viewBox="0 0 28 28 ">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 3h14v4l-7 8 7 8v4H5v-4l7-8-7-8V3z" />
                            </svg>
                        </div>
                    </div>
                </div>

                <div
                    class="bg-gradient-to-br from-orange-200 to-orange-600 dark:bg-gray-800 overflow-hidden shadow-lg rounded-xl p-4 sm:p-6 border border-gray-200 dark:border-gray-700 hover:shadow-xl transition-all duration-300">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs sm:text-sm text-gray-600 dark:text-gray-400 font-medium">Suspended</p>
                            <p class="text-2xl sm:text-2xl font-bold text-orange-600 dark:text-orange-400 mt-1">
                                {{ number_format($stats['suspended']) }} Keys</p>
                        </div>
                        <div class="bg-orange-100 dark:bg-orange-900/30 p-3 rounded-full">
                            <svg class="w-6 h-6 sm:w-8 sm:h-8 text-orange-600 dark:text-orange-400" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                    </div>
                </div>

                <div
                    class="bg-gradient-to-br from-red-200 to-red-600 dark:bg-gray-800 overflow-hidden shadow-lg rounded-xl p-4 sm:p-6 border border-gray-200 dark:border-gray-700 hover:shadow-xl transition-all duration-300">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs sm:text-sm text-gray-600 dark:text-gray-400 font-medium">Deleted</p>
                            <p class="text-2xl sm:text-2xl font-bold text-red-600 dark:text-red-400 mt-1">
                                {{ number_format($stats['deleted']) }} Keys</p>
                        </div>
                        <div class="bg-red-100 dark:bg-red-900/30 p-3 rounded-full">
                            <svg class="w-6 h-6 sm:w-8 sm:h-8 text-red-600 dark:text-red-400" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filter Box -->
            <div
                class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-5 mb-6 border border-gray-200 dark:border-gray-700">
                <form method="GET" class="grid grid-cols-1 md:grid-cols-5 gap-4">
                    <div class="col-span-1 md:col-span-2">
                        <input type="text" name="search" value="{{ request('search') }}"
                            placeholder="Tìm theo Key Code hoặc Email User..."
                            class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 transition">
                    </div>

                    <div>
                        <select name="status"
                            class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 cursor-pointer">
                            <option value="">-- Tất cả trạng thái --</option>
                            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="expired" {{ request('status') == 'expired' ? 'selected' : '' }}>Expired
                            </option>
                            <option value="suspended" {{ request('status') == 'suspended' ? 'selected' : '' }}>
                                Suspended
                            </option>
                        </select>
                    </div>

                    <div>
                        <select name="show_deleted"
                            class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 cursor-pointer">
                            <option value="">Key Chưa xóa</option>
                            <option value="with" {{ request('show_deleted') == 'with' ? 'selected' : '' }}>Tất cả
                                Key
                            </option>
                            <option value="only" {{ request('show_deleted') == 'only' ? 'selected' : '' }}>Key đã
                                xóa
                            </option>
                        </select>
                    </div>

                    <div class="flex gap-2">
                        <button type="submit"
                            class="flex-1 bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 font-medium transition shadow-md">
                            🔍 Lọc
                        </button>
                        <a href="{{ route('admin.keys.index') }}"
                            class="px-4 py-2 bg-gray-200 dark:bg-gray-600 text-gray-700 dark:text-gray-200 rounded-lg hover:bg-gray-300 transition flex items-center justify-center">
                            ↺
                        </a>
                    </div>
                </form>
            </div>

            <!-- Table -->
            <div
                class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden border border-gray-200 dark:border-gray-700">
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left">
                        <thead
                            class="text-xs text-gray-500 uppercase bg-gray-50 dark:bg-gray-700/50 dark:text-gray-400">
                            <tr>
                                <th class="px-6 py-4 font-semibold uppercase tracking-wider">ID / Thời gian</th>
                                <th class="px-6 py-4 font-semibold uppercase tracking-wider">Key Code</th>
                                <th class="px-6 py-4 font-semibold uppercase tracking-wider hidden lg:table-cell">Người
                                    sở hữu</th>
                                <th class="px-6 py-4 font-semibold uppercase tracking-wider hidden lg:table-cell">Loại
                                </th>
                                <th class="px-6 py-4 font-semibold uppercase tracking-wider hidden lg:table-cell">Trạng
                                    thái</th>
                                <th class="px-6 py-4 font-semibold uppercase tracking-wider">Hết hạn</th>
                                <th class="px-6 py-4 text-center font-semibold uppercase tracking-wider">Hành động</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse($keys as $key)
                                <tr
                                    class="bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition {{ $key->trashed() ? 'opacity-60' : '' }}">
                                    <td class="px-6 py-4">
                                        <div class="flex flex-col">
                                            <span class="font-medium text-gray-900 dark:text-white">
                                                ID Key: {{ $key->id }}
                                            </span>
                                            <span class="text-xs text-gray-500 dark:text-gray-400">
                                                {{ $key->created_at->setTimezone('Asia/Ho_Chi_Minh')->format('d/m/Y H:i:s') }}
                                            </span>
                                            @if ($key->trashed())
                                                <span class="text-xs text-red-500 font-semibold mt-1">
                                                    🗑️ Đã xóa
                                                </span>
                                            @endif
                                            <span class="lg:hidden font-medium text-gray-900 dark:text-white">
                                                Owner: {{ $key->user->email ?? 'Không có' }}
                                            </span>
                                            <span class="lg:hidden text-xs text-gray-500">
                                                ID User: {{ $key->user->id ?? 'N/A' }}
                                            </span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="font-mono font-bold text-blue-600 dark:text-blue-400 select-all">
                                            {{ $key->key_code }}
                                        </span>
                                        <span class="lg:hidden flex flex-col">
                                            @if ($key->key_type == 'custom')
                                                <span class="font-medium text-gray-900 dark:text-white">Class:
                                                    Custom</span>
                                            @else
                                                <span class="font-medium text-gray-900 dark:text-white">Class:
                                                    Auto</span>
                                            @endif
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 hidden lg:table-cell">
                                        <div class="flex flex-col">
                                            <span class="font-medium text-gray-900 dark:text-white">
                                                {{ $key->user->email ?? 'Không có' }}
                                            </span>
                                            <span class="text-xs text-gray-500">
                                                ID: {{ $key->user->id ?? 'N/A' }}
                                            </span>
                                        </div>
                                    </td>

                                    <td class="px-6 py-4 hidden lg:table-cell">
                                        @if ($key->key_type == 'custom')
                                            <span
                                                class="bg-purple-100 dark:bg-purple-900/30 text-purple-700 dark:text-purple-300 text-xs font-semibold px-2.5 py-0.5 rounded-full border border-purple-200 dark:border-purple-800">Custom</span>
                                        @else
                                            <span
                                                class="bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300 text-xs font-semibold px-2.5 py-0.5 rounded-full border border-blue-200 dark:border-blue-800">Auto</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 hidden lg:table-cell">
                                        @if ($key->isActive())
                                            <span
                                                class="inline-flex items-center gap-1 bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-300 text-xs font-bold px-2.5 py-0.5 rounded-full border border-green-200 dark:border-green-800">
                                                Active
                                            </span>
                                        @elseif($key->isExpired())
                                            <span
                                                class="inline-flex items-center gap-1 bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-400 text-xs font-bold px-2.5 py-0.5 rounded-full border border-gray-300">
                                                Expired
                                            </span>
                                        @elseif($key->isSuspended())
                                            <span
                                                class="inline-flex items-center gap-1 bg-yellow-100 dark:bg-yellow-900/30 text-yellow-700 dark:text-yellow-300 text-xs font-bold px-2.5 py-0.5 rounded-full border border-yellow-200 dark:border-yellow-800">
                                                Suspended
                                            </span>
                                        @elseif($key->isRevoked())
                                            <span
                                                class="inline-flex items-center gap-1 bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-300 text-xs font-bold px-2.5 py-0.5 rounded-full border border-red-200 dark:border-red-800">
                                                Revoked
                                            </span>
                                        @endif
                                    </td>
                                    {{-- <!-- Status Badge -->
                    <div class="absolute top-4 right-4 z-10">
                        @if ($key->isActive())
                            <span
                                class="px-2 py-1 bg-green-100 text-green-700 text-xs font-bold rounded-lg shadow-sm">ACTIVE</span>
                        @elseif($key->isExpired())
                            <span
                                class="px-2 py-1 bg-gray-100 text-gray-600 text-xs font-bold rounded-lg shadow-sm">EXPIRED</span>
                        @else
                            <span
                                class="px-2 py-1 bg-red-100 text-red-600 text-xs font-bold rounded-lg shadow-sm">{{ strtoupper($key->status) }}</span>
                        @endif
                    </div> --}}
                                    <td class="px-6 py-4 text-gray-600 dark:text-gray-300">
                                        <span class="flex flex-col">
                                            @if ($key->expires_at)
                                                <div>
                                                    {{ $key->expires_at->setTimezone('Asia/Ho_Chi_Minh')->format('d/m/Y') }}
                                                </div>
                                                <div>
                                                    {{ $key->expires_at->setTimezone('Asia/Ho_Chi_Minh')->format('H:i:s') }}
                                                </div>
                                                <div class="text-xs text-gray-400">
                                                    {{ $key->expires_at->setTimezone('Asia/Ho_Chi_Minh')->diffForHumans() }}
                                                </div>
                                            @else
                                                <span class="text-infinity text-lg">∞</span>
                                            @endif
                                        </span>
                                        <span class="lg:hidden flex flex-col">
                                            @if ($key->isActive())
                                                <span
                                                    class="inline-flex items-center gap-1 bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-300 text-xs font-bold px-2.5 py-0.5 rounded-full border border-green-200 dark:border-green-800">
                                                    Active
                                                </span>
                                            @elseif($key->isExpired())
                                                <span
                                                    class="inline-flex items-center gap-1 bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-400 text-xs font-bold px-2.5 py-0.5 rounded-full border border-gray-300">
                                                    Expired
                                                </span>
                                            @elseif($key->isSuspended())
                                                <span
                                                    class="inline-flex items-center gap-1 bg-yellow-100 dark:bg-yellow-900/30 text-yellow-700 dark:text-yellow-300 text-xs font-bold px-2.5 py-0.5 rounded-full border border-yellow-200 dark:border-yellow-800">
                                                    Suspended
                                                </span>
                                            @elseif($key->isRevoked())
                                                <span
                                                    class="inline-flex items-center gap-1 bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-300 text-xs font-bold px-2.5 py-0.5 rounded-full border border-red-200 dark:border-red-800">
                                                    Revoked
                                                </span>
                                            @endif
                                        </span>

                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <div class="flex justify-center items-center gap-2">
                                            @if ($key->trashed())
                                                {{-- Key đã xóa: Chỉ có Xem & Khôi phục --}}
                                                <a href="{{ route('admin.keys.show', $key->id) }}"
                                                    class="inline-flex items-center gap-1 px-3 py-1.5 bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400 hover:bg-blue-100 dark:hover:bg-blue-900/40 rounded-lg transition text-xs font-medium"
                                                    title="Xem chi tiết">
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                    </svg>
                                                    Xem
                                                </a>

                                                <form action="{{ route('admin.keys.restore', $key->id) }}"
                                                    method="POST" class="inline">
                                                    @csrf
                                                    <button type="submit"
                                                        class="inline-flex items-center gap-1 px-3 py-1.5 bg-green-50 dark:bg-green-900/20 text-green-600 dark:text-green-400 hover:bg-green-100 dark:hover:bg-green-900/40 rounded-lg transition text-xs font-medium"
                                                        title="Khôi phục">
                                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor"
                                                            viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                                        </svg>
                                                        Khôi phục
                                                    </button>
                                                </form>
                                            @else
                                                {{-- Key còn: Xem, Sửa, Xóa --}}
                                                <a href="{{ route('admin.keys.show', $key->id) }}"
                                                    class="inline-flex items-center gap-1 px-3 py-1.5 bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400 hover:bg-blue-100 dark:hover:bg-blue-900/40 rounded-lg transition text-xs font-medium"
                                                    title="Xem chi tiết">
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                    </svg>
                                                    Xem
                                                </a>

                                                <a href="{{ route('admin.keys.edit', $key->id) }}"
                                                    class="inline-flex items-center gap-1 px-3 py-1.5 bg-indigo-50 dark:bg-indigo-900/20 text-indigo-600 dark:text-indigo-400 hover:bg-indigo-100 dark:hover:bg-indigo-900/40 rounded-lg transition text-xs font-medium"
                                                    title="Chỉnh sửa">
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                    </svg>
                                                    Sửa
                                                </a>

                                                <button onclick="confirmDelete{{ $key->id }}()"
                                                    class="inline-flex items-center gap-1 px-3 py-1.5 bg-red-50 dark:bg-red-900/20 text-red-600 dark:text-red-400 hover:bg-red-100 dark:hover:bg-red-900/40 rounded-lg transition text-xs font-medium"
                                                    title="Xóa key">
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                    </svg>
                                                    Xóa
                                                </button>

                                                {{-- Modal xác nhận xóa --}}
                                                <div id="deleteModal{{ $key->id }}"
                                                    class="hidden fixed inset-0 bg-black/50 flex items-center justify-center z-50">
                                                    <div
                                                        class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl max-w-md w-full mx-4 overflow-hidden">
                                                        <div
                                                            class="bg-gradient-to-r from-red-500 to-red-600 px-6 py-4">
                                                            <h3
                                                                class="text-xl font-bold text-white flex items-center gap-2">
                                                                <svg class="w-6 h-6" fill="none"
                                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round"
                                                                        stroke-linejoin="round" stroke-width="2"
                                                                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                                                </svg>
                                                                Xác nhận xóa Key
                                                            </h3>
                                                        </div>

                                                        <div class="p-6">
                                                            <div
                                                                class="mb-4 p-4 bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-lg">
                                                                <p
                                                                    class="text-sm text-yellow-800 dark:text-yellow-200">
                                                                    <strong>⚠️ Lưu ý:</strong> Key sẽ bị xóa
                                                                </p>
                                                            </div>

                                                            <div class="space-y-3 mb-6">
                                                                <div class="flex items-start gap-3">
                                                                    <div
                                                                        class="w-8 h-8 bg-red-100 dark:bg-red-900/30 rounded-full flex items-center justify-center flex-shrink-0">
                                                                        <svg class="w-4 h-4 text-red-600 dark:text-red-400"
                                                                            fill="none" stroke="currentColor"
                                                                            viewBox="0 0 24 24">
                                                                            <path stroke-linecap="round"
                                                                                stroke-linejoin="round"
                                                                                stroke-width="2"
                                                                                d="M6 18L18 6M6 6l12 12" />
                                                                        </svg>
                                                                    </div>
                                                                    <div>
                                                                        <p
                                                                            class="font-semibold text-gray-900 dark:text-white">
                                                                            Key Code: <span
                                                                                class="font-mono text-red-600">{{ $key->key_code }}</span>
                                                                        </p>
                                                                    </div>
                                                                </div>

                                                                <div class="flex items-start gap-3">
                                                                    <div
                                                                        class="w-8 h-8 bg-blue-100 dark:bg-blue-900/30 rounded-full flex items-center justify-center flex-shrink-0">
                                                                        <svg class="w-4 h-4 text-blue-600 dark:text-blue-400"
                                                                            fill="none" stroke="currentColor"
                                                                            viewBox="0 0 24 24">
                                                                            <path stroke-linecap="round"
                                                                                stroke-linejoin="round"
                                                                                stroke-width="2"
                                                                                d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                                        </svg>
                                                                    </div>
                                                                    <div>
                                                                        <p
                                                                            class="text-sm text-gray-700 dark:text-gray-300">
                                                                            Admin vẫn thấy key với nhãn <span
                                                                                class="px-2 py-0.5 bg-red-100 text-red-700 text-xs rounded font-semibold">ĐÃ
                                                                                XÓA</span></p>
                                                                    </div>
                                                                </div>

                                                                <div class="flex items-start gap-3">
                                                                    <div
                                                                        class="w-8 h-8 bg-green-100 dark:bg-green-900/30 rounded-full flex items-center justify-center flex-shrink-0">
                                                                        <svg class="w-4 h-4 text-green-600 dark:text-green-400"
                                                                            fill="none" stroke="currentColor"
                                                                            viewBox="0 0 24 24">
                                                                            <path stroke-linecap="round"
                                                                                stroke-linejoin="round"
                                                                                stroke-width="2"
                                                                                d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                                                        </svg>
                                                                    </div>
                                                                    <div>
                                                                        <p
                                                                            class="text-sm text-gray-700 dark:text-gray-300">
                                                                            Có thể <strong class="text-green-600">khôi
                                                                                phục</strong> key này bất cứ lúc nào</p>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <form action="{{ route('admin.keys.destroy', $key->id) }}"
                                                                method="POST">
                                                                @csrf
                                                                @method('DELETE')
                                                                <div class="flex gap-3">
                                                                    <button type="button"
                                                                        onclick="closeDeleteModal{{ $key->id }}()"
                                                                        class="flex-1 px-4 py-2.5 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 font-medium transition">
                                                                        Hủy bỏ
                                                                    </button>
                                                                    <button type="submit"
                                                                        class="flex-1 px-4 py-2.5 bg-red-600 text-white rounded-lg hover:bg-red-700 font-medium transition flex items-center justify-center gap-2">
                                                                        <svg class="w-4 h-4" fill="none"
                                                                            stroke="currentColor" viewBox="0 0 24 24">
                                                                            <path stroke-linecap="round"
                                                                                stroke-linejoin="round"
                                                                                stroke-width="2"
                                                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                                        </svg>
                                                                        Xóa Key
                                                                    </button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>

                                                <script>
                                                    function confirmDelete{{ $key->id }}() {
                                                        document.getElementById('deleteModal{{ $key->id }}').classList.remove('hidden');
                                                    }

                                                    function closeDeleteModal{{ $key->id }}() {
                                                        document.getElementById('deleteModal{{ $key->id }}').classList.add('hidden');
                                                    }
                                                </script>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-8 text-center text-gray-500 dark:text-gray-400">
                                        Không tìm thấy Key nào phù hợp.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if ($keys->hasPages())
                    <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800">
                        {{ $keys->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
