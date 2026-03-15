<?php
class UserModel
{
  private $conn;

  public function __construct()
  {
    $this->conn = connectDB();
  }

  public function findByEmail($email)
  {
    $stmt = $this->conn->prepare("
      SELECT 
        user_id as id, 
        role_id as roles, 
        full_name as fullname, 
        email, 
        password, 
        phone, 
        avatar, 
        status, 
        created_at
      FROM users 
      WHERE email = :email 
      LIMIT 1
    ");
    $stmt->execute(['email' => $email]);
    return $stmt->fetch();
  }

  public function findById($id)
  {
    $stmt = $this->conn->prepare("
      SELECT 
        user_id as id, 
        role_id as roles, 
        full_name as fullname, 
        email, 
        password, 
        phone, 
        avatar, 
        status, 
        created_at
      FROM users 
      WHERE user_id = :id 
      LIMIT 1
    ");
    $stmt->execute(['id' => $id]);
    return $stmt->fetch();
  }

  public function getAll()
  {
    $stmt = $this->conn->prepare("
      SELECT 
        user_id as id, 
        role_id as roles, 
        full_name as fullname, 
        email, 
        password, 
        phone, 
        avatar, 
        status, 
        created_at
      FROM users 
      ORDER BY created_at DESC
    ");
    $stmt->execute();
    return $stmt->fetchAll();
  }

  public function create($data)
  {
    $roleId = 2;
    if ($data['roles'] === 'admin') {
      $roleId = 1;
    }

    try {
      $stmt = $this->conn->prepare("
        INSERT INTO users (role_id, full_name, email, password, phone, status, avatar)
        VALUES (:role_id, :fullname, :email, :password, :phone, :status, :avatar)
      ");
      $stmt->execute([
        'role_id'  => $roleId,
        'fullname' => $data['fullname'],
        'email'    => $data['email'],
        'password' => $data['password'],
        'phone'    => $data['phone'] ?? null,
        'status'   => $data['status'] ?? 1,
        'avatar'   => $data['avatar'] ?? null,
      ]);
      return $this->conn->lastInsertId();
    } catch (PDOException $e) {
      return false;
    }
  }

  public function update($id, $data)
  {
    $roleId = $data['roles'];
    if ($data['roles'] === 'admin') {
      $roleId = 1;
    } elseif ($data['roles'] === 'customer') {
      $roleId = 2;
    }

    $stmt = $this->conn->prepare("
      UPDATE users
      SET full_name = :fullname,
          email     = :email,
          role_id   = :role_id,
          status    = :status,
          avatar    = :avatar
      WHERE user_id = :id
    ");
    return $stmt->execute([
      'fullname' => $data['fullname'],
      'email'    => $data['email'],
      'role_id'  => $roleId,
      'status'   => $data['status'],
      'avatar'   => $data['avatar'] ?? null,
      'id'       => $id,
    ]);
  }

  public function changePassword($id, $newPassword)
  {
    $stmt = $this->conn->prepare("UPDATE users SET password = :password WHERE user_id = :id");
    return $stmt->execute([
      'password' => password_hash($newPassword, PASSWORD_DEFAULT),
      'id'       => $id,
    ]);
  }

  public function delete($id)
  {
    $stmt = $this->conn->prepare("DELETE FROM users WHERE user_id = :id");
    return $stmt->execute(['id' => $id]);
  }
}