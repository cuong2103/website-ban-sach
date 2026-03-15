<?php
require_once './views/components/navbar.php';

$success = Message::get('success');
$error = Message::get('error');

function formatVnd($amount)
{
  return number_format((float)$amount, 0, ',', '.') . 'đ';
}
?>

<div class="max-w-[1200px] mx-auto px-4 py-8">
  <div class="flex items-center justify-between mb-6">
    <div>
      <p class="text-sm text-gray-500 mb-1">
        <a href="<?= BASE_URL ?>?act=home" class="hover:text-[#4CAF50]">Trang chủ</a>
        <span class="mx-2">›</span>
        <span>Giỏ hàng</span>
      </p>
      <h1 class="text-3xl font-bold text-[#333]">Giỏ hàng (<?= (int)$totals['item_count'] ?> sản phẩm)</h1>
    </div>
    <a href="<?= BASE_URL ?>?act=books" class="text-sm text-[#4CAF50] hover:underline">+ Tiếp tục mua sắm</a>
  </div>

  <?php if ($success): ?>
  <div class="mb-4 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-green-700 text-sm">
    <?= htmlspecialchars($success) ?>
  </div>
  <?php endif; ?>

  <?php if ($error): ?>
  <div class="mb-4 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-red-700 text-sm">
    <?= htmlspecialchars($error) ?>
  </div>
  <?php endif; ?>

  <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <section class="lg:col-span-2 bg-white rounded-xl border border-gray-100 overflow-hidden">
      <?php if (empty($items)): ?>
      <div class="p-12 text-center">
        <i data-lucide="shopping-cart" class="w-14 h-14 text-gray-300 mx-auto mb-3"></i>
        <p class="text-gray-600 mb-4">Giỏ hàng đang trống.</p>
        <a href="<?= BASE_URL ?>?act=books"
          class="inline-flex items-center px-4 py-2 bg-[#4CAF50] text-white rounded-lg hover:bg-[#43A047] transition-colors">
          Mua sắm ngay
        </a>
      </div>
      <?php else: ?>
      <div class="overflow-x-auto">
        <table class="w-full min-w-[680px]">
          <thead class="bg-gray-50 text-left text-xs uppercase tracking-wide text-gray-500">
            <tr>
              <th class="px-4 py-3">Sản phẩm</th>
              <th class="px-4 py-3">Giá</th>
              <th class="px-4 py-3">Số lượng</th>
              <th class="px-4 py-3 text-right">Tổng</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($items as $item): ?>
            <tr class="border-t border-gray-100 align-top">
              <td class="px-4 py-4">
                <div class="flex gap-3">
                  <img
                    src="<?= htmlspecialchars($item['thumbnail'] ?: 'https://via.placeholder.com/80x110?text=No+Image') ?>"
                    alt="<?= htmlspecialchars($item['title']) ?>"
                    class="w-16 h-20 object-cover rounded-md bg-gray-100">
                  <div>
                    <h3 class="font-semibold text-[#333] text-sm mb-1"><?= htmlspecialchars($item['title']) ?></h3>
                    <p class="text-xs text-gray-500 mb-2"><?= htmlspecialchars($item['author']) ?></p>
                    <?php if (!$item['is_available']): ?>
                    <p class="text-xs text-red-500 mb-1">Sản phẩm ngừng bán</p>
                    <?php elseif ((int)$item['stock'] < (int)$item['quantity']): ?>
                    <p class="text-xs text-red-500 mb-1">Chỉ còn <?= (int)$item['stock'] ?> sản phẩm trong kho</p>
                    <?php endif; ?>
                    <a href="<?= BASE_URL ?>?act=cart-remove&book_id=<?= (int)$item['book_id'] ?>"
                      class="text-xs text-red-500 hover:text-red-600">Xóa</a>
                  </div>
                </div>
              </td>

              <td class="px-4 py-4 text-[#4CAF50] font-semibold text-sm"><?= formatVnd($item['unit_price']) ?></td>

              <td class="px-4 py-4">
                <div class="flex items-center gap-2">
                  <form method="POST" action="<?= BASE_URL ?>?act=cart-update">
                    <input type="hidden" name="book_id" value="<?= (int)$item['book_id'] ?>">
                    <input type="hidden" name="current_qty" value="<?= (int)$item['quantity'] ?>">
                    <input type="hidden" name="mode" value="dec">
                    <button type="submit"
                      class="w-7 h-7 rounded-full border border-gray-300 hover:bg-gray-50 text-gray-700">-</button>
                  </form>
                  <span class="min-w-6 text-center text-sm"><?= (int)$item['quantity'] ?></span>
                  <form method="POST" action="<?= BASE_URL ?>?act=cart-update">
                    <input type="hidden" name="book_id" value="<?= (int)$item['book_id'] ?>">
                    <input type="hidden" name="current_qty" value="<?= (int)$item['quantity'] ?>">
                    <input type="hidden" name="mode" value="inc">
                    <button type="submit"
                      class="w-7 h-7 rounded-full border border-gray-300 hover:bg-gray-50 text-gray-700">+</button>
                  </form>
                </div>
              </td>

              <td class="px-4 py-4 text-right text-sm font-semibold text-[#333]">
                <?= formatVnd($item['line_total']) ?>
              </td>
            </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
      <?php endif; ?>
    </section>

    <aside class="space-y-4">
      <div class="bg-white rounded-xl border border-gray-100 p-4">
        <h2 class="font-semibold text-[#333] mb-3 flex items-center gap-2">
          <i data-lucide="ticket" class="w-4 h-4 text-[#FFC107]"></i>
          Mã giảm giá
        </h2>

        <form method="POST" action="<?= BASE_URL ?>?act=cart-apply-voucher" class="flex gap-2 mb-2">
          <input type="text" name="voucher_code" placeholder="Nhập mã voucher"
            value="<?= htmlspecialchars($appliedVoucher['code'] ?? '') ?>"
            class="flex-1 px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#4CAF50]">
          <button type="submit"
            class="px-4 py-2 bg-[#FFC107] hover:bg-[#E6AE06] text-[#333] rounded-lg text-sm font-medium transition-colors">
            Áp dụng
          </button>
        </form>

        <?php if (!empty($appliedVoucher)): ?>
        <div class="rounded-lg bg-green-50 border border-green-200 p-2 mb-2 text-xs text-green-700 flex items-center justify-between">
          <span>Đang dùng: <strong><?= htmlspecialchars($appliedVoucher['code']) ?></strong></span>
          <a href="<?= BASE_URL ?>?act=cart-clear-voucher" class="text-red-500 hover:text-red-600">Gỡ</a>
        </div>
        <?php endif; ?>

        <?php if (!empty($suggestedVouchers)): ?>
        <p class="text-xs text-gray-500 mb-1">Mã voucher gợi ý:</p>
        <div class="space-y-1 text-xs">
          <?php foreach ($suggestedVouchers as $voucher): ?>
          <div class="text-[#4CAF50]">
            <?= htmlspecialchars($voucher['code']) ?> -
            <?php if ($voucher['discount_type'] === 'percent'): ?>
            <?= (int)$voucher['discount_value'] ?>%
            <?php else: ?>
            <?= formatVnd($voucher['discount_value']) ?>
            <?php endif; ?>
          </div>
          <?php endforeach; ?>
        </div>
        <?php endif; ?>
      </div>

      <div class="bg-white rounded-xl border border-gray-100 p-4">
        <h2 class="font-semibold text-[#333] mb-4">Tóm tắt đơn hàng</h2>
        <div class="space-y-2 text-sm">
          <div class="flex items-center justify-between text-gray-600">
            <span>Tạm tính (<?= (int)$totals['item_count'] ?> sản phẩm)</span>
            <span><?= formatVnd($totals['subtotal']) ?></span>
          </div>
          <div class="flex items-center justify-between text-gray-600">
            <span>Phí vận chuyển</span>
            <span class="text-[#4CAF50]"><?= $totals['shipping_fee'] > 0 ? formatVnd($totals['shipping_fee']) : 'Miễn phí' ?></span>
          </div>
          <div class="flex items-center justify-between text-gray-600">
            <span>Giảm giá</span>
            <span class="text-red-500">-<?= formatVnd($totals['discount']) ?></span>
          </div>
          <div class="border-t border-gray-100 pt-3 mt-3 flex items-center justify-between text-lg font-bold">
            <span>Tổng cộng</span>
            <span class="text-[#4CAF50]"><?= formatVnd($totals['total']) ?></span>
          </div>
        </div>

        <a href="<?= BASE_URL ?>?act=checkout"
          class="mt-4 w-full inline-flex items-center justify-center bg-[#4CAF50] hover:bg-[#43A047] text-white py-3 rounded-lg font-medium transition-colors <?= empty($items) ? 'pointer-events-none opacity-50' : '' ?>">
          Tiến hành thanh toán
        </a>
      </div>
    </aside>
  </div>
</div>

<?php require_once './views/components/customer_footer.php'; ?>
