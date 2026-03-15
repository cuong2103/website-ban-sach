<?php
require_once './views/components/navbar.php';

$currentSearch = $search;
$currentCategory = $category;
$currentMinPrice = $minPrice;
$currentMaxPrice = $maxPrice;

// For display
$displaySearch = htmlspecialchars($search);
$displayCategory = htmlspecialchars($category);

// For URLs
$urlSearch = urlencode($search);
$urlCategory = urlencode($category);
$cacheBust = time();

// DEBUG - Check if parameters are being received
$debug = false; // Set to true to show debug info
if ($debug):
?>
<div class="bg-yellow-100 border border-yellow-400 p-4 m-4">
    <p><strong>DEBUG INFO (remove this later):</strong></p>
    <p>Search: "<?=$displaySearch?>" (raw: "<?= $search?>")</p>
    <p>Category: "<?=$displayCategory?>" (raw: "<?= $category?>")</p>
    <p>Price: $<?= $minPrice?> - $<?=$maxPrice?></p>
    <p>Total Books: <?=$total?>, Pages: <?=$totalPages?></p>
    <p>Current URL: <?= $_SERVER['REQUEST_URI']?></p>
</div>
<?php endif; ?>

<div class="max-w-[1200px] mx-auto px-4 py-8">
    <div class="flex gap-6">
        <!-- Sidebar Filters -->
        <aside class="w-64 flex-shrink-0">
            <div class="bg-white rounded-lg shadow-sm p-6 sticky top-24">
                <h3 class="text-lg font-bold text-gray-800 mb-6">Bộ lọc</h3>

                <!-- Search -->
                <form method="GET" class="space-y-6">
                    <input type="hidden" name="act" value="books">
                    <input type="hidden" name="_cb" value="<?= $cacheBust ?>">

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Tìm kiếm</label>
                        <input type="text" name="search" placeholder="Tên sách, tác giả..."
                            value="<?= $displaySearch ?>"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#4CAF50]">
                    </div>

                    <!-- Category Filter -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-3">Danh mục</label>
                        <div class="space-y-2">
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="radio" name="category" value=""
                                    <?= empty($currentCategory) ? 'checked' : '' ?> class="w-4 h-4">
                                <span class="text-sm text-gray-700">Tất cả</span>
                            </label>
                            <?php foreach ($categories as $cat): ?>
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="radio" name="category" value="<?= htmlspecialchars($cat['slug']) ?>"
                                    <?= $currentCategory === $cat['slug'] ? 'checked' : '' ?> class="w-4 h-4">
                                <span class="text-sm text-gray-700"><?= htmlspecialchars($cat['name']) ?></span>
                            </label>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <!-- Price Range Filter -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-3">Khoảng giá</label>
                        <div class="space-y-3">
                            <div>
                                <label class="text-xs text-gray-600">Từ:</label>
                                <input type="number" name="min_price" value="<?= $currentMinPrice ?>"
                                    min="<?= $priceRange['min'] ?>" max="<?= $priceRange['max'] ?>"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#4CAF50]">
                            </div>
                            <div>
                                <label class="text-xs text-gray-600">Đến:</label>
                                <input type="number" name="max_price" value="<?= $currentMaxPrice ?>"
                                    min="<?= $priceRange['min'] ?>" max="<?= $priceRange['max'] ?>"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#4CAF50]">
                            </div>
                        </div>
                    </div>

                    <!-- Filter Buttons -->
                    <div class="flex gap-2 pt-4 border-t border-gray-200">
                        <button type="submit"
                            class="flex-1 bg-[#4CAF50] hover:bg-[#43A047] text-white py-2 rounded-lg text-sm font-medium transition-colors">
                            Lọc
                        </button>
                        <a href="<?= BASE_URL ?>?act=books"
                            class="flex-1 border border-gray-300 hover:border-gray-400 text-gray-700 py-2 rounded-lg text-sm font-medium text-center transition-colors">
                            Xóa
                        </a>
                    </div>
                </form>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="flex-1">
            <!-- Results Header -->
            <div class="mb-6">
                <h2 class="text-2xl font-bold text-gray-800 mb-2">Danh sách sách</h2>
                <p class="text-sm text-gray-600">
                    Hiển thị
                    <strong><?= ($offset + 1) ?></strong>
                    đến
                    <strong><?= min($offset + $limit, $total) ?></strong>
                    trong tổng số
                    <strong><?= $total ?></strong>
                    sách
                </p>
            </div>

            <?php if (empty($books)): ?>
            <div class="bg-gray-50 rounded-lg p-12 text-center">
                <i data-lucide="inbox" class="w-12 h-12 text-gray-400 mx-auto mb-3"></i>
                <p class="text-gray-600">Không tìm thấy sách nào phù hợp với tiêu chí của bạn</p>
            </div>
            <?php else: ?>
            <!-- Books Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
                <?php foreach ($books as $book): 
            $displayPrice = $book['sale_price'] ?? $book['price'];
            $originalPrice = $book['sale_price'] ? $book['price'] : null;
          ?>
                <div class="bg-white rounded-lg shadow-sm hover:shadow-lg overflow-hidden transition-shadow">
                    <!-- Book Image -->
                    <div class="relative bg-gray-100 h-64 overflow-hidden group">
                        <a href="<?= BASE_URL ?>?act=book-detail&id=<?= $book['id'] ?>"
                            class="relative block w-full h-full">
                            <img src="<?= $book['thumbnail'] ?? 'https://via.placeholder.com/300x400?text=No+Image' ?>"
                                alt="<?= htmlspecialchars($book['title']) ?>"
                                class="w-full h-full object-cover group-hover:scale-105 transition-transform">

                            <?php if ($book['sale_price']): ?>
                            <div
                                class="absolute top-3 right-3 bg-red-500 text-white px-3 py-1 rounded-lg text-sm font-bold">
                                -<?= round((1 - $book['sale_price'] / $book['price']) * 100) ?>%
                            </div>
                            <?php endif; ?>

                            <?php if ($book['stock'] <= 5 && $book['stock'] > 0): ?>
                            <div
                                class="absolute bottom-3 left-3 bg-yellow-500 text-white px-2 py-1 rounded text-xs font-medium">
                                Còn <?= $book['stock'] ?> cuốn
                            </div>
                            <?php elseif ($book['stock'] === 0): ?>
                            <div class="absolute inset-0 bg-black/40 flex items-center justify-center">
                                <span class="bg-gray-800 text-white px-4 py-2 rounded">Hết hàng</span>
                            </div>
                            <?php endif; ?>
                        </a>

                        <!-- Action Buttons Overlay -->
                        <div
                            class="absolute inset-0 bg-black/0 group-hover:bg-black/30 opacity-0 group-hover:opacity-100 transition-all duration-200 flex items-center justify-center gap-3">
                            <?php if ($book['stock'] > 0): ?>
                            <button onclick="addToCart(<?= $book['id'] ?>)"
                                class="bg-[#4CAF50] hover:bg-[#43A047] text-white p-3 rounded-full transition-colors shadow-lg transform hover:scale-110">
                                <i data-lucide="shopping-cart" class="w-5 h-5"></i>
                            </button>
                            <a href="<?= BASE_URL ?>?act=book-detail&id=<?= $book['id'] ?>"
                                class="bg-white hover:bg-gray-100 text-[#333] p-3 rounded-full transition-colors shadow-lg transform hover:scale-110">
                                <i data-lucide="eye" class="w-5 h-5"></i>
                            </a>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Book Info -->
                    <div class="p-4">
                        <p class="text-xs text-gray-500 mb-1">
                            <?= htmlspecialchars($book['category_name'] ?? 'Chưa phân loại') ?></p>
                        <h3 class="font-semibold text-gray-800 text-sm mb-1 line-clamp-2">
                            <?= htmlspecialchars($book['title']) ?>
                        </h3>
                        <p class="text-xs text-gray-600 mb-3">
                            <?= htmlspecialchars($book['author'] ?? 'Chưa cập nhật') ?>
                        </p>

                        <!-- Price -->
                        <div class="flex items-baseline gap-2">
                            <span class="text-lg font-bold text-[#4CAF50]">
                                <?= number_format($displayPrice, 0, ',', '.') ?>₫
                            </span>
                            <?php if ($originalPrice): ?>
                            <span class="text-sm text-gray-500 line-through">
                                <?= number_format($originalPrice, 0, ',', '.') ?>₫
                            </span>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>

            <!-- Pagination -->
            <?php if ($totalPages > 1): ?>
            <div class="flex items-center justify-center gap-2">
                <?php if ($page > 1): ?>
                <a href="<?= BASE_URL ?>?act=books&page=<?= $page - 1 ?>&search=<?= $urlSearch ?>&category=<?= $urlCategory ?>&min_price=<?= $currentMinPrice ?>&max_price=<?= $currentMaxPrice ?>&_cb=<?= $cacheBust ?>"
                    class="px-3 py-2 border border-gray-300 rounded-lg text-sm hover:bg-gray-50 transition-colors">
                    <i data-lucide="chevron-left" class="w-4 h-4"></i>
                </a>
                <?php endif; ?>

                <?php 
              $startPage = max(1, $page - 2);
              $endPage = min($totalPages, $page + 2);
              
              for ($i = $startPage; $i <= $endPage; $i++): 
                $isActive = $i === $page;
            ?>
                <a href="<?= BASE_URL ?>?act=books&page=<?= $i ?>&search=<?= $urlSearch ?>&category=<?= $urlCategory ?>&min_price=<?= $currentMinPrice ?>&max_price=<?= $currentMaxPrice ?>&_cb=<?= $cacheBust ?>"
                    class="px-3 py-2 rounded-lg text-sm transition-colors <?= $isActive ? 'bg-[#4CAF50] text-white' : 'border border-gray-300 hover:bg-gray-50' ?>">
                    <?= $i ?>
                </a>
                <?php endfor; ?>

                <?php if ($page < $totalPages): ?>
                <a href="<?= BASE_URL ?>?act=books&page=<?= $page + 1 ?>&search=<?= $urlSearch ?>&category=<?= $urlCategory ?>&min_price=<?= $currentMinPrice ?>&max_price=<?= $currentMaxPrice ?>&_cb=<?= $cacheBust ?>"
                    class="px-3 py-2 border border-gray-300 rounded-lg text-sm hover:bg-gray-50 transition-colors">
                    <i data-lucide="chevron-right" class="w-4 h-4"></i>
                </a>
                <?php endif; ?>
            </div>
            <?php endif; ?>
            <?php endif; ?>
        </main>
    </div>
</div>

<?php require_once './views/components/customer_footer.php'; ?>

<script>
lucide.createIcons();

function addToCart(bookId) {
    // TODO: Implement add to cart functionality
    // For now, just show an alert or redirect to cart page
    // You can replace this with actual cart logic
    alert('Chức năng giỏ hàng đang được phát triển');
    // Example: window.location.href = '<?= BASE_URL ?>?act=cart&add=' + bookId;
}
</script>
</body>

</html>