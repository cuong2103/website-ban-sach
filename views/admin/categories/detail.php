<?php 
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
                <h1 class="text-2xl font-bold text-gray-900">Chi tiết danh mục #<?= htmlspecialchars($category['id']) ?></h1>
                <p class="text-sm text-gray-500 mt-1">Quản lý thông tin danh mục sách</p>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
            <h2 class="text-lg font-bold text-gray-900 mb-6 flex items-center gap-2">
                <i data-lucide="folder-open" class="w-5 h-5 text-[#4CAF50]"></i>
                Thông tin danh mục
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-y-6 gap-x-6 text-sm">
                
                <div>
                    <span class="text-gray-500 block mb-1">Tên danh mục</span>
                    <span class="font-medium text-gray-900 text-base"><?= htmlspecialchars($category['name']) ?></span>
                </div>

                <div>
                    <span class="text-gray-500 block mb-1">Đường dẫn tĩnh (Slug)</span>
                    <span class="font-medium text-gray-900 bg-gray-50 border border-gray-100 px-3 py-1 rounded-lg">
                        <?= htmlspecialchars($category['slug']) ?>
                    </span>
                </div>

                <div>
                    <span class="text-gray-500 block mb-1">Trạng thái</span>
                    <?php if ($category['status'] == 1): ?>
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                            <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span>
                            Hoạt động
                        </span>
                    <?php else: ?>
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                            <span class="w-1.5 h-1.5 rounded-full bg-red-500"></span>
                            Khóa
                        </span>
                    <?php endif; ?>
                </div>

                <div>
                    <span class="text-gray-500 block mb-1">Ngày cập nhật</span>
                    <span class="font-medium text-gray-900">
                        <?= isset($category['updated_at']) ? date('d/m/Y H:i', strtotime($category['updated_at'])) : 'Không có' ?>
                    </span>
                </div>

                <div class="md:col-span-2">
                    <span class="text-gray-500 block mb-2">Mô tả danh mục</span>
                    <?php if (!empty($category['description'])): ?>
                        <div class="font-medium text-gray-800 bg-gray-50/50 p-4 rounded-xl border border-gray-100 leading-relaxed min-h-[100px]">
                            <?= nl2br(htmlspecialchars($category['description'])) ?>
                        </div>
                    <?php else: ?>
                        <div class="font-medium text-gray-400 italic bg-gray-50 p-4 rounded-xl border border-gray-100">
                            Chưa có mô tả chi tiết.
                        </div>
                    <?php endif; ?>
                </div>

            </div>

            <div class="mt-8 pt-6 border-t border-gray-100 flex gap-3">
                <a href="<?= BASE_URL ?>?act=admin-categories-edit&id=<?= $category['id'] ?>" class="px-5 py-2.5 bg-[#4CAF50] text-white rounded-xl hover:bg-green-600 transition-colors font-medium flex items-center gap-2">
                    <i data-lucide="edit-2" class="w-4 h-4"></i> Cập nhật danh mục
                </a>
                <a href="<?= BASE_URL ?>?act=admin-categories-delete&id=<?= $category['id'] ?>" onclick="return confirm('Bạn có chắc chắn muốn xóa danh mục này?');" class="px-5 py-2.5 bg-white text-red-500 border border-red-200 rounded-xl hover:bg-red-50 transition-colors font-medium flex items-center gap-2">
                    <i data-lucide="trash-2" class="w-4 h-4"></i> Xóa
                </a>
            </div>
        </div>
    </div>
</main>

<?php include_once './views/components/footer.php'; ?>
