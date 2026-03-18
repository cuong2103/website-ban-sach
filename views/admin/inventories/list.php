<?php
$currentPage = $page ?? 1;

$validationErrors = $_SESSION['validation_errors'] ?? [];
unset($_SESSION['validation_errors']);

include_once './views/components/header.php';
include_once './views/components/sidebar.php';
?>

<main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-50 p-6">
    <div class="w-full">
        
        <div class="flex flex-wrap justify-between items-center mb-6 gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Quản lý Kho Hàng</h1>
                <p class="text-sm text-gray-500 mt-1">Danh sách tồn kho và nhập kho của tất cả các đầu sách</p>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            
            <div class="p-4 border-b border-gray-100 bg-gray-50/50">
                <form action="" method="GET" class="flex flex-wrap gap-4 items-center justify-between">
                    <input type="hidden" name="act" value="admin-inventories">
                    
                    <div class="flex flex-1 min-w-[300px] gap-4">
                        <div class="relative flex-1 max-w-md">
                            <i data-lucide="search" class="w-5 h-5 absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                            <input type="text" 
                                   name="search" 
                                   value="<?= htmlspecialchars($_GET['search'] ?? '') ?>"
                                   placeholder="Tìm kiếm theo tên sách, tác giả, hoặc ID..." 
                                   class="w-full pl-10 pr-4 py-2 bg-white border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#4CAF50]/20 focus:border-[#4CAF50] transition-colors">
                        </div>

                        <button type="submit" class="px-6 py-2 bg-gray-900 text-white rounded-xl hover:bg-gray-800 transition-colors font-medium">
                            Lọc
                        </button>
                        
                        <?php if (!empty($_GET['search'])): ?>
                            <a href="<?= BASE_URL ?>?act=admin-inventories" class="px-4 py-2 bg-gray-100 text-gray-600 hover:bg-gray-200 rounded-xl transition-colors font-medium flex items-center gap-2">
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
                            <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Tên Sách</th>
                            <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Tác Giả</th>
                            <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Danh Mục</th>
                            <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Giá</th>
                            <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Tồn Kho</th>
                            <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider text-center">Nhập Kho</th>
                            <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Cập Nhật</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        <?php if (empty($inventories)): ?>
                            <tr>
                                <td colspan="8" class="px-6 py-12 text-center text-gray-500">
                                    <div class="flex flex-col items-center justify-center gap-2">
                                        <i data-lucide="package-open" class="w-8 h-8 text-gray-300"></i>
                                        <p>Chưa có kho hàng nào.</p>
                                    </div>
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($inventories as $inventory): ?>
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 text-sm text-gray-500">
                                    #<?= $inventory['id'] ?>
                                </td>
                                <td class="px-6 py-4 font-medium text-gray-900 max-w-[200px] truncate" title="<?= htmlspecialchars($inventory['title']) ?>">
                                    <?= htmlspecialchars($inventory['title']) ?>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-500">
                                    <?= htmlspecialchars($inventory['author']) ?>
                                </td>
                                <td class="px-6 py-4 text-sm">
                                    <span class="px-2.5 py-1 bg-gray-100 text-gray-600 rounded-lg font-mono text-xs"><?= htmlspecialchars($inventory['category_name'] ?? 'N/A') ?></span>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-900 font-medium whitespace-nowrap">
                                    <?= number_format($inventory['price']) ?>₫
                                </td>
                                <td class="px-6 py-4">
                                    <?php if ($inventory['stock_quantity'] <= 5): ?>
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                            <span class="w-1.5 h-1.5 rounded-full bg-red-500"></span>
                                            <?= $inventory['stock_quantity'] ?> cuốn
                                        </span>
                                    <?php elseif ($inventory['stock_quantity'] <= 20): ?>
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                            <span class="w-1.5 h-1.5 rounded-full bg-yellow-500"></span>
                                            <?= $inventory['stock_quantity'] ?> cuốn
                                        </span>
                                    <?php else: ?>
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span>
                                            <?= $inventory['stock_quantity'] ?> cuốn
                                        </span>
                                    <?php endif; ?>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-500 text-center font-medium">
                                    <?= $inventory['imported_quantity'] ?>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap">
                                    <?= date('d/m/Y H:i', strtotime($inventory['updated_at'])) ?>
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
                    $baseUrl = BASE_URL . "?act=admin-inventories";
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