<?php

$flashMessage = Message::get('success');
$errorMsg     = Message::get('error');
deleteSessionError();

include_once './views/components/header.php';
include_once './views/components/sidebar.php';
?>

<main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-50 p-6">
    <div class="w-full">
        
        <?php if ($flashMessage): ?>
            <div id="flash-success" class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-xl relative flex items-center gap-2 transition-opacity duration-500" role="alert">
                <i data-lucide="check" class="w-4 h-4 shrink-0"></i>
                <span><?= htmlspecialchars($flashMessage) ?></span>
            </div>
        <?php endif; ?>

        <?php if ($errorMsg): ?>
            <div id="flash-error" class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-xl relative flex items-center gap-2 transition-opacity duration-500" role="alert">
                <i data-lucide="triangle-alert" class="w-4 h-4 shrink-0"></i>
                <span><?= htmlspecialchars($errorMsg) ?></span>
            </div>
        <?php endif; ?>
        <script>
            ['flash-success','flash-error'].forEach(function(id) {
                var el = document.getElementById(id);
                if (!el) return;
                setTimeout(function() {
                    el.style.opacity = '0';
                    setTimeout(function() { el.remove(); }, 500);
                }, 3000);
            });
        </script>

        <div class="flex flex-wrap justify-between items-center mb-6 gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Quản lý Sách</h1>
                <p class="text-sm text-gray-500 mt-1">
                    Tổng cộng <span class="font-semibold text-gray-700"><?= number_format($total) ?></span> sách trong hệ thống
                </p>
            </div>
            <a href="<?= BASE_URL ?>?act=admin-books-create" class="px-5 py-2.5 bg-[#4CAF50] text-white rounded-xl hover:bg-green-600 transition-colors font-medium flex items-center gap-2 shadow-sm">
                <i data-lucide="plus" class="w-4 h-4"></i> Thêm sách mới
            </a>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            
            <!-- Thanh tìm kiếm & lọc -->
            <div class="p-4 border-b border-gray-100 bg-gray-50/50">
                <form action="" method="GET" class="flex flex-wrap gap-3 items-center w-full">
                    <input type="hidden" name="act" value="admin-books">
                    
                    <!-- Tìm kiếm -->
                    <div class="relative flex-1 min-w-[200px]">
                        <i data-lucide="search" class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                        <input type="text" 
                               name="search" 
                               value="<?= htmlspecialchars($_GET['search'] ?? '') ?>"
                               placeholder="Tìm theo tên sách hoặc tác giả..." 
                               class="w-full pl-9 pr-4 py-2 bg-white border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#4CAF50]/20 focus:border-[#4CAF50] transition-colors text-sm">
                    </div>

                    <!-- Lọc danh mục -->
                    <div class="relative w-44">
                        <select name="category" class="w-full px-3 py-2 bg-white border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#4CAF50]/20 focus:border-[#4CAF50] transition-colors text-gray-600 text-sm">
                            <option value="">Tất cả danh mục</option>
                            <?php foreach($categories as $cat): ?>
                                <option value="<?= $cat['id'] ?>" <?= (isset($_GET['category']) && $_GET['category'] == $cat['id']) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($cat['name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Lọc trạng thái -->
                    <div class="relative w-36">
                        <select name="status_filter" class="w-full px-3 py-2 bg-white border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#4CAF50]/20 focus:border-[#4CAF50] transition-colors text-gray-600 text-sm">
                            <option value="">Tất cả TT</option>
                            <option value="1" <?= (isset($_GET['status_filter']) && $_GET['status_filter'] === '1') ? 'selected' : '' ?>>Đang hiển thị</option>
                            <option value="0" <?= (isset($_GET['status_filter']) && $_GET['status_filter'] === '0') ? 'selected' : '' ?>>Đang ẩn</option>
                        </select>
                    </div>

                    <button type="submit" class="px-5 py-2 bg-gray-900 text-white rounded-xl hover:bg-gray-800 transition-colors font-medium text-sm whitespace-nowrap">
                        Lọc
                    </button>
                    
                    <?php if (!empty($_GET['search']) || !empty($_GET['category']) || isset($_GET['status_filter']) && $_GET['status_filter'] !== ''): ?>
                        <a href="<?= BASE_URL ?>?act=admin-books" class="px-4 py-2 bg-gray-100 text-gray-600 hover:bg-gray-200 rounded-xl transition-colors font-medium flex items-center gap-1.5 text-sm whitespace-nowrap">
                            <i data-lucide="x" class="w-3.5 h-3.5"></i> Xóa lọc
                        </a>
                    <?php endif; ?>
                </form>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50/50 border-b border-gray-100">
                            <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Tên sách</th>
                            <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Danh mục</th>
                            <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Ảnh</th>
                            <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Giá / Khuyến mãi</th>
                            <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Kho</th>
                            <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Trạng thái</th>
                            <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider text-right">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        <?php if (empty($books)): ?>
                            <tr>
                                <td colspan="7" class="px-6 py-14 text-center text-gray-500">
                                    <div class="flex flex-col items-center justify-center gap-3">
                                        <i data-lucide="book-open" class="w-10 h-10 text-gray-300"></i>
                                        <p class="text-sm">Không tìm thấy sách nào.</p>
                                        <a href="<?= BASE_URL ?>?act=admin-books-create" class="text-sm text-[#4CAF50] hover:underline font-medium">+ Thêm sách mới</a>
                                    </div>
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($books as $book): ?>
                            <tr class="hover:bg-gray-50 transition-colors">
                                
                                <!-- Tên sách & tác giả -->
                                <td class="px-6 py-4">
                                    <div class="text-sm font-semibold text-gray-900 max-w-[200px] truncate" title="<?= htmlspecialchars($book['title']) ?>"><?= htmlspecialchars($book['title']) ?></div>
                                    <div class="text-xs text-gray-500 mt-0.5"><?= htmlspecialchars($book['author']) ?></div>
                                </td>
                                
                                <!-- Danh mục -->
                                <td class="px-6 py-4 text-sm text-gray-600">
                                    <?= htmlspecialchars($book['category_name'] ?? '---') ?>
                                </td>
                                
                                <!-- Ảnh bìa -->
                                <td class="px-6 py-4">
                                    <?php if ($book['thumbnail']): ?>
                                        <?php $imgPath = PATH_ROOT . ltrim($book['thumbnail'], '/'); ?>
                                        <img src="<?= htmlspecialchars(BASE_URL . $book['thumbnail']) ?>?v=<?= file_exists($imgPath) ? filemtime($imgPath) : 0 ?>" alt="<?= htmlspecialchars($book['title']) ?>" class="w-11 h-14 object-cover rounded-lg shadow-sm border border-gray-200">
                                    <?php else: ?>
                                        <div class="w-11 h-14 bg-gray-100 rounded-lg flex items-center justify-center border border-gray-200 text-gray-400">
                                            <i data-lucide="image" class="w-5 h-5"></i>
                                        </div>
                                    <?php endif; ?>
                                </td>
                                
                                <!-- Giá -->
                                <td class="px-6 py-4 text-sm">
                                    <?php if ($book['sale_price']): ?>
                                        <div class="text-red-500 font-semibold"><?= number_format($book['sale_price'], 0, ',', '.') ?>đ</div>
                                        <div class="text-gray-400 line-through text-xs"><?= number_format($book['price'], 0, ',', '.') ?>đ</div>
                                    <?php else: ?>
                                        <div class="text-gray-900 font-semibold"><?= number_format($book['price'], 0, ',', '.') ?>đ</div>
                                    <?php endif; ?>
                                </td>

                                <!-- Kho -->
                                <td class="px-6 py-4 text-sm">
                                    <?php if ($book['stock'] > 10): ?>
                                        <span class="text-green-600 font-semibold"><?= $book['stock'] ?></span>
                                    <?php elseif ($book['stock'] > 0): ?>
                                        <span class="text-orange-500 font-semibold"><?= $book['stock'] ?></span>
                                    <?php else: ?>
                                        <span class="text-red-500 font-semibold bg-red-50 px-2 py-0.5 rounded-full text-xs">Hết</span>
                                    <?php endif; ?>
                                </td>

                                <!-- Trạng thái -->
                                <td class="px-6 py-4">
                                    <div class="flex flex-col gap-1.5">
                                        <!-- Nút toggle status nhanh -->
                                        <?php
                                        $toggleUrl = BASE_URL . '?act=admin-books-toggle-status&id=' . $book['book_id'];
                                        if (!empty($_GET['search'])) $toggleUrl .= '&search=' . urlencode($_GET['search']);
                                        if (!empty($_GET['category'])) $toggleUrl .= '&category=' . urlencode($_GET['category']);
                                        if (!empty($_GET['page'])) $toggleUrl .= '&page=' . (int)$_GET['page'];
                                        ?>
                                        <a href="<?= $toggleUrl ?>" 
                                           title="Nhấn để <?= $book['status'] == 1 ? 'ẩn' : 'hiện' ?> sách"
                                           class="inline-flex w-fit items-center gap-1.5 px-2 py-0.5 rounded-full text-[10px] font-semibold cursor-pointer transition-all hover:opacity-80
                                                  <?= $book['status'] == 1 ? 'bg-green-100 text-green-800 border border-green-200' : 'bg-gray-100 text-gray-600 border border-gray-200' ?>">
                                            <i data-lucide="<?= $book['status'] == 1 ? 'eye' : 'eye-off' ?>" class="w-3 h-3"></i>
                                            <?= $book['status'] == 1 ? 'Hiển thị' : 'Đang ẩn' ?>
                                        </a>
                                        
                                        <?php if (!empty($book['is_featured']) && $book['is_featured'] == 1): ?>
                                            <span class="inline-flex w-fit items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-semibold bg-yellow-100 text-yellow-800 border border-yellow-200">
                                                <i data-lucide="star" class="w-3 h-3"></i> Nổi bật
                                            </span>
                                        <?php endif; ?>

                                        <?php if (!empty($book['is_bestseller']) && $book['is_bestseller'] == 1): ?>
                                            <span class="inline-flex w-fit items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-semibold bg-blue-100 text-blue-800 border border-blue-200">
                                                <i data-lucide="trending-up" class="w-3 h-3"></i> Bán chạy
                                            </span>
                                        <?php endif; ?>
                                    </div>
                                </td>

                                <!-- Thao tác -->
                                <td class="px-6 py-4 text-right">
                                    <div class="flex items-center justify-end gap-1">
                                        <a href="<?= BASE_URL ?>?act=admin-books-detail&id=<?= $book['book_id'] ?>" 
                                           class="p-2 text-green-500 hover:bg-green-50 rounded-lg transition-colors"
                                           title="Xem chi tiết">
                                            <i data-lucide="eye" class="w-4 h-4"></i>
                                        </a>
                                        <a href="<?= BASE_URL ?>?act=admin-books-edit&id=<?= $book['book_id'] ?>" 
                                           class="p-2 text-blue-500 hover:bg-blue-50 rounded-lg transition-colors"
                                           title="Chỉnh sửa">
                                            <i data-lucide="pencil" class="w-4 h-4"></i>
                                        </a>
                                        <a href="<?= BASE_URL ?>?act=admin-books-delete&id=<?= $book['book_id'] ?>" 
                                           onclick="return confirm('Bạn chắc chắn muốn xóa sách:\n«<?= addslashes($book['title']) ?>»?\n\nCác ảnh liên quan sẽ bị xóa theo!');"
                                           class="p-2 text-red-500 hover:bg-red-50 rounded-lg transition-colors"
                                           title="Xóa">
                                            <i data-lucide="trash" class="w-4 h-4"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <?php if (!empty($totalPages) && $totalPages > 1): ?>
            <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/50 flex flex-wrap items-center justify-between gap-4">
                <div class="text-sm text-gray-500">
                    Trang <span class="font-semibold text-gray-900"><?= $page ?></span> / <?= $totalPages ?>
                    &nbsp;·&nbsp; <span class="font-semibold text-gray-700"><?= number_format($total) ?></span> sách
                </div>
                
                <div class="flex gap-1">
                    <?php
                    $baseUrl = BASE_URL . "?act=admin-books";
                    if (!empty($_GET['search'])) $baseUrl .= "&search=" . urlencode($_GET['search']);
                    if (!empty($_GET['category'])) $baseUrl .= "&category=" . urlencode($_GET['category']);
                    if (isset($_GET['status_filter']) && $_GET['status_filter'] !== '') $baseUrl .= "&status_filter=" . urlencode($_GET['status_filter']);
                    ?>
                    
                    <?php if ($page > 1): ?>
                        <a href="<?= $baseUrl ?>&page=1" class="px-3 py-1.5 border border-gray-200 rounded-lg bg-white text-gray-600 hover:bg-gray-50 text-sm hidden sm:block">Đầu</a>
                        <a href="<?= $baseUrl ?>&page=<?= $page - 1 ?>" class="px-3 py-1.5 border border-gray-200 rounded-lg bg-white text-gray-600 hover:bg-gray-50 flex items-center text-sm">
                            <i data-lucide="chevron-left" class="w-4 h-4"></i>
                        </a>
                    <?php endif; ?>

                    <?php
                    $start = max(1, $page - 2);
                    $end = min($totalPages, $page + 2);
                    for ($i = $start; $i <= $end; $i++): 
                    ?>
                        <a href="<?= $baseUrl ?>&page=<?= $i ?>" 
                           class="px-3 py-1.5 border rounded-lg text-sm <?= $i == $page ? 'bg-[#4CAF50] border-[#4CAF50] text-white font-semibold' : 'border-gray-200 bg-white text-gray-600 hover:bg-gray-50' ?>">
                            <?= $i ?>
                        </a>
                    <?php endfor; ?>

                    <?php if ($page < $totalPages): ?>
                        <a href="<?= $baseUrl ?>&page=<?= $page + 1 ?>" class="px-3 py-1.5 border border-gray-200 rounded-lg bg-white text-gray-600 hover:bg-gray-50 flex items-center text-sm">
                            <i data-lucide="chevron-right" class="w-4 h-4"></i>
                        </a>
                        <a href="<?= $baseUrl ?>&page=<?= $totalPages ?>" class="px-3 py-1.5 border border-gray-200 rounded-lg bg-white text-gray-600 hover:bg-gray-50 text-sm hidden sm:block">Cuối</a>
                    <?php endif; ?>
                </div>
            </div>
            <?php endif; ?>

        </div>
    </div>
</main>

<?php include_once './views/components/footer.php'; ?>
