<?php
require_once './views/components/navbar.php';

function formatVnd($amount)
{
  return number_format((float)$amount, 0, ',', '.') . 'đ';
}
?>

<div class="max-w-[1200px] mx-auto px-4 py-8">
  <div class="mb-6">
    <p class="text-sm text-gray-500 mb-1">
      <a href="<?= BASE_URL ?>?act=home" class="hover:text-[#4CAF50]">Trang chủ</a>
      <span class="mx-2">›</span>
      <a href="<?= BASE_URL ?>?act=orders" class="hover:text-[#4CAF50]">Lịch sử đơn hàng</a>
      <span class="mx-2">›</span>
      <span>#<?= htmlspecialchars($order['order_code']) ?></span>
    </p>
    <div class="flex flex-wrap gap-3 items-center justify-between">
      <h1 class="text-3xl font-bold text-[#333]">Chi tiết đơn hàng #<?= htmlspecialchars($order['order_code']) ?></h1>
      <span class="inline-flex px-3 py-1 rounded-full text-sm bg-blue-50 text-blue-600">
        <?= htmlspecialchars($order['status_name'] ?? 'Pending') ?>
      </span>
    </div>
  </div>

  <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <section class="lg:col-span-2 bg-white rounded-xl border border-gray-100 overflow-hidden">
      <div class="overflow-x-auto">
        <table class="w-full min-w-[700px]">
          <thead class="bg-gray-50 text-left text-xs uppercase tracking-wide text-gray-500">
            <tr>
              <th class="px-4 py-3">Sản phẩm</th>
              <th class="px-4 py-3">Đơn giá</th>
              <th class="px-4 py-3">Số lượng</th>
              <th class="px-4 py-3 text-right">Tạm tính</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($order['items'] as $item): ?>
            <tr class="border-t border-gray-100 align-top">
              <td class="px-4 py-4">
                <div class="flex gap-3">
                  <img
                    src="<?= htmlspecialchars($item['thumbnail'] ?: 'https://via.placeholder.com/80x110?text=No+Image') ?>"
                    alt="<?= htmlspecialchars($item['title']) ?>"
                    class="w-14 h-18 object-cover rounded-md bg-gray-100">
                  <div>
                    <h3 class="font-semibold text-[#333] text-sm mb-1"><?= htmlspecialchars($item['title']) ?></h3>
                    <p class="text-xs text-gray-500"><?= htmlspecialchars($item['author'] ?? 'N/A') ?></p>
                  </div>
                </div>
              </td>
              <td class="px-4 py-4 text-sm text-[#333] font-medium"><?= formatVnd($item['price']) ?></td>
              <td class="px-4 py-4 text-sm text-gray-600"><?= (int)$item['quantity'] ?></td>
              <td class="px-4 py-4 text-right text-sm font-semibold text-[#333]"><?= formatVnd($item['subtotal']) ?></td>
            </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </section>

    <aside class="space-y-4">
      <div class="bg-white rounded-xl border border-gray-100 p-4">
        <h2 class="font-semibold text-[#333] mb-3">Thông tin giao hàng</h2>
        <div class="space-y-2 text-sm text-gray-600">
          <p><span class="text-gray-500">SĐT:</span> <?= htmlspecialchars($order['phone']) ?></p>
          <p><span class="text-gray-500">Địa chỉ:</span> <?= htmlspecialchars($order['shipping_address']) ?></p>
          <p><span class="text-gray-500">Ghi chú:</span> <?= htmlspecialchars($order['note'] ?: 'Không có') ?></p>
          <p><span class="text-gray-500">Thanh toán:</span> <?= htmlspecialchars($order['payment_method'] ?? 'COD') ?></p>
          <p><span class="text-gray-500">Ngày đặt:</span> <?= date('d/m/Y H:i', strtotime($order['created_at'])) ?></p>
        </div>
      </div>

      <div class="bg-white rounded-xl border border-gray-100 p-4">
        <h2 class="font-semibold text-[#333] mb-3">Tổng thanh toán</h2>
        <div class="space-y-2 text-sm">
          <div class="flex items-center justify-between text-gray-600">
            <span>Giảm giá</span>
            <span class="text-red-500">-<?= formatVnd($order['discount_amount']) ?></span>
          </div>
          <div class="border-t border-gray-100 pt-3 flex items-center justify-between text-base font-bold">
            <span>Tổng cộng</span>
            <span class="text-[#4CAF50]"><?= formatVnd($order['total_amount']) ?></span>
          </div>
        </div>
      </div>
    </aside>
  </div>
</div>

<?php require_once './views/components/customer_footer.php'; ?>
