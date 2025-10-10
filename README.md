# Hướng dẫn Cài đặt (Setup Guide)
Yêu cầu Tiên quyết
Để chạy dự án, bạn cần đảm bảo các phần mềm sau đã được cài đặt:

- PHP: Phiên bản >= 8.1

- Composer: Trình quản lý gói cho PHP.

- Node.js & npm: Để quản lý các gói frontend (Tailwind CSS).

- Database Server: Ví dụ: MySQL, PostgreSQL, hoặc SQLite (để nhanh chóng sử dụng)

- Web Server: Ví dụ: Apache, Nginx hoặc sử dụng PHP built-in server.

Các bước cài đặt chi tiết
# Clone Repository:
Tải mã nguồn về máy của bạn:

- git clone [repository-url]
- cd [project-name]

# Cài đặt Dependencies Backend (PHP):
- Sử dụng Composer để tải về tất cả các thư viện PHP cần thiết.

composer install

# Cấu hình Môi trường (.env):
- Tạo file cấu hình môi trường và sinh khóa ứng dụng.

cp .env.example .env
php artisan key:generate

# Cấu hình Database: Mở file .env và cập nhật các dòng sau cho phù hợp với Database Server của bạn:

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=laravel_base_app  # Thay bằng tên database của bạn
DB_USERNAME=root              # Thay bằng username database của bạn
DB_PASSWORD=                  # Thay bằng password database của bạn

- Lưu ý: Nếu sử dụng SQLite, chỉ cần tạo file database rỗng và cấu hình DB_CONNECTION=sqlite.

# Chạy Database Migrations:
- Laravel sẽ tạo các bảng cần thiết, bao gồm cả cột is_admin mà chúng ta đã thêm.

php artisan migrate --force

# Cài đặt và Build Dependencies Frontend (Node/npm):
- Tải các gói frontend (Tailwind CSS) và biên dịch chúng để tạo ra file CSS cuối cùng.

npm install
npm run dev  # Chạy ở chế độ phát triển
npm run build (Chạy ở chế độ production)

# Gán quyền Admin (Tùy chọn):
Để kiểm tra tính năng phân quyền, bạn cần gán quyền Admin cho một tài khoản người dùng đã tồn tại.

php artisan tinker
> $user = App\Models\User::where('email', 'your-email@example.com')->first();
> $user->is_admin = true;
> $user->save();
> exit;

(Hãy đảm bảo bạn đã đăng ký một tài khoản trước khi thực hiện bước này).

# Khởi động Server:
Sử dụng server tích hợp của PHP để khởi chạy ứng dụng.

php artisan serve

# Ứng dụng sẽ chạy tại địa chỉ https://www.google.com/search?q=http://127.0.0.1:8000.
