<?php
class CategoryController
{
    private $categoryModel;

    public function __construct()
    {
        $this->categoryModel = new CategoryModel();
    }

    /**
     * Danh sách danh mục
     */
    public function list()
    {
        $search = trim($_GET['search'] ?? '');
        $date = trim($_GET['date'] ?? '');
        $page = (int) ($_GET['page'] ?? 1);
        $page = $page < 1 ? 1 : $page;
        $limit = 10;
        $offset = ($page - 1) * $limit;

        $categories = $this->categoryModel->getAll($search, $date, $limit, $offset);
        $total = $this->categoryModel->countAll($search, $date);
        $totalPages = ceil($total / $limit);

        require_once './views/admin/categories/list.php';
    }

    /**
     * Xem chi tiết danh mục
     */
    public function detail()
    {
        $id = (int) ($_GET['id'] ?? 0);

        if ($id <= 0) {
            Message::set('error', 'Danh mục không hợp lệ.');
            redirect('admin-categories');
        }

        $category = $this->categoryModel->getById($id);

        if (!$category) {
            Message::set('error', 'Danh mục không tồn tại.');
            redirect('admin-categories');
        }

        require_once './views/admin/categories/detail.php';
    }

    /**
     * Form tạo mới danh mục
     */
    public function formCreate()
    {
        require_once './views/admin/categories/create.php';
    }

    /**
     * Xử lý tạo mới danh mục
     */
    public function create()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('admin-categories');
        }

        $name = trim($_POST['name'] ?? '');
        $slug = trim($_POST['slug'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $status = (int) ($_POST['status'] ?? 1);

        // Validate
        $errors = [];

        if (empty($name)) {
            $errors['name'] = 'Tên danh mục không được bỏ trống.';
        } elseif (strlen($name) > 150) {
            $errors['name'] = 'Tên danh mục không được vượt quá 150 ký tự.';
        }

        if (empty($slug)) {
            $errors['slug'] = 'Slug không được bỏ trống.';
        } elseif (strlen($slug) > 150) {
            $errors['slug'] = 'Slug không được vượt quá 150 ký tự.';
        } elseif (!preg_match('/^[a-z0-9-]+$/', $slug)) {
            $errors['slug'] = 'Slug chỉ có thể chứa chữ thường, số và dấu gạch ngang.';
        } elseif ($this->categoryModel->checkSlugExists($slug)) {
            $errors['slug'] = 'Slug này đã tồn tại.';
        }

        if (!empty($errors)) {
            $_SESSION['validation_errors'] = $errors;
            $_SESSION['old'] = [
                'name' => $name,
                'slug' => $slug,
                'description' => $description,
                'status' => $status
            ];
            redirect('admin-categories-create');
        }

        // Tạo danh mục
        $result = $this->categoryModel->create([
            'name' => $name,
            'slug' => $slug,
            'description' => $description,
            'status' => $status
        ]);

        if ($result['ok']) {
            Message::set('success', $result['message']);
            unset($_SESSION['old']);
            unset($_SESSION['validation_errors']);
        } else {
            Message::set('error', $result['message']);
        }

        redirect('admin-categories');
    }

    /**
     * Form sửa danh mục
     */
    public function formEdit()
    {
        $id = (int) ($_GET['id'] ?? 0);

        if ($id <= 0) {
            Message::set('error', 'Danh mục không hợp lệ.');
            redirect('admin-categories');
        }

        $category = $this->categoryModel->getById($id);

        if (!$category) {
            Message::set('error', 'Danh mục không tồn tại.');
            redirect('admin-categories');
        }

        require_once './views/admin/categories/edit.php';
    }

    /**
     * Xử lý cập nhật danh mục
     */
    public function update()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('admin-categories');
        }

        $id = (int) ($_POST['id'] ?? 0);
        $name = trim($_POST['name'] ?? '');
        $slug = trim($_POST['slug'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $status = (int) ($_POST['status'] ?? 1);

        // Validate ID
        if ($id <= 0) {
            Message::set('error', 'Danh mục không hợp lệ.');
            redirect('admin-categories');
        }

        $category = $this->categoryModel->getById($id);
        if (!$category) {
            Message::set('error', 'Danh mục không tồn tại.');
            redirect('admin-categories');
        }

        // Validate dữ liệu
        $errors = [];

        if (empty($name)) {
            $errors['name'] = 'Tên danh mục không được bỏ trống.';
        } elseif (strlen($name) > 150) {
            $errors['name'] = 'Tên danh mục không được vượt quá 150 ký tự.';
        }

        if (empty($slug)) {
            $errors['slug'] = 'Slug không được bỏ trống.';
        } elseif (strlen($slug) > 150) {
            $errors['slug'] = 'Slug không được vượt quá 150 ký tự.';
        } elseif (!preg_match('/^[a-z0-9-]+$/', $slug)) {
            $errors['slug'] = 'Slug chỉ có thể chứa chữ thường, số và dấu gạch ngang.';
        } elseif ($this->categoryModel->checkSlugExists($slug, $id)) {
            $errors['slug'] = 'Slug này đã tồn tại.';
        }

        if (!empty($errors)) {
            $_SESSION['validation_errors'] = $errors;
            $_SESSION['old'] = [
                'name' => $name,
                'slug' => $slug,
                'description' => $description,
                'status' => $status
            ];
            redirect('admin-categories-edit&id=' . $id);
        }

        // Cập nhật danh mục
        $result = $this->categoryModel->update($id, [
            'name' => $name,
            'slug' => $slug,
            'description' => $description,
            'status' => $status
        ]);

        if ($result['ok']) {
            Message::set('success', $result['message']);
            unset($_SESSION['old']);
            unset($_SESSION['validation_errors']);
        } else {
            Message::set('error', $result['message']);
        }

        redirect('admin-categories');
    }

    /**
     * Xóa danh mục
     */
    public function delete()
    {
        $id = (int) ($_GET['id'] ?? 0);

        if ($id <= 0) {
            Message::set('error', 'Danh mục không hợp lệ.');
            redirect('admin-categories');
        }

        $category = $this->categoryModel->getById($id);
        if (!$category) {
            Message::set('error', 'Danh mục không tồn tại.');
            redirect('admin-categories');
        }

        $result = $this->categoryModel->delete($id);

        if ($result['ok']) {
            Message::set('success', $result['message']);
        } else {
            Message::set('error', $result['message']);
        }

        redirect('admin-categories');
    }
}
