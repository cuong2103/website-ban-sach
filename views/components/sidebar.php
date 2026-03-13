<?php
// Xác định action hiện tại để highlight menu
$currentAct = $_GET['act'] ?? '';

// Helper check menu active
function isActiveMenu($acts, $currentAct)
{
  if (is_array($acts)) {
    return in_array($currentAct, $acts);
  }
  return $currentAct === $acts;
}

$activeClass   = 'bg-indigo-50 text-indigo-700';
$inactiveClass = 'text-gray-700 hover:bg-gray-100';
?>
<aside class="w-64 bg-white shadow-lg h-screen fixed inset-y-0 left-0 flex flex-col z-50">
  <!-- Logo -->
  <div class="px-6 py-7 border-b border-gray-200">
    <div class="flex items-center space-x-3">
      <div class="relative w-12 h-12">
        <div class="absolute inset-0 bg-indigo-600 rounded-2xl"></div>
        <i data-lucide="layout-dashboard" class="w-7 h-7 text-white absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2"></i>
      </div>
      <div>
        <h1 class="text-xl font-bold text-gray-900">Agile Manager</h1>
        <p class="text-xs text-gray-500"><?= $role === 'Admin' ? 'Admin Panel' : 'Nhân viên' ?></p>
      </div>
    </div>
  </div>

  <!-- Menu -->
  <nav class="flex-1 px-4 py-4 space-y-1 overflow-y-auto">

    <!-- Dashboard -->
    <a href="<?= BASE_URL ?>" class="flex items-center px-4 py-3 text-sm font-medium rounded-lg <?= $currentAct === '' ? $activeClass : $inactiveClass ?> transition">
      <i class="mr-3 w-6 h-6" data-lucide="layout-dashboard"></i>
      Dashboard
    </a>

    <!-- ===================== -->
    <!-- THÊM MENU MỚI Ở ĐÂY  -->
    <!-- ===================== -->
    <!--
    Ví dụ thêm menu đơn:
    <a href="<?= BASE_URL ?>?act=products" class="flex items-center px-4 py-3 text-sm font-medium rounded-lg <?= $currentAct === 'products' ? $activeClass : $inactiveClass ?> transition">
      <i class="mr-3 w-6 h-6" data-lucide="package"></i>
      Sản phẩm
    </a>

    Ví dụ thêm menu có submenu:
    <div class="menu-group">
      <button class="menu-toggle w-full flex items-center justify-between px-4 py-3 text-sm font-medium <?= $inactiveClass ?> rounded-lg transition">
        <div class="flex items-center">
          <i class="mr-3 w-6 h-6" data-lucide="folder"></i>
          Danh mục
        </div>
        <i class="w-4 h-4" data-lucide="chevron-down"></i>
      </button>
      <div class="submenu pl-12 space-y-1 overflow-hidden transition-all duration-300 max-h-0">
        <a href="<?= BASE_URL ?>?act=categories" class="block px-4 mt-1 py-2 text-sm <?= $inactiveClass ?> rounded">Tất cả</a>
      </div>
    </div>
    -->

    <!-- ===================== -->
    <!-- THÊM MENU MỚI Ở ĐÂY  -->
    <!-- ===================== -->

  </nav>

  <!-- Đăng xuất -->
  <div class="border-t border-gray-200 px-4 py-4">
    <a href="<?= BASE_URL . '?act=logout' ?>" class="flex items-center px-4 py-3 text-sm font-medium text-red-600 hover:bg-red-50 rounded-lg transition">
      <i class="mr-3 w-6 h-6 text-red-500" data-lucide="log-out"></i>
      Đăng xuất
    </a>
  </div>
</aside>
