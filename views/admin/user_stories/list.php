<?php
require_once './views/components/header.php';
require_once './views/components/sidebar.php';

$successMessage = Message::get('success');
$errorMessage = Message::get('error');

$search = htmlspecialchars($_GET['search'] ?? '');
$epicFilter = htmlspecialchars($_GET['epic'] ?? '');
$statusFilter = htmlspecialchars($_GET['status'] ?? '');
$sortField = htmlspecialchars($_GET['sort'] ?? 'rank');
$sortDir = htmlspecialchars($_GET['dir'] ?? 'asc');
$currentPage = $page;

function buildSortUrl($field, $currentSort, $currentDir) {
    $newDir = 'asc';
    if ($field === $currentSort) {
        $newDir = $currentDir === 'asc' ? 'desc' : 'asc';
    }
    return BASE_URL . 'admin-user-stories?sort=' . urlencode($field) . '&dir=' . urlencode($newDir);
}
?>

<main class="flex-1 overflow-y-auto p-5">
  <div class="mb-4 flex items-center justify-between">
    <div>
      <h1 class="text-xl font-bold text-[#333]">Quản lý Backlog (User Stories)</h1>
      <p class="text-sm text-gray-500">Xem/sửa/xóa user story theo yêu cầu</p>
    </div>
    <a href="<?= BASE_URL ?>admin-user-stories-create" class="bg-[#4CAF50] text-white px-4 py-2 rounded-lg hover:bg-[#3c9744]">+ Thêm user story</a>
  </div>

  <?php if ($successMessage): ?>
    <div class="mb-4 px-4 py-3 rounded-lg bg-green-100 text-green-700 border border-green-200"><?= $successMessage ?></div>
  <?php endif; ?>
  <?php if ($errorMessage): ?>
    <div class="mb-4 px-4 py-3 rounded-lg bg-red-100 text-red-700 border border-red-200"><?= $errorMessage ?></div>
  <?php endif; ?>

  <form method="GET" class="mb-4 flex flex-wrap gap-3 items-end">
    <input type="hidden" name="act" value="admin-user-stories">
    <div>
      <label class="text-xs text-gray-600">Tìm kiếm</label><br>
      <input type="text" name="search" value="<?= $search ?>" placeholder="ID/Epic/User Story" class="border border-gray-300 rounded px-3 py-2 w-[240px]" />
    </div>
    <div>
      <label class="text-xs text-gray-600">Epic</label><br>
      <input type="text" name="epic" value="<?= $epicFilter ?>" placeholder="Ví dụ: Tài khoản" class="border border-gray-300 rounded px-3 py-2 w-[200px]" />
    </div>
    <div>
      <label class="text-xs text-gray-600">Trạng thái</label><br>
      <select name="status" class="border border-gray-300 rounded px-3 py-2 w-[140px]">
        <option value="">Tất cả</option>
        <option value="To do" <?= $statusFilter === 'To do' ? 'selected' : '' ?>>To do</option>
        <option value="Done" <?= $statusFilter === 'Done' ? 'selected' : '' ?>>Done</option>
      </select>
    </div>
    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Lọc</button>
    <a href="<?= BASE_URL ?>admin-user-stories" class="text-sm text-gray-500 hover:text-gray-700">Xóa bộ lọc</a>
  </form>

  <?php if (empty($stories)): ?>
    <div class="p-6 bg-white rounded-lg shadow text-center text-gray-400">Không tìm thấy user story nào.</div>
  <?php else: ?>
    <form method="POST" action="<?= BASE_URL ?>admin-user-stories-bulk">
      <div class="mb-2 flex items-center gap-2">
        <select name="bulk_action" class="border border-gray-300 rounded px-2 py-1">
          <option value="">Chọn hành động</option>
          <option value="mark_done">Toggle trạng thái</option>
          <option value="delete">Xóa chọn</option>
        </select>
        <button type="submit" class="bg-blue-600 text-white px-3 py-1 rounded">Thực hiện</button>
        <span class="text-xs text-gray-500">* Chọn ít nhất 1 dòng</span>
      </div>

      <div class="overflow-x-auto bg-white rounded-lg shadow">
        <table class="w-full border-collapse">
          <thead class="bg-[#f3f4f6]">
            <tr>
              <th class="px-3 py-2 border"><input type="checkbox" id="select_all" onclick="document.querySelectorAll('.select-row').forEach(cb => cb.checked = this.checked);" /></th>
              <th class="px-3 py-2 border"><a href="<?= buildSortUrl('id', $sortField, $sortDir) ?>">ID<?= $sortField === 'id' ? ($sortDir === 'asc' ? ' ↑' : ' ↓') : '' ?></a></th>
              <th class="px-3 py-2 border"><a href="<?= buildSortUrl('epic', $sortField, $sortDir) ?>">Epic<?= $sortField === 'epic' ? ($sortDir === 'asc' ? ' ↑' : ' ↓') : '' ?></a></th>
              <th class="px-3 py-2 border">User Story</th>
              <th class="px-3 py-2 border">As</th>
              <th class="px-3 py-2 border">I want</th>
              <th class="px-3 py-2 border">So that</th>
              <th class="px-3 py-2 border"><a href="<?= buildSortUrl('priority', $sortField, $sortDir) ?>">Priority<?= $sortField === 'priority' ? ($sortDir === 'asc' ? ' ↑' : ' ↓') : '' ?></a></th>
              <th class="px-3 py-2 border">Story Point</th>
              <th class="px-3 py-2 border"><a href="<?= buildSortUrl('rank', $sortField, $sortDir) ?>">Rank<?= $sortField === 'rank' ? ($sortDir === 'asc' ? ' ↑' : ' ↓') : '' ?></a></th>
              <th class="px-3 py-2 border"><a href="<?= buildSortUrl('status', $sortField, $sortDir) ?>">Status<?= $sortField === 'status' ? ($sortDir === 'asc' ? ' ↑' : ' ↓') : '' ?></a></th>
              <th class="px-3 py-2 border">Action</th>
            </tr>
          </thead>
          <tbody>
          <?php foreach ($stories as $story): ?>
            <tr class="hover:bg-gray-50">
              <td class="px-3 py-2 border"><input type="checkbox" class="select-row" name="selected_ids[]" value="<?= htmlspecialchars($story['id']) ?>" /></td>
              <td class="px-3 py-2 border text-sm font-semibold"><?= htmlspecialchars($story['id']) ?></td>
              <td class="px-3 py-2 border text-sm"><?= htmlspecialchars($story['epic']) ?></td>
              <td class="px-3 py-2 border text-sm"><?= htmlspecialchars($story['user_story']) ?></td>
              <td class="px-3 py-2 border text-sm"><?= htmlspecialchars($story['as']) ?></td>
              <td class="px-3 py-2 border text-sm"><?= htmlspecialchars($story['i_want']) ?></td>
              <td class="px-3 py-2 border text-sm"><?= htmlspecialchars($story['so_that']) ?></td>
              <td class="px-3 py-2 border text-sm"><?= htmlspecialchars($story['priority']) ?></td>
              <td class="px-3 py-2 border text-sm"><?= htmlspecialchars($story['story_point']) ?></td>
              <td class="px-3 py-2 border text-sm"><?= htmlspecialchars($story['rank']) ?></td>
              <td class="px-3 py-2 border text-sm">
                <span class="px-2 py-1 rounded <?= $story['status'] === 'Done' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' ?>"><?= htmlspecialchars($story['status']) ?></span>
              </td>
              <td class="px-3 py-2 border text-sm space-x-1">
                <a href="<?= BASE_URL ?>admin-user-stories-edit&id=<?= urlencode($story['id']) ?>" class="px-2 py-1 bg-blue-600 text-white rounded text-xs">Sửa</a>
                <a href="<?= BASE_URL ?>admin-user-stories-delete&id=<?= urlencode($story['id']) ?>" class="px-2 py-1 bg-red-500 text-white rounded text-xs" onclick="return confirm('Bạn muốn xóa user story <?= htmlspecialchars($story['id']) ?>?');">Xóa</a>
                <a href="<?= BASE_URL ?>admin-user-stories-toggle&id=<?= urlencode($story['id']) ?>" class="px-2 py-1 bg-gray-600 text-white rounded text-xs">Chuyển</a>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </form>

    <?php if ($totalPages > 1): ?>
      <div class="mt-4 flex justify-center gap-1"> 
        <?php for ($i=1; $i<=$totalPages; $i++): ?>
          <a href="<?= BASE_URL ?>admin-user-stories?page=<?= $i ?>&search=<?= urlencode($search) ?>&epic=<?= urlencode($epicFilter) ?>&status=<?= urlencode($statusFilter) ?>" class="px-3 py-1 rounded <?= $i === $currentPage ? 'bg-[#4CAF50] text-white' : 'bg-gray-100 text-gray-700' ?>"><?= $i ?></a>
        <?php endfor; ?>
      </div>
    <?php endif; ?>
  <?php endif; ?>
</main>

<?php require_once './views/components/footer.php'; ?>