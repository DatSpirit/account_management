import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();


/**
 * Logic Kích hoạt Dark Mode
 * 1. Kiểm tra Local Storage hoặc Cài đặt hệ thống
 * 2. Áp dụng class 'dark' lên thẻ <html>
 * 3. Cung cấp hàm toàn cục để chuyển đổi chế độ
 */

const htmlElement = document.documentElement;

// Hàm kiểm tra và áp dụng theme khi tải trang
function initializeTheme() {
    const savedTheme = localStorage.getItem('color-theme');
    
    // Nếu chưa có trạng thái lưu, sử dụng cài đặt hệ thống
    const systemPrefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;

    // Xác định xem trang có nên ở chế độ tối hay không
    const isDark = savedTheme === 'dark' || (savedTheme === null && systemPrefersDark);

    if (isDark) {
        htmlElement.classList.add('dark');
        // Đặt 'color-theme' trong localStorage nếu nó chưa tồn tại (để tránh FOUC)
        if (savedTheme === null) {
            localStorage.setItem('color-theme', 'dark');
        }
    } else {
        htmlElement.classList.remove('dark');
        if (savedTheme === null) {
            localStorage.setItem('color-theme', 'light');
        }
    }
}

// Hàm chuyển đổi theme và lưu trạng thái
window.toggleTheme = function() {
    if (htmlElement.classList.contains('dark')) {
        htmlElement.classList.remove('dark');
        localStorage.setItem('color-theme', 'light');
    } else {
        htmlElement.classList.add('dark');
        localStorage.setItem('color-theme', 'dark');
    }
};

// Khởi tạo theme ngay lập tức
initializeTheme();
