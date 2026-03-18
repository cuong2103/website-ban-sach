<?php
$currentPage = 'books';
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

        <div class="flex items-center gap-4 mb-6">
            <a href="<?= BASE_URL ?>?act=admin-books" class="p-2 hover:bg-gray-200 rounded-lg transition-colors">
                <i data-lucide="arrow-left" class="w-5 h-5 text-gray-600"></i>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Thêm sách mới</h1>
                <p class="text-sm text-gray-500 mt-1">Điền thông tin chi tiết để thêm sách vào hệ thống</p>
            </div>
        </div>

        <form action="<?= BASE_URL ?>?act=admin-books-store" method="POST" enctype="multipart/form-data" class="grid grid-cols-1 xl:grid-cols-3 gap-6">
            
            <!-- Cột trái: Thông tin chính -->
            <div class="xl:col-span-2 space-y-6">
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                    <h2 class="text-lg font-bold text-gray-900 mb-4 border-b pb-2">Thông tin cơ bản</h2>
                    
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Tên sách <span class="text-red-500">*</span></label>
                            <input type="text" name="title" value="<?= old('title') ?>" required
                                   class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#4CAF50]/20 focus:border-[#4CAF50] transition-colors">
                            <?php if (isset($errors['title'])): ?>
                                <p class="text-red-500 text-xs mt-1"><?= $errors['title'][0] ?></p>
                            <?php endif; ?>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Tác giả <span class="text-red-500">*</span></label>
                                <input type="text" name="author" list="author_list" value="<?= old('author') ?>" required
                                       class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#4CAF50]/20 focus:border-[#4CAF50] transition-colors">
                                <datalist id="author_list">
                                    <?php foreach ($authors as $a): ?>
                                        <option value="<?= htmlspecialchars($a) ?>">
                                    <?php endforeach; ?>
                                </datalist>
                                <?php if (isset($errors['author'])): ?>
                                    <p class="text-red-500 text-xs mt-1"><?= $errors['author'][0] ?></p>
                                <?php endif; ?>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Nhà xuất bản</label>
                                <input type="text" name="publisher" list="publisher_list" value="<?= old('publisher') ?>"
                                       class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#4CAF50]/20 focus:border-[#4CAF50] transition-colors">
                                <datalist id="publisher_list">
                                    <?php foreach ($publishers as $p): ?>
                                        <option value="<?= htmlspecialchars($p) ?>">
                                    <?php endforeach; ?>
                                </datalist>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Mô tả chi tiết</label>
                            <textarea name="description" rows="5"
                                      class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#4CAF50]/20 focus:border-[#4CAF50] transition-colors"><?= old('description') ?></textarea>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                    <h2 class="text-lg font-bold text-gray-900 mb-4 border-b pb-2">Giá & Tồn kho</h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Giá gốc (VNĐ) <span class="text-red-500">*</span></label>
                            <input type="number" name="price" value="<?= old('price') ?>" required min="0"
                                   class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#4CAF50]/20 focus:border-[#4CAF50] transition-colors">
                            <?php if (isset($errors['price'])): ?>
                                <p class="text-red-500 text-xs mt-1"><?= $errors['price'][0] ?></p>
                            <?php endif; ?>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Giá khuyến mãi (VNĐ)</label>
                            <input type="number" name="sale_price" value="<?= old('sale_price') ?>" min="0"
                                   class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#4CAF50]/20 focus:border-[#4CAF50] transition-colors">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Tồn kho <span class="text-red-500">*</span></label>
                            <input type="number" name="stock" value="<?= old('stock', 0) ?>" required min="0"
                                   class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#4CAF50]/20 focus:border-[#4CAF50] transition-colors">
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                    <h2 class="text-lg font-bold text-gray-900 mb-4 border-b pb-2">Đặc điểm sách</h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Trọng lượng (Ví dụ: 300g)</label>
                            <input type="text" name="weight" value="<?= old('weight') ?>"
                                   class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#4CAF50]/20 focus:border-[#4CAF50] transition-colors">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Kích thước (Ví dụ: 13x20cm)</label>
                            <input type="text" name="dimensions" value="<?= old('dimensions') ?>"
                                   class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#4CAF50]/20 focus:border-[#4CAF50] transition-colors">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Loại bìa</label>
                            <select name="cover_type" class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#4CAF50]/20 focus:border-[#4CAF50] transition-colors">
                                <option value="Bìa mềm" <?= old('cover_type') == 'Bìa mềm' ? 'selected' : '' ?>>Bìa mềm</option>
                                <option value="Bìa cứng" <?= old('cover_type') == 'Bìa cứng' ? 'selected' : '' ?>>Bìa cứng</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Cột phải: Phân loại & Hình ảnh -->
            <div class="space-y-6">
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                    <h2 class="text-lg font-bold text-gray-900 mb-4 border-b pb-2">Phân loại & Trạng thái</h2>
                    
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Danh mục <span class="text-red-500">*</span></label>
                            <select name="category_id" required class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#4CAF50]/20 focus:border-[#4CAF50] transition-colors">
                                <option value="">Chọn danh mục</option>
                                <?php foreach ($categories as $cat): ?>
                                    <option value="<?= $cat['category_id'] ?>" <?= old('category_id') == $cat['category_id'] ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($cat['name']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <?php if (isset($errors['category_id'])): ?>
                                <p class="text-red-500 text-xs mt-1"><?= $errors['category_id'][0] ?></p>
                            <?php endif; ?>
                        </div>

                        <div class="pt-4 space-y-3 border-t">
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="checkbox" name="status" value="1" <?= old('status', '1') == '1' ? 'checked' : '' ?> class="w-4 h-4 text-[#4CAF50] border-gray-300 rounded focus:ring-[#4CAF50]">
                                <span class="text-sm text-gray-700">Hiển thị sách (Kích hoạt)</span>
                            </label>
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="checkbox" name="is_featured" value="1" <?= old('is_featured') == '1' ? 'checked' : '' ?> class="w-4 h-4 text-yellow-500 border-gray-300 rounded focus:ring-yellow-500">
                                <span class="text-sm text-gray-700">Đánh dấu <b>Sách nổi bật</b></span>
                            </label>
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="checkbox" name="is_bestseller" value="1" <?= old('is_bestseller') == '1' ? 'checked' : '' ?> class="w-4 h-4 text-blue-500 border-gray-300 rounded focus:ring-blue-500">
                                <span class="text-sm text-gray-700">Đánh dấu <b>Sách bán chạy</b></span>
                            </label>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                    <h2 class="text-lg font-bold text-gray-900 mb-4 border-b pb-2">Ảnh bìa chính</h2>
                    
                    <div>
                        <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-xl relative hover:bg-gray-50 transition-colors">
                            <div class="space-y-1 text-center" id="thumbnail-upload-container">
                                <i data-lucide="image" class="mx-auto h-12 w-12 text-gray-400"></i>
                                <div class="flex text-sm text-gray-600 justify-center">
                                    <label for="thumbnail" class="relative cursor-pointer bg-white rounded-md font-medium text-[#4CAF50] hover:text-green-600 focus-within:outline-none">
                                        <span>Tải ảnh lên</span>
                                        <input id="thumbnail" name="thumbnail" type="file" class="sr-only" accept="image/*">
                                    </label>
                                </div>
                                <p class="text-xs text-gray-500">PNG, JPG, WEBP tối đa 2MB</p>
                            </div>
                            <div id="thumbnail-preview" class="hidden absolute inset-0 w-full h-full p-2 bg-white rounded-xl">
                                <img src="" alt="Preview" class="w-full h-full object-contain rounded-lg">
                                <button type="button" id="thumbnail-remove" class="absolute top-4 right-4 bg-red-500 text-white p-1.5 rounded-full hover:bg-red-600 transition-colors shadow-sm">
                                    <i data-lucide="x" class="w-4 h-4"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                    <div class="flex items-center justify-between border-b pb-2 mb-4">
                        <h2 class="text-lg font-bold text-gray-900">Ảnh minh họa phụ</h2>
                        <label for="gallery" class="cursor-pointer text-sm font-medium text-[#4CAF50] hover:text-green-600">
                            Thêm ảnh
                        </label>
                        <input id="gallery" name="gallery_images[]" type="file" multiple class="sr-only" accept="image/*">
                    </div>
                    
                    <div id="gallery-preview-container" class="grid grid-cols-3 gap-2">
                        <!-- Preview Images will be appended here -->
                        <div class="w-full aspect-square border-2 border-dashed border-gray-200 rounded-lg flex items-center justify-center text-gray-400 text-xs text-center p-2" id="gallery-empty">
                            Chưa có ảnh
                        </div>
                    </div>
                </div>

                <button type="submit" class="w-full py-3 bg-[#4CAF50] text-white rounded-xl hover:bg-green-600 transition-colors font-bold text-lg shadow-sm">
                    Lưu sách mới
                </button>
            </div>
        </form>
    </div>
</main>

<script src="https://unpkg.com/lucide@latest"></script>
<script>
    lucide.createIcons();

    // --- Xử lý Thumbnail (Ảnh bìa chính) ---
    const thumbnailInput = document.getElementById('thumbnail');
    const thumbnailContainer = document.getElementById('thumbnail-upload-container');
    const thumbnailPreview = document.getElementById('thumbnail-preview');
    const thumbnailImg = thumbnailPreview.querySelector('img');
    const thumbnailRemove = document.getElementById('thumbnail-remove');

    thumbnailInput.addEventListener('change', function(e) {
        if (this.files && this.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                thumbnailImg.src = e.target.result;
                thumbnailContainer.classList.add('hidden');
                thumbnailPreview.classList.remove('hidden');
            }
            reader.readAsDataURL(this.files[0]);
        }
    });

    thumbnailRemove.addEventListener('click', function() {
        thumbnailInput.value = ''; // Clear input
        thumbnailImg.src = '';
        thumbnailContainer.classList.remove('hidden');
        thumbnailPreview.classList.add('hidden');
    });

    // --- Xử lý Gallery (Ảnh phụ) ---
    const galleryInput = document.getElementById('gallery');
    const galleryContainer = document.getElementById('gallery-preview-container');
    const galleryEmpty = document.getElementById('gallery-empty');
    
    // Sử dụng mảng để tracking files vì FileList là read-only
    let galleryFiles = [];
    const dt = new DataTransfer();

    galleryInput.addEventListener('change', function(e) {
        if (this.files && this.files.length > 0) {
            if (galleryEmpty) galleryEmpty.style.display = 'none';

            Array.from(this.files).forEach(file => {
                dt.items.add(file);
                
                const reader = new FileReader();
                reader.onload = function(e) {
                    const div = document.createElement('div');
                    div.className = 'w-full aspect-square relative rounded-lg border border-gray-200 overflow-hidden group';
                    
                    div.innerHTML = `
                        <img src="${e.target.result}" class="w-full h-full object-cover">
                        <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                            <button type="button" class="bg-red-500 text-white p-1.5 rounded-full hover:bg-red-600 transition-colors remove-gallery-item" data-name="${file.name}">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-x"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
                            </button>
                        </div>
                    `;
                    galleryContainer.appendChild(div);
                }
                reader.readAsDataURL(file);
            });

            // Update input files with DataTransfer
            galleryInput.files = dt.files;
        }
    });

    galleryContainer.addEventListener('click', function(e) {
        const btn = e.target.closest('.remove-gallery-item');
        if (btn) {
            const fileName = btn.getAttribute('data-name');
            // Remove from DOM
            btn.closest('.relative').remove();
            
            // Remove from DataTransfer
            const newDt = new DataTransfer();
            for (let i = 0; i < dt.items.length; i++) {
                if (dt.items[i].getAsFile().name !== fileName) {
                    newDt.items.add(dt.items[i].getAsFile());
                }
            }
            // Update reference
            dt.items.clear();
            for (let i = 0; i < newDt.items.length; i++) {
                dt.items.add(newDt.items[i].getAsFile());
            }
            galleryInput.files = dt.files;

            // Show empty state if needed
            if (dt.files.length === 0 && galleryEmpty) {
                galleryEmpty.style.display = 'flex';
            }
        }
    });

</script>

<?php include_once './views/components/footer.php'; ?>
