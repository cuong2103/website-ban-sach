# 🗂️ Cấu Trúc Project — Quan_li_Agile

```
Quan_li_Agile/
│
├── 📄 index.php                     # Entry point duy nhất của ứng dụng
│                                    # Load config → commons → router
│
├── 📄 db.sql                        # Script tạo database + dữ liệu mẫu
│                                    # Import vào phpMyAdmin để bắt đầu
│
├── 📄 .gitignore                    # Bỏ qua: config/env.php, uploads/*
│
├── 📄 README.md                     # Hướng dẫn cài đặt và phát triển
│
├── 📁 config/
│   ├── env.example.php              # ✅ COMMIT — Mẫu cấu hình môi trường
│   ├── env.php                      # ❌ KHÔNG COMMIT — Cấu hình thực tế (local)
│   │                                # Tạo từ env.example.php
│   └── autoload.php                 # SPL autoload: tự động require class
│                                    # Quét: controllers/, controllers/admin/, models/
│
├── 📁 commons/
│   ├── function.php                 # Hàm helper dùng chung toàn hệ thống
│   │                                # connectDB, validate, redirect, uploadFile...
│   ├── message.php                  # Class Message — Flash session messages
│   │                                # Message::set('success', 'msg')
│   │                                # Message::get('success')
│   └── helperTree.php               # Helper cây danh mục đệ quy
│                                    # buildTree, renderCategory, renderOption
│
├── 📁 routers/
│   └── web.php                      # Định nghĩa tất cả routes
│                                    # Dùng match($act) — ?act=ten-route
│                                    # Thêm route mới vào đây
│
├── 📁 assets/
│   ├── common.js                    # JS toggle submenu sidebar
│   └── lucide.js                    # Shim file (icons load từ CDN)
│
├── 📁 models/                       # Tầng dữ liệu — kết nối database qua PDO
│   └── UserModel.php                # CRUD bảng users
│   # Thêm: BookModel.php, CategoryModel.php, OrderModel.php...
│
├── 📁 controllers/                  # Tầng xử lý logic
│   ├── AuthController.php           # Đăng nhập / Đăng xuất
│   ├── ProfileController.php        # Xem/sửa profile, đổi mật khẩu
│   └── admin/                       # Controllers chỉ dành cho Admin
│       └── DashboardController.php  # Trang tổng quan
│       # Thêm: BookController.php, CategoryController.php...
│
└── 📁 views/                        # Tầng giao diện (HTML + PHP)
    │
    ├── 📁 components/               # Layout dùng chung — include trong mọi trang
    │   ├── header.php               # <!DOCTYPE html>, <head>, top bar, navbar
    │   │                            # Load: Tailwind CDN, Lucide CDN, common.js
    │   ├── sidebar.php              # Sidebar menu — Thêm menu mới ở đây
    │   └── footer.php               # </body>, Toast messages, lucide.createIcons()
    │
    ├── 📁 auth/
    │   └── login.php                # Trang đăng nhập (không cần header/sidebar)
    │
    ├── 📁 admin/                    # Trang quản trị — chỉ Admin truy cập
    │   └── dashboard.php            # Dashboard với stat cards
    │   # Thêm: books/, categories/, orders/, vouchers/...
    │
    ├── 📁 shared/                   # Trang dùng chung (Admin & Khách hàng)
    │   ├── profile.php              # Xem thông tin cá nhân
    │   └── profile_edit.php         # Chỉnh sửa thông tin + upload avatar
    │
    ├── forbidden.php                # Trang 403 — Không có quyền truy cập
    └── notFound.php                 # Trang 404 — Không tìm thấy
```

## 📦 Quy trình làm việc với Git

```bash
# 1. Tạo branch tính năng mới
git checkout -b feature/ten-tinh-nang

# 2. Làm việc, commit thường xuyên
git add .
git commit -m "feat: thêm CRUD quản lý sách"

# 3. Push lên và tạo Pull Request
git push origin feature/ten-tinh-nang
```

> **Lưu ý quan trọng:**
> - ❌ **KHÔNG commit** `config/env.php`
> - ✅ **Chỉ commit** `config/env.example.php` khi có thay đổi cấu hình
> - ❌ **KHÔNG commit** file trong `uploads/`
