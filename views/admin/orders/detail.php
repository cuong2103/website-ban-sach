<?php 
include_once './views/components/header.php';
include_once './views/components/sidebar.php';
?>

<main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-50 p-6">
    <div class="max-w-4xl mx-auto">
              
              <!-- Header -->
              <div class="flex items-center gap-4 mb-6">
                  <a href="<?= BASE_URL ?>?act=admin-orders" class="p-2 bg-white text-gray-500 rounded-xl hover:bg-gray-50 border border-gray-200 transition-colors">
                      <i data-lucide="arrow-left" class="w-5 h-5"></i>
                  </a>
                  <div>
                      <h1 class="text-2xl font-bold text-gray-900">Chi tiết đơn hàng #<?= htmlspecialchars($order['order_code']) ?></h1>
                      <p class="text-sm text-gray-500 mt-1">Đặt lúc: <?= date('d/m/Y H:i', strtotime($order['created_at'])) ?></p>
                  </div>
              </div>

              

              <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                  <!-- Cột trái: Thông tin khách + Sản phẩm -->
                  <div class="lg:col-span-2 space-y-6">
                      
                      <!-- Box khách hàng -->
                      <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
                          <h2 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                              <i data-lucide="user" class="w-5 h-5 text-[#4CAF50]"></i>
                              Thông tin khách hàng
                          </h2>
                          <div class="grid grid-cols-1 md:grid-cols-2 gap-y-4 gap-x-6 text-sm">
                              <div>
                                  <span class="text-gray-500 block mb-1">Họ tên</span>
                                  <span class="font-medium text-gray-900"><?= htmlspecialchars($order['customer_name']) ?></span>
                              </div>
                              <div>
                                  <span class="text-gray-500 block mb-1">Số điện thoại</span>
                                  <span class="font-medium text-gray-900"><?= htmlspecialchars($order['phone']) ?></span>
                              </div>
                              <div>
                                  <span class="text-gray-500 block mb-1">Email</span>
                                  <span class="font-medium text-gray-900"><?= htmlspecialchars($order['customer_email']) ?></span>
                              </div>
                              <div>
                                  <span class="text-gray-500 block mb-1">Phương thức TT</span>
                                  <span class="font-medium text-gray-900"><?= htmlspecialchars($order['payment_method']) ?></span>
                              </div>
                              <div class="md:col-span-2">
                                  <span class="text-gray-500 block mb-1">Địa chỉ giao hàng</span>
                                  <span class="font-medium text-gray-900"><?= htmlspecialchars($order['shipping_address']) ?></span>
                              </div>
                              <?php if (!empty($order['note'])): ?>
                              <div class="md:col-span-2">
                                  <span class="text-gray-500 block mb-1">Ghi chú của khách</span>
                                  <p class="font-medium text-gray-900 bg-orange-50 p-3 rounded-xl border border-orange-100"><?= nl2br(htmlspecialchars($order['note'])) ?></p>
                              </div>
                              <?php endif; ?>
                          </div>
                      </div>

                      <!-- Box sản phẩm -->
                      <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
                          <h2 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                              <i data-lucide="package" class="w-5 h-5 text-[#4CAF50]"></i>
                              Sản phẩm đã đặt
                          </h2>
                          <div class="space-y-4 divide-y divide-gray-100">
                              <?php foreach ($items as $item): ?>
                                  <div class="flex gap-4 pt-4 first:pt-0">
                                      <div class="w-20 h-28 shrink-0 bg-gray-50 rounded-xl overflow-hidden border border-gray-100">
                                          <?php if (!empty($item['thumbnail'])): ?>
                                              <img src="<?= BASE_URL . $item['thumbnail'] ?>" alt="<?= htmlspecialchars($item['title']) ?>" class="w-full h-full object-cover">
                                          <?php else: ?>
                                              <div class="w-full h-full flex items-center justify-center text-gray-400">
                                                  <i data-lucide="image" class="w-6 h-6"></i>
                                              </div>
                                          <?php endif; ?>
                                      </div>
                                      <div class="flex-1 min-w-0">
                                          <h3 class="font-medium text-gray-900 line-clamp-2"><?= htmlspecialchars($item['title']) ?></h3>
                                          <div class="mt-2 text-sm text-gray-500">
                                              Số lượng: <span class="font-medium text-gray-900"><?= $item['quantity'] ?></span> 
                                              <span class="mx-2">x</span> 
                                              Đơn giá: <span class="font-medium text-[#4CAF50]"><?= number_format($item['price'], 0, ',', '.') ?>đ</span>
                                          </div>
                                      </div>
                                      <div class="text-right shrink-0">
                                          <div class="font-bold text-[#4CAF50]"><?= number_format($item['subtotal'], 0, ',', '.') ?>đ</div>
                                      </div>
                                  </div>
                              <?php endforeach; ?>
                          </div>
                      </div>

                  </div>

                  <!-- Cột phải: Trạng thái + Tổng kết -->
                  <div class="space-y-6">
                      
                      <!-- Box cập nhật trạng thái -->
                      <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
                          <h2 class="text-lg font-bold text-gray-900 mb-4">Trạng thái đơn hàng</h2>
                          <form action="<?= BASE_URL ?>?act=admin-order-update-status" method="POST">
                              <input type="hidden" name="order_id" value="<?= $order['order_id'] ?>">
                              
                              <div class="mb-4">
                                  <select name="status_id" class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#4CAF50]/20 focus:border-[#4CAF50]">
                                      <?php foreach ($statuses as $st): ?>
                                          <option value="<?= $st['status_id'] ?>" <?= $st['status_id'] == $order['status_id'] ? 'selected' : '' ?>>
                                              <?= htmlspecialchars($st['status_name']) ?>
                                          </option>
                                      <?php endforeach; ?>
                                  </select>
                              </div>

                              <?php 
                              // Nếu trạng thái đã là Completed(4) hoặc Cancelled(5) thì vô hiệu hóa nút update
                              $isFinal = in_array((int)$order['status_id'], [4, 5]); 
                              ?>
                              <button type="submit" 
                                      class="w-full py-2.5 rounded-xl font-medium flex items-center justify-center gap-2 <?= $isFinal ? 'bg-gray-100 text-gray-400 cursor-not-allowed' : 'bg-[#1B2537] text-white hover:bg-gray-800 transition-colors' ?>"
                                      <?= $isFinal ? 'disabled' : '' ?>>
                                  <i data-lucide="save" class="w-4 h-4"></i>
                                  Cập nhật trạng thái
                              </button>

                              <?php if ($isFinal): ?>
                                  <p class="text-xs text-center text-red-500 mt-3 font-medium">Đơn hàng ở trạng thái cuối cùng, không thể cập nhật thêm.</p>
                              <?php endif; ?>
                              <?php if ((int)$order['status_id'] === 5): ?>
                                  <p class="text-xs justify-center italic text-red-500 mt-2">Sản phẩm bị hủy đã được hoàn lại kho.</p>
                              <?php endif; ?>
                          </form>
                      </div>

                      <!-- Box tổng tiền -->
                      <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
                          <h2 class="text-lg font-bold text-gray-900 mb-4">Tổng cộng</h2>
                          <div class="space-y-3 text-sm">
                              <div class="flex justify-between text-gray-600">
                                  <span>Tạm tính</span>
                                  <?php $subtotal = $order['total_amount'] + $order['discount_amount']; ?>
                                  <span class="font-medium text-gray-900"><?= number_format($subtotal, 0, ',', '.') ?>đ</span>
                              </div>
                              <div class="flex justify-between text-green-600">
                                  <span>Giảm giá (Voucher)</span>
                                  <span class="font-medium">- <?= number_format($order['discount_amount'], 0, ',', '.') ?>đ</span>
                              </div>
                              <div class="flex justify-between text-gray-600">
                                  <span>Phí vận chuyển</span>
                                  <span class="font-medium">Miễn phí</span>
                              </div>
                              
                              <div class="pt-4 border-t border-dashed border-gray-200">
                                  <div class="flex justify-between items-center">
                                      <span class="font-bold text-gray-900">Tổng thanh toán</span>
                                      <span class="text-xl font-bold text-[#4CAF50]"><?= number_format($order['total_amount'], 0, ',', '.') ?>đ</span>
                                  </div>
                              </div>
                          </div>
                      </div>

                  </div>
              </div>

        </div>
    </main>
<?php include_once './views/components/footer.php'; ?>
