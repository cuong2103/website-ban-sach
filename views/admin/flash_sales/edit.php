<?php
$validationErrors = $_SESSION['validation_errors'] ?? [];
$old = $_SESSION['old'] ?? [];

unset($_SESSION['validation_errors']);
unset($_SESSION['old']);

// Nếu không có old data, lấy từ $flashSale
if (empty($old)) {
    $old = [
        'name' => $flashSale['name'] ?? '',
        'start_time' => $flashSale['start_time'] ?? '',
        'end_time' => $flashSale['end_time'] ?? '',
        'status' => $flashSale['status'] ?? 1
    ];
}

$successMessage = Message::get('success');
$errorMessage = Message::get('error');

include_once './views/components/header.php';
include_once './views/components/sidebar.php';
?>

<main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-50 p-6">
    <div class="w-full">
              
        <!-- Header -->
        <div class="flex items-center gap-4 mb-6">
            <a href="<?= BASE_URL ?>?act=admin-flash-sales" class="p-2 bg-white text-gray-500 rounded-xl hover:bg-gray-50 border border-gray-200 transition-colors">
                <i data-lucide="arrow-left" class="w-5 h-5"></i>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Chi tiết Flash Sale #<?= $flashSale['id'] ?></h1>
                <p class="text-sm text-gray-500 mt-1">Quản lý và cập nhật thông tin chương trình khuyến mãi chớp nhoáng</p>
            </div>
        </div>

        <?php if ($successMessage): ?>
            <div class="mb-6 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl flex items-center gap-2">
                <i data-lucide="check-circle" class="w-5 h-5 text-green-500"></i>
                <?php echo $successMessage; ?>
            </div>
        <?php endif; ?>

        <?php if ($errorMessage): ?>
            <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl flex items-center gap-2">
                <i data-lucide="alert-circle" class="w-5 h-5 text-red-500"></i>
                <?php echo $errorMessage; ?>
            </div>
        <?php endif; ?>

        <div class="grid grid-cols-1 xl:grid-cols-2 gap-6">
            <!-- Thông tin Flash Sale -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 bg-white">
                    <h2 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                        <i data-lucide="info" class="w-5 h-5 text-[#FF6B35]"></i>
                        Thông tin chương trình
                    </h2>
                </div>
                <form method="POST" action="<?php echo BASE_URL; ?>admin-flash-sales-update" class="p-6 space-y-6">
                    <input type="hidden" name="id" value="<?php echo $flashSale['id']; ?>">

                    <!-- Tên flash sale -->
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Tên Flash Sale <span class="text-red-500">*</span></label>
                        <input type="text" id="name" name="name" 
                               value="<?php echo htmlspecialchars($old['name']); ?>"
                               placeholder="Nhập tên flash sale" 
                               class="w-full px-4 py-2 border <?= isset($validationErrors['name']) ? 'border-red-500 focus:ring-red-500/20 focus:border-red-500' : 'border-gray-200 focus:ring-[#FF6B35]/20 focus:border-[#FF6B35]' ?> rounded-xl focus:outline-none focus:ring-2 transition-colors"
                               required>
                        <?php if (isset($validationErrors['name'])): ?>
                            <p class="mt-2 text-sm text-red-500 flex items-center gap-1"><i data-lucide="alert-circle" class="w-4 h-4"></i> <?php echo $validationErrors['name']; ?></p>
                        <?php endif; ?>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Thời gian bắt đầu -->
                        <div>
                            <label for="start_time" class="block text-sm font-medium text-gray-700 mb-2">Thời gian bắt đầu <span class="text-red-500">*</span></label>
                            <input type="datetime-local" id="start_time" name="start_time"
                                   value="<?php echo str_replace(' ', 'T', $old['start_time']); ?>" 
                                   class="w-full px-4 py-2 border <?= isset($validationErrors['start_time']) ? 'border-red-500 focus:ring-red-500/20 focus:border-red-500' : 'border-gray-200 focus:ring-[#FF6B35]/20 focus:border-[#FF6B35]' ?> rounded-xl focus:outline-none focus:ring-2 transition-colors"
                                   required>
                            <?php if (isset($validationErrors['start_time'])): ?>
                                <p class="mt-2 text-sm text-red-500 flex items-center gap-1"><i data-lucide="alert-circle" class="w-4 h-4"></i> <?php echo $validationErrors['start_time']; ?></p>
                            <?php endif; ?>
                        </div>

                        <!-- Thời gian kết thúc -->
                        <div>
                            <label for="end_time" class="block text-sm font-medium text-gray-700 mb-2">Thời gian kết thúc <span class="text-red-500">*</span></label>
                            <input type="datetime-local" id="end_time" name="end_time"
                                   value="<?php echo str_replace(' ', 'T', $old['end_time']); ?>" 
                                   class="w-full px-4 py-2 border <?= isset($validationErrors['end_time']) ? 'border-red-500 focus:ring-red-500/20 focus:border-red-500' : 'border-gray-200 focus:ring-[#FF6B35]/20 focus:border-[#FF6B35]' ?> rounded-xl focus:outline-none focus:ring-2 transition-colors"
                                   required>
                            <?php if (isset($validationErrors['end_time'])): ?>
                                <p class="mt-2 text-sm text-red-500 flex items-center gap-1"><i data-lucide="alert-circle" class="w-4 h-4"></i> <?php echo $validationErrors['end_time']; ?></p>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Trạng thái -->
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Trạng thái <span class="text-red-500">*</span></label>
                        <select id="status" name="status" class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#FF6B35]/20 focus:border-[#FF6B35] transition-colors" required>
                            <option value="1" <?php echo $old['status'] == 1 ? 'selected' : ''; ?>>Hoạt động</option>
                            <option value="0" <?php echo $old['status'] == 0 ? 'selected' : ''; ?>>Khóa</option>
                        </select>
                    </div>

                    <div class="pt-4 border-t border-gray-100">
                        <button type="submit" class="w-full px-6 py-2.5 bg-[#FF6B35] text-white rounded-xl hover:bg-[#E55A24] font-medium flex items-center justify-center gap-2 transition-colors">
                            <i data-lucide="save" class="w-4 h-4"></i> Cập nhật Flash Sale
                        </button>
                    </div>
                </form>
            </div>

            <div class="space-y-6">
                <!-- Thêm sách vào Flash Sale -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100 bg-white">
                        <h2 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                            <i data-lucide="plus-circle" class="w-5 h-5 text-[#FF6B35]"></i>
                            Thêm sách tham gia
                        </h2>
                    </div>
                    <form method="POST" action="<?php echo BASE_URL; ?>admin-flash-sales-add-item" class="p-6 space-y-5 bg-gray-50/50">
                        <input type="hidden" name="flash_sale_id" value="<?php echo $flashSale['id']; ?>">

                        <div>
                            <label for="book_id" class="block text-sm font-medium text-gray-700 mb-2">Chọn sách <span class="text-red-500">*</span></label>
                            <select id="book_id" name="book_id" class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#FF6B35]/20 focus:border-[#FF6B35] bg-white transition-colors" required>
                                <option value="">-- Chọn sách --</option>
                                <?php foreach ($books as $book): ?>
                                    <option value="<?php echo $book['id']; ?>">
                                        <?php echo htmlspecialchars($book['title']); ?> - <?php echo htmlspecialchars($book['author']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                            <div>
                                <label for="discount_percent" class="block text-sm font-medium text-gray-700 mb-2">Giảm giá (%) <span class="text-red-500">*</span></label>
                                <input type="number" id="discount_percent" name="discount_percent" placeholder="Nhập %" value="0" min="0" max="100" class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#FF6B35]/20 focus:border-[#FF6B35] transition-colors" required>
                                <p class="mt-1 text-xs text-gray-500">Giảm theo phần trăm</p>
                            </div>

                            <div>
                                <label for="sale_price" class="block text-sm font-medium text-gray-700 mb-2">Giá sale (₫) <span class="text-red-500">*</span></label>
                                <input type="number" id="sale_price" name="sale_price" placeholder="Nhập giá" class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#FF6B35]/20 focus:border-[#FF6B35] transition-colors" required>
                            </div>
                        </div>

                        <div>
                            <label for="stock_limit" class="block text-sm font-medium text-gray-700 mb-2">Giới hạn số lượng bán</label>
                            <input type="number" id="stock_limit" name="stock_limit" placeholder="Để trống nếu không giới hạn" value="0" class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#FF6B35]/20 focus:border-[#FF6B35] transition-colors">
                            <p class="mt-1 text-xs text-gray-500">Mặc định 0 = Không giới hạn số lượng</p>
                        </div>

                        <div class="pt-2">
                            <button type="submit" class="w-full px-4 py-2 bg-gray-800 text-white rounded-xl hover:bg-gray-900 font-medium flex items-center justify-center gap-2 transition-colors">
                                <i data-lucide="download" class="w-4 h-4"></i> Thêm vào danh sách
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Danh sách sách trong Flash Sale -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100 bg-white flex justify-between items-center">
                        <h2 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                            <i data-lucide="list" class="w-5 h-5 text-[#FF6B35]"></i>
                            Sách trong chương trình
                        </h2>
                        <span class="bg-[#FF6B35] text-white text-xs font-bold px-2.5 py-1 rounded-full"><?php echo count($items); ?> sách</span>
                    </div>

                    <?php if (empty($items)): ?>
                        <div class="p-8 text-center bg-gray-50/50">
                            <i data-lucide="inbox" class="w-12 h-12 text-gray-300 mx-auto mb-3"></i>
                            <p class="text-gray-500 text-sm">Chưa có sách nào trong flash sale này.<br>Hãy thêm sách ở ô phía trên.</p>
                        </div>
                    <?php else: ?>
                        <div class="max-h-[500px] overflow-y-auto p-4 space-y-3 bg-gray-50/50">
                            <?php foreach ($items as $item): ?>
                                <div class="bg-white p-4 border border-gray-100 rounded-xl shadow-sm flex flex-col sm:flex-row gap-4 items-start sm:items-center justify-between transition-all hover:border-[#FF6B35]/50 hover:shadow-md">
                                    <div class="flex-1 min-w-0">
                                        <h3 class="font-bold text-gray-900 truncate" title="<?php echo htmlspecialchars($item['title']); ?>"><?php echo htmlspecialchars($item['title']); ?></h3>
                                        <p class="text-xs text-gray-500 mt-1 truncate">Tác giả: <?php echo htmlspecialchars($item['author']); ?></p>
                                        <div class="flex flex-wrap items-center gap-2 mt-2">
                                            <span class="text-sm font-medium text-gray-400 line-through"><?php echo number_format($item['price']); ?>₫</span>
                                            <span class="text-sm font-bold text-[#FF6B35]"><?php echo number_format($item['sale_price']); ?>₫</span>
                                            <span class="text-[10px] font-bold bg-red-100 text-red-600 px-1.5 py-0.5 rounded ml-1">-<?php echo $item['discount_percent']; ?>%</span>
                                            <?php if ($item['stock_limit'] > 0): ?>
                                                <span class="text-xs text-blue-600 bg-blue-50 px-2 py-0.5 rounded-full ml-auto sm:ml-2">SL: <?php echo $item['stock_limit']; ?></span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    <div class="shrink-0 w-full sm:w-auto mt-2 sm:mt-0 pt-3 sm:pt-0 border-t border-gray-100 sm:border-0">
                                        <a href="<?php echo BASE_URL; ?>admin-flash-sales-remove-item?item_id=<?php echo $item['id']; ?>&flash_sale_id=<?php echo $flashSale['id']; ?>"
                                           class="w-full sm:w-auto px-3 py-1.5 bg-red-50 text-red-600 border border-red-100 hover:bg-red-500 hover:text-white rounded-lg text-sm font-medium transition-colors flex items-center justify-center gap-1.5"
                                           onclick="return confirm('Bạn chắc chắn muốn xóa sách này khỏi flash sale?');">
                                           <i data-lucide="trash-2" class="w-4 h-4"></i> Xóa
                                        </a>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

    </div>
</main>

<script>
    // Logic for auto-calculating sale price or discount percent
    document.addEventListener('DOMContentLoaded', function() {
        const bookSelect = document.getElementById('book_id');
        const discountInput = document.getElementById('discount_percent');
        const priceInput = document.getElementById('sale_price');
        
        let originalPrice = 0;

        bookSelect.addEventListener('change', function() {
            // Need book original prices array in real scenario
            // For now, assume it's retrieved or leave as manual
        });

        // Basic calculation placeholders
        discountInput.addEventListener('input', function() {
            // If we had originalPrice, calculate salePrice
            // salePrice = originalPrice * (100 - this.value) / 100
        });
        
        priceInput.addEventListener('input', function() {
            // If we had originalPrice, calculate discount Input
        });
    });
</script>

<?php include_once './views/components/footer.php'; ?>