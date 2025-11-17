<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-gray-800 dark:text-gray-100 tracking-tight">
            Liên hệ Hỗ trợ Kỹ thuật
        </h2>
    </x-slot>

    <div class="py-8 px-4 sm:px-6 lg:px-8 bg-gray-50 dark:bg-gray-900 min-h-screen">
        <div class="max-w-2xl mx-auto">
            
            {{-- Form Liên hệ --}}
            <div class="bg-white dark:bg-gray-800 shadow-xl rounded-2xl p-6 sm:p-8 border border-gray-200 dark:border-gray-700">
                <p class="mb-6 text-gray-600 dark:text-gray-400">
                    Vui lòng điền vào biểu mẫu dưới đây, đội ngũ hỗ trợ của chúng tôi sẽ phản hồi bạn trong thời gian sớm nhất.
                </p>

                {{-- Thông báo Flash Message (Success/Error) --}}
                @if (session('success'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                        <span class="block sm:inline">{{ session('success') }}</span>
                    </div>
                @endif
                @if ($errors->any())
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                        <span class="block sm:inline">Vui lòng kiểm tra lại các trường thông tin.</span>
                    </div>
                @endif

                <form action="{{ route('support.contact.submit') }}" method="POST" class="space-y-6">
                    @csrf
                    
                    {{-- Tên --}}
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Tên của bạn</label>
                        <input type="text" name="name" id="name" required value="{{ old('name', auth()->user()->name ?? '') }}"
                               class="mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-gray-100 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        @error('name') <p class="text-sm text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>

                    {{-- Email --}}
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Địa chỉ Email</label>
                        <input type="email" name="email" id="email" required value="{{ old('email', auth()->user()->email ?? '') }}"
                               class="mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-gray-100 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        @error('email') <p class="text-sm text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>
                    
                    {{-- Chủ đề --}}
                    <div>
                        <label for="subject" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Chủ đề</label>
                        <input type="text" name="subject" id="subject" required value="{{ old('subject') }}"
                               class="mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-gray-100 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        @error('subject') <p class="text-sm text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>

                    {{-- Nội dung --}}
                    <div>
                        <label for="message" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nội dung yêu cầu</label>
                        <textarea name="message" id="message" rows="5" required
                                  class="mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-gray-100 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('message') }}</textarea>
                        @error('message') <p class="text-sm text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>

                    {{-- Nút Gửi --}}
                    <div class="pt-2">
                        <button type="submit"
                                class="w-full flex justify-center py-3 px-4 border border-transparent rounded-lg shadow-sm text-base font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition duration-150 ease-in-out">
                            Gửi yêu cầu hỗ trợ
                        </button>
                    </div>
                </form>
            </div>
            
            {{-- Thông tin liên hệ phụ --}}
            <div class="mt-8 text-center space-y-4">
                <p class="text-gray-600 dark:text-gray-400">Hoặc liên hệ trực tiếp qua:</p>
                <div class="flex justify-center space-x-6 text-sm font-medium">
                    <a href="mailto:support@yourdomain.com" class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-500 flex items-center">
                        <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26c.45.3.93.3 1.35 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                        Email Hỗ trợ
                    </a>
                    <a href="tel:+84123456789" class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-500 flex items-center">
                        <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.128a11.042 11.042 0 005.518 5.518l1.128-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-11a2 2 0 01-2-2z"/></svg>
                        (0844) 420 444
                    </a>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>