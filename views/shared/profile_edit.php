<?php
require_once './views/components/header.php';
require_once './views/components/sidebar.php';

$avatarUrl = !empty($user['avatar']) ? UPLOADS_URL . ltrim($user['avatar'], 'uploads/') : null;
$avatarLetter = strtoupper(mb_substr($user['fullname'], 0, 1));
?>

<main class="mt-24 px-6 pb-6 pt-4">
  <div class="max-w-2xl mx-auto">
    <div class="mb-6 flex items-center gap-2">
      <a href="?act=profile" class="text-gray-500 hover:text-indigo-600 transition">
        <i data-lucide="arrow-left" class="w-5 h-5"></i>
      </a>
      <h2 class="text-2xl font-bold text-gray-900">Chỉnh sửa thông tin</h2>
    </div>

    <form action="<?= BASE_URL ?>?act=profile-update" method="POST" enctype="multipart/form-data"
      class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 space-y-5">

      <!-- Avatar -->
      <div class="flex items-center gap-4">
        <div id="preview-wrap">
          <?php if ($avatarUrl): ?>
            <img id="avatar-preview" src="<?= $avatarUrl ?>" alt="Avatar"
              class="w-16 h-16 rounded-full border-2 border-indigo-200 object-cover">
          <?php else: ?>
            <div class="w-16 h-16 rounded-full bg-indigo-600 flex items-center justify-center text-white text-xl font-bold border-2 border-indigo-200">
              <?= $avatarLetter ?>
            </div>
          <?php endif; ?>
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Ảnh đại diện</label>
          <input type="file" name="avatar" accept="image/*"
            onchange="previewImage(event)"
            class="text-sm text-gray-500 file:mr-3 file:py-1 file:px-3 file:rounded-lg file:border-0 file:text-sm file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
        </div>
      </div>

      <!-- Fullname -->
      <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Họ và tên</label>
        <input type="text" name="fullname" required
          value="<?= htmlspecialchars(old('fullname', $user['fullname'])) ?>"
          class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 text-sm">
      </div>

      <!-- Email -->
      <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
        <input type="email" name="email" required
          value="<?= htmlspecialchars(old('email', $user['email'])) ?>"
          class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 text-sm">
      </div>

      <div class="flex gap-3 pt-2">
        <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2.5 rounded-lg text-sm font-medium transition flex items-center gap-2">
          <i data-lucide="save" class="w-4 h-4"></i>
          Lưu thay đổi
        </button>
        <a href="?act=profile" class="px-6 py-2.5 border border-gray-300 text-gray-600 rounded-lg text-sm font-medium hover:bg-gray-50 transition">
          Hủy
        </a>
      </div>
    </form>
  </div>
</main>

<script>
  function previewImage(event) {
    const file = event.target.files[0];
    if (!file) return;
    const reader = new FileReader();
    reader.onload = function(e) {
      const wrap = document.getElementById('preview-wrap');
      wrap.innerHTML = `<img id="avatar-preview" src="${e.target.result}" class="w-16 h-16 rounded-full border-2 border-indigo-200 object-cover" alt="Preview">`;
    };
    reader.readAsDataURL(file);
  }
</script>

<?php require_once './views/components/footer.php'; ?>
