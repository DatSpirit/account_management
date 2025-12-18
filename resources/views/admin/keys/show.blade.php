<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div class="flex items-center space-x-2">
                <svg class="w-7 h-7 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
                </svg>
                <h2 class="font-bold text-2xl text-gray-800 dark:text-white">
                    Chi tiết Key (Chỉ Xem)
                </h2>
                @if($key->trashed())
                    <span class="px-3 py-1 bg-red-100 dark:bg-red-900/30 text-red-600 dark:text-red-300 text-sm rounded-lg font-bold">
                     ĐÃ XÓA
                    </span>
                @endif
            </div>
            <div class="flex gap-2">
                <a href="{{ route('admin.keys.index') }}"
                    class="px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-200 rounded-lg shadow hover:bg-gray-300 transition">
                    ← Quay lại
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-8 px-4 sm:px-6 lg:px-8 bg-gray-50 dark:bg-gray-900 min-h-screen">

        {{-- Key Info Card --}}
        <div class="bg-white dark:bg-gray-800 shadow rounded-xl p-6 border border-gray-200 dark:border-gray-700 mb-6">

            <h3 class="text-xl font-semibold mb-4 text-gray-800 dark:text-white flex items-center gap-2">
                🔑 Thông tin Key
            </h3>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

                <div class="p-4 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                    <p class="text-xs text-gray-500 dark:text-gray-400 uppercase font-semibold mb-1">Key Code</p>
                    <p class="font-mono text-lg font-bold text-indigo-600 dark:text-indigo-400 select-all break-all">
                        {{ $key->key_code }}
                    </p>
                </div>

                <div class="p-4 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                    <p class="text-xs text-gray-500 dark:text-gray-400 uppercase font-semibold mb-1">Trạng thái</p>
                    @if ($key->status == 'active')
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-300 text-sm rounded-lg font-semibold">
                            <span class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></span>
                            Active
                        </span>
                    @elseif($key->status == 'expired')
                        <span class="px-3 py-1 bg-gray-200 dark:bg-gray-700 text-gray-600 dark:text-gray-300 text-sm rounded-lg font-semibold">
                            Expired
                        </span>
                    @elseif($key->status == 'suspended')
                        <span class="px-3 py-1 bg-red-100 dark:bg-red-900/30 text-red-600 dark:text-red-300 text-sm rounded-lg font-semibold">
                            Suspended
                        </span>
                    @else
                        <span class="px-3 py-1 bg-orange-100 dark:bg-orange-900/30 text-orange-600 dark:text-orange-300 text-sm rounded-lg font-semibold">
                            {{ ucfirst($key->status) }}
                        </span>
                    @endif
                </div>

                <div class="p-4 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                    <p class="text-xs text-gray-500 dark:text-gray-400 uppercase font-semibold mb-1">Loại Key</p>
                    @if($key->key_type == 'custom')
                        <span class="px-3 py-1 bg-purple-100 dark:bg-purple-900/30 text-purple-700 dark:text-purple-300 rounded-lg text-sm font-semibold">
                            ✨ Custom
                        </span>
                    @else
                        <span class="px-3 py-1 bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300 rounded-lg text-sm font-semibold">
                            📦 Auto
                        </span>
                    @endif
                </div>

                <div class="p-4 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                    <p class="text-xs text-gray-500 dark:text-gray-400 uppercase font-semibold mb-1">Người sở hữu</p>
                    <p class="font-semibold text-gray-800 dark:text-white">
                        {{ $key->user->name ?? 'N/A' }}
                    </p>
                    <p class="text-sm text-gray-500">{{ $key->user->email ?? 'N/A' }}</p>
                </div>

                <div class="p-4 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                    <p class="text-xs text-gray-500 dark:text-gray-400 uppercase font-semibold mb-1">Ngày kích hoạt</p>
                    @if($key->activated_at)
                        <p class="font-semibold text-gray-800 dark:text-white">
                            {{ $key->activated_at->format('d/m/Y H:i') }}
                        </p>
                        <p class="text-sm text-gray-500">{{ $key->activated_at->diffForHumans() }}</p>
                    @else
                        <p class="text-gray-500">Chưa kích hoạt</p>
                    @endif
                </div>

                <div class="p-4 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                    <p class="text-xs text-gray-500 dark:text-gray-400 uppercase font-semibold mb-1">Ngày hết hạn</p>
                    @if ($key->expires_at)
                        <p class="font-semibold text-gray-800 dark:text-white">
                            {{ $key->expires_at->format('d/m/Y H:i') }}
                        </p>
                        <p class="text-sm {{ $key->isExpired() ? 'text-red-500' : 'text-green-600' }}">
                            {{ $key->expires_at->diffForHumans() }}
                        </p>
                    @else
                        <p class="text-lg font-bold text-blue-600">∞ Vĩnh viễn</p>
                    @endif
                </div>

                <div class="p-4 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                    <p class="text-xs text-gray-500 dark:text-gray-400 uppercase font-semibold mb-1">Thời lượng</p>
                    <p class="font-semibold text-gray-800 dark:text-white">{{ number_format($key->duration_minutes) }} phút</p>
                </div>

                <div class="p-4 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                    <p class="text-xs text-gray-500 dark:text-gray-400 uppercase font-semibold mb-1">Chi phí</p>
                    <p class="font-bold text-yellow-600 dark:text-yellow-400">{{ number_format($key->key_cost) }} 🪙</p>
                </div>

                <div class="p-4 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                    <p class="text-xs text-gray-500 dark:text-gray-400 uppercase font-semibold mb-1">Lượt xác thực</p>
                    <p class="font-semibold text-gray-800 dark:text-white">{{ number_format($key->validation_count) }}</p>
                </div>

                @if($key->assigned_to_email)
                <div class="p-4 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                    <p class="text-xs text-gray-500 dark:text-gray-400 uppercase font-semibold mb-1">Gán cho</p>
                    <p class="font-semibold text-gray-800 dark:text-white">{{ $key->assigned_to_email }}</p>
                </div>
                @endif

                @if($key->deleted_at)
                <div class="col-span-full p-4 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg">
                    <p class="text-xs text-red-500 dark:text-red-400 uppercase font-semibold mb-1">Ngày xóa</p>
                    <p class="font-semibold text-red-600 dark:text-red-400">{{ $key->deleted_at->format('d/m/Y H:i:s') }}</p>
                </div>
                @endif

            </div>

            @if($key->notes)
            <div class="mt-6 p-4 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg">
                <p class="text-xs text-blue-600 dark:text-blue-400 uppercase font-semibold mb-2">Ghi chú</p>
                <p class="text-sm text-gray-700 dark:text-gray-300 whitespace-pre-wrap">{{ $key->notes }}</p>
            </div>
            @endif
        </div>

        {{-- Validation Stats --}}
        <div class="bg-white dark:bg-gray-800 shadow rounded-xl p-6 border border-gray-200 dark:border-gray-700 mb-6">

            <h3 class="text-xl font-semibold mb-4 text-gray-800 dark:text-white flex items-center gap-2">
                📊 Thống kê xác thực
            </h3>

            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div class="p-4 bg-gradient-to-br from-blue-50 to-blue-100 dark:from-blue-900/20 dark:to-blue-800/20 rounded-lg border border-blue-200 dark:border-blue-800">
                    <p class="text-xs text-blue-600 dark:text-blue-400 uppercase font-semibold">Tổng số</p>
                    <p class="text-3xl font-bold text-blue-700 dark:text-blue-300 mt-1">
                        {{ number_format($validationStats['total_validations']) }}
                    </p>
                </div>

                <div class="p-4 bg-gradient-to-br from-green-50 to-green-100 dark:from-green-900/20 dark:to-green-800/20 rounded-lg border border-green-200 dark:border-green-800">
                    <p class="text-xs text-green-600 dark:text-green-400 uppercase font-semibold">Thành công</p>
                    <p class="text-3xl font-bold text-green-700 dark:text-green-300 mt-1">
                        {{ number_format($validationStats['success_count']) }}
                    </p>
                </div>

                <div class="p-4 bg-gradient-to-br from-red-50 to-red-100 dark:from-red-900/20 dark:to-red-800/20 rounded-lg border border-red-200 dark:border-red-800">
                    <p class="text-xs text-red-600 dark:text-red-400 uppercase font-semibold">Thất bại</p>
                    <p class="text-3xl font-bold text-red-700 dark:text-red-300 mt-1">
                        {{ number_format($validationStats['failed_count']) }}
                    </p>
                </div>

                <div class="p-4 bg-gradient-to-br from-purple-50 to-purple-100 dark:from-purple-900/20 dark:to-purple-800/20 rounded-lg border border-purple-200 dark:border-purple-800">
                    <p class="text-xs text-purple-600 dark:text-purple-400 uppercase font-semibold">IP duy nhất</p>
                    <p class="text-3xl font-bold text-purple-700 dark:text-purple-300 mt-1">
                        {{ number_format($validationStats['unique_ips']) }}
                    </p>
                </div>
            </div>
        </div>

        {{-- Recent Validations --}}
        <div class="bg-white dark:bg-gray-800 shadow rounded-xl p-6 border border-gray-200 dark:border-gray-700">

            <h3 class="text-xl font-semibold mb-4 text-gray-800 dark:text-white flex items-center gap-2">
                🕒 Lịch sử xác thực gần nhất
            </h3>

            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 text-xs uppercase">
                        <tr>
                            <th class="px-4 py-3 text-left">IP Address</th>
                            <th class="px-4 py-3 text-left">Device Info</th>
                            <th class="px-4 py-3 text-left">Kết quả</th>
                            <th class="px-4 py-3 text-left">Thời gian</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse($recentValidations as $log)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50">
                                <td class="px-4 py-3 font-mono text-xs">{{ $log->ip_address }}</td>
                                <td class="px-4 py-3 text-xs">{{ Str::limit($log->device_info, 50) }}</td>
                                <td class="px-4 py-3">
                                    @if ($log->validation_result == 'success')
                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 bg-green-100 text-green-700 text-xs rounded font-semibold">
                                            ✓ Success
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 bg-red-100 text-red-700 text-xs rounded font-semibold">
                                            ✗ {{ ucfirst($log->validation_result) }}
                                        </span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-xs text-gray-600 dark:text-gray-400">
                                    {{ $log->validated_at->format('d/m/Y H:i:s') }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-4 py-8 text-center text-gray-500">
                                    Chưa có lịch sử validation
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</x-app-layout>