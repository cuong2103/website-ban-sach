<?php
class FlashSaleModel
{
    private $conn;

    public function __construct()
    {
        $this->conn = connectDB();
    }

    /**
     * Lấy tất cả flash sale
     */
    public function getAll($search = '', $limit = 10, $offset = 0)
    {
        $query = "
      SELECT 
        flash_sale_id as id,
        name,
        start_time,
        end_time,
        status,
        (SELECT COUNT(*) FROM flash_sale_items WHERE flash_sale_id = fs.flash_sale_id) as item_count
      FROM flash_sales fs
      WHERE 1=1
    ";

        if (!empty($search)) {
            $query .= " AND name LIKE ?";
        }

        $query .= " ORDER BY start_time DESC LIMIT ? OFFSET ?";

        $stmt = $this->conn->prepare($query);

        $paramIndex = 1;
        if (!empty($search)) {
            $stmt->bindValue($paramIndex++, '%' . $search . '%', PDO::PARAM_STR);
        }

        $stmt->bindValue($paramIndex++, (int) $limit, PDO::PARAM_INT);
        $stmt->bindValue($paramIndex++, (int) $offset, PDO::PARAM_INT);

        $stmt->execute();
        return $stmt->fetchAll();
    }

    /**
     * Đếm tổng số flash sale
     */
    public function countAll($search = '')
    {
        $query = "SELECT COUNT(*) as total FROM flash_sales WHERE 1=1";

        if (!empty($search)) {
            $query .= " AND name LIKE ?";
        }

        $stmt = $this->conn->prepare($query);

        if (!empty($search)) {
            $stmt->bindValue(1, '%' . $search . '%', PDO::PARAM_STR);
        }

        $stmt->execute();
        return $stmt->fetch()['total'] ?? 0;
    }

    /**
     * Lấy flash sale theo ID
     */
    public function getById($id)
    {
        $stmt = $this->conn->prepare("
      SELECT 
        flash_sale_id as id,
        name,
        start_time,
        end_time,
        status
      FROM flash_sales
      WHERE flash_sale_id = :id
      LIMIT 1
    ");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }

    /**
     * Lấy sách trong flash sale
     */
    public function getItems($flashSaleId)
    {
        $stmt = $this->conn->prepare("
      SELECT 
        fsi.id,
        fsi.flash_sale_id,
        fsi.book_id,
        fsi.discount_percent,
        fsi.sale_price,
        fsi.stock_limit,
        b.title,
        b.author,
        b.price
      FROM flash_sale_items fsi
      JOIN books b ON fsi.book_id = b.book_id
      WHERE fsi.flash_sale_id = :flash_sale_id
      ORDER BY b.title
    ");
        $stmt->execute(['flash_sale_id' => $flashSaleId]);
        return $stmt->fetchAll();
    }

    /**
     * Tạo flash sale mới
     */
    public function create($data)
    {
        try {
            $stmt = $this->conn->prepare("
        INSERT INTO flash_sales (name, start_time, end_time, status)
        VALUES (:name, :start_time, :end_time, :status)
      ");

            $stmt->bindValue(':name', trim($data['name']), PDO::PARAM_STR);
            $stmt->bindValue(':start_time', $data['start_time'], PDO::PARAM_STR);
            $stmt->bindValue(':end_time', $data['end_time'], PDO::PARAM_STR);
            $stmt->bindValue(':status', (int) ($data['status'] ?? 1), PDO::PARAM_INT);

            if ($stmt->execute()) {
                return [
                    'ok' => true,
                    'id' => $this->conn->lastInsertId(),
                    'message' => 'Tạo flash sale thành công.'
                ];
            }

            return [
                'ok' => false,
                'message' => 'Tạo flash sale thất bại.'
            ];
        } catch (Exception $e) {
            return [
                'ok' => false,
                'message' => 'Lỗi: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Cập nhật flash sale
     */
    public function update($id, $data)
    {
        try {
            $stmt = $this->conn->prepare("
        UPDATE flash_sales 
        SET 
          name = :name,
          start_time = :start_time,
          end_time = :end_time,
          status = :status
        WHERE flash_sale_id = :id
      ");

            $stmt->bindValue(':name', trim($data['name']), PDO::PARAM_STR);
            $stmt->bindValue(':start_time', $data['start_time'], PDO::PARAM_STR);
            $stmt->bindValue(':end_time', $data['end_time'], PDO::PARAM_STR);
            $stmt->bindValue(':status', (int) ($data['status'] ?? 1), PDO::PARAM_INT);
            $stmt->bindValue(':id', (int) $id, PDO::PARAM_INT);

            if ($stmt->execute()) {
                return [
                    'ok' => true,
                    'message' => 'Cập nhật flash sale thành công.'
                ];
            }

            return [
                'ok' => false,
                'message' => 'Cập nhật flash sale thất bại.'
            ];
        } catch (Exception $e) {
            return [
                'ok' => false,
                'message' => 'Lỗi: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Xóa flash sale
     */
    public function delete($id)
    {
        try {
            $stmt = $this->conn->prepare("DELETE FROM flash_sales WHERE flash_sale_id = :id");
            $stmt->execute(['id' => $id]);

            return [
                'ok' => true,
                'message' => 'Xóa flash sale thành công.'
            ];
        } catch (Exception $e) {
            return [
                'ok' => false,
                'message' => 'Lỗi: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Thêm sách vào flash sale
     */
    public function addItem($flashSaleId, $data)
    {
        try {
            $stmt = $this->conn->prepare("
        INSERT INTO flash_sale_items (flash_sale_id, book_id, discount_percent, sale_price, stock_limit)
        VALUES (:flash_sale_id, :book_id, :discount_percent, :sale_price, :stock_limit)
      ");

            $stmt->bindValue(':flash_sale_id', $flashSaleId, PDO::PARAM_INT);
            $stmt->bindValue(':book_id', $data['book_id'], PDO::PARAM_INT);
            $stmt->bindValue(':discount_percent', $data['discount_percent'], PDO::PARAM_INT);
            $stmt->bindValue(':sale_price', $data['sale_price'], PDO::PARAM_STR);
            $stmt->bindValue(':stock_limit', $data['stock_limit'] ?? 0, PDO::PARAM_INT);

            if ($stmt->execute()) {
                return [
                    'ok' => true,
                    'message' => 'Thêm sách vào flash sale thành công.'
                ];
            }

            return [
                'ok' => false,
                'message' => 'Thêm sách vào flash sale thất bại.'
            ];
        } catch (Exception $e) {
            return [
                'ok' => false,
                'message' => 'Lỗi: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Xóa sách khỏi flash sale
     */
    public function removeItem($itemId)
    {
        try {
            $stmt = $this->conn->prepare("DELETE FROM flash_sale_items WHERE id = :id");
            $stmt->execute(['id' => $itemId]);

            return [
                'ok' => true,
                'message' => 'Xóa sách khỏi flash sale thành công.'
            ];
        } catch (Exception $e) {
            return [
                'ok' => false,
                'message' => 'Lỗi: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Lấy danh sách sách có sẵn
     */
    public function getAvailableBooks($flashSaleId = null)
    {
        $query = "
      SELECT 
        b.book_id as id,
        b.title,
        b.author,
        b.price,
        b.thumbnail
      FROM books b
      WHERE b.status = 1
    ";

        // Nếu flashSaleId được cung cấp, loại trừ những sách đã có trong flash sale này
        if ($flashSaleId) {
            $query .= " AND b.book_id NOT IN (SELECT book_id FROM flash_sale_items WHERE flash_sale_id = :flash_sale_id)";
        }

        $query .= " ORDER BY b.title ASC";

        $stmt = $this->conn->prepare($query);

        if ($flashSaleId) {
            $stmt->bindValue(':flash_sale_id', $flashSaleId, PDO::PARAM_INT);
        }

        $stmt->execute();
        return $stmt->fetchAll();
    }

    /**
     * Kiểm tra flash sale có tồn tại không
     */
    public function exists($id)
    {
        $stmt = $this->conn->prepare("SELECT COUNT(*) as total FROM flash_sales WHERE flash_sale_id = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch()['total'] > 0;
    }
}
