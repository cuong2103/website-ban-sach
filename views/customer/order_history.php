<?php
require_once './views/components/navbar.php';

$error = Message::get('error');

function formatVnd($amount)
{
  return number_format((float)$amount, 0, ',', '.') . 'đ';
}

$totalOrders = count($orders ?? []);
$pendingOrders = 0;
$totalSpent = 0;

foreach (($orders ?? []) as $orderRow) {
    $totalSpent += (float)($orderRow['total_amount'] ?? 0);
    if (strtolower((string)($orderRow['status_name'] ?? '')) === 'pending') {
        $pendingOrders++;
    }
}
?>

<div class="max-w-[1200px] mx-auto px-4 py-8 min-h-[52vh]">
    <div class="mb-6">
        <p class="text-sm text-gray-500 mb-1">
            <a href="<?= BASE_URL ?>?act=home" class="hover:text-[#4CAF50]">Trang chủ</a>
            <span class="mx-2">›</span>
            <span>Lịch sử đơn hàng</span>
        </p>
        <h1 class="text-3xl font-bold text-[#333]">Lịch sử đơn hàng</h1>
    </div>

    <?php if ($error): ?>
    <div class="mb-4 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-red-700 text-sm">
        <?= htmlspecialchars($error) ?>
    </div>
    <?php endif; ?>

    <?php if (empty($orders)): ?>
    <div class="bg-white rounded-xl border border-gray-100 p-12 text-center">
        <i data-lucide="receipt" class="w-12 h-12 text-gray-300 mx-auto mb-3"></i>
        <p class="text-gray-600 mb-4">Bạn chưa có đơn hàng nào.</p>
        <a href="<?= BASE_URL ?>?act=books"
            class="inline-flex px-4 py-2 bg-[#4CAF50] text-white rounded-lg hover:bg-[#43A047] transition-colors">
            Mua sắm ngay
        </a>
    </div>
    <?php else: ?>
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3 mb-4">
        <div class="bg-white rounded-xl border border-gray-100 p-4">
            <p class="text-xs text-gray-500 mb-1">Tổng đơn hàng</p>
            <p class="text-2xl font-bold text-[#333]"><?= $totalOrders ?></p>
        </div>
        <div class="bg-white rounded-xl border border-gray-100 p-4">
            <p class="text-xs text-gray-500 mb-1">Đang chờ xử lý</p>
            <p class="text-2xl font-bold text-[#4CAF50]"><?= $pendingOrders ?></p>
        </div>
        <div class="bg-white rounded-xl border border-gray-100 p-4 sm:col-span-2 lg:col-span-1">
            <p class="text-xs text-gray-500 mb-1">Tổng chi tiêu</p>
            <p class="text-2xl font-bold text-[#333]"><?= formatVnd($totalSpent) ?></p>
        </div>
    </div>

    <div class="bg-white rounded-xl border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full min-w-[760px]">
                <thead class="bg-gray-50 text-left text-xs uppercase tracking-wide text-gray-500">
                    <tr>
                        <th class="px-4 py-3">Mã đơn</th>
                        <th class="px-4 py-3">Ngày đặt</th>
                        <th class="px-4 py-3">Số lượng</th>
                        <th class="px-4 py-3">Giảm giá</th>
                        <th class="px-4 py-3">Trạng thái</th>
                        <th class="px-4 py-3 text-right">Tổng tiền</th>
                        <th class="px-4 py-3 text-right">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($orders as $order): ?>
                    <tr class="border-t border-gray-100 text-sm hover:bg-gray-50/60 transition-colors">
                        <td class="px-4 py-3 font-medium text-[#333]">#<?= htmlspecialchars($order['order_code']) ?>
                        </td>
                        <td class="px-4 py-3 text-gray-600"><?= date('d/m/Y H:i', strtotime($order['created_at'])) ?>
                        </td>
                        <td class="px-4 py-3 text-gray-600"><?= (int)$order['item_count'] ?> sản phẩm</td>
                        <td class="px-4 py-3 text-red-500">-<?= formatVnd($order['discount_amount']) ?></td>
                        <td class="px-4 py-3">
                            <span class="inline-flex px-2 py-1 rounded-full text-xs bg-blue-50 text-blue-600">
                                <?= htmlspecialchars($order['status_name'] ?? 'Pending') ?>
                            </span>
                        </td>
                        <td class="px-4 py-3 text-right font-semibold text-[#333]">
                            <?= formatVnd($order['total_amount']) ?></td>
                        <td class="px-4 py-3 text-right">
                            <a href="<?= BASE_URL ?>?act=order-detail&code=<?= urlencode($order['order_code']) ?>"
                                class="text-[#4CAF50] hover:text-[#43A047] font-medium">Xem chi tiết</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>

                    <?php if ($totalOrders < 4): ?>
                    <tr class="border-t border-gray-100">
                        <td colspan="7" class="px-4 py-6 text-sm text-gray-500">
                            Bạn có thể tiếp tục mua sắm để nhận thêm ưu đãi cho các đơn tiếp theo.
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
    <?php endif; ?>
</div>

<?php require_once './views/components/customer_footer.php'; ?>