<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div class="flex items-center space-x-2">
                <svg class="w-7 h-7 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
                </svg>
                <h2 class="font-bold text-2xl text-gray-800 dark:text-white">
                    Chi tiết Key
                </h2>
            </div>
            <a href="{{ route('admin.keys.index') }}"
                class="px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-200 rounded-lg shadow hover:bg-gray-300 transition">
                ← Quay lại
            </a>
        </div>
    </x-slot>

    <div class="py-8 px-4 sm:px-6 lg:px-8 bg-gray-50 dark:bg-gray-900 min-h-screen">

        {{-- Key Info --}}
        <div class="bg-white dark:bg-gray-800 shadow rounded-xl p-6 border border-gray-200 dark:border-gray-700 mb-6">

            <h3 class="text-xl font-semibold mb-4 text-gray-800 dark:text-white">
                🔑 Thông tin Key
            </h3>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">

                <div>
                    <p class="text-sm text-gray-500">Key Code</p>
                    <p class="font-mono text-lg font-bold text-indigo-600 dark:text-indigo-400 select-all">
                        {{ $key->key_code }}
                    </p>
                </div>

                <div>
                    <p class="text-sm text-gray-500">Trạng thái</p>
                    @if ($key->status == 'active')
                        <span
                            class="px-3 py-1 bg-green-100 dark:bg-green-900/30 text-green-600 dark:text-green-300 text-sm rounded-lg font-semibold">
                            Active
                        </span>
                    @elseif($key->status == 'expired')
                        <span
                            class="px-3 py-1 bg-gray-200 dark:bg-gray-700 text-gray-600 dark:text-gray-300 text-sm rounded-lg font-semibold">
                            Expired
                        </span>
                    @else
                        <span
                            class="px-3 py-1 bg-red-100 dark:bg-red-900/30 text-red-600 dark:text-red-300 text-sm rounded-lg font-semibold">
                            Suspended
                        </span>
                    @endif
                </div>

                <div>
                    <p class="text-sm text-gray-500">Loại Key</p>
                    <span
                        class="px-3 py-1 bg-blue-100 dark:bg-blue-900/20 text-blue-700 dark:text-blue-300 rounded-lg text-sm font-semibold">
                        {{ ucfirst($key->key_type) }}
                    </span>
                </div>

                <div>
                    <p class="text-sm text-gray-500">Người sở hữu</p>
                    <p class="font-semibold text-gray-800 dark:text-white">
                        {{ $key->user->name ?? 'Unknown' }}
                    </p>
                    <p class="text-sm text-gray-500">{{ $key->user->email ?? 'N/A' }}</p>
                </div>

                <div>
                    <p class="text-sm text-gray-500">Ngày hết hạn</p>
                    @if ($key->expires_at)
                        <p class="font-semibold text-gray-800 dark:text-white">
                            {{ $key->expires_at->format('d/m/Y H:i') }}</p>
                        <p class="text-sm text-gray-400">{{ $key->expires_at->diffForHumans() }}</p>
                    @else
                        <p class="text-lg">∞ Không giới hạn</p>
                    @endif
                </div>

                <div>
                    <p class="text-sm text-gray-500">Lượt xác thực</p>
                    <p class="font-semibold text-gray-800 dark:text-white">{{ $key->validation_count }}</p>
                </div>

            </div>

            {{-- Action Buttons --}}
            <div class="mt-6 flex gap-3">

                @if ($key->status == 'active')
                    {{-- Suspend --}}
                    <form action="{{ route('admin.keys.suspend', $key->id) }}" method="POST"
                        onsubmit="return confirm('Khóa key này?')">
                        @csrf
                        <button class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">
                            Khóa Key
                        </button>
                    </form>
                @endif

                @if ($key->status == 'suspended')
                    {{-- Activate --}}
                    <form action="{{ route('admin.keys.activate', $key->id) }}" method="POST">
                        @csrf
                        <button class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
                            Mở khóa Key
                        </button>
                    </form>
                @endif

                {{-- Extend --}}
                <form action="{{ route('admin.keys.extend-admin', $key->id) }}" method="POST">
                    @csrf
                    <button class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">
                        Gia hạn Key
                    </button>
                </form>

            </div>

            <!-- Extend Modal -->
            <div id="extendModal" class="hidden fixed inset-0 bg-black/50 flex items-center justify-center">
                <div class="bg-white dark:bg-gray-800 p-6 rounded-xl w-full max-w-md">
                    <h2 class="text-lg font-bold mb-3">Gia hạn thời gian</h2>

                    <form method="POST" action="{{ route('admin.keys.extend-admin', $key->id) }}">
                        @csrf
                        <label class="font-semibold">Số phút gia hạn</label>
                        <input type="number" name="minutes" class="w-full mt-2 p-2 border rounded" required>

                        <div class="mt-4 flex justify-end gap-3">
                            <button type="button" onclick="closeExtend()"
                                class="px-3 py-2 bg-gray-200 rounded">Hủy</button>
                            <button type="submit" class="px-3 py-2 bg-blue-600 text-white rounded">Xác nhận</button>
                        </div>
                    </form>
                </div>
            </div>

            <script>
                function openExtend() {
                    document.getElementById('extendModal').classList.remove('hidden');
                }

                function closeExtend() {
                    document.getElementById('extendModal').classList.add('hidden');
                }
            </script>
        </div>


        {{-- Validation Stats --}}
        <div class="bg-white dark:bg-gray-800 shadow rounded-xl p-6 border border-gray-200 dark:border-gray-700 mb-6">

            <h3 class="text-xl font-semibold mb-4 text-gray-800 dark:text-white">
                📊 Thống kê xác thực
            </h3>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

                <div class="p-4 bg-gray-100 dark:bg-gray-700 rounded-lg">
                    <p class="text-sm text-gray-500">Tổng số validation</p>
                    <p class="text-2xl font-bold text-gray-800 dark:text-white">
                        {{ $validationStats['total_validations'] }}
                    </p>
                </div>

                <div class="p-4 bg-green-100 dark:bg-green-900/30 rounded-lg">
                    <p class="text-sm text-gray-500">Thành công</p>
                    <p class="text-2xl font-bold text-green-700 dark:text-green-300">
                        {{ $validationStats['success_count'] }}
                    </p>
                </div>

                <div class="p-4 bg-red-100 dark:bg-red-900/30 rounded-lg">
                    <p class="text-sm text-gray-500">Thất bại</p>
                    <p class="text-2xl font-bold text-red-700 dark:text-red-300">
                        {{ $validationStats['failed_count'] }}
                    </p>
                </div>

            </div>

        </div>


        {{-- Recent Validations --}}
        <div class="bg-white dark:bg-gray-800 shadow rounded-xl p-6 border border-gray-200 dark:border-gray-700">

            <h3 class="text-xl font-semibold mb-4 text-gray-800 dark:text-white">
                🕒 Lịch sử xác thực gần nhất
            </h3>

            <table class="w-full text-sm">
                <thead class="bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 text-xs uppercase">
                    <tr>
                        <th class="px-4 py-3 text-left">IP</th>
                        <th class="px-4 py-3 text-left">Thiết bị</th>
                        <th class="px-4 py-3 text-left">Kết quả</th>
                        <th class="px-4 py-3 text-left">Thời gian</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @foreach ($recentValidations as $log)
                        <tr>
                            <td class="px-4 py-3">{{ $log->ip_address }}</td>
                            <td class="px-4 py-3">{{ $log->device_info }}</td>
                            <td class="px-4 py-3">
                                @if ($log->status == 'success')
                                    <span class="text-green-600 font-bold">✔ Success</span>
                                @else
                                    <span class="text-red-600 font-bold">✖ Failed</span>
                                @endif
                            </td>
                            <td class="px-4 py-3">{{ $log->validated_at }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

        </div>

    </div>
</x-app-layout>
