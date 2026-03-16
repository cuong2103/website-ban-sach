<?php
class AdminOrderController
{
    private $adminOrderModel;

    public function __construct()
    {
        $this->adminOrderModel = new AdminOrderModel();
    }

    /**
     * Hiển thị danh sách đơn hàng
     */
    public function list()
    {
        $search = trim($_GET['search'] ?? '');
        $statusId = trim($_GET['status_id'] ?? '');
        
        $page = (int)($_GET['page'] ?? 1);
        $page = $page < 1 ? 1 : $page;
        $limit = 10;
        $offset = ($page - 1) * $limit;

        $orders = $this->adminOrderModel->getAllOrders($search, $statusId, $limit, $offset);
        $total = $this->adminOrderModel->countOrders($search, $statusId);
        $totalPages = ceil($total / $limit);
        
        $statuses = $this->adminOrderModel->getAllStatuses();

        require_once './views/admin/orders/list.php';
    }

    /**
     * Xem chi tiết đơn hàng
     */
    public function detail()
    {
        $orderId = (int)($_GET['id'] ?? 0);
        
        if ($orderId <= 0) {
            Message::set('error', 'Đơn hàng không hợp lệ.');
            redirect('admin-orders');
        }

        $order = $this->adminOrderModel->getOrderById($orderId);
        
        if (!$order) {
            Message::set('error', 'Không tìm thấy đơn hàng.');
            redirect('admin-orders');
        }

        $items = $this->adminOrderModel->getOrderItems($orderId);
        $statuses = $this->adminOrderModel->getAllStatuses();

        require_once './views/admin/orders/detail.php';
    }

    /**
     * Xử lý cập nhật trạng thái đơn hàng
     */
    public function updateStatus()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('admin-orders');
        }

        $orderId = (int)($_POST['order_id'] ?? 0);
        $statusId = (int)($_POST['status_id'] ?? 0);

        if ($orderId <= 0 || $statusId <= 0) {
            Message::set('error', 'Dữ liệu không hợp lệ.');
            redirect('admin-orders');
        }

        $order = $this->adminOrderModel->getOrderById($orderId);
        if (!$order) {
            Message::set('error', 'Không tìm thấy đơn hàng để cập nhật.');
            redirect('admin-orders');
        }

        // Không cho sửa nếu đã Hủy (5) hoặc Đã hoàn thành (4)
        if (in_array((int)$order['status_id'], [4, 5])) {
            Message::set('error', 'Đơn hàng ở trạng thái cuối cùng, không thể cập nhật.');
            redirect("admin-order-detail&id={$orderId}");
        }

        // Cập nhật trạng thái
        $ok = $this->adminOrderModel->updateOrderStatus($orderId, $statusId);

        if ($ok) {
            Message::set('success', 'Cập nhật trạng thái thành công.');
        } else {
            Message::set('error', 'Lỗi cập nhật trạng thái đơn hàng.');
        }

        redirect("admin-order-detail&id={$orderId}");
    }
}
