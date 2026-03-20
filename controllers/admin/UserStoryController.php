<?php
class UserStoryController
{
    private $userStoryModel;

    public function __construct()
    {
        $this->userStoryModel = new UserStoryModel();
    }

    public function list()
    {
        $search = trim($_GET['search'] ?? '');
        $epic = trim($_GET['epic'] ?? '');
        $status = trim($_GET['status'] ?? '');

        $page = (int) ($_GET['page'] ?? 1);
        $page = $page < 1 ? 1 : $page;

        $limit = 10;
        $offset = ($page - 1) * $limit;

        $sortField = trim($_GET['sort'] ?? 'rank');
        $sortDir = trim($_GET['dir'] ?? 'asc');

        $total = 0;
        $stories = $this->userStoryModel->getAll($search, $epic, $status, $limit, $offset, $total, $sortField, $sortDir);
        $totalPages = (int)ceil($total / $limit);

        require_once './views/admin/user_stories/list.php';
    }

    public function bulkAction()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('admin-user-stories');
        }

        $selected = $_POST['selected_ids'] ?? [];
        if (!is_array($selected) || empty($selected)) {
            Message::set('error', 'Vui lòng chọn ít nhất 1 user story.');
            redirect('admin-user-stories');
        }

        $action = $_POST['bulk_action'] ?? '';

        if ($action === 'mark_done') {
            $this->userStoryModel->toggleStatusMany($selected);
            Message::set('success', 'Đã chuyển trạng thái chọn thành Done/To do.');
        } elseif ($action === 'delete') {
            $this->userStoryModel->deleteMany($selected);
            Message::set('success', 'Đã xóa các user story được chọn.');
        } else {
            Message::set('error', 'Hành động không hợp lệ.');
        }

        redirect('admin-user-stories');
    }

    public function formCreate()
    {
        $validationErrors = $_SESSION['validation_errors'] ?? [];
        $old = $_SESSION['old'] ?? [];
        unset($_SESSION['validation_errors'], $_SESSION['old']);

        require_once './views/admin/user_stories/create.php';
    }

    public function create()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('admin-user-stories');
        }

        $id = trim($_POST['id'] ?? '');
        $epic = trim($_POST['epic'] ?? '');
        $userStory = trim($_POST['user_story'] ?? '');
        $as = trim($_POST['as'] ?? '');
        $iWant = trim($_POST['i_want'] ?? '');
        $soThat = trim($_POST['so_that'] ?? '');
        $priority = trim($_POST['priority'] ?? '');
        $storyPoint = (int)($_POST['story_point'] ?? 0);
        $rank = (int)($_POST['rank'] ?? 0);
        $status = trim($_POST['status'] ?? 'To do');

        $errors = [];

        if ($id === '') {
            $errors['id'] = 'Story ID không được để trống.';
        } elseif ($this->userStoryModel->getById($id)) {
            $errors['id'] = 'Story ID đã tồn tại.';
        }

        if ($userStory === '') {
            $errors['user_story'] = 'User story không được để trống.';
        }

        if (!in_array($status, ['To do', 'Done'], true)) {
            $status = 'To do';
        }

        if ($rank <= 0) {
            $errors['rank'] = 'Hạng (rank) phải là số lớn hơn 0.';
        }

        if (!empty($errors)) {
            $_SESSION['validation_errors'] = $errors;
            $_SESSION['old'] = compact('id', 'epic', 'userStory', 'as', 'iWant', 'soThat', 'priority', 'storyPoint', 'rank', 'status');
            redirect('admin-user-stories-create');
        }

        $created = $this->userStoryModel->create([
            'id' => $id,
            'epic' => $epic,
            'user_story' => $userStory,
            'as' => $as,
            'i_want' => $iWant,
            'so_that' => $soThat,
            'priority' => $priority,
            'story_point' => $storyPoint,
            'rank' => $rank,
            'status' => $status,
        ]);

        if ($created) {
            Message::set('success', 'Tạo user story thành công.');
        } else {
            Message::set('error', 'Tạo user story thất bại.');
        }

        redirect('admin-user-stories');
    }

    public function formEdit()
    {
        $id = trim($_GET['id'] ?? '');
        if ($id === '') {
            Message::set('error', 'ID user story không hợp lệ.');
            redirect('admin-user-stories');
        }

        $story = $this->userStoryModel->getById($id);
        if (!$story) {
            Message::set('error', 'User story không tồn tại.');
            redirect('admin-user-stories');
        }

        $validationErrors = $_SESSION['validation_errors'] ?? [];
        $old = $_SESSION['old'] ?? [];
        unset($_SESSION['validation_errors'], $_SESSION['old']);

        require_once './views/admin/user_stories/edit.php';
    }

    public function update()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('admin-user-stories');
        }

        $id = trim($_POST['id'] ?? '');
        $epic = trim($_POST['epic'] ?? '');
        $userStory = trim($_POST['user_story'] ?? '');
        $as = trim($_POST['as'] ?? '');
        $iWant = trim($_POST['i_want'] ?? '');
        $soThat = trim($_POST['so_that'] ?? '');
        $priority = trim($_POST['priority'] ?? '');
        $storyPoint = (int)($_POST['story_point'] ?? 0);
        $rank = (int)($_POST['rank'] ?? 0);
        $status = trim($_POST['status'] ?? 'To do');

        $errors = [];

        if ($id === '') {
            $errors['id'] = 'ID không hợp lệ.';
        }

        if ($userStory === '') {
            $errors['user_story'] = 'User story không được để trống.';
        }

        if ($rank <= 0) {
            $errors['rank'] = 'Hạng (rank) phải lớn hơn 0.';
        }

        if (!in_array($status, ['To do', 'Done'], true)) {
            $status = 'To do';
        }

        if (!empty($errors)) {
            $_SESSION['validation_errors'] = $errors;
            $_SESSION['old'] = compact('id', 'epic', 'userStory', 'as', 'iWant', 'soThat', 'priority', 'storyPoint', 'rank', 'status');
            redirect('admin-user-stories-edit&id=' . urlencode($id));
        }

        $updated = $this->userStoryModel->update($id, [
            'epic' => $epic,
            'user_story' => $userStory,
            'as' => $as,
            'i_want' => $iWant,
            'so_that' => $soThat,
            'priority' => $priority,
            'story_point' => $storyPoint,
            'rank' => $rank,
            'status' => $status,
        ]);

        if ($updated) {
            Message::set('success', 'Cập nhật user story thành công.');
        } else {
            Message::set('error', 'Cập nhật user story thất bại.');
        }

        redirect('admin-user-stories');
    }

    public function delete()
    {
        $id = trim($_GET['id'] ?? '');
        if ($id === '') {
            Message::set('error', 'ID user story không hợp lệ.');
            redirect('admin-user-stories');
        }

        $deleted = $this->userStoryModel->delete($id);

        if ($deleted) {
            Message::set('success', 'Xóa user story thành công.');
        } else {
            Message::set('error', 'Xóa user story thất bại.');
        }

        redirect('admin-user-stories');
    }

    public function toggleStatus()
    {
        $id = trim($_GET['id'] ?? '');

        if ($id === '') {
            Message::set('error', 'ID user story không hợp lệ.');
            redirect('admin-user-stories');
        }

        $toggled = $this->userStoryModel->toggleStatus($id);

        if ($toggled) {
            Message::set('success', 'Đã chuyển trạng thái thành công.');
        } else {
            Message::set('error', 'Chuyển trạng thái thất bại.');
        }

        redirect('admin-user-stories');
    }
}
