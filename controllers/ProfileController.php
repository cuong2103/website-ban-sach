<?php
class ProfileController
{
  private $userModel;

  public function __construct()
  {
    $this->userModel = new UserModel();
  }

  // Xem profile
  public function GetById()
  {
    $userId = $_SESSION['currentUser']['id'];
    $user   = $this->userModel->findById($userId);

    if (!$user) {
      Message::set('error', 'Không tìm thấy thông tin người dùng.');
      redirect('/');
    }

    require_once './views/shared/profile.php';
  }

  // Form chỉnh sửa profile
  public function edit()
  {
    $userId = $_SESSION['currentUser']['id'];
    $user   = $this->userModel->findById($userId);

    if (!$user) {
      redirect('/');
    }

    require_once './views/shared/profile_edit.php';
  }

  // Lưu cập nhật profile
  public function update()
  {
    $userId  = $_SESSION['currentUser']['id'];
    $fullname = trim($_POST['fullname'] ?? '');
    $email    = trim($_POST['email'] ?? '');

    $errors = validate($_POST, [
      'fullname' => 'required|max:100',
      'email'    => 'required|email',
    ]);

    if (!empty($errors)) {
      Message::set('error', 'Vui lòng kiểm tra lại thông tin.');
      redirect('profile-edit');
    }

    // Xử lý avatar
    $user = $this->userModel->findById($userId);
    $avatar = $user['avatar'];

    if (!empty($_FILES['avatar']['name'])) {
      if ($avatar) deleteFile($avatar);
      $avatar = uploadFile($_FILES['avatar'], 'uploads/avatars/');
    }

    $this->userModel->update($userId, [
      'fullname' => $fullname,
      'email'    => $email,
      'roles'    => $user['roles'],
      'status'   => $user['status'],
      'avatar'   => $avatar,
    ]);

    // Cập nhật session
    $_SESSION['currentUser']['fullname'] = $fullname;
    $_SESSION['currentUser']['email']    = $email;
    $_SESSION['currentUser']['avatar']   = $avatar;

    Message::set('success', 'Cập nhật thông tin thành công!');
    redirect('profile');
  }

  // Đổi mật khẩu
  public function changePassword()
  {
    $userId      = $_SESSION['currentUser']['id'];
    $oldPassword = $_POST['old_password'] ?? '';
    $newPassword = $_POST['new_password'] ?? '';

    $user = $this->userModel->findById($userId);

    if (!password_verify($oldPassword, $user['password'])) {
      Message::set('error', 'Mật khẩu cũ không đúng.');
      redirect('profile');
    }

    if (strlen($newPassword) < 6) {
      Message::set('error', 'Mật khẩu mới phải có ít nhất 6 ký tự.');
      redirect('profile');
    }

    $this->userModel->changePassword($userId, $newPassword);
    Message::set('success', 'Đổi mật khẩu thành công!');
    redirect('profile');
  }
}
