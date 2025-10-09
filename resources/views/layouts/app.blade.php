<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="//unpkg.com/alpinejs" defer></script>
</head>

<body class="font-sans antialiased bg-gray-100 dark:bg-gray-900 transition-colors duration-300">
    
    <div class="min-h-screen bg-gray-100 dark:bg-gray-900">
        @include('layouts.navigation')

        <!-- Page Heading -->
        @isset($header)
            <header class="bg-white dark:bg-gray-800 shadow">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    {{ $header }}
                </div>
            </header>
        @endisset

        <!-- Page Content -->
        <main>
            {{ $slot }}
        </main>
    </div>

    <!-- Script chuyển chế độ Dark/Light -->
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        // Lấy các phần tử
        const html = document.documentElement;

        // Lấy các nút và icon từ Navigation component
        const desktopToggle = document.getElementById('theme-toggle');
        const desktopDarkIcon = document.getElementById('theme-toggle-dark-icon');
        const desktopLightIcon = document.getElementById('theme-toggle-light-icon');
        
        const mobileToggle = document.getElementById('theme-toggle-mobile');
        const mobileDarkIcon = document.getElementById('theme-toggle-dark-icon-mobile');
        const mobileLightIcon = document.getElementById('theme-toggle-light-icon-mobile');


        // --- Hàm áp dụng theme ---
        function applyTheme(isDark) {
            if (isDark) {
                html.classList.add('dark');
                localStorage.theme = 'dark';
                
                // Hiển thị icon Light (Mặt Trăng) - Ẩn icon Dark (Mặt Trời)
                [desktopLightIcon, mobileLightIcon].forEach(el => el && el.classList.remove('hidden'));
                [desktopDarkIcon, mobileDarkIcon].forEach(el => el && el.classList.add('hidden'));

            } else {
                html.classList.remove('dark');
                localStorage.theme = 'light';
                
                // Hiển thị icon Dark (Mặt Trời) - Ẩn icon Light (Mặt Trăng)
                [desktopDarkIcon, mobileDarkIcon].forEach(el => el && el.classList.remove('hidden'));
                [desktopLightIcon, mobileLightIcon].forEach(el => el && el.classList.add('hidden'));
            }
        }

        // 1. Kiểm tra theme đã lưu khi tải trang
        const savedTheme = localStorage.theme;
        const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
        
        if (savedTheme === 'dark' || (!savedTheme && prefersDark)) {
            applyTheme(true);
        } else {
            applyTheme(false);
        }

        // 2. Khi click đổi chế độ
        function handleThemeToggle() {
            const isDark = html.classList.contains('dark');
            applyTheme(!isDark); // Chuyển đổi trạng thái hiện tại
        }

        // Gắn sự kiện cho cả nút Desktop và Mobile
        if (desktopToggle) {
            desktopToggle.addEventListener('click', handleThemeToggle);
        }
        if (mobileToggle) {
            mobileToggle.addEventListener('click', handleThemeToggle);
        }
    });
    </script>
</body>
</html>
