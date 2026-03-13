<?php
session_start();
$act = $_GET['act'] ?? '/';

if ($act !== 'login'  && $act !== 'check-login' && $act !== 'logout') {
  checkLogin();
}

match ($act) {
  // Dashboard
  '/' => (new DashboardController())->Dashboard(),

  // Auth
  'login'       => (new AuthController())->formLogin(),
  'check-login' => (new AuthController())->login(),
  'logout'      => (new AuthController())->logout(),

  // Profile
  'profile'         => (new ProfileController())->GetById(),
  'change-password' => (new ProfileController())->changePassword(),
  'profile-edit'    => (new ProfileController())->edit(),
  'profile-update'  => (new ProfileController())->update(),

  // ================================
  // THÊM ROUTES MỚI Ở ĐÂY
  // ================================

  // Error pages
  '403'   => require_once './views/forbidden.php',
  default => require_once './views/notFound.php',
};
