-- ============================================================
-- BOOKSTORE MANAGEMENT SYSTEM - DATABASE SCHEMA
-- Project  : Quan_li_Agile (Bookstore)
-- Engine   : MySQL / InnoDB
-- Charset  : utf8mb4
-- Created  : 2026-03-13
-- ============================================================

SET NAMES utf8mb4;
SET
  FOREIGN_KEY_CHECKS = 0;

-- ============================================================
-- DROP TABLES (in reverse dependency order for clean rebuild)
-- ============================================================
DROP TABLE IF EXISTS reviews;
DROP TABLE IF EXISTS voucher_usages;
DROP TABLE IF EXISTS flash_sale_items;
DROP TABLE IF EXISTS flash_sales;
DROP TABLE IF EXISTS promotions;
DROP TABLE IF EXISTS order_items;
DROP TABLE IF EXISTS orders;
DROP TABLE IF EXISTS cart_items;
DROP TABLE IF EXISTS carts;
DROP TABLE IF EXISTS inventories;
DROP TABLE IF EXISTS book_images;
DROP TABLE IF EXISTS books;
DROP TABLE IF EXISTS categories;
DROP TABLE IF EXISTS vouchers;
DROP TABLE IF EXISTS payment_methods;
DROP TABLE IF EXISTS order_status;
DROP TABLE IF EXISTS users;
DROP TABLE IF EXISTS roles;

-- ============================================================
-- 1. ROLES - Phân quyền
-- ============================================================
CREATE TABLE roles (
  role_id   INT          NOT NULL AUTO_INCREMENT,
  role_name VARCHAR(50)  NOT NULL UNIQUE,
  PRIMARY KEY (role_id)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4
  COMMENT = 'Bảng phân quyền người dùng';

INSERT INTO roles (role_id, role_name) VALUES
  (1, 'admin'),
  (2, 'customer');

-- ============================================================
-- 2. USERS - Người dùng
-- ============================================================
CREATE TABLE users (
  user_id    INT          NOT NULL AUTO_INCREMENT,
  role_id    INT          NOT NULL DEFAULT 2,      -- mặc định là customer
  full_name  VARCHAR(150) NOT NULL,
  email      VARCHAR(150) NOT NULL UNIQUE,
  password   VARCHAR(255) NOT NULL,
  phone      VARCHAR(20)           DEFAULT NULL,
  address    TEXT                  DEFAULT NULL,
  avatar     VARCHAR(255)          DEFAULT NULL,
  status     TINYINT      NOT NULL DEFAULT 1       COMMENT '1=active, 0=inactive',
  created_at DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (user_id),
  CONSTRAINT fk_users_role FOREIGN KEY (role_id) REFERENCES roles (role_id)
    ON UPDATE CASCADE ON DELETE RESTRICT
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4
  COMMENT = 'Bảng người dùng';

-- ============================================================
-- 3. CATEGORIES - Danh mục sách
-- ============================================================
CREATE TABLE categories (
  category_id INT          NOT NULL AUTO_INCREMENT,
  name        VARCHAR(150) NOT NULL,
  slug        VARCHAR(150) NOT NULL UNIQUE,
  description TEXT                  DEFAULT NULL,
  status      TINYINT      NOT NULL DEFAULT 1 COMMENT '1=active, 0=inactive',
  created_at  DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at  DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (category_id)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4
  COMMENT = 'Bảng danh mục sách';

-- ============================================================
-- 4. BOOKS - Sản phẩm sách
-- ============================================================
CREATE TABLE books (
  book_id     INT            NOT NULL AUTO_INCREMENT,
  category_id INT            NOT NULL,
  title       VARCHAR(255)   NOT NULL,
  author      VARCHAR(150)   NOT NULL,
  publisher   VARCHAR(150)            DEFAULT NULL,
  price       DECIMAL(10, 2) NOT NULL CHECK (price >= 0),
  sale_price  DECIMAL(10, 2)          DEFAULT NULL CHECK (sale_price >= 0),
  description TEXT                    DEFAULT NULL,
  thumbnail   VARCHAR(255)            DEFAULT NULL,
  weight      VARCHAR(50)             DEFAULT NULL,
  dimensions  VARCHAR(50)             DEFAULT NULL,
  cover_type  ENUM('Bìa mềm', 'Bìa cứng') DEFAULT 'Bìa mềm',
  stock       INT            NOT NULL DEFAULT 0 CHECK (stock >= 0),
  status      TINYINT        NOT NULL DEFAULT 1 COMMENT '1=active, 0=inactive',
  is_featured TINYINT        NOT NULL DEFAULT 0 COMMENT '1=featured, 0=normal',
  is_bestseller TINYINT      NOT NULL DEFAULT 0 COMMENT '1=bestseller, 0=normal',
  created_at  DATETIME       NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at  DATETIME       NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (book_id),
  CONSTRAINT fk_books_category FOREIGN KEY (category_id) REFERENCES categories (category_id)
    ON UPDATE CASCADE ON DELETE RESTRICT,
  INDEX idx_books_category (category_id),
  INDEX idx_books_status   (status)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4
  COMMENT = 'Bảng sản phẩm sách';

-- ============================================================
-- 5. BOOK_IMAGES - Ảnh sách
-- ============================================================
CREATE TABLE book_images (
  image_id   INT          NOT NULL AUTO_INCREMENT,
  book_id    INT          NOT NULL,
  image_url  VARCHAR(255) NOT NULL,
  created_at DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (image_id),
  CONSTRAINT fk_book_images_book FOREIGN KEY (book_id) REFERENCES books (book_id)
    ON UPDATE CASCADE ON DELETE CASCADE,
  INDEX idx_book_images_book (book_id)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4
  COMMENT = 'Bảng ảnh chi tiết sách';

-- ============================================================
-- 6. INVENTORIES - Quản lý kho
-- ============================================================
CREATE TABLE inventories (
  inventory_id      INT      NOT NULL AUTO_INCREMENT,
  book_id           INT      NOT NULL UNIQUE,         -- 1-1 với books
  stock_quantity    INT      NOT NULL DEFAULT 0 CHECK (stock_quantity >= 0),
  imported_quantity INT      NOT NULL DEFAULT 0 CHECK (imported_quantity >= 0),
  updated_at        DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (inventory_id),
  CONSTRAINT fk_inventories_book FOREIGN KEY (book_id) REFERENCES books (book_id)
    ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4
  COMMENT = 'Bảng quản lý kho hàng (1-1 với books)';

-- ============================================================
-- 7. CARTS - Giỏ hàng
-- ============================================================
CREATE TABLE carts (
  cart_id    INT      NOT NULL AUTO_INCREMENT,
  user_id    INT      NOT NULL UNIQUE,               -- 1-1: mỗi user 1 giỏ hàng
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (cart_id),
  CONSTRAINT fk_carts_user FOREIGN KEY (user_id) REFERENCES users (user_id)
    ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4
  COMMENT = 'Bảng giỏ hàng (1-1 với users)';

-- ============================================================
-- 8. CART_ITEMS - Mục trong giỏ hàng
-- ============================================================
CREATE TABLE cart_items (
  cart_item_id INT            NOT NULL AUTO_INCREMENT,
  cart_id      INT            NOT NULL,
  book_id      INT            NOT NULL,
  quantity     INT            NOT NULL DEFAULT 1 CHECK (quantity > 0),
  price        DECIMAL(10, 2) NOT NULL CHECK (price >= 0), -- giá tại thời điểm thêm vào giỏ
  PRIMARY KEY (cart_item_id),
  UNIQUE KEY uq_cart_book (cart_id, book_id),              -- tránh trùng sách trong giỏ
  CONSTRAINT fk_cart_items_cart FOREIGN KEY (cart_id) REFERENCES carts (cart_id)
    ON UPDATE CASCADE ON DELETE CASCADE,
  CONSTRAINT fk_cart_items_book FOREIGN KEY (book_id) REFERENCES books (book_id)
    ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4
  COMMENT = 'Bảng chi tiết giỏ hàng';

-- ============================================================
-- 9. ORDER_STATUS - Trạng thái đơn hàng
-- ============================================================
CREATE TABLE order_status (
  status_id   INT          NOT NULL AUTO_INCREMENT,
  status_name VARCHAR(100) NOT NULL UNIQUE,
  PRIMARY KEY (status_id)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4
  COMMENT = 'Bảng trạng thái đơn hàng';

INSERT INTO order_status (status_id, status_name) VALUES
  (1, 'Pending'),
  (2, 'Confirmed'),
  (3, 'Shipping'),
  (4, 'Completed'),
  (5, 'Cancelled');

-- ============================================================
-- 10. PAYMENT_METHODS - Phương thức thanh toán
-- ============================================================
CREATE TABLE payment_methods (
  payment_method_id INT          NOT NULL AUTO_INCREMENT,
  name              VARCHAR(100) NOT NULL UNIQUE,
  PRIMARY KEY (payment_method_id)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4
  COMMENT = 'Bảng phương thức thanh toán';

INSERT INTO payment_methods (payment_method_id, name) VALUES
  (1, 'COD');
  -- Lưu ý: đã bỏ thanh toán Online theo yêu cầu

-- ============================================================
-- 11. VOUCHERS - Mã giảm giá
-- ============================================================
CREATE TABLE vouchers (
  voucher_id      INT                     NOT NULL AUTO_INCREMENT,
  code            VARCHAR(50)             NOT NULL UNIQUE,
  discount_type   ENUM('percent','fixed') NOT NULL,
  discount_value  DECIMAL(10, 2)          NOT NULL CHECK (discount_value > 0),
  max_discount    DECIMAL(10, 2)                   DEFAULT NULL CHECK (max_discount >= 0),
  min_order_value DECIMAL(10, 2)          NOT NULL DEFAULT 0 CHECK (min_order_value >= 0),
  start_date      DATETIME                NOT NULL,
  end_date        DATETIME                NOT NULL,
  usage_limit     INT                              DEFAULT NULL CHECK (usage_limit > 0),
  status          TINYINT                 NOT NULL DEFAULT 1 COMMENT '1=active, 0=inactive',
  created_at      DATETIME                NOT NULL DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT chk_voucher_dates CHECK (end_date > start_date),
  PRIMARY KEY (voucher_id)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4
  COMMENT = 'Bảng mã giảm giá';

-- ============================================================
-- 12. ORDERS - Đơn hàng
-- ============================================================
CREATE TABLE orders (
  order_id          INT            NOT NULL AUTO_INCREMENT,
  user_id           INT            NOT NULL,
  order_code        VARCHAR(50)    NOT NULL UNIQUE,
  total_amount      DECIMAL(10, 2) NOT NULL CHECK (total_amount >= 0),
  voucher_id        INT                     DEFAULT NULL,
  discount_amount   DECIMAL(10, 2) NOT NULL DEFAULT 0 CHECK (discount_amount >= 0),
  payment_method_id INT            NOT NULL,
  status_id         INT            NOT NULL DEFAULT 1,
  shipping_address  TEXT           NOT NULL,
  phone             VARCHAR(20)    NOT NULL,
  note              TEXT                    DEFAULT NULL,
  created_at        DATETIME       NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at        DATETIME       NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (order_id),
  CONSTRAINT fk_orders_user           FOREIGN KEY (user_id)           REFERENCES users          (user_id)           ON UPDATE CASCADE ON DELETE RESTRICT,
  CONSTRAINT fk_orders_voucher        FOREIGN KEY (voucher_id)        REFERENCES vouchers       (voucher_id)        ON UPDATE CASCADE ON DELETE SET NULL,
  CONSTRAINT fk_orders_payment_method FOREIGN KEY (payment_method_id) REFERENCES payment_methods(payment_method_id) ON UPDATE CASCADE ON DELETE RESTRICT,
  CONSTRAINT fk_orders_status         FOREIGN KEY (status_id)         REFERENCES order_status   (status_id)         ON UPDATE CASCADE ON DELETE RESTRICT,
  INDEX idx_orders_user       (user_id),
  INDEX idx_orders_status     (status_id),
  INDEX idx_orders_created_at (created_at)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4
  COMMENT = 'Bảng đơn hàng';

-- ============================================================
-- 13. ORDER_ITEMS - Chi tiết đơn hàng
-- ============================================================
CREATE TABLE order_items (
  order_item_id INT            NOT NULL AUTO_INCREMENT,
  order_id      INT            NOT NULL,
  book_id       INT            NOT NULL,
  quantity      INT            NOT NULL CHECK (quantity > 0),
  price         DECIMAL(10, 2) NOT NULL CHECK (price >= 0),  -- giá tại thời điểm đặt
  subtotal      DECIMAL(10, 2) NOT NULL CHECK (subtotal >= 0),
  PRIMARY KEY (order_item_id),
  CONSTRAINT fk_order_items_order FOREIGN KEY (order_id) REFERENCES orders (order_id)
    ON UPDATE CASCADE ON DELETE CASCADE,
  CONSTRAINT fk_order_items_book  FOREIGN KEY (book_id)  REFERENCES books  (book_id)
    ON UPDATE CASCADE ON DELETE RESTRICT,
  INDEX idx_order_items_order (order_id),
  INDEX idx_order_items_book  (book_id)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4
  COMMENT = 'Bảng chi tiết đơn hàng';

-- ============================================================
-- 14. VOUCHER_USAGES - Lịch sử dùng voucher
-- ============================================================
CREATE TABLE voucher_usages (
  usage_id   INT      NOT NULL AUTO_INCREMENT,
  voucher_id INT      NOT NULL,
  user_id    INT      NOT NULL,
  order_id   INT      NOT NULL UNIQUE,               -- 1 đơn chỉ dùng 1 voucher
  used_at    DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (usage_id),
  CONSTRAINT fk_voucher_usages_voucher FOREIGN KEY (voucher_id) REFERENCES vouchers (voucher_id)
    ON UPDATE CASCADE ON DELETE CASCADE,
  CONSTRAINT fk_voucher_usages_user    FOREIGN KEY (user_id)    REFERENCES users    (user_id)
    ON UPDATE CASCADE ON DELETE CASCADE,
  CONSTRAINT fk_voucher_usages_order   FOREIGN KEY (order_id)   REFERENCES orders   (order_id)
    ON UPDATE CASCADE ON DELETE CASCADE,
  INDEX idx_voucher_usages_voucher (voucher_id),
  INDEX idx_voucher_usages_user    (user_id)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4
  COMMENT = 'Bảng lịch sử sử dụng voucher';

-- ============================================================
-- 15. PROMOTIONS - Chương trình khuyến mãi
-- ============================================================
CREATE TABLE promotions (
  promotion_id     INT          NOT NULL AUTO_INCREMENT,
  name             VARCHAR(150) NOT NULL,
  discount_percent INT          NOT NULL CHECK (discount_percent BETWEEN 1 AND 100),
  start_date       DATETIME     NOT NULL,
  end_date         DATETIME     NOT NULL,
  status           TINYINT      NOT NULL DEFAULT 1 COMMENT '1=active, 0=inactive',
  CONSTRAINT chk_promotion_dates CHECK (end_date > start_date),
  PRIMARY KEY (promotion_id)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4
  COMMENT = 'Bảng chương trình khuyến mãi';

-- ============================================================
-- 16. FLASH_SALES - Flash sale
-- ============================================================
CREATE TABLE flash_sales (
  flash_sale_id INT          NOT NULL AUTO_INCREMENT,
  name          VARCHAR(150) NOT NULL,
  start_time    DATETIME     NOT NULL,
  end_time      DATETIME     NOT NULL,
  status        TINYINT      NOT NULL DEFAULT 1 COMMENT '1=active, 0=inactive',
  CONSTRAINT chk_flash_sale_times CHECK (end_time > start_time),
  PRIMARY KEY (flash_sale_id)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4
  COMMENT = 'Bảng flash sale';

-- ============================================================
-- 17. FLASH_SALE_ITEMS - Sách trong flash sale
-- ============================================================
CREATE TABLE flash_sale_items (
  id               INT            NOT NULL AUTO_INCREMENT,
  flash_sale_id    INT            NOT NULL,
  book_id          INT            NOT NULL,
  discount_percent INT            NOT NULL CHECK (discount_percent BETWEEN 1 AND 100),
  sale_price       DECIMAL(10, 2) NOT NULL CHECK (sale_price >= 0),
  stock_limit      INT            NOT NULL DEFAULT 0 CHECK (stock_limit >= 0),
  PRIMARY KEY (id),
  UNIQUE KEY uq_flash_sale_book (flash_sale_id, book_id),   -- tránh trùng sách trong cùng flash sale
  CONSTRAINT fk_flash_sale_items_sale FOREIGN KEY (flash_sale_id) REFERENCES flash_sales (flash_sale_id)
    ON UPDATE CASCADE ON DELETE CASCADE,
  CONSTRAINT fk_flash_sale_items_book FOREIGN KEY (book_id)       REFERENCES books       (book_id)
    ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4
  COMMENT = 'Bảng sách trong flash sale';

-- ============================================================
-- 18. REVIEWS - Đánh giá sách
-- ============================================================
CREATE TABLE reviews (
  review_id  INT      NOT NULL AUTO_INCREMENT,
  book_id    INT      NOT NULL,
  user_id    INT      NOT NULL,
  rating     INT      NOT NULL CHECK (rating BETWEEN 1 AND 5),
  comment    TEXT              DEFAULT NULL,
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (review_id),
  UNIQUE KEY uq_review_user_book (user_id, book_id),         -- mỗi user chỉ review 1 lần / sách
  CONSTRAINT fk_reviews_book FOREIGN KEY (book_id) REFERENCES books (book_id)
    ON UPDATE CASCADE ON DELETE CASCADE,
  CONSTRAINT fk_reviews_user FOREIGN KEY (user_id) REFERENCES users (user_id)
    ON UPDATE CASCADE ON DELETE CASCADE,
  INDEX idx_reviews_book (book_id),
  INDEX idx_reviews_user (user_id)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4
  COMMENT = 'Bảng đánh giá sách';

-- ============================================================
-- RE-ENABLE FOREIGN KEY CHECKS
-- ============================================================
SET FOREIGN_KEY_CHECKS = 1;

-- ============================================================
-- USEFUL VIEWS (tùy chọn)
-- ============================================================

-- View: sách kèm tồn kho
CREATE OR REPLACE VIEW v_books_with_stock AS
SELECT
  b.book_id,
  b.title,
  b.author,
  b.price,
  b.sale_price,
  b.status        AS book_status,
  c.name          AS category_name,
  i.stock_quantity,
  i.imported_quantity
FROM books b
LEFT JOIN categories  c ON b.category_id  = c.category_id
LEFT JOIN inventories i ON b.book_id      = i.book_id;

-- View: đơn hàng kèm thông tin trạng thái và thanh toán
CREATE OR REPLACE VIEW v_orders_detail AS
SELECT
  o.order_id,
  o.order_code,
  u.full_name      AS customer_name,
  u.email          AS customer_email,
  o.total_amount,
  o.discount_amount,
  (o.total_amount - o.discount_amount) AS final_amount,
  pm.name          AS payment_method,
  os.status_name   AS order_status,
  v.code           AS voucher_code,
  o.shipping_address,
  o.phone,
  o.note,
  o.created_at
FROM orders o
JOIN users          u  ON o.user_id           = u.user_id
JOIN payment_methods pm ON o.payment_method_id = pm.payment_method_id
JOIN order_status   os ON o.status_id          = os.status_id
LEFT JOIN vouchers  v  ON o.voucher_id         = v.voucher_id;

-- ============================================================
-- END OF SCHEMA
-- ============================================================
