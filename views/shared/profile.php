<?php
require_once './views/components/header.php';
require_once './views/components/sidebar.php';

$currentUser = $_SESSION['currentUser'];
$avatarUrl   = !empty($user['avatar']) ? UPLOADS_URL . ltrim($user['avatar'], 'uploads/') : null;
$avatarLetter = strtoupper(mb_substr($user['fullname'], 0, 1));
?>

<main class="mt-24 px-6 pb-6 pt-4">
  <div class="max-w-2xl mx-auto">

    <!-- Header -->
    <div class="mb-6 flex items-center justify-between">
      <h2 class="text-2xl font-bold text-gray-900">Thông tin cá nhân</h2>
      <a href="?act=profile-edit" class="inline-flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition">
        <i data-lucide="pencil" class="w-4 h-4"></i>
        Chỉnh sửa
      </a>
    </div>

    <!-- Profile card -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
      <!-- Cover -->
      <div class="h-24 bg-gradient-to-r from-indigo-500 to-purple-600"></div>

      <!-- Avatar + Info -->
      <div class="px-6 pb-6">
        <div class="-mt-10 mb-4">
          <?php if ($avatarUrl): ?>
            <img src="<?= $avatarUrl ?>" alt="Avatar"
              class="w-20 h-20 rounded-full border-4 border-white shadow object-cover">
          <?php else: ?>
            <div class="w-20 h-20 rounded-full border-4 border-white shadow bg-indigo-600 flex items-center justify-center text-white text-2xl font-bold">
              <?= $avatarLetter ?>
            </div>
          <?php endif; ?>
        </div>

        <h3 class="text-xl font-bold text-gray-900"><?= htmlspecialchars($user['fullname']) ?></h3>
        <p class="text-sm text-gray-500 mb-4"><?= $user['roles'] === 'admin' ? 'Quản trị viên' : 'Nhân viên' ?></p>

        <dl class="grid grid-cols-2 gap-4">
          <div class="bg-gray-50 rounded-xl p-4">
            <dt class="text-xs text-gray-500 mb-1 flex items-center gap-1">
              <i data-lucide="mail" class="w-3 h-3"></i> Email
            </dt>
            <dd class="text-sm font-medium text-gray-800"><?= htmlspecialchars($user['email']) ?></dd>
          </div>
          <div class="bg-gray-50 rounded-xl p-4">
            <dt class="text-xs text-gray-500 mb-1 flex items-center gap-1">
              <i data-lucide="shield" class="w-3 h-3"></i> Vai trò
            </dt>
            <dd class="text-sm font-medium text-gray-800"><?= $user['roles'] === 'admin' ? 'Admin' : 'User' ?></dd>
          </div>
          <div class="bg-gray-50 rounded-xl p-4">
            <dt class="text-xs text-gray-500 mb-1 flex items-center gap-1">
              <i data-lucide="calendar" class="w-3 h-3"></i> Ngày tạo
            </dt>
            <dd class="text-sm font-medium text-gray-800"><?= isset($user['created_at']) ? date('d/m/Y', strtotime($user['created_at'])) : '--' ?></dd>
          </div>
          <div class="bg-gray-50 rounded-xl p-4">
            <dt class="text-xs text-gray-500 mb-1 flex items-center gap-1">
              <i data-lucide="activity" class="w-3 h-3"></i> Trạng thái
            </dt>
            <dd>
              <?php if ($user['status'] == 1): ?>
                <span class="px-2 py-0.5 text-xs font-medium bg-green-100 text-green-700 rounded-full">Hoạt động</span>
              <?php else: ?>
                <span class="px-2 py-0.5 text-xs font-medium bg-red-100 text-red-700 rounded-full">Bị khóa</span>
              <?php endif; ?>
            </dd>
          </div>
        </dl>
      </div>
    </div>

    <!-- Đổi mật khẩu -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mt-5">
      <h3 class="text-base font-semibold text-gray-800 mb-4 flex items-center gap-2">
        <i data-lucide="lock" class="w-4 h-4 text-indigo-500"></i>
        Đổi mật khẩu
      </h3>
      <form action="<?= BASE_URL ?>?act=change-password" method="POST" class="space-y-4">
        <div>
          <label class="block text-sm text-gray-600 mb-1">Mật khẩu cũ</label>
          <input type="password" name="old_password" required
            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 text-sm">
        </div>
        <div>
          <label class="block text-sm text-gray-600 mb-1">Mật khẩu mới</label>
          <input type="password" name="new_password" required
            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 text-sm">
        </div>
        <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2 rounded-lg text-sm font-medium transition">
          Cập nhật mật khẩu
        </button>
      </form>
    </div>

  </div>
</main>

<?php require_once './views/components/footer.php'; ?>
