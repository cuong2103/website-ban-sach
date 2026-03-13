-- ============================================================
-- Database: quan_li_agile
-- Mô tả: Base database - Website Bán Sách
-- Ngày tạo: 2026-03-11
-- ============================================================

CREATE DATABASE IF NOT EXISTS `quan_li_agile`
  DEFAULT CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE `quan_li_agile`;

-- ============================================================
-- Bảng: users
-- Vai trò: admin (quản trị), customer (khách hàng)
-- ============================================================
CREATE TABLE IF NOT EXISTS `users` (
  `id`         INT(11)      NOT NULL AUTO_INCREMENT,
  `fullname`   VARCHAR(100) NOT NULL,
  `email`      VARCHAR(100) NOT NULL UNIQUE,
  `password`   VARCHAR(255) NOT NULL,
  `roles`      ENUM('admin','customer') NOT NULL DEFAULT 'customer',
  `status`     TINYINT(1)   NOT NULL DEFAULT 1  COMMENT '1=active, 0=banned',
  `avatar`     VARCHAR(255)           DEFAULT NULL,
  `phone`      VARCHAR(20)            DEFAULT NULL,
  `address`    VARCHAR(255)           DEFAULT NULL,
  `created_at` DATETIME               DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME               DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- Dữ liệu mẫu: tài khoản Admin
-- Mật khẩu mẫu: admin123
-- ============================================================
INSERT INTO `users` (`fullname`, `email`, `password`, `roles`, `status`) VALUES
('Quản Trị Viên', 'admin@example.com', '$2y$10$TKh8H1.PfunAV7GGiqiL1.qiOqM4bN5kHiDWaWc/R6UbHbNgI.7lK', 'admin', 1);

-- Để tạo hash mật khẩu mới, chạy lệnh PHP:
-- echo password_hash('your_password', PASSWORD_DEFAULT);

-- ============================================================
-- THÊM CÁC BẢNG MỚI Ở ĐÂY
