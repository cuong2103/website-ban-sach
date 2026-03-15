<?php
require_once './views/components/navbar.php';
$error = Message::get('error');
?>

<div class="max-w-[1200px] mx-auto px-4 py-12">
    <div class="flex items-center justify-center min-h-[calc(100vh-200px)]">
        <div class="w-full max-w-md">
            <!-- Logo -->
            <div class="text-center mb-8">
                <div class="inline-flex items-center justify-center w-16 h-16 bg-[#4CAF50] rounded-2xl mb-4 shadow-lg">
                    <i data-lucide="book-open" class="w-8 h-8 text-white"></i>
                </div>
                <h1 class="text-2xl font-bold text-gray-900">Book<span class="text-[#4CAF50]">Store</span></h1>
                <p class="text-sm text-gray-500 mt-1">Nhà sách trực tuyến uy tín</p>
            </div>

            <!-- Card -->
            <div class="bg-white rounded-2xl shadow-xl p-8">
                <h2 class="text-xl font-semibold text-gray-800 mb-6">Đăng nhập</h2>

                <?php if ($error): ?>
                <div
                    class="mb-4 px-4 py-3 bg-red-50 border border-red-300 text-red-700 rounded-lg text-sm flex items-center gap-2">
                    <i data-lucide="triangle-alert" class="w-4 h-4 flex-shrink-0"></i>
                    <?= htmlspecialchars($error) ?>
                </div>
                <?php endif; ?>

                <form action="<?= BASE_URL ?>?act=check-login" method="POST" class="space-y-5">
                    <!-- Email -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                        <div class="relative">
                            <i data-lucide="mail" class="absolute left-3 top-2.5 w-5 h-5 text-gray-400"></i>
                            <input type="email" name="email" required placeholder="email@example.com"
                                value="<?= old('email') ?>"
                                class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#4CAF50] text-sm">
                        </div>
                    </div>

                    <!-- Password -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Mật khẩu</label>
                        <div class="relative">
                            <i data-lucide="lock" class="absolute left-3 top-2.5 w-5 h-5 text-gray-400"></i>
                            <input type="password" name="password" required placeholder="••••••••"
                                class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#4CAF50] text-sm">
                        </div>
                    </div>

                    <button type="submit"
                        class="w-full bg-[#4CAF50] hover:bg-[#43A047] text-white font-semibold py-2.5 rounded-lg transition duration-150 flex items-center justify-center gap-2">
                        <i data-lucide="log-in" class="w-5 h-5"></i>
                        Đăng nhập
                    </button>
                </form>

                <p class="text-center text-sm text-gray-500 mt-6">
                    Chưa có tài khoản?
                    <a href="<?= BASE_URL ?>?act=register" class="text-[#4CAF50] font-medium hover:underline">Đăng ký
                        ngay</a>
                </p>
            </div>

        </div>
    </div>
</div>

<?php require_once './views/components/customer_footer.php'; ?>