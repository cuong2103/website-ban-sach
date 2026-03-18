<?php
$currentPage = 'books';

$flashMessage = Message::get('success');
$errorMsg     = Message::get('error');
deleteSessionError();

include_once './views/components/header.php';
include_once './views/components/sidebar.php';
?>

<main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-50 p-6">
    <div class="w-full">
        
        <?php if ($flashMessage): ?>
            <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-xl relative flex items-center gap-2" role="alert">
                <i data-lucide="check-circle" class="w-4 h-4 shrink-0"></i>
                <span><?= htmlspecialchars($flashMessage) ?></span>
            </div>
        <?php endif; ?>

        <?php if ($errorMsg): ?>
            <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-xl relative flex items-center gap-2" role="alert">
                <i data-lucide="alert-circle" class="w-4 h-4 shrink-0"></i>
                <span><?= htmlspecialchars($errorMsg) ?></span>
            </div>
        <?php endif; ?>

        <div class="flex items-center gap-4 mb-6">
            <a href="<?= BASE_URL ?>?act=admin-books" class="p-2 hover:bg-gray-200 rounded-lg transition-colors">
                <i data-lucide="arrow-left" class="w-5 h-5 text-gray-600"></i>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Chi tiết sách</h1>
                <p class="text-sm text-gray-500 mt-1">Thông tin đầy đủ của sách trong hệ thống</p>
            </div>
            <div class="ml-auto flex gap-3">
                <!-- Toggle status nhanh -->
                <?php
                $toggleUrl = BASE_URL . '?act=admin-books-toggle-status&id=' . $book['book_id'];
                $isVisible = $book['status'] == 1;
                ?>
                <a href="<?= $toggleUrl ?>" 
                   title="<?= $isVisible ? 'Ẩn sách này' : 'Hiện sách này' ?>"
                   class="px-4 py-2 rounded-xl font-medium flex items-center gap-2 text-sm transition-colors shadow-sm
                          <?= $isVisible ? 'bg-gray-100 text-gray-700 hover:bg-gray-200' : 'bg-green-100 text-green-700 hover:bg-green-200' ?>">
                    <i data-lucide="<?= $isVisible ? 'eye-off' : 'eye' ?>" class="w-4 h-4"></i>
                    <?= $isVisible ? 'Ẩn sách' : 'Hiện sách' ?>
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
            
            <!-- Cột trái: Thông tin chi tiết -->
            <div class="xl:col-span-2 space-y-6">
                <!-- Thông tin cơ bản -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8">
                    <div class="flex flex-col md:flex-row gap-8">
                        <!-- Preview Thumbnail -->
                        <div class="w-full md:w-64 flex-shrink-0">
                            <?php if ($book['thumbnail']): ?>
                                <img src="<?= htmlspecialchars(BASE_URL . $book['thumbnail']) ?>" alt="<?= htmlspecialchars($book['title']) ?>" class="w-full aspect-[3/4] object-cover rounded-xl shadow-lg border border-gray-100">
                            <?php else: ?>
                                <div class="w-full aspect-[3/4] bg-gray-100 rounded-xl flex items-center justify-center border border-gray-200 text-gray-400">
                                    <i data-lucide="image" class="w-12 h-12"></i>
                                </div>
                            <?php endif; ?>
                        </div>

                        <!-- Thông tin chính -->
                        <div class="flex-1 space-y-4">
                            <div>
                                <h2 class="text-3xl font-extrabold text-gray-900"><?= htmlspecialchars($book['title']) ?></h2>
                                <p class="text-lg text-gray-500 mt-1">Tác giả: <span class="font-semibold text-gray-700"><?= htmlspecialchars($book['author']) ?></span></p>
                            </div>

                            <div class="flex flex-wrap gap-2">
                                <span class="px-3 py-1 bg-gray-100 text-gray-700 rounded-full text-sm font-medium border border-gray-200">
                                    Danh mục: <?= htmlspecialchars($book['category_name']) ?>
                                </span>
                                <?php if ($book['publisher']): ?>
                                    <span class="px-3 py-1 bg-gray-100 text-gray-700 rounded-full text-sm font-medium border border-gray-200">
                                        NXB: <?= htmlspecialchars($book['publisher']) ?>
                                    </span>
                                <?php endif; ?>
                            </div>

                            <div class="pt-4 flex items-baseline gap-4">
                                <?php if ($book['sale_price']): ?>
                                    <span class="text-4xl font-bold text-red-600"><?= number_format($book['sale_price'], 0, ',', '.') ?> đ</span>
                                    <span class="text-xl text-gray-400 line-through"><?= number_format($book['price'], 0, ',', '.') ?> đ</span>
                                <?php else: ?>
                                    <span class="text-4xl font-bold text-gray-900"><?= number_format($book['price'], 0, ',', '.') ?> đ</span>
                                <?php endif; ?>
                            </div>

                            <div class="pt-6 grid grid-cols-2 gap-6">
                                <div class="p-4 bg-gray-50 rounded-xl border border-gray-100">
                                    <p class="text-sm text-gray-500 mb-1">Tình trạng kho</p>
                                    <p class="text-xl font-bold <?= $book['stock'] > 0 ? 'text-green-600' : 'text-red-600' ?>">
                                        <?= $book['stock'] > 0 ? $book['stock'] . ' cuốn' : 'Hết hàng' ?>
                                    </p>
                                </div>
                                <div class="p-4 bg-gray-50 rounded-xl border border-gray-100">
                                    <p class="text-sm text-gray-500 mb-1">Trạng thái bán</p>
                                    <div class="flex flex-wrap gap-2 mt-1">
                                        <?php if ($book['status'] == 1): ?>
                                            <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800">Hiển thị</span>
                                        <?php else: ?>
                                            <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-800">Đang ẩn</span>
                                        <?php endif; ?>
                                        
                                        <?php if (isset($book['is_featured']) && $book['is_featured'] == 1): ?>
                                            <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded text-xs font-medium bg-yellow-100 text-yellow-800">Nổi bật</span>
                                        <?php endif; ?>

                                        <?php if (isset($book['is_bestseller']) && $book['is_bestseller'] == 1): ?>
                                            <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800">Bán chạy</span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-10">
                        <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                            <i data-lucide="file-text" class="w-5 h-5 text-gray-400"></i> Mô tả chi tiết
                        </h3>
                        <div class="prose max-w-none text-gray-600 leading-relaxed bg-gray-50/50 p-6 rounded-2xl border border-gray-100">
                            <?= nl2br(htmlspecialchars($book['description'] ?? 'Chưa có mô tả chi tiết cho sách này.')) ?>
                        </div>
                    </div>
                </div>


                <!-- Ảnh minh họa (Gallery) -->
                <?php if (!empty($bookImages)): ?>
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8">
                    <h2 class="text-lg font-bold text-gray-900 mb-6 border-b pb-4">Ảnh minh họa chi tiết</h2>
                    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4">
                        <?php foreach($bookImages as $img): ?>
                            <a href="<?= htmlspecialchars(BASE_URL . $img['image_url']) ?>" target="_blank" class="aspect-square rounded-xl overflow-hidden border border-gray-100 hover:opacity-90 transition-opacity">
                                <img src="<?= htmlspecialchars(BASE_URL . $img['image_url']) ?>" class="w-full h-full object-cover">
                            </a>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endif; ?>
            </div>

            <!-- Cột phải: Timeline / Metadata -->
            <div class="space-y-6">

                            <!-- Thông số kỹ thuật -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8">
                    <h2 class="text-lg font-bold text-gray-900 mb-6 border-b pb-4">Thông số kỹ thuật</h2>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                        <div class="space-y-1">
                            <p class="text-sm text-gray-400">Trọng lượng</p>
                            <p class="text-base font-semibold text-gray-700"><?= htmlspecialchars($book['weight'] ?: '--') ?></p>
                        </div>
                        <div class="space-y-1">
                            <p class="text-sm text-gray-400">Kích thước</p>
                            <p class="text-base font-semibold text-gray-700"><?= htmlspecialchars($book['dimensions'] ?: '--') ?></p>
                        </div>
                        <div class="space-y-1">
                            <p class="text-sm text-gray-400">Loại bìa</p>
                            <p class="text-base font-semibold text-gray-700"><?= htmlspecialchars($book['cover_type'] ?: '--') ?></p>
                        </div>
                    </div>
                </div>
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                    <h2 class="text-lg font-bold text-gray-900 mb-4 border-b pb-2">Thông tin hệ thống</h2>
                    <div class="space-y-4">
                        <div class="flex justify-between items-center text-sm">
                            <span class="text-gray-400">ID sách</span>
                            <span class="font-mono font-bold text-gray-700">#<?= $book['book_id'] ?></span>
                        </div>
                        <div class="flex justify-between items-center text-sm">
                            <span class="text-gray-400">Ngày tạo</span>
                            <span class="text-gray-700 font-medium"><?= date('d/m/Y H:i', strtotime($book['created_at'])) ?></span>
                        </div>
                        <div class="flex justify-between items-center text-sm">
                            <span class="text-gray-400">Cập nhật cuối</span>
                            <span class="text-gray-700 font-medium"><?= date('d/m/Y H:i', strtotime($book['updated_at'])) ?></span>
                        </div>
                    </div>
                </div>
                
            </div>
        </div>
    </div>
</main>

<?php include_once './views/components/footer.php'; ?>
