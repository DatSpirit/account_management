<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <a href="{{ route('keys.keydetails', $key->id) }}" 
                   class="p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors duration-200">
                    <svg class="w-6 h-6 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                </a>
                <div>
                    <h2 class="font-bold text-2xl text-gray-800 dark:text-white">Lịch sử hoạt động</h2>
                    <p class="text-sm text-gray-500 dark:text-gray-400">
                        Key: <code class="font-mono font-bold bg-gray-100 dark:bg-gray-700 px-2 py-1 rounded">{{ $key->key_code }}</code>
                    </p>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-6 px-2 sm:px-6 lg:px-8 max-w-5xl mx-auto">
        <div class="relative">
            {{-- Timeline Line --}}
            <div class="absolute left-8 md:left-1/2 top-0 bottom-0 w-0.5 bg-gradient-to-b from-gray-200 via-gray-300 to-gray-200 dark:from-gray-700 dark:via-gray-600 dark:to-gray-700"></div>

            @forelse($histories as $index => $history)
                @php
                    // Đảo bên trái phải (desktop)
                    $isRight = $index % 2 !== 0;
                    
                    // Màu sắc & Icon dựa theo hành động
                    $config = match($history->action) {
                        'create' => ['color' => 'blue', 'icon' => '✨', 'title' => 'Tạo mới', 'gradient' => 'from-blue-400 to-blue-600'],
                        'extend' => ['color' => 'green', 'icon' => '⚡', 'title' => 'Gia hạn', 'gradient' => 'from-green-400 to-green-600'],
                        'custom_extend' => ['color' => 'indigo', 'icon' => '🎯', 'title' => 'Gia hạn tùy chỉnh', 'gradient' => 'from-indigo-400 to-purple-600'],
                        'suspend' => ['color' => 'yellow', 'icon' => '⏸️', 'title' => 'Tạm dừng', 'gradient' => 'from-yellow-400 to-yellow-600'],
                        'activate' => ['color' => 'emerald', 'icon' => '▶️', 'title' => 'Kích hoạt', 'gradient' => 'from-emerald-400 to-emerald-600'],
                        'revoke' => ['color' => 'red', 'icon' => '🚫', 'title' => 'Thu hồi', 'gradient' => 'from-red-400 to-red-600'],
                        default => ['color' => 'gray', 'icon' => '📝', 'title' => 'Ghi nhận', 'gradient' => 'from-gray-400 to-gray-600']
                    };
                @endphp

                {{-- Timeline Item --}}
                <div class="mb-8 flex {{ $isRight ? 'justify-end' : 'justify-start' }}">
                    
                    {{-- Card --}}
                    <div class="relative w-full md:w-5/12 {{ $isRight ? 'md:pl-8' : 'md:pr-8' }} pl-16">
                        {{-- Timeline Dot --}}
                        <div class="absolute left-8 md:left-auto {{ $isRight ? 'md:-left-4' : 'md:-right-4' }} top-6 w-8 h-8 bg-gradient-to-br {{ $config['gradient'] }} shadow-xl rounded-full border-4 border-white dark:border-gray-900 flex items-center justify-center text-white text-sm font-bold z-10 transform hover:scale-110 transition-transform duration-200">
                            {{ $config['icon'] }}
                        </div>

                        {{-- Content Card --}}
                        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg hover:shadow-2xl border-l-4 border-{{ $config['color'] }}-500 p-6 transition-all duration-300 transform hover:scale-[1.02]">
                            {{-- Header --}}
                            <div class="flex items-start justify-between mb-3">
                                <div>
                                    <h3 class="font-bold text-lg text-gray-800 dark:text-gray-100 flex items-center gap-2">
                                        <span class="text-2xl">{{ $config['icon'] }}</span>
                                        {{ $config['title'] }}
                                    </h3>
                                    <p class="text-xs text-gray-400 mt-1 flex items-center gap-1">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        {{ $history->created_at->format('H:i - d/m/Y') }}
                                    </p>
                                </div>
                                {{-- Action Badge --}}
                                <span class="px-3 py-1 bg-{{ $config['color'] }}-100 dark:bg-{{ $config['color'] }}-900/30 text-{{ $config['color'] }}-700 dark:text-{{ $config['color'] }}-400 rounded-full text-xs font-semibold">
                                    {{ ucfirst($history->action) }}
                                </span>
                            </div>
                            
                            {{-- Description --}}
                            <p class="text-sm text-gray-600 dark:text-gray-300 leading-relaxed mb-4">
                                {{ $history->description }}
                            </p>

                            {{-- Metadata --}}
                            @if($history->meta_data && count($history->meta_data) > 0)
                                <div class="bg-gradient-to-br from-gray-50 to-gray-100 dark:from-gray-700/50 dark:to-gray-800/50 rounded-xl p-4 border border-gray-200 dark:border-gray-600">
                                    <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-3">Chi tiết</p>
                                    <div class="grid gap-2">
                                        @foreach($history->meta_data as $metaKey => $value)
                                            <div class="flex items-center justify-between text-sm">
                                                <span class="text-gray-500 dark:text-gray-400 capitalize flex items-center gap-2">
                                                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                                                    </svg>
                                                    {{ str_replace('_', ' ', $metaKey) }}:
                                                </span>
                                                <span class="font-mono font-bold text-gray-900 dark:text-gray-100 bg-white dark:bg-gray-800 px-3 py-1 rounded-lg">
                                                    {{ $value }}
                                                </span>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

            @empty
                {{-- Empty State --}}
                <div class="text-center py-16">
                    <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-gradient-to-br from-gray-100 to-gray-200 dark:from-gray-800 dark:to-gray-700 mb-4 shadow-inner">
                        <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <p class="text-gray-500 dark:text-gray-400 text-lg font-medium">Chưa có lịch sử hoạt động</p>
                    <p class="text-gray-400 dark:text-gray-500 text-sm mt-2">Các thao tác trên Key sẽ được ghi lại tại đây</p>
                </div>
            @endforelse
        </div>
        
        {{-- Pagination --}}
        <div class="mt-8">
            {{ $histories->links() }}
        </div>
    </div>
</x-app-layout>