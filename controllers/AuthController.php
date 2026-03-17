<?php
class AuthController
{
  private $userModel;

  public function __construct()
  {
    $this->userModel = new UserModel();
  }

  public function formLogin()
  {
    require_once './views/auth/login.php';
  }

  public function login()
  {
    $email    = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

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

    $_SESSION['currentUser'] = [
      'id'       => $user['id'],
      'fullname' => $user['fullname'],
      'email'    => $user['email'],
      'roles'    => $user['roles'],
      'status'   => $user['status'],
      'avatar'   => $user['avatar'] ?? null,
    ];

    unset($_SESSION['old']);

    Message::set('success', 'Đăng nhập thành công! Chào mừng ' . $user['fullname']);
    redirect('/');
  }

  public function formRegister()
  {
    require_once './views/auth/register.php';
  }

  public function register()
  {
    $fullname           = trim($_POST['fullname'] ?? '');
    $email              = trim($_POST['email'] ?? '');
    $phone              = trim($_POST['phone'] ?? '');
    $password           = $_POST['password'] ?? '';
    $password_confirm   = $_POST['password_confirm'] ?? '';

    $_SESSION['old']['fullname'] = $fullname;
    $_SESSION['old']['email'] = $email;
    $_SESSION['old']['phone'] = $phone;

    $errors = validate([
      'fullname' => $fullname,
      'email' => $email,
      'phone' => $phone,
      'password' => $password,
      'password_confirm' => $password_confirm
    ], [
      'fullname' => 'required|min:3',
      'email' => 'required|email',
      'phone' => 'required|phone',
      'password' => 'required|min:8',
      'password_confirm' => 'required|min:8',
    ]);

    if (!isset($_POST['terms'])) {
      $errors['terms'][] = 'Bạn phải đồng ý với Điều khoản dịch vụ và Chính sách bảo mật.';
    }

    if (!empty($errors)) {
      $_SESSION['validation_errors'] = $errors;
      Message::set('error', 'Vui lòng điền đầy đủ thông tin hợp lệ.');
      redirect('register');
    }

    if ($password !== $password_confirm) {
      Message::set('error', 'Mật khẩu xác nhận không khớp.');
      redirect('register');
    }

    $existingUser = $this->userModel->findByEmail($email);
    if ($existingUser) {
      Message::set('error', 'Email này đã được đăng ký.');
      redirect('register');
    }

    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

    $newUser = [
      'fullname' => $fullname,
      'email' => $email,
      'phone' => $phone,
      'password' => $hashedPassword,
      'roles' => 'customer',
      'status' => 1,
    ];

    $userId = $this->userModel->create($newUser);

    if ($userId) {
      $user = $this->userModel->findById($userId);
      
      if (!$user) {
        Message::set('error', 'Tài khoản được tạo nhưng không thể lấy thông tin.');
        redirect('register');
      }

      $_SESSION['currentUser'] = [
        'id'       => $user['id'],
        'fullname' => $user['fullname'],
        'email'    => $user['email'],
        'roles'    => $user['roles'],
        'status'   => $user['status'],
        'avatar'   => $user['avatar'] ?? null,
      ];

      unset($_SESSION['old']);

      Message::set('success', 'Đăng ký thành công! Chào mừng ' . $user['fullname']);
      redirect('home');
    } else {
      Message::set('error', 'Có lỗi xảy ra. Vui lòng thử lại.');
      redirect('register');
    }
  }

  public function logout()
  {
    unset($_SESSION['currentUser']);
    unset($_SESSION['cart_voucher']);
    // You can unset other specific session data if needed
    Message::set('success', 'Đã đăng xuất thành công.');
    redirect('login');
  }
}