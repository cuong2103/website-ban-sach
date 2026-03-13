<?php
session_start();
$act = $_GET['act'] ?? '/';

// TODO: Bật lại khi làm module đăng nhập/đăng ký
// if (!in_array($act, ['login', 'check-login', 'logout', 'home', 'register'])) {
//   checkLogin();
// }

match ($act) {
  // ─── Home mặc định → trang chủ khách hàng ─────────────────────────
  '/' => (new HomeController())->home(),

  // ─── Auth (tạm thời vô hiệu – làm sau cùng) ───────────────────────
  // 'login'       => (new AuthController())->formLogin(),
  // 'check-login' => (new AuthController())->login(),
  // 'logout'      => (new AuthController())->logout(),
  // 'register'    => require_once './views/auth/register.php',

  // ─── Customer ─────────────────────────────────────────────────────
  'home' => (new HomeController())->home(),

  // ─── Admin ────────────────────────────────────────────────────────
  'admin-dashboard' => (new DashboardController())->Dashboard(),

  // ================================
  // THÊM ROUTES MỚI Ở ĐÂY
  // ================================

  // ─── Error pages ──────────────────────────────────────────────────
  '403'   => require_once './views/forbidden.php',
  default => require_once './views/notFound.php',
};
