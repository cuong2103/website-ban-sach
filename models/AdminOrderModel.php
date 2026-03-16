<?php
class AdminOrderModel
{
    private $conn;

    public function __construct()
    {
        $this->conn = connectDB();
    }

    /**
     * Lấy danh sách đơn hàng có phân trang
     */
    public function getAllOrders($search = '', $statusId = '', $limit = 10, $offset = 0)
    {
        $query = "
            SELECT 
                o.order_id,
                o.order_code,
                o.total_amount,
                o.discount_amount,
                o.created_at,
                os.status_name,
                os.status_id,
                u.full_name as customer_name
            FROM orders o
            JOIN users u ON o.user_id = u.user_id
            JOIN order_status os ON o.status_id = os.status_id
            WHERE 1=1
        ";

        $params = [];

        if (!empty($search)) {
            $query .= " AND (o.order_code LIKE :search OR u.full_name LIKE :search)";
            $params['search'] = "%$search%";
        }

        if (!empty($statusId)) {
            $query .= " AND o.status_id = :status_id";
            $params['status_id'] = $statusId;
        }

        $query .= " ORDER BY o.created_at DESC LIMIT :limit OFFSET :offset";

        $stmt = $this->conn->prepare($query);

        // Bind params safely
        if (isset($params['search'])) {
            $stmt->bindValue(':search', $params['search'], PDO::PARAM_STR);
        }
        if (isset($params['status_id'])) {
            $stmt->bindValue(':status_id', (int)$params['status_id'], PDO::PARAM_INT);
        }

        $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);

        $stmt->execute();
        return $stmt->fetchAll();
    }

    /**
     * Đếm tổng số đơn hàng
     */
    public function countOrders($search = '', $statusId = '')
    {
        $query = "
            SELECT COUNT(o.order_id) as total
            FROM orders o
            JOIN users u ON o.user_id = u.user_id
            WHERE 1=1
        ";

        $params = [];

        if (!empty($search)) {
            $query .= " AND (o.order_code LIKE :search OR u.full_name LIKE :search)";
            $params['search'] = "%$search%";
        }

        if (!empty($statusId)) {
            $query .= " AND o.status_id = :status_id";
            $params['status_id'] = $statusId;
        }

        $stmt = $this->conn->prepare($query);

        if (isset($params['search'])) {
            $stmt->bindValue(':search', $params['search'], PDO::PARAM_STR);
        }
        if (isset($params['status_id'])) {
            $stmt->bindValue(':status_id', (int)$params['status_id'], PDO::PARAM_INT);
        }

        $stmt->execute();
        return $stmt->fetch()['total'] ?? 0;
    }

    /**
     * Lấy chi tiết đơn hàng theo ID
     */
    public function getOrderById($orderId)
    {
        $stmt = $this->conn->prepare("
            SELECT 
                o.order_id,
                o.order_code,
                o.total_amount,
                o.discount_amount,
                o.shipping_address,
                o.phone,
                o.note,
                o.created_at,
                o.status_id,
                os.status_name,
                pm.name AS payment_method,
                u.full_name as customer_name,
                u.email as customer_email
            FROM orders o
            JOIN users u ON o.user_id = u.user_id
            JOIN order_status os ON o.status_id = os.status_id
            JOIN payment_methods pm ON pm.payment_method_id = o.payment_method_id
            WHERE o.order_id = :order_id
            LIMIT 1
        ");
        $stmt->execute(['order_id' => (int)$orderId]);
        return $stmt->fetch();
    }

    /**
     * Lấy sản phẩm của đơn hàng
     */
    public function getOrderItems($orderId)
    {
        $stmt = $this->conn->prepare("
            SELECT 
                oi.book_id,
                oi.quantity,
                oi.price,
                oi.subtotal,
                b.title,
                b.thumbnail
            FROM order_items oi
            JOIN books b ON oi.book_id = b.book_id
            WHERE oi.order_id = :order_id
            ORDER BY oi.order_item_id ASC
        ");
        $stmt->execute(['order_id' => (int)$orderId]);
        return $stmt->fetchAll();
    }

    /**
     * Danh sách trạng thái
     */
    public function getAllStatuses()
    {
        $stmt = $this->conn->prepare("SELECT * FROM order_status ORDER BY status_id ASC");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    /**
     * Cập nhật trạng thái đơn hàng
     * Trạng thái 5 là Cancelled. Khi Hủy thì trả hàng về kho.
     */
    public function updateOrderStatus($orderId, $statusId)
    {
        try {
            $this->conn->beginTransaction();

            $stmt = $this->conn->prepare("UPDATE orders SET status_id = :status_id WHERE order_id = :order_id");
            $result = $stmt->execute([
                'status_id' => (int)$statusId,
                'order_id' => (int)$orderId
            ]);

            // Nếu hủy đơn: trả lại số lượng tồn kho
            if ((int)$statusId === 5) {
                $items = $this->getOrderItems($orderId);
                $restockStmt = $this->conn->prepare("UPDATE books SET stock = stock + :quantity WHERE book_id = :book_id");
                
                foreach ($items as $item) {
                    $restockStmt->execute([
                        'quantity' => (int)$item['quantity'],
                        'book_id' => (int)$item['book_id']
                    ]);
                }
            }

            $this->conn->commit();
            return $result;
        } catch (Exception $e) {
            if ($this->conn->inTransaction()) {
                $this->conn->rollBack();
            }
            return false;
        }
    }
}
