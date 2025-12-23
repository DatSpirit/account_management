### Quản Lý Tài Khoản (Account Management System)

Đây là hệ thống quản lý người dùng được xây dựng bằng **Laravel** và sử dụng cơ sở dữ liệu **MySQL/MariaDB**.

Dưới đây là các bước cần thiết để thiết lập và chạy dự án trên máy cục bộ của bạn.

--- 

## 1. Yêu Cầu Hệ Thống

Để chạy ứng dụng này, bạn cần cài đặt các công cụ sau:

- **Web Server & Database**: XAMPP (bao gồm Apache và MySQL/MariaDB) hoặc môi trường tương đương (WAMP, MAMP, Docker, v.v.).

- **PHP**: Phiên bản PHP 8.x (Tùy thuộc vào phiên bản Laravel bạn đang dùng).

- **Composer**: Trình quản lý thư viện PHP.

- **Git**: Dùng để clone mã nguồn.

---


## 2. Thiết Lập Môi Trường Cục Bộ

# 2.1. Clone Dự Án

- Mở Git Bash hoặc Command Prompt và thực hiện lệnh sau để tải mã nguồn về:

```bash
git clone https://github.com/DatSpirit/account_management.git
cd (tên dự án của bạn)
```
---

# 2.2. Khởi động Web Server và Database

1. Mở **XAMPP Control Panel**
2. Nhấn **Start Apache**
3. Nhấn **Start MySQL**

---

# 2.3. Cài đặt các Thư viện PHP
Trong thư mục dự án bạn tải về, chạy lệnh Composer để tải các dependency cần thiết:

```bash
composer install
```

---

# 2.4. Tạo Tệp Cấu Hình Môi Trường (.env)

- Tạo một bản sao của tệp mẫu và đổi tên:

## Windows
```bash
copy .env.example .env
```

Hoặc: 

## Linux/Mac/Git Bash
```bash
cp .env.example .env
```

- Sau đó, tạo khóa ứng dụng (Application Key):

```bash
php artisan key:generate
```

---

## 3. Cấu Hình Cơ Sở Dữ Liệu (Database)

# 3.1. Cấu hình Kết nối trong `.env`

Mở tệp .env và cập nhật các thông số kết nối cơ sở dữ liệu:  

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306

DB_DATABASE=your_database_name
DB_USERNAME=root
DB_PASSWORD=
```

> 🔹 Nếu dùng XAMPP mặc định → **username: root**, **password để trống**

---

# 3.2. Chạy Migration và Seeder

Nếu muốn dùng tài khoản Admin mẫu → mở:

```
database/seeders/UserSeeder.php
```

→ Bỏ comment tài khoản Admin.

Chạy migration + seeder:

```bash
php artisan migrate --seed
```

Sẽ hiện:

```
Would you like to create it? (yes/no) [yes]
```

Nhập:

```
yes
```

---

- Nếu bạn chạy php artisan migrate --seed thành công.
- Bạn có thể sử dụng thông tin đăng nhập (Admin) mặc định để kiểm tra khi set-up xong.

# 3.3. (Tùy chọn) Tạo Storage Link
- Để ứng dụng có sử dụng lưu trữ file, bạn cần tạo liên kết tượng trưng (symlink):

```bash
php artisan storage:link
```

---

# 3.4 Cài đặt các gói phụ thuộc frontend
- Dùng lệnh:
```bash
npm install
npm run build
```

---

## 4. Chạy Ứng Dụng
Sau khi hoàn tất các bước trên, bạn có thể chạy ứng dụng theo hai cách:

# Sử dụng Server Laravel tích hợp 

```bash
php artisan serve
```

Truy cập:

```
http://127.0.0.1:8000
```

---

## 5. Lưu Ý
Nên kiểm tra lại tạo tài khoản Admin tránh bị trùng sẽ báo lỗi.

Xóa cache nếu lỗi cấu hình:

```bash
php artisan config:clear
php artisan cache:clear
```

---

# Chúc bạn thành công! Nếu gặp bất kỳ lỗi nào, vui lòng kiểm tra lại: 
- File `.env`  
- Apache/MySQL đã chạy chưa  
- Phiên bản PHP  
- Đã migrate database chưa  


