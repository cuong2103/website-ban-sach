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

  <!-- Main wrapper (ml-56 = độ rộng sidebar) -->
  <div class="flex-1 ml-56 flex flex-col min-h-screen">

    <!-- Top Header -->
    <header class="bg-white border-b border-gray-100 h-14 flex items-center px-4 gap-4 shrink-0 sticky top-0 z-40">

      <!-- Alert inline -->
      <div id="alert-message"
        class="fixed top-5 right-5 bg-red-500 text-white px-4 py-2 rounded shadow-lg opacity-0 transition-opacity duration-500 z-50">
      </div>

      <div class="flex-1"></div>

      <!-- Bell -->
      <button class="relative p-1.5 hover:bg-gray-100 rounded-lg transition-colors">
        <i data-lucide="bell" class="w-[18px] h-[18px] text-gray-500"></i>
        <span class="absolute top-0.5 right-0.5 w-2 h-2 bg-red-500 rounded-full"></span>
      </button>

      <!-- User info -->
      <div class="flex items-center gap-2 pl-3 border-l border-gray-100">
        <div class="w-7 h-7 bg-[#4CAF50] rounded-full flex items-center justify-center text-white text-xs font-bold">
          <?= $avatar ?>
        </div>
        <div class="hidden sm:block">
          <p class="text-xs font-medium text-[#333]"><?= htmlspecialchars($fullname) ?></p>
          <p class="text-xs text-gray-400"><?= $role ?></p>
        </div>
      </div>
    </header>
