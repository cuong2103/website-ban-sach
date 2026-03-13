<?php
class AuthController
{
  private $userModel;

  public function __construct()
  {
    $this->userModel = new UserModel();
  }

  // Hiển thị form đăng nhập
  public function formLogin()
  {
    require_once './views/auth/login.php';
  }

  // Xử lý đăng nhập
  public function login()
  {
    $email    = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    // Lưu lại email cũ nếu lỗi
    $_SESSION['old']['email'] = $email;

    $errors = validate(['email' => $email, 'password' => $password], [
      'email'    => 'required|email',
      'password' => 'required',
    ]);

    if (!empty($errors)) {
      Message::set('error', 'Vui lòng điền đầy đủ thông tin hợp lệ.');
      redirect('login');
    }

    $user = $this->userModel->findByEmail($email);

    if (!$user || !password_verify($password, $user['password'])) {
      Message::set('error', 'Email hoặc mật khẩu không đúng.');
      redirect('login');
    }

    if ($user['status'] != 1) {
      Message::set('error', 'Tài khoản đã bị khóa. Vui lòng liên hệ quản trị viên.');
      redirect('login');
    }

    // Lưu user vào session
    $_SESSION['currentUser'] = [
      'id'       => $user['id'],
      'fullname' => $user['fullname'],
      'email'    => $user['email'],
      'roles'    => $user['roles'],
      'status'   => $user['status'],
      'avatar'   => $user['avatar'] ?? null,
    ];

    // Xóa old session
    unset($_SESSION['old']);

    Message::set('success', 'Đăng nhập thành công! Chào mừng ' . $user['fullname']);
    redirect('/');
  }

  // Đăng xuất
  public function logout()
  {
    session_destroy();
    header("Location: " . BASE_URL . "?act=login");
    exit();
  }
}
