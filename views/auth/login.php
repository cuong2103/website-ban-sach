<?php
// Trang login
$error = Message::get('error');
?>
<!DOCTYPE html>
<html lang="vi" class="h-full">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Đăng nhập - Agile Manager</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.min.js"></script>
</head>
<body class="h-full bg-gradient-to-br from-indigo-50 via-white to-indigo-100 flex items-center justify-center">

  <div class="w-full max-w-md">
    <!-- Logo -->
    <div class="text-center mb-8">
      <div class="inline-flex items-center justify-center w-16 h-16 bg-indigo-600 rounded-2xl mb-4 shadow-lg">
        <i data-lucide="layout-dashboard" class="w-8 h-8 text-white"></i>
      </div>
      <h1 class="text-2xl font-bold text-gray-900">Agile Manager</h1>
      <p class="text-sm text-gray-500 mt-1">Hệ thống quản lý nội bộ</p>
    </div>

    <!-- Card -->
    <div class="bg-white rounded-2xl shadow-xl p-8">
      <h2 class="text-xl font-semibold text-gray-800 mb-6">Đăng nhập</h2>

      <?php if ($error): ?>
        <div class="mb-4 px-4 py-3 bg-red-50 border border-red-300 text-red-700 rounded-lg text-sm flex items-center gap-2">
          <i data-lucide="triangle-alert" class="w-4 h-4 flex-shrink-0"></i>
          <?= htmlspecialchars($error) ?>
        </div>
      <?php endif; ?>

      <form action="<?= BASE_URL ?>?act=check-login" method="POST" class="space-y-5">
        <!-- Email -->
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
          <div class="relative">
            <i data-lucide="mail" class="absolute left-3 top-2.5 w-5 h-5 text-gray-400"></i>
            <input type="email" name="email" required
              placeholder="admin@example.com"
              value="<?= old('email') ?>"
              class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 text-sm">
          </div>
        </div>

        <!-- Password -->
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Mật khẩu</label>
          <div class="relative">
            <i data-lucide="lock" class="absolute left-3 top-2.5 w-5 h-5 text-gray-400"></i>
            <input type="password" name="password" required
              placeholder="••••••••"
              class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 text-sm">
          </div>
        </div>

        <button type="submit"
          class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2.5 rounded-lg transition duration-150 flex items-center justify-center gap-2">
          <i data-lucide="log-in" class="w-5 h-5"></i>
          Đăng nhập
        </button>
      </form>
    </div>

    <p class="text-center text-xs text-gray-400 mt-6">© <?= date('Y') ?> Agile Manager</p>
  </div>

  <script>lucide.createIcons();</script>
</body>
</html>
