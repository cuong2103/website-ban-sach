<?php
class DashboardController
{
  public function Dashboard()
  {
    // TODO: Bật lại khi làm module auth
    // requireAdmin();
    $currentUser = $_SESSION['currentUser'] ?? [];
    $fullname = $currentUser['fullname'] ?? 'Admin';

    require_once './views/admin/dashboard.php';
  }
}
