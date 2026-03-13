<?php
class UserModel
{
  private $conn;

  public function __construct()
  {
    $this->conn = connectDB();
  }

  // Lấy user theo email (dùng khi login)
  public function findByEmail($email)
  {
    $stmt = $this->conn->prepare("SELECT * FROM users WHERE email = :email LIMIT 1");
    $stmt->execute(['email' => $email]);
    return $stmt->fetch();
  }

  // Lấy user theo ID
  public function findById($id)
  {
    $stmt = $this->conn->prepare("SELECT * FROM users WHERE id = :id LIMIT 1");
    $stmt->execute(['id' => $id]);
    return $stmt->fetch();
  }

  // Lấy tất cả users
  public function getAll()
  {
    $stmt = $this->conn->prepare("SELECT * FROM users ORDER BY created_at DESC");
    $stmt->execute();
    return $stmt->fetchAll();
  }

  // Tạo user mới
  public function create($data)
  {
    $stmt = $this->conn->prepare("
      INSERT INTO users (fullname, email, password, roles, status, avatar, created_at)
      VALUES (:fullname, :email, :password, :roles, :status, :avatar, NOW())
    ");
    $stmt->execute([
      'fullname' => $data['fullname'],
      'email'    => $data['email'],
      'password' => password_hash($data['password'], PASSWORD_DEFAULT),
      'roles'    => $data['roles'] ?? 'user',
      'status'   => $data['status'] ?? 1,
      'avatar'   => $data['avatar'] ?? null,
    ]);
    return $this->conn->lastInsertId();
  }

  // Cập nhật user
  public function update($id, $data)
  {
    $stmt = $this->conn->prepare("
      UPDATE users
      SET fullname = :fullname,
          email    = :email,
          roles    = :roles,
          status   = :status,
          avatar   = :avatar,
          updated_at = NOW()
      WHERE id = :id
    ");
    return $stmt->execute([
      'fullname' => $data['fullname'],
      'email'    => $data['email'],
      'roles'    => $data['roles'],
      'status'   => $data['status'],
      'avatar'   => $data['avatar'] ?? null,
      'id'       => $id,
    ]);
  }

  // Đổi password
  public function changePassword($id, $newPassword)
  {
    $stmt = $this->conn->prepare("UPDATE users SET password = :password WHERE id = :id");
    return $stmt->execute([
      'password' => password_hash($newPassword, PASSWORD_DEFAULT),
      'id'       => $id,
    ]);
  }

  // Xóa user
  public function delete($id)
  {
    $stmt = $this->conn->prepare("DELETE FROM users WHERE id = :id");
    return $stmt->execute(['id' => $id]);
  }
}
