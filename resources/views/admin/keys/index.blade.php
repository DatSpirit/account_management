<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
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
                <!-- Nếu có trang thống kê validation -->
                <!-- <a href="#" class="px-4 py-2 bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-200 rounded-lg shadow hover:bg-gray-50">📊 Thống Kê</a> -->
            </div>
    </x-slot>

    <div class="py-8 px-4 sm:px-6 lg:px-8 min-h-screen bg-gray-50 dark:bg-gray-900">

        <!-- Filter Box -->
        <div
            class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-5 mb-6 border border-gray-200 dark:border-gray-700">
            <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div class="col-span-1 md:col-span-2">
                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="Tìm theo Key Code hoặc Email User..."
                        class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 transition">
                </div>

                <div>
                    <select name="status"
                        class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 cursor-pointer">
                        <option value="">-- Tất cả trạng thái --</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active (Đang chạy)
                        </option>
                        <option value="expired" {{ request('status') == 'expired' ? 'selected' : '' }}>Expired (Hết hạn)
                        </option>
                        <option value="suspended" {{ request('status') == 'suspended' ? 'selected' : '' }}>Suspended (Đã
                            khóa)
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
                    <thead class="text-xs text-gray-500 uppercase bg-gray-50 dark:bg-gray-700/50 dark:text-gray-400">
                        <tr>
                            <th class="px-6 py-4 font-semibold">Key Code</th>
                            <th class="px-6 py-4 font-semibold">Người sở hữu</th>
                            <th class="px-6 py-4 font-semibold">Loại</th>
                            <th class="px-6 py-4 font-semibold">Trạng thái</th>
                            <th class="px-6 py-4 font-semibold">Hết hạn</th>
                            <th class="px-6 py-4 text-center font-semibold">Hành động</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse($keys as $key)
                            <tr class="bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition">
                                <td class="px-6 py-4">
                                    <span class="font-mono font-bold text-indigo-600 dark:text-indigo-400 select-all">
                                        {{ $key->key_code }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex flex-col">
                                        <span class="font-medium text-gray-900 dark:text-white">
                                            {{ $key->user->email ?? 'Không có' }}
                                        </span>
                                        <span class="text-xs text-gray-500">
                                            ID: {{ $key->user->id ?? 'N/A' }}
                                        </span>
                                    </div>
                                </td>

                                <td class="px-6 py-4">
                                    @if ($key->key_type == 'custom')
                                        <span
                                            class="bg-purple-100 dark:bg-purple-900/30 text-purple-700 dark:text-purple-300 text-xs font-semibold px-2.5 py-0.5 rounded-full border border-purple-200 dark:border-purple-800">Custom</span>
                                    @else
                                        <span
                                            class="bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300 text-xs font-semibold px-2.5 py-0.5 rounded-full border border-blue-200 dark:border-blue-800">Auto</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    @if ($key->status == 'active')
                                        <span
                                            class="inline-flex items-center gap-1 bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-300 text-xs font-bold px-2.5 py-0.5 rounded-full border border-green-200 dark:border-green-800">
                                            <span class="w-1.5 h-1.5 bg-green-500 rounded-full"></span> Active
                                        </span>
                                    @elseif($key->status == 'expired')
                                        <span
                                            class="inline-flex items-center gap-1 bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-400 text-xs font-bold px-2.5 py-0.5 rounded-full border border-gray-300">
                                            Expired
                                        </span>
                                    @elseif($key->status == 'suspended')
                                        <span
                                            class="inline-flex items-center gap-1 bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-300 text-xs font-bold px-2.5 py-0.5 rounded-full border border-red-200 dark:border-red-800">
                                            Suspended
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-gray-600 dark:text-gray-300">
                                    @if ($key->expires_at)
                                        <div>{{ $key->expires_at->format('d/m/Y') }}</div>
                                        <div class="text-xs text-gray-400">{{ $key->expires_at->diffForHumans() }}</div>
                                    @else
                                        <span class="text-infinity text-lg">∞</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <div class="flex justify-center space-x-3">
                                        <a href="{{ route('admin.keys.show', $key->id) }}"
                                            class="p-1.5 bg-blue-50 dark:bg-blue-900/20 text-blue-600 hover:bg-blue-100 rounded transition"
                                            title="Chi tiết">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                        </a>

                                        @if ($key->status == 'active')
                                            <form action="{{ route('admin.keys.suspend', $key->id) }}" method="POST"
                                                onsubmit="return confirm('Bạn có chắc muốn KHÓA key này không?');">
                                                @csrf
                                                <button type="submit"
                                                    class="p-1.5 bg-red-50 dark:bg-red-900/20 text-red-600 hover:bg-red-100 rounded transition"
                                                    title="Khóa Key">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                                    </svg>
                                                </button>
                                            </form>
                                        @elseif($key->status == 'suspended')
                                            <form action="{{ route('admin.keys.activate', $key->id) }}" method="POST">
                                                @csrf
                                                <button type="submit"
                                                    class="p-1.5 bg-green-50 dark:bg-green-900/20 text-green-600 hover:bg-green-100 rounded transition"
                                                    title="Mở khóa">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M8 11V7a4 4 0 118 0m-4 8v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2z" />
                                                    </svg>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-8 text-center text-gray-500 dark:text-gray-400">
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
</x-app-layout>
