<?php
$currentPage = $page;

$validationErrors = $_SESSION['validation_errors'] ?? [];
unset($_SESSION['validation_errors']);

include_once './views/components/header.php';
include_once './views/components/sidebar.php';
?>

<main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-50 p-6">
    <div class="w-full">
        
        <div class="flex flex-wrap justify-between items-center mb-6 gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Quản lý Danh mục</h1>
                <p class="text-sm text-gray-500 mt-1">Danh sách tất cả danh mục sách</p>
            </div>
            <a href="<?= BASE_URL ?>?act=admin-categories-create" class="px-5 py-2.5 bg-[#4CAF50] text-white rounded-xl hover:bg-green-600 transition-colors font-medium flex items-center gap-2 shadow-sm">
                <i data-lucide="plus" class="w-4 h-4"></i> Thêm danh mục
            </a>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            
            <div class="p-4 border-b border-gray-100 bg-gray-50/50">
                <form action="" method="GET" class="flex flex-wrap gap-4 items-center justify-between">
                    <input type="hidden" name="act" value="admin-categories">
                    
                    <div class="flex flex-1 min-w-[300px] gap-4">
                        <div class="relative flex-1 max-w-md">
                            <i data-lucide="search" class="w-5 h-5 absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                            <input type="text" 
                                   name="search" 
                                   value="<?= htmlspecialchars($_GET['search'] ?? '') ?>"
                                   placeholder="Tìm theo tên hoặc slug..." 
                                   class="w-full pl-10 pr-4 py-2 bg-white border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#4CAF50]/20 focus:border-[#4CAF50] transition-colors">
                        </div>

                        <button type="submit" class="px-6 py-2 bg-gray-900 text-white rounded-xl hover:bg-gray-800 transition-colors font-medium">
                            Lọc
                        </button>
                        
                        <?php if (!empty($_GET['search'])): ?>
                            <a href="<?= BASE_URL ?>?act=admin-categories" class="px-4 py-2 bg-gray-100 text-gray-600 hover:bg-gray-200 rounded-xl transition-colors font-medium flex items-center gap-2">
                                <i data-lucide="x" class="w-4 h-4"></i> Xóa lọc
                            </a>
                        <?php endif; ?>
                    </div>
                </form>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50/50 border-b border-gray-100">
                            <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">ID</th>
                            <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Tên danh mục</th>
                            <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Slug</th>
                            <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Mô tả</th>
                            <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Trạng thái</th>
                            <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Ngày tạo</th>
                            <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider text-right">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        <?php if (empty($categories)): ?>
                            <tr>
                                <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                                    <div class="flex flex-col items-center justify-center gap-2">
                                        <i data-lucide="folder-open" class="w-8 h-8 text-gray-300"></i>
                                        <p>Không có danh mục nào.</p>
                                    </div>
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($categories as $category): ?>
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 text-sm text-gray-500">
                                    #<?= $category['id'] ?>
                                </td>
                                <td class="px-6 py-4 font-medium text-gray-900">
                                    <?= htmlspecialchars($category['name']) ?>
                                </td>
                                <td class="px-6 py-4 text-sm">
                                    <span class="px-2.5 py-1 bg-gray-100 text-gray-600 rounded-lg font-mono text-xs"><?= htmlspecialchars($category['slug']) ?></span>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-500 max-w-[200px] truncate">
                                    <?= htmlspecialchars(substr($category['description'] ?? '', 0, 50)) ?><?= strlen($category['description'] ?? '') > 50 ? '...' : '' ?>
                                </td>
                                <td class="px-6 py-4">
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
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-500">
                                    <?= date('d/m/Y H:i', strtotime($category['created_at'])) ?>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="<?= BASE_URL ?>?act=admin-categories-edit&id=<?= $category['id'] ?>" 
                                           class="p-2 text-blue-500 hover:bg-blue-50 rounded-lg transition-colors"
                                           title="Sửa">
                                            <i data-lucide="edit-2" class="w-4 h-4"></i>
                                        </a>
                                        <a href="<?= BASE_URL ?>?act=admin-categories-delete&id=<?= $category['id'] ?>" 
                                           onclick="return confirm('Bạn chắc chắn muốn xóa danh mục này?');"
                                           class="p-2 text-red-500 hover:bg-red-50 rounded-lg transition-colors"
                                           title="Xóa">
                                            <i data-lucide="trash-2" class="w-4 h-4"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <?php if (!empty($totalPages) && $totalPages > 1): ?>
            <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/50 flex flex-wrap items-center justify-between gap-4">
                <div class="text-sm text-gray-500">
                    Trang <span class="font-medium text-gray-900"><?= $currentPage ?></span> / <?= $totalPages ?>
                </div>
                
                <div class="flex gap-1">
                    <?php
                    $baseUrl = BASE_URL . "?act=admin-categories";
                    if (!empty($_GET['search'])) $baseUrl .= "&search=" . urlencode($_GET['search']);
                    ?>
                    
                    <?php if ($currentPage > 1): ?>
                        <a href="<?= $baseUrl ?>&page=1" class="px-3 py-1.5 border border-gray-200 rounded-lg bg-white text-gray-600 hover:bg-gray-50 hidden sm:block">Đầu</a>
                        <a href="<?= $baseUrl ?>&page=<?= $currentPage - 1 ?>" class="px-3 py-1.5 border border-gray-200 rounded-lg bg-white text-gray-600 hover:bg-gray-50 flex items-center">
                            <i data-lucide="chevron-left" class="w-4 h-4"></i>
                        </a>
                    <?php endif; ?>

                    <?php
                    $start = max(1, $currentPage - 2);
                    $end = min($totalPages, $currentPage + 2);
                    for ($i = $start; $i <= $end; $i++): 
                    ?>
                        <a href="<?= $baseUrl ?>&page=<?= $i ?>" 
                           class="px-3 py-1.5 border rounded-lg <?= $i == $currentPage ? 'bg-[#4CAF50] border-[#4CAF50] text-white' : 'border-gray-200 bg-white text-gray-600 hover:bg-gray-50' ?>">
                            <?= $i ?>
                        </a>
                    <?php endfor; ?>

                    <?php if ($currentPage < $totalPages): ?>
                        <a href="<?= $baseUrl ?>&page=<?= $currentPage + 1 ?>" class="px-3 py-1.5 border border-gray-200 rounded-lg bg-white text-gray-600 hover:bg-gray-50 flex items-center">
                            <i data-lucide="chevron-right" class="w-4 h-4"></i>
                        </a>
                        <a href="<?= $baseUrl ?>&page=<?= $totalPages ?>" class="px-3 py-1.5 border border-gray-200 rounded-lg bg-white text-gray-600 hover:bg-gray-50 hidden sm:block">Cuối</a>
                    <?php endif; ?>
                </div>
            </div>
            <?php endif; ?>

        </div>
    </div>
</main>
<?php include_once './views/components/footer.php'; ?>