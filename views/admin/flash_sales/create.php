<?php
$validationErrors = $_SESSION['validation_errors'] ?? [];
$old = $_SESSION['old'] ?? [];

unset($_SESSION['validation_errors']);
unset($_SESSION['old']);

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
                <h1 class="text-2xl font-bold text-gray-900">Tạo Flash Sale mới</h1>
                <p class="text-sm text-gray-500 mt-1">Tạo chương trình khuyến mãi chớp nhoáng</p>
            </div>
        </div>

        <!-- Form Box -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <form method="POST" action="<?= BASE_URL ?>?act=admin-flash-sales-store" class="p-6 space-y-6">
                
                <!-- Tên Flash Sale -->
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Tên Flash Sale <span class="text-red-500">*</span></label>
                    <input type="text" id="name" name="name" 
                           value="<?= htmlspecialchars($old['name'] ?? '') ?>"
                           placeholder="Nhập tên flash sale (ví dụ: Flash Sale Thứ 6)" 
                           class="w-full px-4 py-2 border <?= isset($validationErrors['name']) ? 'border-red-500 focus:ring-red-500/20 focus:border-red-500' : 'border-gray-200 focus:ring-[#FF6B35]/20 focus:border-[#FF6B35]' ?> rounded-xl focus:outline-none focus:ring-2 transition-colors"
                           required>
                    <?php if (isset($validationErrors['name'])): ?>
                        <p class="mt-2 text-sm text-red-500 flex items-center gap-1"><i data-lucide="alert-circle" class="w-4 h-4"></i> <?= $validationErrors['name'] ?></p>
                    <?php endif; ?>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Thời gian bắt đầu -->
                    <div>
                        <label for="start_time" class="block text-sm font-medium text-gray-700 mb-2">Thời gian bắt đầu <span class="text-red-500">*</span></label>
                        <input type="datetime-local" id="start_time" name="start_time" 
                               value="<?= htmlspecialchars($old['start_time'] ?? '') ?>"
                               class="w-full px-4 py-2 border <?= isset($validationErrors['start_time']) ? 'border-red-500 focus:ring-red-500/20 focus:border-red-500' : 'border-gray-200 focus:ring-[#FF6B35]/20 focus:border-[#FF6B35]' ?> rounded-xl focus:outline-none focus:ring-2 transition-colors"
                               required>
                        <?php if (isset($validationErrors['start_time'])): ?>
                            <p class="mt-2 text-sm text-red-500 flex items-center gap-1"><i data-lucide="alert-circle" class="w-4 h-4"></i> <?= $validationErrors['start_time'] ?></p>
                        <?php endif; ?>
                    </div>

                    <!-- Thời gian kết thúc -->
                    <div>
                        <label for="end_time" class="block text-sm font-medium text-gray-700 mb-2">Thời gian kết thúc <span class="text-red-500">*</span></label>
                        <input type="datetime-local" id="end_time" name="end_time" 
                               value="<?= htmlspecialchars($old['end_time'] ?? '') ?>"
                               class="w-full px-4 py-2 border <?= isset($validationErrors['end_time']) ? 'border-red-500 focus:ring-red-500/20 focus:border-red-500' : 'border-gray-200 focus:ring-[#FF6B35]/20 focus:border-[#FF6B35]' ?> rounded-xl focus:outline-none focus:ring-2 transition-colors"
                               required>
                        <?php if (isset($validationErrors['end_time'])): ?>
                            <p class="mt-2 text-sm text-red-500 flex items-center gap-1"><i data-lucide="alert-circle" class="w-4 h-4"></i> <?= $validationErrors['end_time'] ?></p>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Trạng thái -->
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Trạng thái hiển thị <span class="text-red-500">*</span></label>
                    <select id="status" name="status" class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#FF6B35]/20 focus:border-[#FF6B35] transition-colors" required>
                        <option value="1" <?= ($old['status'] ?? 1) == 1 ? 'selected' : '' ?>>Hoạt động</option>
                        <option value="0" <?= ($old['status'] ?? 1) == 0 ? 'selected' : '' ?>>Khóa</option>
                    </select>
                </div>

                <!-- Actions -->
                <div class="pt-6 border-t border-gray-100 flex items-center justify-end gap-3">
                    <a href="<?= BASE_URL ?>?act=admin-flash-sales" class="px-6 py-2.5 text-gray-600 bg-gray-100 hover:bg-gray-200 rounded-xl font-medium transition-colors">
                        Hủy bỏ
                    </a>
                    <button type="submit" class="px-6 py-2.5 bg-[#FF6B35] text-white rounded-xl hover:bg-[#E55A24] font-medium flex items-center gap-2 transition-colors">
                        <i data-lucide="plus-circle" class="w-4 h-4"></i> Tạo Flash Sale
                    </button>
                </div>

            </form>
        </div>
    </div>
</main>

<?php include_once './views/components/footer.php'; ?>