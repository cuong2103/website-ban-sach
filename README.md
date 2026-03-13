# 📚 Quan_li_Agile — Website Bán Sách

> **Base code PHP MVC thuần**

---

## 🚀 Hướng dẫn cài đặt nhanh

### 1. Clone project

```bash
git clone <repository-url>
cd Quan_li_Agile
```

### 2. Tạo file cấu hình môi trường

Sao chép file mẫu và điền thông tin thực tế:

```bash
# Copy file mẫu
cp config/env.example.php config/env.php
```

Mở `config/env.php` và sửa các giá trị:

```php
define('BASE_URL', 'http://quan-li-agile.test/');  // URL local của bạn
define('DB_NAME',     'quan_li_agile');              // Tên database
define('DB_USERNAME', 'root');                       // Username MySQL
define('DB_PASSWORD', '');                           // Password MySQL
```

> ⚠️ **Lưu ý:** File `config/env.php` đã bị `.gitignore` — **KHÔNG** được commit lên Git.
> Chỉ `config/env.example.php` mới được commit.

### 3. Tạo Database

Mở **phpMyAdmin** → Import file `db.sql`

Hoặc chạy lệnh:
```bash
mysql -u root -p < db.sql
```

### 4. Tài khoản mẫu

| Email | Mật khẩu | Vai trò |
|---|---|---|
| `admin@example.com` | `admin123` | Admin |

> Để đổi mật khẩu, chạy PHP:
> ```php
> echo password_hash('mật_khẩu_mới', PASSWORD_DEFAULT);
> ```
> Rồi cập nhật bảng `users` trong database.

### 5. Cấu hình Virtual Host (Laragon)

- Đặt domain: `quan-li-agile.test`
- Trỏ về thư mục: `c:\laragon\www\Quan_li_Agile`
- Mở trình duyệt: `http://quan-li-agile.test`

---

## 🏗️ Kiến trúc hệ thống

Xem file [`STRUCTURE.md`](STRUCTURE.md) để hiểu chi tiết cách tổ chức code.

---

## 🔧 Các hàm helper có sẵn

| Hàm | Mô tả |
|---|---|
| `connectDB()` | Kết nối PDO đến database |
| `redirect('act')` | Chuyển hướng đến `?act=` |
| `validate($data, $rules)` | Validation form |
| `old('field')` | Lấy giá trị cũ sau khi validation lỗi |
| `uploadFile($file, $folder)` | Upload file lên `uploads/` |
| `deleteFile($path)` | Xóa file khỏi `uploads/` |
| `requireAdmin()` | Chặn nếu không phải Admin |
| `checkLogin()` | Chặn nếu chưa đăng nhập |
| `timeAgo($datetime)` | "2 giờ trước", "3 ngày trước"... |
| `Message::set('success', 'msg')` | Set flash message |
| `Message::get('success')` | Lấy flash message (tự xóa sau khi đọc) |
| `dd($data)` | Debug dump + die |

---

## 📐 Quy tắc đặt tên

| Thành phần | Quy tắc | Ví dụ |
|---|---|---|
| Model | `PascalCase` + `Model` | `BookModel`, `CategoryModel` |
| Controller | `PascalCase` + `Controller` | `BookController` |
| View folder | `kebab-case` | `views/admin/books/` |
| Route (act) | `kebab-case` | `books`, `book-create`, `book-update` |
| DB table | `snake_case`, số nhiều | `books`, `order_items` |
| DB column | `snake_case` | `created_at`, `category_id` |

---

## 🎨 UI / CSS

- **Framework:** Tailwind CSS (CDN)
- **Icons:** Lucide Icons (CDN)
- Không có file CSS riêng — dùng utility classes của Tailwind trực tiếp trong HTML
- Tham khảo [Tailwind Docs](https://tailwindcss.com/docs) | [Lucide Icons](https://lucide.dev/icons)

