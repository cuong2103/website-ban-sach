<?php
$currentAct = $_GET['act'] ?? '';

function isActive($acts, $currentAct)
{
  if (is_array($acts))
    return in_array($currentAct, $acts);
  return $currentAct === $acts;
}

$activeClass = 'bg-[#4CAF50] text-white';
$inactiveClass = 'text-gray-400 hover:bg-white/10 hover:text-white';
?>
<aside class="w-56 bg-[#1B2537] text-white flex-shrink-0 flex flex-col h-screen fixed inset-y-0 left-0 z-50">

  <!-- Logo -->
  <div class="flex items-center gap-3 px-4 h-14 border-b border-white/10">
    <div class="w-7 h-7 bg-[#4CAF50] rounded-lg flex items-center justify-center shrink-0">
      <i data-lucide="book-open" class="w-4 h-4 text-white"></i>
    </div>
    <span class="font-bold text-sm whitespace-nowrap">
      Book<span class="text-[#4CAF50]">Admin</span>
    </span>
  </div>

  <!-- Nav -->
  <nav class="flex-1 overflow-y-auto py-3 space-y-0.5">

    <!-- Dashboard -->
    <a href="<?= BASE_URL ?>admin-dashboard"
      class="flex items-center gap-3 px-4 py-2.5 mx-2 rounded-xl text-sm transition-colors <?= isActive('admin-dashboard', $currentAct) ? $activeClass : $inactiveClass ?>">
      <i data-lucide="layout-dashboard" class="w-4 h-4 shrink-0"></i>
      <span class="whitespace-nowrap">Dashboard</span>
    </a>

    <!-- Quản lý danh mục -->
    <a href="<?= BASE_URL ?>admin-categories"
      class="flex items-center gap-3 px-4 py-2.5 mx-2 rounded-xl text-sm transition-colors <?= isActive(['admin-categories', 'admin-categories-create', 'admin-categories-edit'], $currentAct) ? $activeClass : $inactiveClass ?>">
      <i data-lucide="folder" class="w-4 h-4 shrink-0"></i>
      <span class="whitespace-nowrap">Danh mục</span>
    </a>

    <!-- Quản lý flash sale -->
    <a href="<?= BASE_URL ?>admin-flash-sales"
      class="flex items-center gap-3 px-4 py-2.5 mx-2 rounded-xl text-sm transition-colors <?= isActive(['admin-flash-sales', 'admin-flash-sales-create', 'admin-flash-sales-edit'], $currentAct) ? $activeClass : $inactiveClass ?>">
      <i data-lucide="zap" class="w-4 h-4 shrink-0"></i>
      <span class="whitespace-nowrap">Flash Sale</span>
    </a>

    <!-- Quản lý kho hàng -->
    <a href="<?= BASE_URL ?>admin-inventories"
      class="flex items-center gap-3 px-4 py-2.5 mx-2 rounded-xl text-sm transition-colors <?= isActive(['admin-inventories', 'admin-inventories-create', 'admin-inventories-edit'], $currentAct) ? $activeClass : $inactiveClass ?>">
      <i data-lucide="package" class="w-4 h-4 shrink-0"></i>
      <span class="whitespace-nowrap">Kho Hàng</span>
    </a>

    <!-- Quản lý đơn hàng -->
    <a href="<?= BASE_URL ?>admin-orders"
      class="flex items-center gap-3 px-4 py-2.5 mx-2 rounded-xl text-sm transition-colors <?= isActive(['admin-orders', 'admin-order-detail'], $currentAct) ? $activeClass : $inactiveClass ?>">
      <i data-lucide="shopping-bag" class="w-4 h-4 shrink-0"></i>
      <span class="whitespace-nowrap">Đơn Hàng</span>
    </a>

  </nav>

  <!-- Bottom: Đăng xuất -->
  <div class="p-3 border-t border-white/10">
    <a href="<?= BASE_URL ?>logout"
      class="flex items-center gap-3 px-3 py-2 rounded-xl text-gray-400 hover:bg-white/10 hover:text-white text-sm transition-colors">
      <i data-lucide="log-out" class="w-4 h-4 shrink-0"></i>
      <span>Đăng xuất</span>
    </a>
  </div>
</aside>