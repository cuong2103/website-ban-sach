# 📚 Website Bán Sách — PHP MVC Base Code

> **Base code PHP MVC thuần** — Giao diện 2 role: **Khách hàng** & **Admin (BookAdmin)**
> Thiết kế theo Figma AI project. Tailwind CSS + Lucide Icons.

---

## 🚀 Cài đặt nhanh

### 1. Clone project
```bash
git clone <repository-url>
cd website-ban-sach
```

### 2. Tạo file cấu hình
```bash
cp config/env.example.php config/env.php
```

Mở `config/env.php` và chỉnh:
```php
define('BASE_URL',    'http://localhost/website-ban-sach/');
define('DB_NAME',     'website_ban_sach');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', '');
```

> ⚠️ `config/env.php` đã bị `.gitignore` — **KHÔNG** commit file này.

### 3. Import Database
Mở **phpMyAdmin** → Import `db.sql`

### 4. Truy cập
| URL | Trang |
|---|---|
| `http://localhost/website-ban-sach/` | Trang chủ khách hàng |
| `http://localhost/website-ban-sach/?act=admin-dashboard` | Admin Dashboard |

> 🔒 **Auth tạm thời tắt** — truy cập trực tiếp không cần đăng nhập (sẽ bật lại sau).

---

## 🔧 Helper functions

| Hàm | Mô tả |
|---|---|
| `connectDB()` | Kết nối PDO tới database |
| `redirect('act')` | Chuyển hướng đến `?act=` |
| `validate($data, $rules)` | Validation form |
| `old('field')` | Lấy giá trị cũ khi validation lỗi |
| `uploadFile($file, $folder)` | Upload file vào `uploads/` |
| `deleteFile($path)` | Xóa file trong `uploads/` |
| `requireAdmin()` | Chặn nếu không phải Admin *(tạm tắt)* |
| `checkLogin()` | Chặn nếu chưa đăng nhập *(tạm tắt)* |
| `timeAgo($datetime)` | "2 giờ trước", "3 ngày trước"... |
| `Message::set('success', 'msg')` | Set flash message |
| `Message::get('success')` | Lấy flash message (tự xóa sau đọc) |
| `dd($data)` | Debug dump + die |

---

## � Quy trình Git

```bash
# Tạo branch tính năng
git checkout -b feature/ten-tinh-nang

# Commit
git add .
git commit -m "feat: thêm quản lý sách"

# Push & tạo Pull Request
git push origin feature/ten-tinh-nang
```

> - ❌ **KHÔNG commit** `config/env.php` và `uploads/*`
> - ✅ **Chỉ commit** `config/env.example.php` khi thay đổi cấu hình

---

## 🎨 UI Stack

- **CSS:** Tailwind CSS (CDN) — dùng utility class trực tiếp trong HTML
- **Icons:** Lucide Icons (CDN) — `data-lucide="ten-icon"`
- **Màu Admin:** `#1B2537` (sidebar) / `#4CAF50` (accent)
- **Màu Customer:** `#4CAF50` (primary) / `#FFC107` (secondary)
- Tham khảo: [Tailwind Docs](https://tailwindcss.com/docs) | [Lucide Icons](https://lucide.dev/icons)
