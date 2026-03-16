<!DOCTYPE html>
<html lang="vi" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng nhập Quản trị - BookStore</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
</head>
<body class="h-full flex items-center justify-center bg-[#F4F7F6]">

    <div class="w-full max-w-md bg-white rounded-3xl p-8 shadow-xl border border-gray-100 mx-4">
        
        <!-- Logo -->
        <div class="flex items-center justify-center gap-3 mb-8">
            <div class="w-12 h-12 bg-[#4CAF50] rounded-xl flex items-center justify-center shadow-lg shadow-[#4CAF50]/30 text-white">
                <i data-lucide="shield-check" class="w-6 h-6"></i>
            </div>
            <span class="text-2xl font-bold text-[#1B2537] tracking-tight">Admin<span class="text-[#4CAF50]">Portal</span></span>
        </div>

        <div class="text-center mb-8">
            <h1 class="text-2xl font-bold text-gray-900">Chào mừng trở lại!</h1>
            <p class="text-sm text-gray-500 mt-2">Đăng nhập tài khoản Quản trị viên để tiếp tục</p>
        </div>

        <?php if ($msg = Message::get('error')): ?>
            <div class="mb-6 p-4 text-sm font-medium text-red-800 rounded-2xl bg-red-50 border border-red-100 flex items-center gap-3">
                <i data-lucide="alert-circle" class="w-5 h-5 shrink-0"></i>
                <?= $msg ?>
            </div>
        <?php endif; ?>
        
        <?php if ($msg = Message::get('success')): ?>
            <div class="mb-6 p-4 text-sm font-medium text-green-800 rounded-2xl bg-green-50 border border-green-100 flex items-center gap-3">
                <i data-lucide="check-circle-2" class="w-5 h-5 shrink-0"></i>
                <?= $msg ?>
            </div>
        <?php endif; ?>

        <form action="<?= BASE_URL ?>?act=check-admin-login" method="POST" class="space-y-5">
            
            <div class="space-y-1.5">
                <label for="email" class="block text-sm font-medium text-gray-700 ml-1">Email quản trị</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400">
                        <i data-lucide="mail" class="w-5 h-5"></i>
                    </div>
                    <input type="email" name="email" id="email" 
                           value="<?= old('email') ?>"
                           class="block w-full pl-11 pr-4 py-3 bg-gray-50 border border-transparent rounded-2xl text-sm transition-all focus:bg-white focus:border-[#4CAF50] focus:ring-4 focus:ring-[#4CAF50]/10 outline-none" 
                           placeholder="admin@example.com" required>
                </div>
            </div>

            <div class="space-y-1.5">
                <label for="password" class="block text-sm font-medium text-gray-700 ml-1">Mật khẩu</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400">
                        <i data-lucide="lock" class="w-5 h-5"></i>
                    </div>
                    <input type="password" name="password" id="password" 
                           class="block w-full pl-11 pr-12 py-3 bg-gray-50 border border-transparent rounded-2xl text-sm transition-all focus:bg-white focus:border-[#4CAF50] focus:ring-4 focus:ring-[#4CAF50]/10 outline-none" 
                           placeholder="••••••••" required>
                    <button type="button" onclick="togglePassword()" class="absolute inset-y-0 right-0 pr-4 flex items-center text-gray-400 hover:text-gray-600 focus:outline-none">
                        <i data-lucide="eye" id="eye-icon" class="w-5 h-5"></i>
                    </button>
                </div>
            </div>

            <div class="pt-2">
                <button type="submit" class="w-full flex justify-center items-center py-3.5 px-4 border border-transparent rounded-2xl shadow-sm text-sm font-medium text-white bg-[#1B2537] hover:bg-gray-800 focus:outline-none focus:ring-4 focus:ring-[#1B2537]/20 transition-all">
                    Đăng nhập Admin
                    <i data-lucide="arrow-right" class="w-4 h-4 ml-2"></i>
                </button>
            </div>
            
        </form>
        
        <div class="mt-8 text-center">
            <a href="<?= BASE_URL ?>" class="text-sm font-medium text-gray-500 hover:text-[#4CAF50] transition-colors inline-flex items-center gap-1.5">
                <i data-lucide="arrow-left" class="w-4 h-4"></i> Quay lại trang khách
            </a>
        </div>
    </div>

    <script>
        lucide.createIcons();

        function togglePassword() {
            const pwdInput = document.getElementById('password');
            const eyeIcon = document.getElementById('eye-icon');
            
            if (pwdInput.type === 'password') {
                pwdInput.type = 'text';
                eyeIcon.setAttribute('data-lucide', 'eye-off');
            } else {
                pwdInput.type = 'password';
                eyeIcon.setAttribute('data-lucide', 'eye');
            }
            lucide.createIcons();
        }
    </script>
</body>
</html>
