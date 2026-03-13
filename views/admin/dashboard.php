<?php
require_once './views/components/header.php';
require_once './views/components/sidebar.php';
?>

<!-- Nội dung dashboard -->
<main class="mt-24 px-6 pb-6 pt-4">
  <!-- Welcome banner -->
  <div class="mb-6">
    <h2 class="text-2xl font-bold text-gray-900">Chào mừng, <?= htmlspecialchars($fullname) ?>! 👋</h2>
    <p class="text-gray-500 mt-1">Hôm nay là <?= date('d/m/Y') ?> — Hệ thống hoạt động bình thường.</p>
  </div>

  <!-- Stats cards (placeholder - kết nối dữ liệu thực tế sau) -->
  <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 flex items-center gap-4">
      <div class="w-12 h-12 bg-indigo-100 rounded-xl flex items-center justify-center">
        <i data-lucide="book-open" class="w-6 h-6 text-indigo-600"></i>
      </div>
      <div>
        <p class="text-sm text-gray-500">Tổng sách</p>
        <p class="text-2xl font-bold text-gray-800">0</p>
      </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 flex items-center gap-4">
      <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center">
        <i data-lucide="shopping-cart" class="w-6 h-6 text-green-600"></i>
      </div>
      <div>
        <p class="text-sm text-gray-500">Đơn hàng</p>
        <p class="text-2xl font-bold text-gray-800">0</p>
      </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 flex items-center gap-4">
      <div class="w-12 h-12 bg-yellow-100 rounded-xl flex items-center justify-center">
        <i data-lucide="users" class="w-6 h-6 text-yellow-600"></i>
      </div>
      <div>
        <p class="text-sm text-gray-500">Khách hàng</p>
        <p class="text-2xl font-bold text-gray-800">0</p>
      </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 flex items-center gap-4">
      <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center">
        <i data-lucide="trending-up" class="w-6 h-6 text-purple-600"></i>
      </div>
      <div>
        <p class="text-sm text-gray-500">Doanh thu</p>
        <p class="text-2xl font-bold text-gray-800">0 ₫</p>
      </div>
    </div>
  </div>

  <!-- Content area placeholder -->
  <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-8 text-center">
    <i data-lucide="layout-dashboard" class="w-12 h-12 text-indigo-300 mx-auto mb-3"></i>
    <h3 class="text-lg font-semibold text-gray-700 mb-1">Dashboard đang được xây dựng</h3>
    <p class="text-gray-400 text-sm">Thêm các widget và biểu đồ tại đây.</p>
  </div>
</main>

<?php require_once './views/components/footer.php'; ?>
