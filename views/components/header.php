<?php
$currentUser = $_SESSION['currentUser'] ?? null;
$fullname = $currentUser['fullname'] ?? 'Admin';
$role     = ($currentUser['roles'] ?? '') == 1 ? 'Quản trị viên' : 'Nhân viên';
$avatar   = strtoupper(mb_substr($fullname, 0, 1));
$userId   = $currentUser['id'] ?? null;
?>

<!DOCTYPE html>
<html lang="vi" class="h-full">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>BookAdmin – Quản trị hệ thống</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.min.js"></script>
  <script src="<?= BASE_URL ?>assets/common.js"></script>
</head>

<body class="h-full bg-[#F5F5F5] flex">

  <!-- Sidebar được include riêng trong mỗi view -->

  <!-- Main wrapper (ml-64 = độ rộng sidebar) -->
  <div class="flex-1 ml-64 flex flex-col min-h-screen">

    <!-- Top Header -->
    <header class="bg-white shadow-sm border-b border-gray-200 sticky top-0 z-40">
      <div id="alert-message"
        class="fixed top-5 right-5 bg-red-500 text-white px-4 py-2 rounded shadow-lg opacity-0 transition-opacity duration-500 z-50">
      </div>
      <div class="px-6 py-5 flex items-center justify-between">
        <div class="flex-1 max-w-2xl">
          <div class="relative">
            <input type="text" placeholder="Tìm kiếm đơn hàng, khách hàng..."
              class="w-full pl-10 pr-4 py-2.5 bg-gray-50 border border-gray-200 rounded-lg focus:bg-white focus:outline-none focus:border-green-500 focus:ring-2 focus:ring-green-100 transition-all text-sm">
            <i data-lucide="search" class="absolute left-3 top-[11px] w-4 h-4 text-gray-400"></i>
          </div>
        </div>
        
        <div class="flex items-center space-x-4 pl-4">
          <!-- Bell -->
          <button class="relative p-2 text-gray-600 hover:bg-gray-100 rounded-full transition-colors">
            <i data-lucide="bell" class="w-5 h-5 text-gray-600"></i>
            <span class="absolute top-1 right-1 bg-red-500 text-white text-[10px] font-bold rounded-full min-w-[16px] h-[16px] flex items-center justify-center px-1">2</span>
          </button>

          <!-- User info -->
          <div class="flex items-center gap-3 pl-4 border-l border-gray-200">
            <a class="flex items-center gap-3" href="<?= BASE_URL ?>?act=admin-profile">
              <div class="w-9 h-9 bg-[#4CAF50] rounded-xl flex items-center justify-center text-white text-sm font-bold shadow-sm">
                <?= $avatar ?>
              </div>
              <div class="hidden sm:block">
                <p class="text-sm font-bold text-gray-900"><?= htmlspecialchars($fullname) ?></p>
                <p class="text-xs text-gray-500"><?= $role ?></p>
              </div>
            </a>
          </div>
        </div>
      </div>
    </header>
