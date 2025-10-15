<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Bảng Điều Khiển Quản Trị') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg p-6 lg:p-8">

                {{-- Thống kê nhanh --}}
                <h3 class="text-3xl font-bold text-gray-900 dark:text-gray-100 mb-6 border-b pb-3">
                    Tổng Quan Hệ Thống
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                    <div class="bg-indigo-600 p-6 rounded-xl shadow-lg text-white">
                        <p class="text-sm opacity-80">Tổng Người Dùng</p>
                        <p class="text-4xl font-extrabold mt-1">{{ $totalUsers }}</p>
                        <p class="text-xs opacity-70 mt-2">% so với tháng trước</p>
                    </div>

                    <div class="bg-teal-600 p-6 rounded-xl shadow-lg text-white">
                        <p class="text-sm opacity-80">Tăng Trưởng Người Dùng (Tháng {{ now()->format('m/Y') }})</p>
                        <p class="text-4xl font-extrabold mt-1">{{ array_sum($growthData) }}</p>
                        <p class="text-xs opacity-70 mt-2">Theo dữ liệu năm {{ date('Y') }}</p>
                    </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                    <div class="bg-indigo-600 p-6 rounded-xl shadow-lg text-white">
                        <p class="text-sm opacity-80">Tổng Người Dùng</p>
                        <p class="text-4xl font-extrabold mt-1">{{ $totalUsers }}</p>
                        <p class="text-xs opacity-70 mt-2">% so với tháng trước</p>
                    </div>

                    <div class="bg-amber-600 p-6 rounded-xl shadow-lg text-white">
                        <p class="text-sm opacity-80">Người Dùng Mới Trong Tháng</p>
                        <p class="text-4xl font-extrabold mt-1">
                            {{ \App\Models\User::whereMonth('created_at', now()->month)->count() }}
                        </p>
                        <p class="text-xs opacity-70 mt-2">Tháng {{ now()->format('m/Y') }}</p>
                    </div>
                </div>

                {{-- Biểu đồ & Danh sách --}}
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <div class="lg:col-span-2 bg-gray-50 dark:bg-gray-700 p-6 rounded-xl shadow-md">
                        <h4 class="text-xl font-semibold text-gray-900 dark:text-gray-100 mb-4">
                            Biểu Đồ Tăng Trưởng Người Dùng
                        </h4>
                        <canvas id="userGrowthChart" height="120"></canvas>
                    </div>

                    <div class="lg:col-span-1 bg-gray-50 dark:bg-gray-700 p-6 rounded-xl shadow-md">
                        <h4 class="text-xl font-semibold text-gray-900 dark:text-gray-100 mb-4">
                            Người Dùng Mới Nhất
                        </h4>
                        <ul class="space-y-3">
                            @foreach ($recentUsers as $rUser)
                                <li class="flex justify-between items-center py-2 border-b border-gray-300 dark:border-gray-600 text-gray-800 dark:text-gray-200">
                                    <span>{{ $rUser->name }}</span>
                                    <span class="text-xs text-gray-400">{{ $rUser->created_at->diffForHumans() }}</span>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>

                {{-- Tiện ích thông minh --}}
                <div class="mt-10 bg-gradient-to-r from-indigo-500 to-purple-600 text-white p-6 rounded-xl shadow-lg">
                    <h3 class="text-2xl font-semibold mb-2">✨ Gợi ý tiện ích thông minh</h3>
                    <p>🔹 Hôm nay {{ now()->format('d/m/Y') }}, hệ thống của bạn đang hoạt động ổn định.</p>
                    <p>🔹 {{ $totalUsers }} người dùng đã tham gia!</p>
                    <p>🔹 Thông tin cập nhật mới nhất trong 24h qua.</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Biểu đồ Chart.js --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctx = document.getElementById('userGrowthChart');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: ['Th1','Th2','Th3','Th4','Th5','Th6','Th7','Th8','Th9','Th10','Th11','Th12'],
                datasets: [{
                    label: 'Người dùng mới',
                    data: @json($growthData),
                    borderColor: '#4F46E5',
                    backgroundColor: 'rgba(79,70,229,0.2)',
                    fill: true,
                    tension: 0.3
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { position: 'top' },
                    title: { display: true, text: 'Thống kê tăng trưởng người dùng năm {{ date('Y') }}' }
                }
            }
        });
    </script>
</x-app-layout>
