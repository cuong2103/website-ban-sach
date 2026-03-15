<?php
require_once './views/components/navbar.php';

$error = Message::get('error');

function formatVnd($amount)
{
  return number_format((float)$amount, 0, ',', '.') . 'đ';
}

$fullNameValue = htmlspecialchars($checkoutOld['full_name'] ?? $customer['full_name'] ?? '');
$emailValue = htmlspecialchars($checkoutOld['email'] ?? $customer['email'] ?? '');
$phoneValue = htmlspecialchars($checkoutOld['phone'] ?? $customer['phone'] ?? '');
$addressLineValue = htmlspecialchars($checkoutOld['address_line'] ?? $customer['address'] ?? '');
$wardValue = htmlspecialchars($checkoutOld['ward'] ?? '');
$districtValue = htmlspecialchars($checkoutOld['district'] ?? '');
$cityValue = htmlspecialchars($checkoutOld['city'] ?? 'TP. Hồ Chí Minh');
$noteValue = htmlspecialchars($checkoutOld['note'] ?? '');
?>

<div class="max-w-[1200px] mx-auto px-4 py-8">
  <div class="mb-6">
    <p class="text-sm text-gray-500 mb-1">
      <a href="<?= BASE_URL ?>?act=home" class="hover:text-[#4CAF50]">Trang chủ</a>
      <span class="mx-2">›</span>
      <a href="<?= BASE_URL ?>?act=cart" class="hover:text-[#4CAF50]">Giỏ hàng</a>
      <span class="mx-2">›</span>
      <span>Thanh toán</span>
    </p>

    <div class="flex items-center justify-center gap-4 text-xs mt-4">
      <div class="flex items-center gap-2 text-[#4CAF50]">
        <span class="w-5 h-5 rounded-full bg-[#4CAF50] text-white flex items-center justify-center">1</span>
        <span class="font-medium">Thông tin giao hàng</span>
      </div>
      <span class="w-10 h-[1px] bg-gray-200"></span>
      <div class="flex items-center gap-2 text-gray-400">
        <span class="w-5 h-5 rounded-full bg-gray-200 text-gray-600 flex items-center justify-center">2</span>
        <span class="font-medium">Thanh toán COD</span>
      </div>
    </div>
  </div>

  <?php if ($error): ?>
  <div class="mb-4 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-red-700 text-sm">
    <?= htmlspecialchars($error) ?>
  </div>
  <?php endif; ?>

  <form method="POST" action="<?= BASE_URL ?>?act=checkout-place" class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <section class="lg:col-span-2 bg-white rounded-xl border border-gray-100 p-5">
      <h2 class="font-semibold text-[#333] mb-4 flex items-center gap-2">
        <i data-lucide="map-pin" class="w-4 h-4 text-[#4CAF50]"></i>
        Thông tin giao hàng
      </h2>

      <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
        <div>
          <label class="text-xs text-gray-600">Họ và tên *</label>
          <input type="text" name="full_name" value="<?= $fullNameValue ?>"
            class="w-full mt-1 px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#4CAF50]">
        </div>
        <div>
          <label class="text-xs text-gray-600">Số điện thoại *</label>
          <input type="text" name="phone" value="<?= $phoneValue ?>"
            class="w-full mt-1 px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#4CAF50]">
        </div>
        <div>
          <label class="text-xs text-gray-600">Email *</label>
          <input type="email" name="email" value="<?= $emailValue ?>"
            class="w-full mt-1 px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#4CAF50]">
        </div>
        <div>
          <label class="text-xs text-gray-600">Địa chỉ *</label>
          <input type="text" name="address_line" value="<?= $addressLineValue ?>"
            class="w-full mt-1 px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#4CAF50]">
        </div>
        <div>
          <label class="text-xs text-gray-600">Phường/Xã *</label>
          <input type="text" name="ward" value="<?= $wardValue ?>"
            class="w-full mt-1 px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#4CAF50]">
        </div>
        <div>
          <label class="text-xs text-gray-600">Quận/Huyện *</label>
          <input type="text" name="district" value="<?= $districtValue ?>"
            class="w-full mt-1 px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#4CAF50]">
        </div>
        <div class="md:col-span-2">
          <label class="text-xs text-gray-600">Tỉnh/Thành phố *</label>
          <input type="text" name="city" value="<?= $cityValue ?>"
            class="w-full mt-1 px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#4CAF50]">
        </div>
        <div class="md:col-span-2">
          <label class="text-xs text-gray-600">Ghi chú</label>
          <textarea name="note" rows="3"
            class="w-full mt-1 px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#4CAF50]"
            placeholder="Ghi chú cho đơn hàng (không bắt buộc)"><?= $noteValue ?></textarea>
        </div>
      </div>

      <button type="submit"
        class="mt-4 w-full bg-[#4CAF50] hover:bg-[#43A047] text-white py-3 rounded-lg font-medium transition-colors">
        Đặt hàng ngay
      </button>
    </section>

    <aside class="bg-white rounded-xl border border-gray-100 p-4 h-fit">
      <h2 class="font-semibold text-[#333] mb-3">Đơn hàng của bạn</h2>

      <div class="space-y-3 mb-4 max-h-64 overflow-auto pr-1">
        <?php foreach ($items as $item): ?>
        <?php if (!$item['is_available']) continue; ?>
        <div class="flex items-start gap-3">
          <img src="<?= htmlspecialchars($item['thumbnail'] ?: 'https://via.placeholder.com/60x80?text=No+Image') ?>"
            alt="<?= htmlspecialchars($item['title']) ?>" class="w-10 h-12 rounded object-cover bg-gray-100">
          <div class="flex-1 min-w-0">
            <p class="text-xs text-[#333] font-medium line-clamp-1"><?= htmlspecialchars($item['title']) ?></p>
            <p class="text-[11px] text-gray-500">SL: <?= (int)$item['quantity'] ?></p>
          </div>
          <p class="text-xs text-[#333] font-medium"><?= formatVnd($item['line_total']) ?></p>
        </div>
        <?php endforeach; ?>
      </div>

      <div class="space-y-2 text-sm border-t border-gray-100 pt-3">
        <div class="flex justify-between text-gray-600">
          <span>Tạm tính</span>
          <span><?= formatVnd($totals['subtotal']) ?></span>
        </div>
        <div class="flex justify-between text-gray-600">
          <span>Phí vận chuyển</span>
          <span class="text-[#4CAF50]"><?= $totals['shipping_fee'] > 0 ? formatVnd($totals['shipping_fee']) : 'Miễn phí' ?></span>
        </div>
        <div class="flex justify-between text-gray-600">
          <span>Giảm giá</span>
          <span class="text-red-500">-<?= formatVnd($totals['discount']) ?></span>
        </div>
        <div class="flex justify-between text-base font-bold pt-2 border-t border-gray-100">
          <span>Tổng cộng</span>
          <span class="text-[#4CAF50]"><?= formatVnd($totals['total']) ?></span>
        </div>
      </div>

      <p class="mt-3 text-xs text-gray-500">Phương thức thanh toán: Thanh toán khi nhận hàng (COD)</p>
    </aside>
  </form>
</div>

<?php require_once './views/components/customer_footer.php'; ?>
