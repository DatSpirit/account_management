<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <a href="{{ route('keys.keydetails', $key->id) }}" class="p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition">
                    <svg class="w-6 h-6 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                </a>
                <div>
                    <h2 class="font-bold text-2xl text-gray-800 dark:text-white">Lịch sử hoạt động</h2>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Key: <span class="font-mono font-bold">{{ $key->key_code }}</span></p>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-6 px-2 sm:px-6 lg:px-8 max-w-4xl mx-auto">
        <div class="relative wrap overflow-hidden h-full">
            <div class="border-2-2 absolute border-opacity-20 border-gray-700 h-full border left-[2rem] md:left-1/2"></div>

            @forelse($histories as $index => $history)
                @php
                    $isLeft = $index % 2 == 0; // Đảo bên trái phải (trên desktop)
                    // Màu sắc và Icon dựa theo hành động
                    switch($history->action) {
                        case 'create':
                            $color = 'blue'; $icon = '✨'; $title = 'Tạo mới'; break;
                        case 'extend':
                            $color = 'green'; $icon = '⚡'; $title = 'Gia hạn'; break;
                        case 'suspend':
                            $color = 'yellow'; $icon = '⏸️'; $title = 'Tạm dừng'; break;
                        case 'activate':
                            $color = 'indigo'; $icon = '▶️'; $title = 'Kích hoạt'; break;
                        case 'revoke':
                            $color = 'red'; $icon = '🚫'; $title = 'Thu hồi'; break;
                        default:
                            $color = 'gray'; $icon = '📝'; $title = 'Ghi nhận';
                    }
                @endphp

                <div class="mb-6 flex justify-between items-center w-full {{ $isLeft ? 'flex-row-reverse' : '' }} md:flex-row">
                    
                    <div class="order-1 w-5/12 hidden md:block"></div>
                    
                    <div class="z-20 flex items-center order-1 bg-{{ $color }}-500 shadow-xl w-8 h-8 rounded-full border-4 border-white dark:border-gray-900 justify-center text-white text-xs font-bold absolute left-[1rem] md:left-1/2 md:-translate-x-1/2">
                        {{ $icon }}
                    </div>
                    
                    <div class="order-1 w-full md:w-5/12 pl-12 md:pl-0 {{ $isLeft ? 'md:pr-8 md:text-right' : 'md:pl-8' }}">
                        <div class="bg-white dark:bg-gray-800 p-4 rounded-xl shadow-md border-l-4 border-{{ $color }}-500 hover:shadow-lg transition-shadow">
                            <h3 class="font-bold text-gray-800 dark:text-gray-100 text-lg">{{ $title }}</h3>
                            <p class="text-xs text-gray-400 mb-2">
                                {{ $history->created_at->format('H:i - d/m/Y') }}
                            </p>
                            
                            <p class="text-sm text-gray-600 dark:text-gray-300 leading-snug">
                                {{ $history->description }}
                            </p>

                            @if($history->meta_data)
                                <div class="mt-3 bg-gray-50 dark:bg-gray-700/50 p-2 rounded text-xs text-left">
                                    <ul class="space-y-1">
                                    @foreach($history->meta_data as $key => $value)
                                        <li class="flex justify-between">
                                            <span class="text-gray-500 capitalize">{{ str_replace('_', ' ', $key) }}:</span>
                                            <span class="font-mono font-semibold text-gray-700 dark:text-gray-200">{{ $value }}</span>
                                        </li>
                                    @endforeach
                                    </ul>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center py-10">
                    <div class="inline-block p-4 rounded-full bg-gray-100 dark:bg-gray-800 mb-3">
                        <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <p class="text-gray-500">Chưa có lịch sử hoạt động nào.</p>
                </div>
            @endforelse
        </div>
        
        <div class="mt-6">
            {{ $histories->links() }}
        </div>
    </div>
</x-app-layout>