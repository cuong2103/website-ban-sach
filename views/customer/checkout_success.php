<?php
require_once './views/components/navbar.php';

function formatVnd($amount)
{
  return number_format((float)$amount, 0, ',', '.') . 'đ';
}
?>

<div class="max-w-[900px] mx-auto px-4 py-12">
    <div class="bg-white rounded-2xl border border-gray-100 p-8 text-center">
        <div class="w-16 h-16 mx-auto rounded-full bg-green-100 flex items-center justify-center mb-4">
            <i data-lucide="check" class="w-8 h-8 text-[#4CAF50]"></i>
        </div>

        <h1 class="text-2xl font-bold text-[#333] mb-2">Đặt hàng thành công!</h1>
        <p class="text-gray-600 mb-6">Cảm ơn bạn đã mua sắm tại BookStore. Chúng tôi sẽ liên hệ xác nhận đơn hàng sớm
            nhất.</p>

        <div class="max-w-md mx-auto rounded-xl border border-gray-100 bg-gray-50 p-4 text-left mb-6">
            <div class="flex items-center justify-between text-sm mb-2">
                <span class="text-gray-500">Mã đơn hàng</span>
                <span class="font-semibold text-[#333]"><?= htmlspecialchars($lastOrder['order_code'] ?? '') ?></span>
            </div>
            <div class="flex items-center justify-between text-sm">
                <span class="text-gray-500">Tổng thanh toán</span>
                <span class="font-semibold text-[#4CAF50]"><?= formatVnd($lastOrder['total'] ?? 0) ?></span>
            </div>
        </div>

        <div class="flex flex-wrap gap-3 justify-center">
            <a href="<?= BASE_URL ?>?act=orders"
                class="px-5 py-2.5 rounded-lg border border-gray-300 text-gray-700 hover:bg-gray-50 transition-colors">
                Xem lịch sử đơn
            </a>
            <a href="<?= BASE_URL ?>?act=books"
                class="px-5 py-2.5 rounded-lg border border-gray-300 text-gray-700 hover:bg-gray-50 transition-colors">
                Tiếp tục mua sắm
            </a>
            <a href="<?= BASE_URL ?>?act=home"
                class="px-5 py-2.5 rounded-lg bg-[#4CAF50] text-white hover:bg-[#43A047] transition-colors">
                Về trang chủ
            </a>
        </div>
    </div>
</div>

<?php require_once './views/components/customer_footer.php'; ?>