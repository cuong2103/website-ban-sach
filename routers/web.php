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
  'login'         => (new AuthController())->formLogin(),
  'check-login'   => (new AuthController())->login(),
  'register'      => (new AuthController())->formRegister(),
  'check-register' => (new AuthController())->register(),
  'logout'        => (new AuthController())->logout(),

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

  // ─── Admin ────────────────────────────────────────────────────────
  'admin-dashboard' => (new DashboardController())->Dashboard(),

  // ================================
  // THÊM ROUTES MỚI Ở ĐÂY
  // ================================

  // ─── Error pages ──────────────────────────────────────────────────
  '403'   => require_once './views/forbidden.php',
  default => require_once './views/notFound.php',
};