<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-gray-800 dark:text-gray-100 tracking-tight">
            Trung tâm Trợ giúp & Câu hỏi Thường gặp
        </h2>
    </x-slot>

    <div class="py-8 px-4 sm:px-6 lg:px-8 bg-gray-50 dark:bg-gray-900 min-h-screen">
        <div class="max-w-4xl mx-auto space-y-10">

            {{-- Thanh tìm kiếm --}}
            <div class="bg-white dark:bg-gray-800 shadow-xl rounded-2xl p-6">
                <h3 class="text-xl font-bold text-gray-900 dark:text-gray-100 mb-4">Tìm kiếm câu trả lời</h3>
                <form action="#" method="GET">
                    <div class="relative">
                        <input type="search" placeholder="Nhập từ khóa, ví dụ: 'tạo đơn hàng'..."
                               class="w-full py-3 pl-12 pr-4 bg-gray-100 dark:bg-gray-700 border-gray-300 dark:border-gray-600 rounded-xl text-gray-900 dark:text-gray-100 focus:ring-indigo-500 focus:border-indigo-500 transition duration-150 ease-in-out">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none">
                            <svg class="w-5 h-5 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </div>
                    </div>
                </form>
            </div>

            {{-- Danh sách Câu hỏi Thường gặp (FAQ) --}}
            <div class="space-y-4">
                <h3 class="text-2xl font-bold text-gray-800 dark:text-gray-100 border-b pb-2 border-gray-200 dark:border-gray-700">
                    Câu hỏi Thường gặp (FAQs)
                </h3>

                @foreach ($faqs as $faq)
                <div class="bg-white dark:bg-gray-800 shadow-lg rounded-xl overflow-hidden">
                    <details class="group">
                        <summary class="flex justify-between items-center cursor-pointer p-4 sm:p-6 list-none hover:bg-gray-50 dark:hover:bg-gray-700/50 transition duration-200">
                            <span class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                                {{ $faq['question'] }}
                            </span>
                            <span class="ml-6 flex items-center">
                                <svg class="w-5 h-5 text-indigo-500 transform group-open:rotate-180 transition duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </span>
                        </summary>
                        <div class="px-4 sm:px-6 pb-6 pt-0 text-gray-700 dark:text-gray-300 border-t border-gray-100 dark:border-gray-700">
                            <p>{{ $faq['answer'] }}</p>
                        </div>
                    </details>
                </div>
                @endforeach
                
                {{-- Liên kết đến trang liên hệ --}}
                <p class="text-center pt-8 text-lg text-gray-600 dark:text-gray-400">
                    Bạn vẫn cần trợ giúp? <a href="{{ route('support.contact') }}" class="font-semibold text-indigo-600 hover:text-indigo-500 dark:text-indigo-400 dark:hover:text-indigo-300 transition-colors">Liên hệ với đội ngũ hỗ trợ của chúng tôi</a>.
                </p>
            </div>
            
        </div>
    </div>
</x-app-layout>