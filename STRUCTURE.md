# 🗂️ Cấu Trúc Project — Website Bán Sách

```
website-ban-sach/
│
├── 📄 index.php                          # Entry point duy nhất
│                                         # Load: config → commons → router
│
├── 📄 db.sql                             # Script tạo database + dữ liệu mẫu
├── 📄 .gitignore
├── 📄 README.md
├── 📄 STRUCTURE.md
│
├── 📁 config/
│   ├── env.example.php                   # ✅ COMMIT — Mẫu cấu hình
│   ├── env.php                           # ❌ KHÔNG COMMIT — Cấu hình local
│   └── autoload.php                      # SPL autoload: quét controllers/, models/
│
├── 📁 commons/
│   ├── function.php                      # Hàm helper dùng chung
│   ├── message.php                       # Class Message — Flash messages
│   └── helperTree.php                    # Helper cây danh mục đệ quy
│
├── 📁 routers/
│   └── web.php                           # Tất cả routes — match($act)
│                                         # ⚠️ Thêm route mới tại đây
│
├── 📁 assets/
│   └── common.js                         # JS dùng chung
│
├── 📁 uploads/                           # ❌ KHÔNG COMMIT — File upload
│
├── 📁 models/                            # Tầng dữ liệu (PDO)
│   └── UserModel.php
│   # Thêm: BookModel.php, CategoryModel.php, OrderModel.php...
│
├── 📁 controllers/
│   ├── AuthController.php                # Đăng nhập / Đăng xuất (tạm tắt)
│   ├── admin/                            # Controllers Admin
│   │   └── DashboardController.php
│   │   # Thêm: BookController.php, OrderController.php...
│   └── customer/                         # Controllers Khách hàng
│       └── HomeController.php
│       # Thêm: BookController.php, CartController.php...
│
└── 📁 views/                             # Tầng giao diện
    │
    ├── 📁 components/                    # Layout dùng chung — include mọi trang
    │   │
    │   ├── ── ADMIN LAYOUT ──────────────────────────────────────────
    │   ├── header.php                    # <!DOCTYPE>, <head>, top bar admin
    │   │                                 # Mở: <html>, <body>, <div.ml-56>
    │   ├── sidebar.php                   # Sidebar admin (dark #1B2537 / #4CAF50)
    │   │                                 # ⚠️ Thêm menu admin mới tại đây
    │   ├── footer.php                    # Đóng wrapper + Toast messages + </html>
    │   │
    │   └── ── CUSTOMER LAYOUT ───────────────────────────────────────
    │   ├── navbar.php                    # <!DOCTYPE>, <head>, navbar khách hàng
    │   │                                 # Mở: <html>, <body>, <nav>
    │   └── customer_footer.php           # Footer 4 cột + </html>
    │
    ├── 📁 auth/
    │   └── login.php                     # Trang đăng nhập (BookStore theme)
    │
    ├── 📁 admin/                         # Trang quản trị
    │   └── dashboard.php                 # Dashboard: stats + top books + orders
    │   # Thêm: books.php, categories.php, orders.php, vouchers.php...
    │
    ├── 📁 customer/                      # Trang khách hàng
    │   └── home.php                      # Trang chủ: banner, flash sale, sections
    │   # Thêm: books.php, book_detail.php, cart.php, checkout.php...
    │
    ├── forbidden.php                     # 403 — Không có quyền
    └── notFound.php                      # 404 — Không tìm thấy
```

---

## 📦 Quy trình Git

```bash
git checkout -b feature/ten-tinh-nang
git add .
git commit -m "feat: mô tả ngắn gọn"
git push origin feature/ten-tinh-nang
# → Tạo Pull Request trên GitHub
```

> ❌ KHÔNG commit: `config/env.php` | `uploads/*`
