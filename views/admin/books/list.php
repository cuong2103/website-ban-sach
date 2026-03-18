<?php
$currentPage = $page;

$flashMessage = $_SESSION['flash'] ?? null;
$errorMsg = $_SESSION['error'] ?? null;
deleteSessionError();

include_once './views/components/header.php';
include_once './views/components/sidebar.php';
?>

<main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-50 p-6">
    <div class="w-full">
        
        <?php if ($flashMessage): ?>
            <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                <span class="block sm:inline"><?= htmlspecialchars($flashMessage) ?></span>
            </div>
        <?php endif; ?>

        <?php if ($errorMsg): ?>
            <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                <span class="block sm:inline"><?= htmlspecialchars($errorMsg) ?></span>
            </div>
        <?php endif; ?>

        <div class="flex flex-wrap justify-between items-center mb-6 gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Quản lý Sách</h1>
                <p class="text-sm text-gray-500 mt-1">Danh sách tất cả sách trong hệ thống</p>
            </div>
            <a href="<?= BASE_URL ?>?act=admin-books-create" class="px-5 py-2.5 bg-[#4CAF50] text-white rounded-xl hover:bg-green-600 transition-colors font-medium flex items-center gap-2 shadow-sm">
                <i data-lucide="plus" class="w-4 h-4"></i> Thêm sách mới
            </a>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            
            <div class="p-4 border-b border-gray-100 bg-gray-50/50">
                <form action="" method="GET" class="flex flex-wrap gap-4 items-center justify-between w-full">
                    <input type="hidden" name="act" value="admin-books">
                    
                    <div class="flex flex-1 gap-4 items-center w-full">
                        <div class="relative flex-1">
                            <i data-lucide="search" class="w-5 h-5 absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                            <input type="text" 
                                   name="search" 
                                   value="<?= htmlspecialchars($_GET['search'] ?? '') ?>"
                                   placeholder="Tìm theo tên sách hoặc tác giả..." 
                                   class="w-full pl-10 pr-4 py-2 bg-white border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#4CAF50]/20 focus:border-[#4CAF50] transition-colors">
                        </div>

                        <div class="relative w-48">
                            <select name="category" class="w-full px-4 py-2 bg-white border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#4CAF50]/20 focus:border-[#4CAF50] transition-colors text-gray-600">
                                <option value="">Tất cả danh mục</option>
                                <?php foreach($categories as $cat): ?>
                                    <option value="<?= $cat['category_id'] ?>" <?= (isset($_GET['category']) && $_GET['category'] == $cat['category_id']) ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($cat['name']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <button type="submit" class="px-6 py-2 bg-gray-900 text-white rounded-xl hover:bg-gray-800 transition-colors font-medium whitespace-nowrap">
                            Lọc
                        </button>
                        
                        <?php if (!empty($_GET['search']) || !empty($_GET['category'])): ?>
                            <a href="<?= BASE_URL ?>?act=admin-books" class="px-4 py-2 bg-gray-100 text-gray-600 hover:bg-gray-200 rounded-xl transition-colors font-medium flex items-center gap-2 whitespace-nowrap">
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
                            <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Ảnh</th>
                            <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Tên sách</th>
                            <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Danh mục</th>
                            <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Giá / Khuyến mãi</th>
                            <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Kho</th>
                            <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Thuộc tính</th>
                            <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider text-right">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        <?php if (empty($books)): ?>
                            <tr>
                                <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                                    <div class="flex flex-col items-center justify-center gap-2">
                                        <i data-lucide="inbox" class="w-8 h-8 text-gray-300"></i>
                                        <p>Không tìm thấy sách nào.</p>
                                    </div>
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($books as $book): ?>
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4">
                                    <?php if ($book['thumbnail']): ?>
                                        <img src="<?= htmlspecialchars(BASE_URL . $book['thumbnail']) ?>" alt="<?= htmlspecialchars($book['title']) ?>" class="w-12 h-16 object-cover rounded shadow-sm border border-gray-200">
                                    <?php else: ?>
                                        <div class="w-12 h-16 bg-gray-100 rounded flex items-center justify-center border border-gray-200 text-gray-400">
                                            <i data-lucide="image" class="w-6 h-6"></i>
                                        </div>
                                    <?php endif; ?>
                                </td>
                                <td class="px-6 py-4 font-medium text-gray-900">
                                    <div class="text-sm font-semibold max-w-xs truncate" title="<?= htmlspecialchars($book['title']) ?>"><?= htmlspecialchars($book['title']) ?></div>
                                    <div class="text-xs text-gray-500 mt-1"><?= htmlspecialchars($book['author']) ?></div>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600">
                                    <?= htmlspecialchars($book['category_name']) ?>
                                </td>
                                <td class="px-6 py-4 text-sm">
                                    <?php if ($book['sale_price']): ?>
                                        <div class="text-red-500 font-medium"><?= number_format($book['sale_price'], 0, ',', '.') ?> đ</div>
                                        <div class="text-gray-400 line-through text-xs"><?= number_format($book['price'], 0, ',', '.') ?> đ</div>
                                    <?php else: ?>
                                        <div class="text-gray-900 font-medium"><?= number_format($book['price'], 0, ',', '.') ?> đ</div>
                                    <?php endif; ?>
                                </td>
                                <td class="px-6 py-4 text-sm">
                                    <?php if ($book['stock'] > 0): ?>
                                        <span class="text-green-600 font-medium"><?= $book['stock'] ?></span>
                                    <?php else: ?>
                                        <span class="text-red-500 font-medium bg-red-50 px-2 py-1 rounded">Hết hàng</span>
                                    <?php endif; ?>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex flex-col gap-1">
                                        <?php if ($book['status'] == 1): ?>
                                            <span class="inline-flex w-fit items-center gap-1.5 px-2 py-0.5 rounded text-[10px] font-medium bg-green-100 text-green-800">Hiển thị</span>
                                        <?php else: ?>
                                            <span class="inline-flex w-fit items-center gap-1.5 px-2 py-0.5 rounded text-[10px] font-medium bg-gray-100 text-gray-800">Đang ẩn</span>
                                        <?php endif; ?>
                                        
                                        <?php if (isset($book['is_featured']) && $book['is_featured'] == 1): ?>
                                            <span class="inline-flex w-fit items-center gap-1.5 px-2 py-0.5 rounded text-[10px] font-medium bg-yellow-100 text-yellow-800">Nổi bật</span>
                                        <?php endif; ?>

                                        <?php if (isset($book['is_bestseller']) && $book['is_bestseller'] == 1): ?>
                                            <span class="inline-flex w-fit items-center gap-1.5 px-2 py-0.5 rounded text-[10px] font-medium bg-blue-100 text-blue-800">Bán chạy</span>
                                        <?php endif; ?>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="<?= BASE_URL ?>?act=admin-books-detail&id=<?= $book['book_id'] ?>" 
                                           class="p-2 text-green-500 hover:bg-green-50 rounded-lg transition-colors"
                                           title="Xem chi tiết">
                                            <i data-lucide="eye" class="w-4 h-4"></i>
                                        </a>
                                        <a href="<?= BASE_URL ?>?act=admin-books-edit&id=<?= $book['book_id'] ?>" 
                                           class="p-2 text-blue-500 hover:bg-blue-50 rounded-lg transition-colors"
                                           title="Sửa">
                                            <i data-lucide="edit-2" class="w-4 h-4"></i>
                                        </a>
                                        <a href="<?= BASE_URL ?>?act=admin-books-delete&id=<?= $book['book_id'] ?>" 
                                           onclick="return confirm('Bạn chắc chắn muốn xóa sách này? Các ảnh liên quan sẽ bị xóa theo!');"
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
                    $baseUrl = BASE_URL . "?act=admin-books";
                    if (!empty($_GET['search'])) $baseUrl .= "&search=" . urlencode($_GET['search']);
                    if (!empty($_GET['category'])) $baseUrl .= "&category=" . urlencode($_GET['category']);
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

<script src="https://unpkg.com/lucide@latest"></script>
<script>
    lucide.createIcons();
</script>

<?php include_once './views/components/footer.php'; ?>
