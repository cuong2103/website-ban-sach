<?php
$currentAct = $_GET['act'] ?? 'home';
?>
<!DOCTYPE html>
<html lang="vi" class="h-full">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BookStore – Nhà sách trực tuyến</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.min.js"></script>
</head>

<body class="h-full bg-[#F9F9F9]">

    <nav class="bg-white shadow-sm sticky top-0 z-50">
        <div class="max-w-[1200px] mx-auto px-4">
            <div class="flex items-center gap-4 h-16">

                <a href="<?= BASE_URL ?>?act=home" class="flex items-center gap-2 shrink-0">
                    <div class="w-8 h-8 bg-[#4CAF50] rounded-lg flex items-center justify-center">
                        <i data-lucide="book-open" class="w-[18px] h-[18px] text-white"></i>
                    </div>
                    <span class="text-lg font-bold text-[#333] hidden sm:block">
                        Book<span class="text-[#4CAF50]">Store</span>
                    </span>
                </a>

                <form action="<?= BASE_URL ?>?act=books" method="GET" class="flex-1 max-w-xl">
                    <input type="hidden" name="act" value="books">
                    <div
                        class="flex items-center border border-gray-200 rounded-lg overflow-hidden hover:border-[#4CAF50] transition-colors">
                        <input type="text" name="search" placeholder="Tìm kiếm sách, tác giả..."
                            value="<?= htmlspecialchars($_GET['search'] ?? '') ?>"
                            class="flex-1 px-4 py-2 text-sm outline-none bg-transparent">
                        <button type="submit"
                            class="px-4 py-2 bg-[#4CAF50] text-white hover:bg-[#43A047] transition-colors">
                            <i data-lucide="search" class="w-4 h-4"></i>
                        </button>
                    </div>
                </form>

                <div class="hidden md:flex items-center gap-1">
                    <div class="relative group">
                        <button
                            class="flex items-center gap-1 px-3 py-2 text-sm text-[#333] hover:text-[#4CAF50] hover:bg-gray-50 rounded-lg transition-colors">
                            Danh mục <i data-lucide="chevron-down" class="w-[14px] h-[14px]"></i>
                        </button>
                        <div
                            class="absolute top-full left-0 bg-white shadow-lg rounded-xl py-2 min-w-48 border border-gray-100 z-50 hidden group-hover:block">
                            <?php
            $categories = [
              ['icon' => '📖', 'name' => 'Văn học'],
              ['icon' => '💼', 'name' => 'Kinh tế'],
              ['icon' => '🧒', 'name' => 'Thiếu nhi'],
              ['icon' => '🌟', 'name' => 'Kỹ năng sống'],
              ['icon' => '🔬', 'name' => 'Khoa học'],
              ['icon' => '📜', 'name' => 'Lịch sử'],
            ];
            foreach ($categories as $cat): ?>
                            <a href="<?= BASE_URL ?>?act=books&category=<?= urlencode($cat['name']) ?>"
                                class="w-full text-left px-4 py-2 text-sm text-[#333] hover:bg-gray-50 hover:text-[#4CAF50] flex items-center gap-2">
                                <span><?= $cat['icon'] ?></span> <?= $cat['name'] ?>
                            </a>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <a href="<?= BASE_URL ?>?act=books&tag=sale"
                        class="px-3 py-2 text-sm text-red-500 hover:bg-red-50 rounded-lg transition-colors font-medium">
                        🔥 Khuyến mãi
                    </a>
                </div>

                <div class="flex items-center gap-2 ml-auto md:ml-0">
                    <?php
        $isLoggedIn = isset($_SESSION['currentUser']);
        $currentUser = $_SESSION['currentUser'] ?? null;
        ?>

                    <?php if ($isLoggedIn): ?>
                    <a href="<?= BASE_URL ?>?act=account"
                        class="hidden md:flex items-center gap-1 px-3 py-2 text-sm text-[#333] hover:text-[#4CAF50] hover:bg-gray-50 rounded-lg transition-colors">
                        <i data-lucide="user" class="w-4 h-4"></i>
                        <span>Tài khoản</span>
                    </a>
                    <a href="<?= BASE_URL ?>?act=cart"
                        class="relative flex items-center gap-1 px-3 py-2 text-sm text-[#333] hover:text-[#4CAF50] hover:bg-gray-50 rounded-lg transition-colors">
                        <i data-lucide="shopping-cart" class="w-[18px] h-[18px]"></i>
                        <span
                            class="absolute -top-1 -right-1 bg-[#FFC107] text-white text-xs w-5 h-5 rounded-full flex items-center justify-center font-medium hidden"
                            id="cart-badge">0</span>
                        <span class="hidden sm:block">Giỏ hàng</span>
                    </a>
                    <?php else: ?>
                    <a href="<?= BASE_URL ?>?act=login"
                        class="hidden md:flex items-center gap-1 px-3 py-2 text-sm text-[#333] hover:text-[#4CAF50] hover:bg-gray-50 rounded-lg transition-colors">
                        <i data-lucide="log-in" class="w-4 h-4"></i>
                        <span>Đăng nhập</span>
                    </a>
                    <a href="<?= BASE_URL ?>?act=register"
                        class="hidden md:flex items-center gap-1 px-4 py-2 text-sm bg-[#4CAF50] text-white hover:bg-[#43A047] rounded-lg transition-colors font-medium">
                        <i data-lucide="user-plus" class="w-4 h-4"></i>
                        <span>Tạo tài khoản</span>
                    </a>
                    <?php endif; ?>

                    <button class="md:hidden p-2 hover:bg-gray-50 rounded-lg"
                        onclick="document.getElementById('mobile-menu').classList.toggle('hidden')">
                        <i data-lucide="menu" class="w-5 h-5"></i>
                    </button>
                </div>
            </div>

            <div id="mobile-menu" class="md:hidden hidden border-t border-gray-100 py-3 space-y-1">
                <?php foreach ($categories as $cat): ?>
                <a href="<?= BASE_URL ?>?act=books&category=<?= urlencode($cat['name']) ?>"
                    class="w-full text-left px-4 py-2 text-sm text-[#333] hover:bg-gray-50 flex items-center gap-2 rounded-lg">
                    <span><?= $cat['icon'] ?></span> <?= $cat['name'] ?>
                </a>
                <?php endforeach; ?>

                <?php if ($isLoggedIn): ?>
                <a href="<?= BASE_URL ?>?act=account"
                    class="flex items-center gap-2 px-4 py-2 text-sm text-[#333] hover:bg-gray-50 rounded-lg">
                    <i data-lucide="user" class="w-4 h-4"></i> Tài khoản
                </a>
                <a href="<?= BASE_URL ?>?act=cart"
                    class="flex items-center gap-2 px-4 py-2 text-sm text-[#333] hover:bg-gray-50 rounded-lg">
                    <i data-lucide="shopping-cart" class="w-4 h-4"></i> Giỏ hàng
                </a>
                <?php else: ?>
                <a href="<?= BASE_URL ?>?act=login"
                    class="flex items-center gap-2 px-4 py-2 text-sm text-[#333] hover:bg-gray-50 rounded-lg">
                    <i data-lucide="log-in" class="w-4 h-4"></i> Đăng nhập
                </a>
                <a href="<?= BASE_URL ?>?act=register"
                    class="flex items-center gap-2 px-4 py-2 text-sm bg-[#4CAF50] text-white hover:bg-[#43A047] rounded-lg font-medium">
                    <i data-lucide="user-plus" class="w-4 h-4"></i> Tạo tài khoản
                </a>
                <?php endif; ?>
            </div>
        </div>
    </nav>

    <!-- Page content starts here -->