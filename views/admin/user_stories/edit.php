<?php
require_once './views/components/header.php';
require_once './views/components/sidebar.php';

$validationErrors = $validationErrors ?? [];
$old = $old ?? [];

$story = $story ?? null;

function oldValue($key, $default = '') {
    global $old, $story;
    if (isset($old[$key]) && $old[$key] !== '') {
        return htmlspecialchars($old[$key]);
    }
    if ($story && isset($story[$key])) {
        return htmlspecialchars($story[$key]);
    }
    return htmlspecialchars($default);
}
?>

<main class="flex-1 overflow-y-auto p-5">
  <div class="max-w-3xl mx-auto bg-white rounded-lg shadow p-6">
    <h1 class="text-xl font-bold mb-4">Sửa User Story</h1>
    <a href="<?= BASE_URL ?>admin-user-stories" class="text-blue-500 text-sm mb-4 inline-block">← Quay lại</a>

    <form method="POST" action="<?= BASE_URL ?>admin-user-stories-update" class="space-y-4">
      <input type="hidden" name="id" value="<?= oldValue('id') ?>" />

      <div>
        <label class="block text-sm font-medium">ID</label>
        <input type="text" class="w-full border rounded px-3 py-2 bg-gray-100" value="<?= oldValue('id') ?>" disabled>
      </div>

      <div>
        <label class="block text-sm font-medium">Epic</label>
        <input type="text" name="epic" class="w-full border rounded px-3 py-2" value="<?= oldValue('epic') ?>">
      </div>

      <div>
        <label class="block text-sm font-medium">User Story</label>
        <input type="text" name="user_story" class="w-full border rounded px-3 py-2" value="<?= oldValue('user_story') ?>" required>
        <?php if (!empty($validationErrors['user_story'])): ?><div class="text-red-600 text-xs mt-1"><?= $validationErrors['user_story'] ?></div><?php endif; ?>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
          <label class="block text-sm font-medium">As</label>
          <input type="text" name="as" class="w-full border rounded px-3 py-2" value="<?= oldValue('as') ?>">
        </div>
        <div>
          <label class="block text-sm font-medium">I want</label>
          <input type="text" name="i_want" class="w-full border rounded px-3 py-2" value="<?= oldValue('i_want') ?>">
        </div>
      </div>

      <div>
        <label class="block text-sm font-medium">So that</label>
        <input type="text" name="so_that" class="w-full border rounded px-3 py-2" value="<?= oldValue('so_that') ?>">
      </div>

      <div class="grid grid-cols-2 gap-4">
        <div>
          <label class="block text-sm font-medium">Priority</label>
          <?php $priority = oldValue('priority'); ?>
          <select name="priority" class="w-full border rounded px-3 py-2">
            <option value="">Chọn</option>
            <option value="Cao" <?= $priority === 'Cao' ? 'selected' : ''; ?>>Cao</option>
            <option value="Trung" <?= $priority === 'Trung' ? 'selected' : ''; ?>>Trung</option>
            <option value="Thấp" <?= $priority === 'Thấp' ? 'selected' : ''; ?>>Thấp</option>
          </select>
        </div>
        <div>
          <label class="block text-sm font-medium">Story Point</label>
          <input type="number" min="1" name="story_point" class="w-full border rounded px-3 py-2" value="<?= oldValue('story_point', 1) ?>">
        </div>
      </div>

      <div class="grid grid-cols-2 gap-4">
        <div>
          <label class="block text-sm font-medium">Rank</label>
          <input type="number" min="1" name="rank" class="w-full border rounded px-3 py-2" value="<?= oldValue('rank', 1) ?>" required>
          <?php if (!empty($validationErrors['rank'])): ?><div class="text-red-600 text-xs mt-1"><?= $validationErrors['rank'] ?></div><?php endif; ?>
        </div>
        <div>
          <label class="block text-sm font-medium">Status</label>
          <?php $status = oldValue('status', 'To do'); ?>
          <select name="status" class="w-full border rounded px-3 py-2">
            <option value="To do" <?= $status === 'To do' ? 'selected' : ''; ?>>To do</option>
            <option value="Done" <?= $status === 'Done' ? 'selected' : ''; ?>>Done</option>
          </select>
        </div>
      </div>

      <div class="flex gap-3 mt-4">
        <button type="submit" class="px-4 py-2 bg-[#4CAF50] text-white rounded">Lưu thay đổi</button>
        <a href="<?= BASE_URL ?>admin-user-stories" class="px-4 py-2 border rounded">Hủy</a>
      </div>
    </form>
  </div>
</main>

<?php require_once './views/components/footer.php'; ?>