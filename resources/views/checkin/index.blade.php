<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-gray-800 dark:text-white flex items-center gap-2">
            <svg class="w-7 h-7 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            Điểm Danh Hàng Ngày
        </h2>
    </x-slot>

    <div class="py-8 px-4 sm:px-6 lg:px-8 space-y-6">
        
        <!-- Main Check-in Card -->
        <div class="relative overflow-hidden bg-gradient-to-br from-green-400 via-emerald-500 to-teal-600 rounded-3xl shadow-2xl p-8 text-white">
            <div class="absolute inset-0 opacity-20">
                <div class="absolute top-0 right-0 w-96 h-96 bg-white rounded-full blur-3xl animate-pulse"></div>
            </div>

            <div class="relative z-10">
                @if($stats['can_checkin'])
                    <!-- Can Check-in State -->
                    <div class="text-center space-y-6">
                        <div class="text-7xl animate-bounce">🎁</div>
                        <div>
                            <h3 class="text-3xl font-black mb-2">Điểm Danh Hôm Nay!</h3>
                            <p class="text-green-100 text-lg">Nhận ngay phần thưởng Coinkey miễn phí</p>
                        </div>

                        <div class="bg-white/20 backdrop-blur-sm rounded-2xl p-6 inline-block">
                            <p class="text-sm text-green-100 mb-2">Phần thưởng dự kiến</p>
                            <p class="text-5xl font-black">+{{ number_format($stats['current_streak'] > 0 ? $stats['current_streak'] * 10 : 10) }}</p>
                            <p class="text-xl font-bold text-green-100">Coinkey</p>
                        </div>

                        <button id="checkin-btn" class="px-12 py-4 bg-white text-green-600 rounded-2xl font-black text-xl shadow-2xl hover:bg-green-50 hover:scale-105 transform transition-all duration-200">
                            🎉 ĐIỂM DANH NGAY
                        </button>
                    </div>
                @else
                    <!-- Already Checked-in State -->
                    <div class="text-center space-y-6">
                        <div class="text-7xl">✅</div>
                        <div>
                            <h3 class="text-3xl font-black mb-2">Đã Điểm Danh!</h3>
                            <p class="text-green-100 text-lg">Quay lại vào ngày mai nhé</p>
                        </div>

                        <div class="bg-white/20 backdrop-blur-sm rounded-2xl p-6 inline-block">
                            <p class="text-sm text-green-100 mb-2">Thời gian còn lại</p>
                            <p class="text-3xl font-black" id="countdown">--:--:--</p>
                        </div>
                    </div>
                @endif

                <!-- Streak Display -->
                <div class="mt-8 pt-6 border-t border-white/30">
                    <div class="grid grid-cols-3 gap-4 text-center">
                        <div>
                            <p class="text-sm text-green-100 mb-1">Chuỗi hiện tại</p>
                            <p class="text-3xl font-black">{{ $stats['current_streak'] }}</p>
                            <p class="text-xs text-green-100">ngày</p>
                        </div>
                        <div>
                            <p class="text-sm text-green-100 mb-1">Dài nhất</p>
                            <p class="text-3xl font-black">{{ $stats['longest_streak'] }}</p>
                            <p class="text-xs text-green-100">ngày</p>
                        </div>
                        <div>
                            <p class="text-sm text-green-100 mb-1">Tổng kiếm được</p>
                            <p class="text-3xl font-black">{{ number_format($stats['total_earned']) }}</p>
                            <p class="text-xs text-green-100">Coinkey</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Milestone Progress -->
        @if($stats['next_milestone'])
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700 p-6">
            <div class="flex items-center justify-between mb-4">
                <h4 class="font-bold text-lg text-gray-900 dark:text-white">Mốc tiếp theo</h4>
                <span class="px-3 py-1 bg-indigo-100 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400 rounded-full text-sm font-bold">
                    +{{ number_format($stats['next_milestone']['bonus']) }} Coinkey
                </span>
            </div>
            
            <div class="space-y-2">
                <div class="flex justify-between text-sm text-gray-600 dark:text-gray-400">
                    <span>Ngày {{ $stats['current_streak'] }}/{{ $stats['next_milestone']['day'] }}</span>
                    <span>Còn {{ $stats['next_milestone']['remaining_days'] }} ngày</span>
                </div>
                <div class="w-full h-4 bg-gray-200 dark:bg-gray-700 rounded-full overflow-hidden">
                    <div class="h-full bg-gradient-to-r from-green-400 to-emerald-500 transition-all duration-500" 
                         style="width: {{ ($stats['current_streak'] / $stats['next_milestone']['day']) * 100 }}%"></div>
                </div>
            </div>
        </div>
        @endif

        <!-- Rewards Table -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700 overflow-hidden">
            <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                <h4 class="font-bold text-xl text-gray-900 dark:text-white">Phần Thưởng Theo Chuỗi</h4>
            </div>
            
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-bold text-gray-700 dark:text-gray-300 uppercase">Ngày</th>
                            <th class="px-6 py-3 text-left text-xs font-bold text-gray-700 dark:text-gray-300 uppercase">Phần Thưởng</th>
                            <th class="px-6 py-3 text-left text-xs font-bold text-gray-700 dark:text-gray-300 uppercase">Bonus</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50">
                            <td class="px-6 py-4 text-gray-900 dark:text-white font-medium">Ngày 1-2</td>
                            <td class="px-6 py-4 text-gray-900 dark:text-white">10 Coinkey/ngày</td>
                            <td class="px-6 py-4 text-gray-500 dark:text-gray-400">-</td>
                        </tr>
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 bg-green-50 dark:bg-green-900/20">
                            <td class="px-6 py-4 text-gray-900 dark:text-white font-medium">✨ Ngày 3</td>
                            <td class="px-6 py-4 text-green-600 dark:text-green-400 font-bold">15 Coinkey</td>
                            <td class="px-6 py-4 text-green-600 dark:text-green-400 font-bold">+5 Bonus</td>
                        </tr>
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50">
                            <td class="px-6 py-4 text-gray-900 dark:text-white font-medium">Ngày 4-6</td>
                            <td class="px-6 py-4 text-gray-900 dark:text-white">10 Coinkey/ngày</td>
                            <td class="px-6 py-4 text-gray-500 dark:text-gray-400">-</td>
                        </tr>
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 bg-yellow-50 dark:bg-yellow-900/20">
                            <td class="px-6 py-4 text-gray-900 dark:text-white font-medium">🎊 Ngày 7</td>
                            <td class="px-6 py-4 text-yellow-600 dark:text-yellow-400 font-bold">30 Coinkey</td>
                            <td class="px-6 py-4 text-yellow-600 dark:text-yellow-400 font-bold">+20 Bonus</td>
                        </tr>
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 bg-orange-50 dark:bg-orange-900/20">
                            <td class="px-6 py-4 text-gray-900 dark:text-white font-medium">🔥 Ngày 14</td>
                            <td class="px-6 py-4 text-orange-600 dark:text-orange-400 font-bold">60 Coinkey</td>
                            <td class="px-6 py-4 text-orange-600 dark:text-orange-400 font-bold">+50 Bonus</td>
                        </tr>
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 bg-red-50 dark:bg-red-900/20">
                            <td class="px-6 py-4 text-gray-900 dark:text-white font-medium">💎 Ngày 30</td>
                            <td class="px-6 py-4 text-red-600 dark:text-red-400 font-bold">210 Coinkey</td>
                            <td class="px-6 py-4 text-red-600 dark:text-red-400 font-bold">+200 Bonus</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- History -->
        @if(count($history) > 0)
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700 overflow-hidden">
            <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                <h4 class="font-bold text-xl text-gray-900 dark:text-white">Lịch Sử Điểm Danh</h4>
            </div>
            
            <div class="divide-y divide-gray-200 dark:divide-gray-700 max-h-96 overflow-y-auto">
                @foreach($history as $log)
                <div class="px-6 py-4 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="font-semibold text-gray-900 dark:text-white">
                                Ngày {{ $log['streak_day'] }} - {{ \Carbon\Carbon::parse($log['checkin_date'])->format('d/m/Y') }}
                            </p>
                            @if($log['notes'])
                            <p class="text-sm text-gray-500 dark:text-gray-400">{{ $log['notes'] }}</p>
                            @endif
                        </div>
                        <div class="text-right">
                            <p class="text-lg font-bold text-green-600 dark:text-green-400">+{{ number_format($log['reward_amount']) }}</p>
                            @if($log['is_bonus'])
                            <span class="inline-block px-2 py-0.5 bg-yellow-100 dark:bg-yellow-900/30 text-yellow-600 dark:text-yellow-400 rounded text-xs font-bold">BONUS</span>
                            @endif
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>

    @push('scripts')
    <script>
        // Countdown Timer
        function updateCountdown() {
            const now = new Date();
            const tomorrow = new Date(now);
            tomorrow.setDate(tomorrow.getDate() + 1);
            tomorrow.setHours(0, 0, 0, 0);
            
            const diff = tomorrow - now;
            const hours = Math.floor(diff / 3600000);
            const minutes = Math.floor((diff % 3600000) / 60000);
            const seconds = Math.floor((diff % 60000) / 1000);
            
            const countdownEl = document.getElementById('countdown');
            if (countdownEl) {
                countdownEl.textContent = `${hours.toString().padStart(2, '0')}:${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
            }
        }
        
        @if(!$stats['can_checkin'])
            setInterval(updateCountdown, 1000);
            updateCountdown();
        @endif

        // Check-in Button Handler
        const checkinBtn = document.getElementById('checkin-btn');
        if (checkinBtn) {
            checkinBtn.addEventListener('click', async function() {
                this.disabled = true;
                this.innerHTML = '<svg class="animate-spin h-5 w-5 inline" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Đang xử lý...';
                
                try {
                    const response = await fetch('{{ route("checkin.process") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        }
                    });
                    
                    const data = await response.json();
                    
                    if (data.success) {
                        // Success animation
                        this.innerHTML = '✅ Thành công!';
                        this.classList.remove('bg-white', 'text-green-600');
                        this.classList.add('bg-green-500', 'text-white');
                        
                        // Reload after 1.5s
                        setTimeout(() => window.location.reload(), 1500);
                    } else {
                        throw new Error(data.message);
                    }
                } catch (error) {
                    alert(error.message || 'Có lỗi xảy ra!');
                    this.disabled = false;
                    this.innerHTML = '🎉 ĐIỂM DANH NGAY';
                }
            });
        }
    </script>
    @endpush
</x-app-layout>