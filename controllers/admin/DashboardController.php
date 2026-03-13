<?php
class DashboardController
{
  public function Dashboard()
  {
    requireAdmin();
    $currentUser = $_SESSION['currentUser'];
    $fullname = $currentUser['fullname'] ?? 'Admin';

    require_once './views/admin/dashboard.php';
  }
}
