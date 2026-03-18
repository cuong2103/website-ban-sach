<?php
$validationErrors = $_SESSION['validation_errors'] ?? [];
$old = $_SESSION['old'] ?? [];

unset($_SESSION['validation_errors']);
unset($_SESSION['old']);

// Nếu không có old data, lấy từ $category
if (empty($old)) {
    $old = [
        'name' => $category['name'] ?? '',
        'slug' => $category['slug'] ?? '',
        'description' => $category['description'] ?? '',
        'status' => $category['status'] ?? 1
    ];
}

include_once './views/components/header.php';
include_once './views/components/sidebar.php';
?>

<main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-50 p-6">
    <div class="w-full">
              
        <!-- Header -->
        <div class="flex items-center gap-4 mb-6">
            <a href="<?= BASE_URL ?>?act=admin-categories" class="p-2 bg-white text-gray-500 rounded-xl hover:bg-gray-50 border border-gray-200 transition-colors">
                <i data-lucide="arrow-left" class="w-5 h-5"></i>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Sửa danh mục</h1>
                <p class="text-sm text-gray-500 mt-1">Cập nhật thông tin chi tiết của danh mục</p>
            </div>
        </div>

        <!-- Form Box -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <form method="POST" action="<?= BASE_URL ?>?act=admin-categories-update" class="p-6 space-y-6">
                
                <input type="hidden" name="id" value="<?= $category['id'] ?>">

                <!-- Tên danh mục -->
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Tên danh mục <span class="text-red-500">*</span></label>
                    <input type="text" id="name" name="name" 
                           value="<?= htmlspecialchars($old['name'] ?? '') ?>"
                           placeholder="Nhập tên danh mục (vd: Tiểu thuyết, Sách khoa học...)" 
                           class="w-full px-4 py-2 border <?= isset($validationErrors['name']) ? 'border-red-500 focus:ring-red-500/20 focus:border-red-500' : 'border-gray-200 focus:ring-[#4CAF50]/20 focus:border-[#4CAF50]' ?> rounded-xl focus:outline-none focus:ring-2 transition-colors"
                           required>
                    <?php if (isset($validationErrors['name'])): ?>
                        <p class="mt-2 text-sm text-red-500 flex items-center gap-1"><i data-lucide="alert-circle" class="w-4 h-4"></i> <?= $validationErrors['name'] ?></p>
                    <?php endif; ?>
                </div>

                <!-- Slug -->
                <div>
                    <label for="slug" class="block text-sm font-medium text-gray-700 mb-2">Đường dẫn tĩnh (Slug) <span class="text-red-500">*</span></label>
                    <input type="text" id="slug" name="slug" 
                           value="<?= htmlspecialchars($old['slug'] ?? '') ?>"
                           placeholder="vd: tieu-thuyet" 
                           class="w-full px-4 py-2 border <?= isset($validationErrors['slug']) ? 'border-red-500 focus:ring-red-500/20 focus:border-red-500' : 'border-gray-200 focus:ring-[#4CAF50]/20 focus:border-[#4CAF50]' ?> bg-gray-50 rounded-xl focus:outline-none focus:ring-2 focus:bg-white transition-colors"
                           required>
                    <?php if (isset($validationErrors['slug'])): ?>
                        <p class="mt-2 text-sm text-red-500 flex items-center gap-1"><i data-lucide="alert-circle" class="w-4 h-4"></i> <?= $validationErrors['slug'] ?></p>
                    <?php else: ?>
                        <p class="mt-2 text-xs text-gray-500">Slug được sử dụng trên URL, chỉ chứa chữ thường, số và gạch ngang.</p>
                    <?php endif; ?>
                </div>

                <!-- Trạng thái -->
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Trạng thái hiển thị <span class="text-red-500">*</span></label>
                    <select id="status" name="status" class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#4CAF50]/20 focus:border-[#4CAF50] transition-colors" required>
                        <option value="1" <?= ($old['status'] ?? 1) == 1 ? 'selected' : '' ?>>Hoạt động</option>
                        <option value="0" <?= ($old['status'] ?? 1) == 0 ? 'selected' : '' ?>>Khóa</option>
                    </select>
                </div>

                <!-- Mô tả -->
                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Mô tả danh mục</label>
                    <textarea id="description" name="description" rows="4"
                              placeholder="Nhập nội dung mô tả..."
                              class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#4CAF50]/20 focus:border-[#4CAF50] transition-colors resize-y"><?= htmlspecialchars($old['description'] ?? '') ?></textarea>
                    <p class="mt-2 text-xs text-gray-500">Không bắt buộc.</p>
                </div>

                <!-- Actions -->
                <div class="pt-6 border-t border-gray-100 flex items-center justify-end gap-3">
                    <a href="<?= BASE_URL ?>?act=admin-categories" class="px-6 py-2.5 text-gray-600 bg-gray-100 hover:bg-gray-200 rounded-xl font-medium transition-colors">
                        Hủy bỏ
                    </a>
                    <button type="submit" class="px-6 py-2.5 bg-[#4CAF50] text-white rounded-xl hover:bg-green-600 font-medium flex items-center gap-2 transition-colors">
                        <i data-lucide="save" class="w-4 h-4"></i> Lưu thay đổi
                    </button>
                </div>

            </form>
        </div>
    </div>
</main>

<script>
    // Tự động generate slug từ tên (nếu người dùng xóa slug hiện tại và gõ lại tên)
    document.getElementById('name').addEventListener('blur', function () {
        const name = this.value.trim();
        if (name && !document.getElementById('slug').value) {
            const slug = name
                .toLowerCase()
                .normalize('NFD')
                .replace(/[\u0300-\u036f]/g, '')
                .replace(/đ/g, 'd').replace(/Đ/g, 'D')
                .replace(/[^a-z0-9]+/g, '-')
                .replace(/^-+|-+$/g, '');
            document.getElementById('slug').value = slug;
        }
    });
</script>

<?php include_once './views/components/footer.php'; ?>