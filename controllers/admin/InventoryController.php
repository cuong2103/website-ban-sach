<?php
class InventoryController
{
    private $inventoryModel;
    private $bookModel;

    public function __construct()
    {
        $this->inventoryModel = new InventoryModel();
        $this->bookModel = new BookModel();
    }

    /**
     * Danh sách kho hàng
     */
    public function list()
    {
        $search = trim($_GET['search'] ?? '');
        $page = (int) ($_GET['page'] ?? 1);
        $page = $page < 1 ? 1 : $page;
        $limit = 10;
        $offset = ($page - 1) * $limit;

        $inventories = $this->inventoryModel->getAll($search, $limit, $offset);
        $total = $this->inventoryModel->countAll($search);
        $totalPages = ceil($total / $limit);

        require_once './views/admin/inventories/list.php';
    }
}
