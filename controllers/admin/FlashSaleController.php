<?php
class FlashSaleController
{
    private $flashSaleModel;
    private $bookModel;

    public function __construct()
    {
        $this->flashSaleModel = new FlashSaleModel();
        $this->bookModel = new BookModel();
    }

    /**
     * Danh sách flash sale
     */
    public function list()
    {
        $search = trim($_GET['search'] ?? '');
        $page = (int) ($_GET['page'] ?? 1);
        $page = $page < 1 ? 1 : $page;
        $limit = 10;
        $offset = ($page - 1) * $limit;

        $flashSales = $this->flashSaleModel->getAll($search, $limit, $offset);
        $total = $this->flashSaleModel->countAll($search);
        $totalPages = ceil($total / $limit);

        require_once './views/admin/flash_sales/list.php';
    }

    /**
     * Form tạo mới flash sale
     */
    public function formCreate()
    {
        $books = $this->bookModel->getAll('', '', 0, PHP_INT_MAX, 999, 0);

        require_once './views/admin/flash_sales/create.php';
    }

    /**
     * Xử lý tạo mới flash sale
     */
    public function create()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('admin-flash-sales');
        }

        $name = trim($_POST['name'] ?? '');
        $startTime = trim($_POST['start_time'] ?? '');
        $endTime = trim($_POST['end_time'] ?? '');
        $status = (int) ($_POST['status'] ?? 1);

        $errors = [];

        if (empty($name)) {
            $errors['name'] = 'Tên flash sale không được bỏ trống.';
        } elseif (strlen($name) > 150) {
            $errors['name'] = 'Tên flash sale không được vượt quá 150 ký tự.';
        }

        if (empty($startTime)) {
            $errors['start_time'] = 'Thời gian bắt đầu không được bỏ trống.';
        }

        if (empty($endTime)) {
            $errors['end_time'] = 'Thời gian kết thúc không được bỏ trống.';
        }

        if (!empty($startTime) && !empty($endTime)) {
            $start = strtotime($startTime);
            $end = strtotime($endTime);
            if ($end <= $start) {
                $errors['end_time'] = 'Thời gian kết thúc phải sau thời gian bắt đầu.';
            }
        }

        if (!empty($errors)) {
            $_SESSION['validation_errors'] = $errors;
            $_SESSION['old'] = ['name' => $name, 'start_time' => $startTime, 'end_time' => $endTime, 'status' => $status];
            redirect('admin-flash-sales-create');
        }

        $result = $this->flashSaleModel->create([
            'name' => $name,
            'start_time' => $startTime,
            'end_time' => $endTime,
            'status' => $status
        ]);

        if ($result['ok']) {
            Message::set('success', $result['message']);
            unset($_SESSION['old']);
            unset($_SESSION['validation_errors']);
        } else {
            Message::set('error', $result['message']);
        }

        redirect('admin-flash-sales');
    }

    /**
     * Form sửa flash sale
     */
    public function formEdit()
    {
        $id = (int) ($_GET['id'] ?? 0);

        if ($id <= 0) {
            Message::set('error', 'Flash sale không hợp lệ.');
            redirect('admin-flash-sales');
        }

        $flashSale = $this->flashSaleModel->getById($id);

        if (!$flashSale) {
            Message::set('error', 'Flash sale không tồn tại.');
            redirect('admin-flash-sales');
        }

        $items = $this->flashSaleModel->getItems($id);
        $books = $this->flashSaleModel->getAvailableBooks($id);

        require_once './views/admin/flash_sales/edit.php';
    }

    /**
     * Xử lý cập nhật flash sale
     */
    public function update()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('admin-flash-sales');
        }

        $id = (int) ($_POST['id'] ?? 0);
        $name = trim($_POST['name'] ?? '');
        $startTime = trim($_POST['start_time'] ?? '');
        $endTime = trim($_POST['end_time'] ?? '');
        $status = (int) ($_POST['status'] ?? 1);

        if ($id <= 0 || !$this->flashSaleModel->exists($id)) {
            Message::set('error', 'Flash sale không hợp lệ.');
            redirect('admin-flash-sales');
        }

        $errors = [];

        if (empty($name)) {
            $errors['name'] = 'Tên flash sale không được bỏ trống.';
        } elseif (strlen($name) > 150) {
            $errors['name'] = 'Tên flash sale không được vượt quá 150 ký tự.';
        }

        if (empty($startTime)) {
            $errors['start_time'] = 'Thời gian bắt đầu không được bỏ trống.';
        }

        if (empty($endTime)) {
            $errors['end_time'] = 'Thời gian kết thúc không được bỏ trống.';
        }

        if (!empty($startTime) && !empty($endTime)) {
            $start = strtotime($startTime);
            $end = strtotime($endTime);
            if ($end <= $start) {
                $errors['end_time'] = 'Thời gian kết thúc phải sau thời gian bắt đầu.';
            }
        }

        if (!empty($errors)) {
            $_SESSION['validation_errors'] = $errors;
            $_SESSION['old'] = ['name' => $name, 'start_time' => $startTime, 'end_time' => $endTime, 'status' => $status];
            redirect('admin-flash-sales-edit&id=' . $id);
        }

        $result = $this->flashSaleModel->update($id, [
            'name' => $name,
            'start_time' => $startTime,
            'end_time' => $endTime,
            'status' => $status
        ]);

        if ($result['ok']) {
            Message::set('success', $result['message']);
            unset($_SESSION['old']);
            unset($_SESSION['validation_errors']);
        } else {
            Message::set('error', $result['message']);
        }

        redirect('admin-flash-sales');
    }

    /**
     * Xóa flash sale
     */
    public function delete()
    {
        $id = (int) ($_GET['id'] ?? 0);

        if ($id <= 0) {
            Message::set('error', 'Flash sale không hợp lệ.');
            redirect('admin-flash-sales');
        }

        $flashSale = $this->flashSaleModel->getById($id);
        if (!$flashSale) {
            Message::set('error', 'Flash sale không tồn tại.');
            redirect('admin-flash-sales');
        }

        $result = $this->flashSaleModel->delete($id);

        if ($result['ok']) {
            Message::set('success', $result['message']);
        } else {
            Message::set('error', $result['message']);
        }

        redirect('admin-flash-sales');
    }

    /**
     * Thêm sách vào flash sale
     */
    public function addItem()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('admin-flash-sales');
        }

        $flashSaleId = (int) ($_POST['flash_sale_id'] ?? 0);
        $bookId = (int) ($_POST['book_id'] ?? 0);
        $discountPercent = (int) ($_POST['discount_percent'] ?? 0);
        $salePrice = trim($_POST['sale_price'] ?? '');
        $stockLimit = (int) ($_POST['stock_limit'] ?? 0);

        $errors = [];

        if ($flashSaleId <= 0 || !$this->flashSaleModel->exists($flashSaleId)) {
            $errors['flash_sale_id'] = 'Flash sale không hợp lệ.';
        }

        if ($bookId <= 0) {
            $errors['book_id'] = 'Sách không hợp lệ.';
        }

        if ($discountPercent <= 0 || $discountPercent > 100) {
            $errors['discount_percent'] = 'Phần trăm giảm giá phải từ 1 đến 100.';
        }

        if (empty($salePrice) || !is_numeric($salePrice)) {
            $errors['sale_price'] = 'Giá sale không hợp lệ.';
        }

        if (!empty($errors)) {
            Message::set('error', implode(', ', $errors));
            redirect('admin-flash-sales-edit&id=' . $flashSaleId);
        }

        $result = $this->flashSaleModel->addItem($flashSaleId, [
            'book_id' => $bookId,
            'discount_percent' => $discountPercent,
            'sale_price' => $salePrice,
            'stock_limit' => $stockLimit
        ]);

        if ($result['ok']) {
            Message::set('success', $result['message']);
        } else {
            Message::set('error', $result['message']);
        }

        redirect('admin-flash-sales-edit&id=' . $flashSaleId);
    }

    /**
     * Xóa sách khỏi flash sale
     */
    public function removeItem()
    {
        $itemId = (int) ($_GET['item_id'] ?? 0);
        $flashSaleId = (int) ($_GET['flash_sale_id'] ?? 0);

        if ($itemId <= 0) {
            Message::set('error', 'Sách không hợp lệ.');
            redirect('admin-flash-sales');
        }

        $result = $this->flashSaleModel->removeItem($itemId);

        if ($result['ok']) {
            Message::set('success', $result['message']);
        } else {
            Message::set('error', $result['message']);
        }

        redirect('admin-flash-sales-edit&id=' . $flashSaleId);
    }
}
