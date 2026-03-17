<?php
$error = Message::get('error');
$errors = $_SESSION['validation_errors'] ?? [];
unset($_SESSION['validation_errors']);
?>
<!DOCTYPE html>
<html lang="vi" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng ký - BookStore</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.min.js"></script>
</head>
<body class="h-full bg-[#F9F9F9] relative">

    <!-- Nút Quay lại -->
    <a href="<?= BASE_URL ?>"
        class="absolute top-4 left-4 sm:top-6 sm:left-6 flex items-center gap-2 text-gray-600 hover:text-[#4CAF50] transition-colors bg-white px-4 py-2 rounded-lg shadow-sm border border-gray-100 z-10">
        <i data-lucide="arrow-left" class="w-4 h-4"></i>
        <span class="text-sm font-medium">Quay lại trang chính</span>
    </a>

<div class="max-w-[1200px] mx-auto px-4 py-12">
    <div class="flex items-center justify-center min-h-[calc(100vh-200px)]">
        <div class="w-full max-w-lg">
            <!-- Logo -->
            <div class="text-center mb-8">
                <div class="inline-flex items-center justify-center w-16 h-16 bg-[#4CAF50] rounded-2xl mb-4 shadow-lg">
                    <i data-lucide="book-open" class="w-8 h-8 text-white"></i>
                </div>
                <h1 class="text-2xl font-bold text-gray-900">Book<span class="text-[#4CAF50]">Store</span></h1>
                <p class="text-sm text-gray-500 mt-1">Tạo tài khoản để bắt đầu mua sắm</p>
            </div>

            <!-- Card -->
            <div class="bg-white rounded-2xl shadow-xl p-8">
                <h2 class="text-xl font-semibold text-gray-800 mb-6">Tạo tài khoản</h2>

                <?php if ($error): ?>
                <div
                    class="mb-4 px-4 py-3 bg-red-50 border border-red-300 text-red-700 rounded-lg text-sm flex items-center gap-2">
                    <i data-lucide="triangle-alert" class="w-4 h-4 flex-shrink-0"></i>
                    <?= htmlspecialchars($error) ?>
                </div>
                <?php endif; ?>

                <form action="<?= BASE_URL ?>?act=check-register" method="POST" class="space-y-4" novalidate>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Họ và tên</label>
                        <div class="relative">
                            <i data-lucide="user" class="absolute left-3 top-2.5 w-5 h-5 text-gray-400"></i>
                            <input type="text" name="fullname" placeholder="Nguyễn Văn A" value="<?= old('fullname') ?>"
                                class="w-full pl-10 pr-4 py-2.5 border <?= isset($errors['fullname']) ? 'border-red-500' : 'border-gray-300' ?> rounded-lg focus:outline-none focus:ring-2 focus:ring-[#4CAF50] text-sm">
                        </div>
                        <?php if (isset($errors['fullname'])): ?>
                        <p class="text-red-500 text-xs mt-1">
                            <i data-lucide="alert-circle" class="w-3 h-3 inline"></i>
                            <?= htmlspecialchars($errors['fullname'][0]) ?>
                        </p>
                        <?php endif; ?>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                        <div class="relative">
                            <i data-lucide="mail" class="absolute left-3 top-2.5 w-5 h-5 text-gray-400"></i>
                            <input type="email" name="email" placeholder="email@example.com" value="<?= old('email') ?>"
                                class="w-full pl-10 pr-4 py-2.5 border <?= isset($errors['email']) ? 'border-red-500' : 'border-gray-300' ?> rounded-lg focus:outline-none focus:ring-2 focus:ring-[#4CAF50] text-sm">
                        </div>
                        <?php if (isset($errors['email'])): ?>
                        <p class="text-red-500 text-xs mt-1">
                            <i data-lucide="alert-circle" class="w-3 h-3 inline"></i>
                            <?= htmlspecialchars($errors['email'][0]) ?>
                        </p>
                        <?php endif; ?>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Số điện thoại</label>
                        <div class="relative">
                            <i data-lucide="phone" class="absolute left-3 top-2.5 w-5 h-5 text-gray-400"></i>
                            <input type="tel" name="phone" placeholder="0901234567" value="<?= old('phone') ?>"
                                class="w-full pl-10 pr-4 py-2.5 border <?= isset($errors['phone']) ? 'border-red-500' : 'border-gray-300' ?> rounded-lg focus:outline-none focus:ring-2 focus:ring-[#4CAF50] text-sm">
                        </div>
                        <?php if (isset($errors['phone'])): ?>
                        <p class="text-red-500 text-xs mt-1">
                            <i data-lucide="alert-circle" class="w-3 h-3 inline"></i>
                            <?= htmlspecialchars($errors['phone'][0]) ?>
                        </p>
                        <?php endif; ?>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Mật khẩu</label>
                        <div class="relative">
                            <i data-lucide="lock" class="absolute left-3 top-2.5 w-5 h-5 text-gray-400"></i>
                            <input type="password" name="password" id="password" placeholder="Tối thiểu 8 ký tự"
                                class="w-full pl-10 pr-4 py-2.5 border <?= isset($errors['password']) ? 'border-red-500' : 'border-gray-300' ?> rounded-lg focus:outline-none focus:ring-2 focus:ring-[#4CAF50] text-sm">
                        </div>
                        <?php if (isset($errors['password'])): ?>
                        <p class="text-red-500 text-xs mt-1">
                            <i data-lucide="alert-circle" class="w-3 h-3 inline"></i>
                            <?= htmlspecialchars($errors['password'][0]) ?>
                        </p>
                        <?php endif; ?>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Xác nhận mật khẩu</label>
                        <div class="relative">
                            <i data-lucide="lock" class="absolute left-3 top-2.5 w-5 h-5 text-gray-400"></i>
                            <input type="password" name="password_confirm" id="password_confirm"
                                placeholder="Nhập lại mật khẩu"
                                class="w-full pl-10 pr-4 py-2.5 border <?= isset($errors['password_confirm']) ? 'border-red-500' : 'border-gray-300' ?> rounded-lg focus:outline-none focus:ring-2 focus:ring-[#4CAF50] text-sm">
                        </div>
                        <?php if (isset($errors['password_confirm'])): ?>
                        <p class="text-red-500 text-xs mt-1">
                            <i data-lucide="alert-circle" class="w-3 h-3 inline"></i>
                            <?= htmlspecialchars($errors['password_confirm'][0]) ?>
                        </p>
                        <?php endif; ?>
                    </div>

                    <div class="flex items-start gap-3 pt-2">
                        <input type="checkbox" id="terms" name="terms" required
                            class="w-4 h-4 mt-0.5 rounded border-gray-300 text-[#4CAF50] focus:ring-[#4CAF50]">
                        <label for="terms" class="text-sm text-gray-600">
                            Tôi đồng ý với <a href="#" class="text-[#4CAF50] font-medium hover:underline">Điều khoản
                                dịch vụ
                                và Chính sách bảo mật</a>
                        </label>
                    </div>
                    <?php if (isset($errors['terms'])): ?>
                    <p class="text-red-500 text-xs mt-1">
                        <i data-lucide="alert-circle" class="w-3 h-3 inline"></i>
                        <?= htmlspecialchars($errors['terms'][0]) ?>
                    </p>
                    <?php endif; ?>

                    <button type="submit"
                        class="w-full bg-[#4CAF50] hover:bg-[#43A047] text-white font-semibold py-2.5 rounded-lg transition duration-150 flex items-center justify-center gap-2 mt-6">
                        <i data-lucide="user-plus" class="w-5 h-5"></i>
                        Đăng ký
                    </button>
                </form>

                <p class="text-center text-sm text-gray-500 mt-6">
                    Đã có tài khoản?
                    <a href="<?= BASE_URL ?>?act=login" class="text-[#4CAF50] font-medium hover:underline">Đăng nhập</a>
                </p>
            </div>

            <p class="text-center text-xs text-gray-400 mt-4">© <?= date('Y') ?> BookStore. Tất cả quyền được bảo lưu.
            </p>
        </div>
    </div>
</div>

<script>
    lucide.createIcons();

    // Ẩn thông báo lỗi khi người dùng bắt đầu nhập
    document.querySelectorAll('input').forEach(input => {
        input.addEventListener('input', function() {
            // Xóa viền đỏ
            this.classList.remove('border-red-500');
            this.classList.add('border-gray-300');
            
            // Ẩn dòng text lỗi ngay bên dưới
            const errorText = this.parentElement.nextElementSibling;
            if (errorText && errorText.tagName === 'P' && errorText.classList.contains('text-red-500')) {
                errorText.style.display = 'none';
            }
        });
    });

    // Xử lý riêng cho ô checkbox terms
    const termsCheckbox = document.getElementById('terms');
    if (termsCheckbox) {
        termsCheckbox.addEventListener('change', function() {
            const errorText = this.parentElement.nextElementSibling;
            if (errorText && errorText.tagName === 'P' && errorText.classList.contains('text-red-500')) {
                errorText.style.display = 'none';
            }
        });
    }
</script>
</body>
</html>