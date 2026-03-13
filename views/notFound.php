<!DOCTYPE html>
<html lang="vi" class="h-full">
<head>
  <meta charset="UTF-8">
  <title>404 - Không tìm thấy trang</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.min.js"></script>
</head>
<body class="h-full bg-gray-50 flex items-center justify-center">
  <div class="text-center">
    <div class="inline-flex items-center justify-center w-20 h-20 bg-indigo-100 rounded-full mb-6">
      <i data-lucide="file-question" class="w-10 h-10 text-indigo-500"></i>
    </div>
    <h1 class="text-6xl font-bold text-gray-800 mb-2">404</h1>
    <p class="text-xl text-gray-600 mb-2">Không tìm thấy trang</p>
    <p class="text-gray-400 mb-8">Trang bạn đang tìm không tồn tại hoặc đã bị xóa.</p>
    <a href="<?= BASE_URL ?>" class="inline-flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-3 rounded-lg font-medium transition">
      <i data-lucide="home" class="w-4 h-4"></i>
      Về trang chủ
    </a>
  </div>
  <script>lucide.createIcons();</script>
</body>
</html>
