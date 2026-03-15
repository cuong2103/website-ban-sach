<?php
class InventoryModel
{
    private $conn;

    public function __construct()
    {
        $this->conn = connectDB();
    }

    /**
     * Lấy tất cả kho hàng (có tìm kiếm và phân trang)
     */
    public function getAll($search = '', $limit = 10, $offset = 0)
    {
        $query = "
      SELECT 
        i.inventory_id as id,
        i.book_id,
        i.stock_quantity,
        i.imported_quantity,
        i.updated_at,
        b.title,
        b.author,
        b.price,
        c.name as category_name
      FROM inventories i
      JOIN books b ON i.book_id = b.book_id
      LEFT JOIN categories c ON b.category_id = c.category_id
      WHERE 1=1
    ";

        if (!empty($search)) {
            $query .= " AND (b.title LIKE ? OR b.author LIKE ? OR b.book_id LIKE ?)";
        }

        $query .= " ORDER BY i.updated_at DESC LIMIT ? OFFSET ?";

        $stmt = $this->conn->prepare($query);

        $paramIndex = 1;
        if (!empty($search)) {
            $stmt->bindValue($paramIndex++, '%' . $search . '%', PDO::PARAM_STR);
            $stmt->bindValue($paramIndex++, '%' . $search . '%', PDO::PARAM_STR);
            $stmt->bindValue($paramIndex++, '%' . $search . '%', PDO::PARAM_STR);
        }

        $stmt->bindValue($paramIndex++, (int) $limit, PDO::PARAM_INT);
        $stmt->bindValue($paramIndex++, (int) $offset, PDO::PARAM_INT);

        $stmt->execute();
        return $stmt->fetchAll();
    }

    /**
     * Đếm tổng số kho hàng
     */
    public function countAll($search = '')
    {
        $query = "
      SELECT COUNT(*) as total
      FROM inventories i
      JOIN books b ON i.book_id = b.book_id
      WHERE 1=1
    ";

        if (!empty($search)) {
            $query .= " AND (b.title LIKE ? OR b.author LIKE ? OR b.book_id LIKE ?)";
        }

        $stmt = $this->conn->prepare($query);

        if (!empty($search)) {
            $stmt->bindValue(1, '%' . $search . '%', PDO::PARAM_STR);
            $stmt->bindValue(2, '%' . $search . '%', PDO::PARAM_STR);
            $stmt->bindValue(3, '%' . $search . '%', PDO::PARAM_STR);
        }

        $stmt->execute();
        return $stmt->fetch()['total'] ?? 0;
    }

    /**
     * Lấy kho hàng theo ID
     */
    public function getById($id)
    {
        $stmt = $this->conn->prepare("
      SELECT 
        i.inventory_id as id,
        i.book_id,
        i.stock_quantity,
        i.imported_quantity,
        i.updated_at,
        b.title,
        b.author,
        b.price,
        c.name as category_name,
        c.category_id
      FROM inventories i
      JOIN books b ON i.book_id = b.book_id
      LEFT JOIN categories c ON b.category_id = c.category_id
      WHERE i.inventory_id = :id
      LIMIT 1
    ");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }

    /**
     * Lấy kho hàng theo book_id
     */
    public function getByBookId($bookId)
    {
        $stmt = $this->conn->prepare("
      SELECT 
        inventory_id as id,
        book_id,
        stock_quantity,
        imported_quantity,
        updated_at
      FROM inventories
      WHERE book_id = :book_id
      LIMIT 1
    ");
        $stmt->execute(['book_id' => $bookId]);
        return $stmt->fetch();
    }

    /**
     * Tạo kho hàng mới
     */
    public function create($data)
    {
        try {
            $stmt = $this->conn->prepare("
        INSERT INTO inventories (book_id, stock_quantity, imported_quantity)
        VALUES (:book_id, :stock_quantity, :imported_quantity)
      ");

            $stmt->bindValue(':book_id', (int) $data['book_id'], PDO::PARAM_INT);
            $stmt->bindValue(':stock_quantity', (int) ($data['stock_quantity'] ?? 0), PDO::PARAM_INT);
            $stmt->bindValue(':imported_quantity', (int) ($data['imported_quantity'] ?? 0), PDO::PARAM_INT);

            if ($stmt->execute()) {
                return [
                    'ok' => true,
                    'id' => $this->conn->lastInsertId(),
                    'message' => 'Tạo kho hàng thành công.'
                ];
            }

            return [
                'ok' => false,
                'message' => 'Tạo kho hàng thất bại.'
            ];
        } catch (Exception $e) {
            return [
                'ok' => false,
                'message' => 'Lỗi: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Cập nhật kho hàng
     */
    public function update($id, $data)
    {
        try {
            $stmt = $this->conn->prepare("
        UPDATE inventories 
        SET 
          stock_quantity = :stock_quantity,
          imported_quantity = :imported_quantity
        WHERE inventory_id = :id
      ");

            $stmt->bindValue(':stock_quantity', (int) $data['stock_quantity'], PDO::PARAM_INT);
            $stmt->bindValue(':imported_quantity', (int) $data['imported_quantity'], PDO::PARAM_INT);
            $stmt->bindValue(':id', (int) $id, PDO::PARAM_INT);

            if ($stmt->execute()) {
                return [
                    'ok' => true,
                    'message' => 'Cập nhật kho hàng thành công.'
                ];
            }

            return [
                'ok' => false,
                'message' => 'Cập nhật kho hàng thất bại.'
            ];
        } catch (Exception $e) {
            return [
                'ok' => false,
                'message' => 'Lỗi: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Xóa kho hàng
     */
    public function delete($id)
    {
        try {
            $stmt = $this->conn->prepare("DELETE FROM inventories WHERE inventory_id = :id");
            $stmt->execute(['id' => $id]);

            return [
                'ok' => true,
                'message' => 'Xóa kho hàng thành công.'
            ];
        } catch (Exception $e) {
            return [
                'ok' => false,
                'message' => 'Lỗi: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Kiểm tra kho hàng có tồn tại không
     */
    public function exists($id)
    {
        $stmt = $this->conn->prepare("SELECT COUNT(*) as total FROM inventories WHERE inventory_id = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch()['total'] > 0;
    }

    /**
     * Kiểm tra book_id đã có kho hàng chưa
     */
    public function bookExists($bookId)
    {
        $stmt = $this->conn->prepare("SELECT COUNT(*) as total FROM inventories WHERE book_id = :book_id");
        $stmt->execute(['book_id' => $bookId]);
        return $stmt->fetch()['total'] > 0;
    }

    /**
     * Lấy danh sách sách chưa có kho hàng
     */
    public function getAvailableBooks()
    {
        $stmt = $this->conn->prepare("
      SELECT 
        b.book_id as id,
        b.title,
        b.author,
        b.price,
        c.name as category_name
      FROM books b
      LEFT JOIN categories c ON b.category_id = c.category_id
      WHERE b.status = 1
      AND b.book_id NOT IN (SELECT book_id FROM inventories)
      ORDER BY b.title ASC
    ");
        $stmt->execute();
        return $stmt->fetchAll();
    }
}
