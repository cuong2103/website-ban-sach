<?php
require_once './views/components/header.php';
require_once './views/components/sidebar.php';
?>

<main class="flex-1 overflow-y-auto p-5 space-y-6">

  <!-- Page title -->
  <div>
    <h1 class="text-xl font-bold text-[#333]">Dashboard</h1>
    <p class="text-sm text-gray-400">Tổng quan hệ thống – <?= date('d/m/Y') ?></p>
  </div>

  <!-- Stats cards -->
  <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">

    <div class="bg-white rounded-xl shadow-sm p-5">
      <div class="flex items-start justify-between mb-3">
        <div class="w-10 h-10 rounded-xl flex items-center justify-center bg-green-100 text-[#4CAF50]">
          <i data-lucide="trending-up" class="w-5 h-5"></i>
        </div>
        <span class="flex items-center gap-1 text-xs font-medium text-[#4CAF50]">
          <i data-lucide="arrow-up-right" class="w-3 h-3"></i>+12.5%
        </span>
      </div>
      <p class="text-xs text-gray-400 mb-1">Doanh thu tháng</p>
      <p class="font-bold text-[#333]">28.000.000 ₫</p>
    </div>

    <div class="bg-white rounded-xl shadow-sm p-5">
      <div class="flex items-start justify-between mb-3">
        <div class="w-10 h-10 rounded-xl flex items-center justify-center bg-blue-100 text-blue-600">
          <i data-lucide="shopping-bag" class="w-5 h-5"></i>
        </div>
        <span class="flex items-center gap-1 text-xs font-medium text-[#4CAF50]">
          <i data-lucide="arrow-up-right" class="w-3 h-3"></i>+8.3%
        </span>
      </div>
      <p class="text-xs text-gray-400 mb-1">Tổng đơn hàng</p>
      <p class="font-bold text-[#333]">156</p>
    </div>

    <div class="bg-white rounded-xl shadow-sm p-5">
      <div class="flex items-start justify-between mb-3">
        <div class="w-10 h-10 rounded-xl flex items-center justify-center bg-yellow-100 text-yellow-600">
          <i data-lucide="book-open" class="w-5 h-5"></i>
        </div>
        <span class="flex items-center gap-1 text-xs font-medium text-red-500">
          <i data-lucide="arrow-down-right" class="w-3 h-3"></i>-2.1%
        </span>
      </div>
      <p class="text-xs text-gray-400 mb-1">Sách trong kho</p>
      <p class="font-bold text-[#333]">1,245</p>
    </div>

    <div class="bg-white rounded-xl shadow-sm p-5">
      <div class="flex items-start justify-between mb-3">
        <div class="w-10 h-10 rounded-xl flex items-center justify-center bg-purple-100 text-purple-600">
          <i data-lucide="users" class="w-5 h-5"></i>
        </div>
        <span class="flex items-center gap-1 text-xs font-medium text-[#4CAF50]">
          <i data-lucide="arrow-up-right" class="w-3 h-3"></i>+15.2%
        </span>
      </div>
      <p class="text-xs text-gray-400 mb-1">Khách hàng mới</p>
      <p class="font-bold text-[#333]">48</p>
    </div>
  </div>

  <!-- Bottom: recent tables -->
  <div class="grid lg:grid-cols-2 gap-4">

    <!-- Sách bán chạy -->
    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
      <div class="px-5 py-4 border-b border-gray-100">
        <h3 class="font-semibold text-[#333] text-sm">Sách bán chạy</h3>
      </div>
      <div class="divide-y divide-gray-50">
        <?php
        $topBooks = [
          ['rank' => 1, 'title' => 'Đắc Nhân Tâm', 'author' => 'Dale Carnegie', 'price' => '68.000 ₫', 'sold' => 1240],
          ['rank' => 2, 'title' => 'Nhà Giả Kim', 'author' => 'Paulo Coelho', 'price' => '79.000 ₫', 'sold' => 987],
          ['rank' => 3, 'title' => 'Tôi Tài Giỏi', 'author' => 'Adam Khoo', 'price' => '85.000 ₫', 'sold' => 854],
          ['rank' => 4, 'title' => 'Sapiens', 'author' => 'Yuval Noah', 'price' => '120.000 ₫', 'sold' => 732],
          ['rank' => 5, 'title' => 'Atomic Habits', 'author' => 'James Clear', 'price' => '95.000 ₫', 'sold' => 698],
        ];
        foreach ($topBooks as $book): ?>
          <div class="flex items-center gap-3 px-5 py-3">
            <span class="text-xs font-bold w-5 <?= $book['rank'] === 1 ? 'text-[#FFC107]' : 'text-gray-400' ?>">
              #<?= $book['rank'] ?>
            </span>
            <div class="w-10 h-12 bg-gray-100 rounded-lg flex items-center justify-center shrink-0">
              <i data-lucide="book" class="w-5 h-5 text-gray-400"></i>
            </div>
            <div class="flex-1 min-w-0">
              <p class="text-sm font-medium text-[#333] truncate"><?= $book['title'] ?></p>
              <p class="text-xs text-gray-400"><?= $book['author'] ?></p>
            </div>
            <div class="text-right shrink-0">
              <p class="text-sm font-semibold text-[#4CAF50]"><?= $book['price'] ?></p>
              <p class="text-xs text-gray-400"><?= $book['sold'] ?> đã bán</p>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    </div>

    <!-- Đơn hàng gần đây -->
    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
      <div class="px-5 py-4 border-b border-gray-100">
        <h3 class="font-semibold text-[#333] text-sm">Đơn hàng gần đây</h3>
      </div>
      <div class="divide-y divide-gray-50">
        <?php
        $statusClass = [
          'Chờ xác nhận' => 'bg-yellow-100 text-yellow-700',
          'Đang giao' => 'bg-blue-100 text-blue-700',
          'Hoàn thành' => 'bg-green-100 text-green-700',
          'Đã hủy' => 'bg-red-100 text-red-500',
        ];
        $orders = [
          ['id' => '#DH001', 'customer' => 'Nguyễn Văn A', 'date' => '13/03/2026', 'total' => '247.000 ₫', 'status' => 'Hoàn thành'],
          ['id' => '#DH002', 'customer' => 'Trần Thị B', 'date' => '13/03/2026', 'total' => '95.000 ₫', 'status' => 'Đang giao'],
          ['id' => '#DH003', 'customer' => 'Lê Văn C', 'date' => '12/03/2026', 'total' => '163.000 ₫', 'status' => 'Chờ xác nhận'],
          ['id' => '#DH004', 'customer' => 'Phạm Thị D', 'date' => '12/03/2026', 'total' => '78.000 ₫', 'status' => 'Đã hủy'],
          ['id' => '#DH005', 'customer' => 'Hoàng Văn E', 'date' => '11/03/2026', 'total' => '340.000 ₫', 'status' => 'Hoàn thành'],
        ];
        foreach ($orders as $order): ?>
          <div class="flex items-center gap-3 px-5 py-3">
            <div class="w-8 h-8 bg-gray-100 rounded-lg flex items-center justify-center shrink-0">
              <i data-lucide="shopping-bag" class="w-[14px] h-[14px] text-gray-500"></i>
            </div>
            <div class="flex-1 min-w-0">
              <p class="text-sm font-medium text-[#333]"><?= $order['id'] ?></p>
              <p class="text-xs text-gray-400"><?= $order['customer'] ?> · <?= $order['date'] ?></p>
            </div>
            <div class="text-right shrink-0">
              <p class="text-sm font-semibold"><?= $order['total'] ?></p>
              <span class="text-xs px-2 py-0.5 rounded-full <?= $statusClass[$order['status']] ?>">
                <?= $order['status'] ?>
              </span>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    </div>

  </div>
</main>

<?php require_once './views/components/footer.php'; ?>