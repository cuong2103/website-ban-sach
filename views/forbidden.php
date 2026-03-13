<!DOCTYPE html>
<html lang="vi" class="h-full">
<head>
  <meta charset="UTF-8">
  <title>403 - Không có quyền truy cập</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.min.js"></script>
</head>
<body class="h-full bg-gray-50 flex items-center justify-center">
  <div class="text-center">
    <div class="inline-flex items-center justify-center w-20 h-20 bg-red-100 rounded-full mb-6">
      <i data-lucide="shield-x" class="w-10 h-10 text-red-500"></i>
    </div>
    <h1 class="text-6xl font-bold text-gray-800 mb-2">403</h1>
    <p class="text-xl text-gray-600 mb-2">Không có quyền truy cập</p>
    <p class="text-gray-400 mb-8">Bạn không có quyền xem trang này.</p>
    <a href="<?= BASE_URL ?>" class="inline-flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-3 rounded-lg font-medium transition">
      <i data-lucide="home" class="w-4 h-4"></i>
      Về trang chủ
    </a>
  </div>
  <script>lucide.createIcons();</script>
</body>
</html>
