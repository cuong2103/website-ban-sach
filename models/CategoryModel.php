<?php
class CategoryModel
{
    private $conn;

    public function __construct()
    {
        $this->conn = connectDB();
    }

    /**
     * Lấy tất cả danh mục
     */
    public function getAll($search = '', $limit = 10, $offset = 0)
    {
        $query = "
      SELECT 
        category_id as id,
        name,
        slug,
        description,
        status,
        created_at,
        updated_at
      FROM categories
      WHERE 1=1
    ";

        if (!empty($search)) {
            $query .= " AND (name LIKE ? OR slug LIKE ?)";
        }

        $query .= " ORDER BY created_at DESC LIMIT ? OFFSET ?";

        $stmt = $this->conn->prepare($query);

        $paramIndex = 1;
        if (!empty($search)) {
            $stmt->bindValue($paramIndex++, '%' . $search . '%', PDO::PARAM_STR);
            $stmt->bindValue($paramIndex++, '%' . $search . '%', PDO::PARAM_STR);
        }

        $stmt->bindValue($paramIndex++, (int) $limit, PDO::PARAM_INT);
        $stmt->bindValue($paramIndex++, (int) $offset, PDO::PARAM_INT);

        $stmt->execute();

        return $stmt->fetchAll();
    }

    /**
     * Đếm tổng số danh mục
     */
    public function countAll($search = '')
    {
        $query = "SELECT COUNT(*) as total FROM categories WHERE 1=1";

        if (!empty($search)) {
            $query .= " AND (name LIKE ? OR slug LIKE ?)";
        }

        $stmt = $this->conn->prepare($query);

        if (!empty($search)) {
            $stmt->bindValue(1, '%' . $search . '%', PDO::PARAM_STR);
            $stmt->bindValue(2, '%' . $search . '%', PDO::PARAM_STR);
        }

        $stmt->execute();

        return $stmt->fetch()['total'] ?? 0;
    }

    /**
     * Lấy danh mục theo ID
     */
    public function getById($id)
    {
        $stmt = $this->conn->prepare("
      SELECT 
        category_id as id,
        name,
        slug,
        description,
        status,
        created_at,
        updated_at
      FROM categories
      WHERE category_id = :id
      LIMIT 1
    ");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }

    /**
     * Lấy danh mục theo slug
     */
    public function getBySlug($slug)
    {
        $stmt = $this->conn->prepare("
      SELECT 
        category_id as id,
        name,
        slug,
        description,
        status
      FROM categories
      WHERE slug = :slug AND status = 1
      LIMIT 1
    ");
        $stmt->execute(['slug' => $slug]);
        return $stmt->fetch();
    }

    /**
     * Kiểm tra slug có tồn tại chưa (ngoại trừ ID hiện tại nếu là update)
     */
    public function checkSlugExists($slug, $exceptId = null)
    {
        $query = "SELECT COUNT(*) as total FROM categories WHERE slug = :slug";

        if ($exceptId) {
            $query .= " AND category_id != :id";
        }

        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(':slug', $slug, PDO::PARAM_STR);

        if ($exceptId) {
            $stmt->bindValue(':id', $exceptId, PDO::PARAM_INT);
        }

        $stmt->execute();
        return $stmt->fetch()['total'] > 0;
    }

    /**
     * Tạo mới danh mục
     */
    public function create($data)
    {
        try {
            $stmt = $this->conn->prepare("
        INSERT INTO categories (name, slug, description, status, created_at, updated_at)
        VALUES (:name, :slug, :description, :status, NOW(), NOW())
      ");

            $stmt->bindValue(':name', trim($data['name']), PDO::PARAM_STR);
            $stmt->bindValue(':slug', trim($data['slug']), PDO::PARAM_STR);
            $stmt->bindValue(':description', trim($data['description'] ?? ''), PDO::PARAM_STR);
            $stmt->bindValue(':status', (int) ($data['status'] ?? 1), PDO::PARAM_INT);

            if ($stmt->execute()) {
                return [
                    'ok' => true,
                    'id' => $this->conn->lastInsertId(),
                    'message' => 'Tạo danh mục thành công.'
                ];
            }

            return [
                'ok' => false,
                'message' => 'Tạo danh mục thất bại.'
            ];
        } catch (Exception $e) {
            return [
                'ok' => false,
                'message' => 'Lỗi: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Cập nhật danh mục
     */
    public function update($id, $data)
    {
        try {
            $stmt = $this->conn->prepare("
        UPDATE categories 
        SET 
          name = :name,
          slug = :slug,
          description = :description,
          status = :status,
          updated_at = NOW()
        WHERE category_id = :id
      ");

            $stmt->bindValue(':name', trim($data['name']), PDO::PARAM_STR);
            $stmt->bindValue(':slug', trim($data['slug']), PDO::PARAM_STR);
            $stmt->bindValue(':description', trim($data['description'] ?? ''), PDO::PARAM_STR);
            $stmt->bindValue(':status', (int) ($data['status'] ?? 1), PDO::PARAM_INT);
            $stmt->bindValue(':id', (int) $id, PDO::PARAM_INT);

            if ($stmt->execute()) {
                return [
                    'ok' => true,
                    'message' => 'Cập nhật danh mục thành công.'
                ];
            }

            return [
                'ok' => false,
                'message' => 'Cập nhật danh mục thất bại.'
            ];
        } catch (Exception $e) {
            return [
                'ok' => false,
                'message' => 'Lỗi: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Xóa danh mục
     */
    public function delete($id)
    {
        try {
            // Kiểm tra xem danh mục có sách không
            $stmtCheck = $this->conn->prepare("SELECT COUNT(*) as total FROM books WHERE category_id = :id");
            $stmtCheck->execute(['id' => $id]);
            $count = $stmtCheck->fetch()['total'] ?? 0;

            if ($count > 0) {
                return [
                    'ok' => false,
                    'message' => 'Không thể xóa danh mục vì còn sách trong danh mục này.'
                ];
            }

            // Xóa danh mục
            $stmt = $this->conn->prepare("DELETE FROM categories WHERE category_id = :id");
            $stmt->execute(['id' => $id]);

            return [
                'ok' => true,
                'message' => 'Xóa danh mục thành công.'
            ];
        } catch (Exception $e) {
            return [
                'ok' => false,
                'message' => 'Lỗi: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Lấy danh mục hoạt động
     */
    public function getActive()
    {
        $stmt = $this->conn->prepare("
      SELECT 
        category_id as id,
        name,
        slug
      FROM categories
      WHERE status = 1
      ORDER BY name ASC
    ");
        $stmt->execute();
        return $stmt->fetchAll();
    }
}
