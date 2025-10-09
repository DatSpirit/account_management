<nav x-data="{ open: false }" class="bg-white dark:bg-gray-800 border-b border-gray-100 dark:border-gray-700">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}">
                        <x-application-logo class="block h-9 w-auto fill-current text-gray-800 dark:text-gray-200" />
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                        {{ __('Dashboard') }}
                    </x-nav-link>

                    <!-- Hiển thị link Admin Panel chỉ khi user là admin -->
                    @if(Auth::check() && Auth::user()->is_admin)
                        <x-nav-link :href="route('admin.users')" :active="request()->routeIs('admin.users')">
                            {{ __('Admin Panel') }}
                        </x-nav-link>
                    @endif
                </div>
            </div>

            <!-- Settings Dropdown & DARK MODE TOGGLE -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                
                <!-- START: DARK MODE TOGGLE BUTTON -->
                <!-- Nút này gọi themeToggle() trong app.blade.php để chuyển đổi chế độ -->
                <!-- Lưu ý: Tôi đã sử dụng lớp 'ms-4' để tạo khoảng cách với Dropdown -->
                <button id="theme-toggle"
                    class="p-2 me-4 text-xl rounded-full shadow-md bg-gray-200 hover:bg-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600 transition duration-200">
                    
                    <!-- Icon hiển thị khi ở chế độ Light (Sáng) - Biểu tượng Mặt Trăng (Dark Icon) -->
                    <span id="theme-toggle-dark-icon" class="text-indigo-600 transition-colors duration-200">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6">
                            <path d="M12 2.25a.75.75 0 0 1 .75.75v2.25a.75.75 0 0 1-1.5 0V3a.75.75 0 0 1 .75-.75ZM7.5 12a4.5 4.5 0 1 1 9 0 4.5 4.5 0 0 1-9 0ZM18.894 6.106a.75.75 0 0 0-1.06-1.06l-1.591 1.59a.75.75 0 1 0 1.06 1.061l1.591-1.59ZM21.75 12a.75.75 0 0 1-.75.75h-2.25a.75.75 0 0 1 0-1.5H21a.75.75 0 0 1 .75.75ZM18.894 17.894a.75.75 0 1 0 1.06-1.06l-1.59-1.591a.75.75 0 0 0-1.061 1.06l1.59 1.591ZM12 21.75a.75.75 0 0 1-.75-.75v-2.25a.75.75 0 0 1 1.5 0V21a.75.75 0 0 1-.75.75ZM5.006 17.894l-1.59-1.591a.75.75 0 0 1 1.06-1.06l1.59 1.591a.75.75 0 0 1-1.06 1.06Zm-1.5-5.25a.75.75 0 0 1 .75-.75h2.25a.75.75 0 0 1 0 1.5H4.25a.75.75 0 0 1-.75-.75ZM6.106 6.106a.75.75 0 0 0 1.06-1.06l-1.59-1.591a.75.75 0 0 0-1.061 1.06l1.591 1.59Z" />
                        </svg>
                    </span>

                    <!-- Icon hiển thị khi ở chế độ Dark (Tối) - Biểu tượng Mặt Trời (Light Icon) -->
                    <span id="theme-toggle-light-icon" class="text-yellow-300 transition-colors duration-200 hidden">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6">
                            <path fill-rule="evenodd" d="M9.52 2.308A1.5 1.5 0 0 1 11.233 2.1c1.334.19 2.593.774 3.717 1.674c.943.777 1.517 1.838 1.574 2.946A.75.75 0 0 0 17.25 7h-.494a4.482 4.482 0 0 0-2.315 1.02c-1.38 1.151-2.24 2.825-2.24 4.542 0 1.716.86 3.39 2.24 4.541a4.482 4.482 0 0 0 2.315 1.02h.494a.75.75 0 0 0 .673-.418c.057-1.108.631-2.17 1.574-2.946A10.835 10.835 0 0 0 20.312 11a1.5 1.5 0 0 1 1.488 1.69c-.068 1.258-.292 2.493-.654 3.684A10.741 10.741 0 0 1 18 20.75a.75.75 0 0 1-.75.75H6.75a.75.75 0 0 1-.75-.75A10.74 10.74 0 0 1 3.398 16.37c-.362-1.191-.586-2.426-.654-3.684a1.5 1.5 0 0 1 1.488-1.69h.448a.75.75 0 0 0 .543-1.26c-.072-.095-.143-.191-.212-.288-.94-.85-1.51-1.976-1.51-3.218a.75.75 0 0 1 1.5 0c0 .762.33 1.45.862 2.001c.144.152.296.3.454.444a1.5 1.5 0 0 1-.848 2.308Z" clip-rule="evenodd" />
                        </svg>
                    </span>
                </button>
                <!-- END: DARK MODE TOGGLE BUTTON -->

                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-800 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none transition ease-in-out duration-150">
                            <div>{{ Auth::user()->name }}</div>

                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">
                            {{ __('Profile') }}
                        </x-dropdown-link>

                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault();
                                                    this.closest('form').submit();">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 dark:text-gray-500 hover:text-gray-500 dark:hover:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-900 focus:outline-none focus:bg-gray-100 dark:focus:bg-gray-900 focus:text-gray-500 dark:focus:text-gray-400 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                {{ __('Dashboard') }}
            </x-responsive-nav-link>

            <!-- Hiển thị link Admin Panel trên mobile -->
            @if(Auth::check() && Auth::user()->is_admin)
                <x-responsive-nav-link :href="route('admin.users')" :active="request()->routeIs('admin.users')">
                    {{ __('Admin Panel') }}
                </x-responsive-nav-link>
            @endif
        </div>

        <!-- Responsive Settings Options -->
        <!-- START: DARK MODE TOGGLE BUTTON CHO MOBILE -->
        <!-- Đặt nút chuyển đổi Dark Mode ở đầu phần cài đặt responsive -->
        <div class="px-4 py-2 border-t border-gray-200 dark:border-gray-600">
            <button id="theme-toggle-mobile"
                class="w-full flex items-center justify-start p-2 rounded-lg bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 transition duration-200">
                
                <!-- Icon và Text -->
                <span class="text-gray-800 dark:text-gray-200 font-medium me-3">
                    {{ __('Toggle Theme') }}
                </span>
                
                <!-- Icon hiển thị khi ở chế độ Light (Sáng) - Biểu tượng Mặt Trời -->
                <span id="theme-toggle-dark-icon-mobile" class="text-indigo-600 transition-colors duration-200">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6">
                        <path d="M12 2.25a.75.75 0 0 1 .75.75v2.25a.75.75 0 0 1-1.5 0V3a.75.75 0 0 1 .75-.75ZM7.5 12a4.5 4.5 0 1 1 9 0 4.5 4.5 0 0 1-9 0ZM18.894 6.106a.75.75 0 0 0-1.06-1.06l-1.591 1.59a.75.75 0 1 0 1.06 1.061l1.591-1.59ZM21.75 12a.75.75 0 0 1-.75.75h-2.25a.75.75 0 0 1 0-1.5H21a.75.75 0 0 1 .75.75ZM18.894 17.894a.75.75 0 1 0 1.06-1.06l-1.59-1.591a.75.75 0 0 0-1.061 1.06l1.59 1.591ZM12 21.75a.75.75 0 0 1-.75-.75v-2.25a.75.75 0 0 1 1.5 0V21a.75.75 0 0 1-.75.75ZM5.006 17.894l-1.59-1.591a.75.75 0 0 1 1.06-1.06l1.59 1.591a.75.75 0 0 1-1.06 1.06Zm-1.5-5.25a.75.75 0 0 1 .75-.75h2.25a.75.75 0 0 1 0 1.5H4.25a.75.75 0 0 1-.75-.75ZM6.106 6.106a.75.75 0 0 0 1.06-1.06l-1.59-1.591a.75.75 0 0 0-1.061 1.06l1.591 1.59Z" />
                    </svg>
                </span>

                <!-- Icon hiển thị khi ở chế độ Dark (Tối) - Biểu tượng Mặt Trăng -->
                <span id="theme-toggle-light-icon-mobile" class="text-yellow-300 transition-colors duration-200 hidden">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6">
                        <path fill-rule="evenodd" d="M9.52 2.308A1.5 1.5 0 0 1 11.233 2.1c1.334.19 2.593.774 3.717 1.674c.943.777 1.517 1.838 1.574 2.946A.75.75 0 0 0 17.25 7h-.494a4.482 4.482 0 0 0-2.315 1.02c-1.38 1.151-2.24 2.825-2.24 4.542 0 1.716.86 3.39 2.24 4.541a4.482 4.482 0 0 0 2.315 1.02h.494a.75.75 0 0 0 .673-.418c.057-1.108.631-2.17 1.574-2.946A10.835 10.835 0 0 0 20.312 11a1.5 1.5 0 0 1 1.488 1.69c-.068 1.258-.292 2.493-.654 3.684A10.741 10.741 0 0 1 18 20.75a.75.75 0 0 1-.75.75H6.75a.75.75 0 0 1-.75-.75A10.74 10.74 0 0 1 3.398 16.37c-.362-1.191-.586-2.426-.654-3.684a1.5 1.5 0 0 1 1.488-1.69h.448a.75.75 0 0 0 .543-1.26c-.072-.095-.143-.191-.212-.288-.94-.85-1.51-1.976-1.51-3.218a.75.75 0 0 1 1.5 0c0 .762.33 1.45.862 2.001c.144.152.296.3.454.444a1.5 1.5 0 0 1-.848 2.308Z" clip-rule="evenodd" />
                    </svg>
                </span>
            </button>
        </div>
        <!-- END: DARK MODE TOGGLE BUTTON CHO MOBILE -->
        
        <div class="pt-4 pb-1 border-t border-gray-200 dark:border-gray-600">
            <div class="px-4">
                <div class="font-medium text-base text-gray-800 dark:text-gray-200">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">
                    {{ __('Profile') }}
                </x-responsive-nav-link>

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault();
                                            this.closest('form').submit();">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>
