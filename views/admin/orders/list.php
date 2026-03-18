<?php 
include_once './views/components/header.php';
include_once './views/components/sidebar.php';
?>

<main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-50 p-6">
    <div class="w-full">
              
              <div class="flex justify-between items-center mb-6">
                  <div>
                      <h1 class="text-2xl font-bold text-gray-900">Quản lý Đơn hàng</h1>
                      <p class="text-sm text-gray-500 mt-1">Danh sách tất cả đơn hàng từ khách hàng</p>
                  </div>
              </div>

              

              <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                  
                  <div class="p-4 border-b border-gray-100 bg-gray-50/50">
                      <form action="" method="GET" class="flex flex-wrap gap-4 items-center justify-between">
                          <input type="hidden" name="act" value="admin-orders">
                          
                          <div class="flex flex-1 min-w-[300px] gap-4">
                              <div class="relative flex-1 max-w-md">
                                  <i data-lucide="search" class="w-5 h-5 absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                                  <input type="text" 
                                         name="search" 
                                         value="<?= htmlspecialchars($_GET['search'] ?? '') ?>"
                                         placeholder="Tìm theo mã đơn hoặc tên khách..." 
                                         class="w-full pl-10 pr-4 py-2 bg-white border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#4CAF50]/20 focus:border-[#4CAF50] transition-colors">
                              </div>

                              <select name="status_id" class="px-4 py-2 bg-white border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#4CAF50]/20 focus:border-[#4CAF50]">
                                  <option value="">-- Tất cả trạng thái --</option>
                                  <?php foreach ($statuses as $st): ?>
                                      <option value="<?= $st['status_id'] ?>" <?= (isset($_GET['status_id']) && $_GET['status_id'] == $st['status_id']) ? 'selected' : '' ?>>
                                          <?= htmlspecialchars($st['status_name']) ?>
                                      </option>
                                  <?php endforeach; ?>
                              </select>
                              
                              <button type="submit" class="px-6 py-2 bg-[#1B2537] text-white rounded-xl hover:bg-gray-800 transition-colors font-medium">
                                  Lọc
                              </button>
                          </div>
                      </form>
                  </div>

                  <div class="overflow-x-auto">
                      <table class="w-full text-left border-collapse">
                          <thead>
                              <tr class="bg-gray-50/50 border-b border-gray-100">
                                  <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Mã đơn</th>
                                  <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Khách hàng</th>
                                  <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Ngày đặt</th>
                                  <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Tổng tiền</th>
                                  <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Trạng thái</th>
                                  <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider text-right">Thao tác</th>
                              </tr>
                          </thead>
                          <tbody class="divide-y divide-gray-100">
                              <?php if (empty($orders)): ?>
                                  <tr>
                                      <td colspan="6" class="px-6 py-8 text-center text-gray-500">
                                          Không tìm thấy đơn hàng nào
                                      </td>
                                  </tr>
                              <?php else: ?>
                                  <?php foreach ($orders as $order): ?>
                                  <tr class="hover:bg-gray-50 transition-colors">
                                      <td class="px-6 py-4">
                                          <span class="font-medium text-gray-900"><?= htmlspecialchars($order['order_code']) ?></span>
                                      </td>
                                      <td class="px-6 py-4">
                                          <div class="text-sm font-medium text-gray-900"><?= htmlspecialchars($order['customer_name']) ?></div>
                                      </td>
                                      <td class="px-6 py-4 text-sm text-gray-500">
                                          <?= date('d/m/Y H:i', strtotime($order['created_at'])) ?>
                                      </td>
                                      <td class="px-6 py-4">
                                          <div class="font-medium text-[#4CAF50]"><?= number_format($order['total_amount'], 0, ',', '.') ?>đ</div>
                                      </td>
                                      <td class="px-6 py-4">
                                          <?php
                                              $statusColors = [
                                                  1 => 'bg-yellow-100 text-yellow-800', // Pending
                                                  2 => 'bg-blue-100 text-blue-800',     // Confirmed
                                                  3 => 'bg-indigo-100 text-indigo-800',  // Shipping
                                                  4 => 'bg-green-100 text-green-800',   // Completed
                                                  5 => 'bg-red-100 text-red-800'        // Cancelled
                                              ];
                                              $colorClass = $statusColors[$order['status_id']] ?? 'bg-gray-100 text-gray-800';
                                          ?>
                                          <span class="px-3 py-1 text-xs font-medium rounded-full <?= $colorClass ?>">
                                              <?= htmlspecialchars($order['status_name']) ?>
                                          </span>
                                      </td>
                                      <td class="px-6 py-4 text-right">
                                          <div class="flex items-center justify-end gap-2">
                                              <a href="<?= BASE_URL ?>?act=admin-order-detail&id=<?= $order['order_id'] ?>" 
                                                 class="p-2 text-blue-500 hover:bg-blue-50 rounded-lg transition-colors"
                                                 title="Xem chi tiết">
                                                  <i data-lucide="eye" class="w-4 h-4"></i>
                                              </a>
                                          </div>
                                      </td>
                                  </tr>
                                  <?php endforeach; ?>
                              <?php endif; ?>
                          </tbody>
                      </table>
                  </div>

                  <?php if ($totalPages > 1): ?>
                  <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/50 flex items-center justify-between">
                      <div class="text-sm text-gray-500">
                          Hiển thị trang <span class="font-medium text-gray-900"><?= $page ?></span> / <span class="font-medium text-gray-900"><?= $totalPages ?></span>
                      </div>
                      
                      <div class="flex gap-1">
                          <?php
                          $baseUrl = BASE_URL . "?act=admin-orders";
                          if (!empty($search)) $baseUrl .= "&search=" . urlencode($search);
                          if (!empty($statusId)) $baseUrl .= "&status_id=" . urlencode($statusId);
                          ?>
                          
                          <?php if ($page > 1): ?>
                              <a href="<?= $baseUrl ?>&page=<?= $page - 1 ?>" class="px-3 py-1.5 border border-gray-200 rounded-lg bg-white text-gray-600 hover:bg-gray-50 flex items-center">
                                  <i data-lucide="chevron-left" class="w-4 h-4"></i>
                              </a>
                          <?php endif; ?>

                          <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                              <a href="<?= $baseUrl ?>&page=<?= $i ?>" 
                                 class="px-3 py-1.5 border rounded-lg <?= $i === $page ? 'bg-[#4CAF50] border-[#4CAF50] text-white' : 'border-gray-200 bg-white text-gray-600 hover:bg-gray-50' ?>">
                                  <?= $i ?>
                              </a>
                          <?php endfor; ?>

                          <?php if ($page < $totalPages): ?>
                              <a href="<?= $baseUrl ?>&page=<?= $page + 1 ?>" class="px-3 py-1.5 border border-gray-200 rounded-lg bg-white text-gray-600 hover:bg-gray-50 flex items-center">
                                  <i data-lucide="chevron-right" class="w-4 h-4"></i>
                              </a>
                          <?php endif; ?>
                      </div>
                  </div>
                  <?php endif; ?>

              </div>
        </div>
    </main>
<?php include_once './views/components/footer.php'; ?>
