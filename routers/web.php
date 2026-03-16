<?php
session_start();
$act = $_GET['act'] ?? '/';

// Whitelist các route không cần login
if (!in_array($act, ['/', 'login', 'check-login', 'logout', 'home', 'register', 'check-register', 'books'])) {
  checkLogin();
}

match ($act) {
  // ─── Home mặc định → trang chủ khách hàng ─────────────────────────
  '/' => (new HomeController())->home(),

  // ─── Auth ───────────────────────────────────────────────────────
  'login' => (new AuthController())->formLogin(),
  'check-login' => (new AuthController())->login(),
  'register' => (new AuthController())->formRegister(),
  'check-register' => (new AuthController())->register(),
  'logout' => (new AuthController())->logout(),

  // ─── Customer ─────────────────────────────────────────────────────
  'home' => (new HomeController())->home(),
  'books' => (new BookController())->list(),
  'cart' => (new CartController())->index(),
  'cart-add' => (new CartController())->add(),
  'cart-update' => (new CartController())->update(),
  'cart-remove' => (new CartController())->remove(),
  'cart-apply-voucher' => (new CartController())->applyVoucher(),
  'cart-clear-voucher' => (new CartController())->clearVoucher(),
  'checkout' => (new CartController())->checkout(),
  'checkout-place' => (new CartController())->placeOrder(),
  'checkout-success' => (new CartController())->success(),
  'orders' => (new CartController())->history(),
  'order-detail' => (new CartController())->orderDetail(),

  // ─── Admin ────────────────────────────────────────────────────────
  'admin-dashboard' => (new DashboardController())->Dashboard(),

  // ─── Admin: Category Management ─────────────────────────────────────
  'admin-categories' => (new CategoryController())->list(),
  'admin-categories-create' => (new CategoryController())->formCreate(),
  'admin-categories-store' => (new CategoryController())->create(),
  'admin-categories-edit' => (new CategoryController())->formEdit(),
  'admin-categories-update' => (new CategoryController())->update(),
  'admin-categories-delete' => (new CategoryController())->delete(),

  // ─── Admin: Flash Sale Management ───────────────────────────────────
  'admin-flash-sales' => (new FlashSaleController())->list(),
  'admin-flash-sales-create' => (new FlashSaleController())->formCreate(),
  'admin-flash-sales-store' => (new FlashSaleController())->create(),
  'admin-flash-sales-edit' => (new FlashSaleController())->formEdit(),
  'admin-flash-sales-update' => (new FlashSaleController())->update(),
  'admin-flash-sales-delete' => (new FlashSaleController())->delete(),
  'admin-flash-sales-add-item' => (new FlashSaleController())->addItem(),
  'admin-flash-sales-remove-item' => (new FlashSaleController())->removeItem(),

  // ─── Admin: Inventory Management (View Only) ───────────────────────────────────
  'admin-inventories' => (new InventoryController())->list(),

  // ─── Admin: Order Management ────────────────────────────────────────────────
  'admin-orders' => (new AdminOrderController())->list(),
  'admin-order-detail' => (new AdminOrderController())->detail(),
  'admin-order-update-status' => (new AdminOrderController())->updateStatus(),

  // ================================
  // THÊM ROUTES MỚI Ở ĐÂY
  // ================================

  // ─── Error pages ──────────────────────────────────────────────────
  '403' => require_once './views/forbidden.php',
  default => require_once './views/notFound.php',
};