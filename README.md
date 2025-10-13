Quản Lý Tài Khoản (Account Management System)
Đây là hệ thống quản lý người dùng được xây dựng bằng Laravel và sử dụng cơ sở dữ liệu MySQL/MariaDB.

Dưới đây là các bước cần thiết để thiết lập và chạy dự án trên máy cục bộ của bạn.

1. Yêu Cầu Hệ Thống
Để chạy ứng dụng này, bạn cần cài đặt các công cụ sau:

Web Server & Database: XAMPP (bao gồm Apache và MySQL/MariaDB) hoặc môi trường tương đương (WAMP, MAMP, Docker, v.v.).

PHP: Phiên bản PHP 8.x (Tùy thuộc vào phiên bản Laravel bạn đang dùng).

Composer: Công cụ quản lý thư viện cho PHP.

Git: Hệ thống kiểm soát phiên bản (để clone dự án).

2. Thiết Lập Môi Trường Cục Bộ
2.1. Clone Dự Án
Mở Git Bash hoặc Command Prompt và thực hiện lệnh sau để tải mã nguồn về:

cd D:\xampp\htdocs
git clone [https://github.com/DatSpirit/account_management.git](https://github.com/DatSpirit/account_management.git)
cd account_management

2.2. Khởi động Web Server và Database
Mở XAMPP Control Panel.

Khởi động module Apache (Web Server).

Khởi động module MySQL (Database Server).

2.3. Cài đặt các Thư viện PHP
Trong thư mục dự án (D:\xampp\htdocs\account_management), chạy lệnh Composer để tải các dependency cần thiết:

composer install

2.4. Tạo Tệp Cấu Hình Môi Trường (.env)
Tạo một bản sao của tệp mẫu và đổi tên:

copy .env.example .env
# Hoặc: cp .env.example .env (trên môi trường Unix/Linux/Git Bash)

Sau đó, tạo khóa ứng dụng (Application Key):

php artisan key:generate

3. Cấu Hình Cơ Sở Dữ Liệu (Database)
3.1. Tạo Database
Truy cập vào phpMyAdmin qua trình duyệt (thường là http://localhost/phpmyadmin/) và tạo một cơ sở dữ liệu mới.

Tên cơ sở dữ liệu đề xuất: account_management_db

3.2. Cấu hình Kết nối trong .env
Mở tệp .env và cập nhật các thông số kết nối cơ sở dữ liệu:

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=account_management_db # Đổi thành tên DB bạn vừa tạo
DB_USERNAME=root                # Thường là root nếu dùng XAMPP mặc định
DB_PASSWORD=                    # Để trống nếu dùng XAMPP mặc định (hoặc điền mật khẩu của bạn)

3.3. Chạy Migration và Seeder
Chạy các lệnh sau để tạo bảng trong cơ sở dữ liệu và điền dữ liệu mẫu (nếu có Seeder):

php artisan migrate --seed

3.4. (Tùy chọn) Tạo Storage Link
Nếu ứng dụng có sử dụng lưu trữ file (ví dụ: ảnh đại diện), bạn cần tạo liên kết tượng trưng (symlink):

php artisan storage:link

4. Chạy Ứng Dụng
Sau khi hoàn tất các bước trên, bạn có thể chạy ứng dụng theo hai cách:

Cách 1: Sử dụng Server Laravel tích hợp (Khuyến nghị)
php artisan serve

Ứng dụng sẽ chạy tại địa chỉ: http://127.0.0.1:8000

Cách 2: Sử dụng Web Server Apache của XAMPP
Truy cập vào địa chỉ: http://localhost/account_management/public

5. Đăng Nhập (Testing)
Nếu bạn đã chạy php artisan migrate --seed thành công, bạn có thể sử dụng thông tin đăng nhập mặc định (hoặc tài khoản đã được tạo qua seeder) để kiểm tra.

Tài khoản Admin mẫu: (Nếu seeder có tạo)

Email: admin@example.com

Mật khẩu: password (hoặc mật khẩu mặc định của seeder)

Chúc bạn thành công! Nếu gặp bất kỳ lỗi nào, vui lòng kiểm tra lại cấu hình .env và đảm bảo các module Apache và MySQL đang chạy trong XAMPP.