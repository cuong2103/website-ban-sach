<?php
class BookModel
{
  private $conn;

  public function __construct()
  {
    $this->conn = connectDB();
  }

  public function getAll($search = '', $category = '', $minPrice = 0, $maxPrice = PHP_INT_MAX, $limit = 12, $offset = 0)
  {
    $query = "
      SELECT 
        b.book_id as id,
        b.title,
        b.author,
        b.price,
        b.sale_price,
        b.thumbnail,
        b.stock,
        c.name as category_name,
        c.category_id,
        c.slug
      FROM books b
      LEFT JOIN categories c ON b.category_id = c.category_id
      WHERE b.status = 1
    ";

    if (!empty($search)) {
      $query .= " AND (b.title LIKE ? OR b.author LIKE ?)";
    }

    if (!empty($category)) {
      $query .= " AND c.slug = ?";
    }

    $query .= " AND COALESCE(b.sale_price, b.price) >= ?";
    $query .= " AND COALESCE(b.sale_price, b.price) <= ?";
    $query .= " ORDER BY b.created_at DESC LIMIT ? OFFSET ?";

    $stmt = $this->conn->prepare($query);
    
    $paramIndex = 1;
    if (!empty($search)) {
      $stmt->bindValue($paramIndex++, '%' . $search . '%', PDO::PARAM_STR);
      $stmt->bindValue($paramIndex++, '%' . $search . '%', PDO::PARAM_STR);
    }

    if (!empty($category)) {
      $stmt->bindValue($paramIndex++, $category, PDO::PARAM_STR);
    }

    $stmt->bindValue($paramIndex++, (int)$minPrice, PDO::PARAM_INT);
    $stmt->bindValue($paramIndex++, (int)$maxPrice, PDO::PARAM_INT);
    $stmt->bindValue($paramIndex++, (int)$limit, PDO::PARAM_INT);
    $stmt->bindValue($paramIndex++, (int)$offset, PDO::PARAM_INT);
    
    $stmt->execute();

    return $stmt->fetchAll();
  }

  public function countAll($search = '', $category = '', $minPrice = 0, $maxPrice = PHP_INT_MAX)
  {
    $query = "
      SELECT COUNT(*) as total
      FROM books b
      LEFT JOIN categories c ON b.category_id = c.category_id
      WHERE b.status = 1
    ";

    if (!empty($search)) {
      $query .= " AND (b.title LIKE ? OR b.author LIKE ?)";
    }

    if (!empty($category)) {
      $query .= " AND c.slug = ?";
    }

    $query .= " AND COALESCE(b.sale_price, b.price) >= ?";
    $query .= " AND COALESCE(b.sale_price, b.price) <= ?";

    $stmt = $this->conn->prepare($query);
    
    $paramIndex = 1;
    if (!empty($search)) {
      $stmt->bindValue($paramIndex++, '%' . $search . '%', PDO::PARAM_STR);
      $stmt->bindValue($paramIndex++, '%' . $search . '%', PDO::PARAM_STR);
    }

    if (!empty($category)) {
      $stmt->bindValue($paramIndex++, $category, PDO::PARAM_STR);
    }

    $stmt->bindValue($paramIndex++, (int)$minPrice, PDO::PARAM_INT);
    $stmt->bindValue($paramIndex++, (int)$maxPrice, PDO::PARAM_INT);
    
    $stmt->execute();

    return $stmt->fetch()['total'] ?? 0;
  }

  public function getById($id)
  {
    $stmt = $this->conn->prepare("
      SELECT 
        b.book_id as id,
        b.title,
        b.author,
        b.publisher,
        b.price,
        b.sale_price,
        b.description,
        b.thumbnail,
        b.stock,
        c.name as category_name,
        c.category_id
      FROM books b
      LEFT JOIN categories c ON b.category_id = c.category_id
      WHERE b.book_id = :id AND b.status = 1
      LIMIT 1
    ");
    $stmt->execute(['id' => $id]);
    return $stmt->fetch();
  }

  public function getCategories()
  {
    $stmt = $this->conn->prepare("
      SELECT 
        category_id,
        name,
        slug
      FROM categories
      WHERE status = 1
      ORDER BY name ASC
    ");
    $stmt->execute();
    return $stmt->fetchAll();
  }

  public function getPriceRange()
  {
    $stmt = $this->conn->prepare("
      SELECT 
        MIN(COALESCE(sale_price, price)) as min_price,
        MAX(COALESCE(sale_price, price)) as max_price
      FROM books
      WHERE status = 1
    ");
    $stmt->execute();
    $result = $stmt->fetch();
    return [
      'min' => (int)($result['min_price'] ?? 0),
      'max' => (int)($result['max_price'] ?? 1000000)
    ];
  }

  public function getBooksByCategory($categorySlug, $limit = 5)
  {
    $stmt = $this->conn->prepare("
      SELECT 
        b.book_id as id,
        b.title,
        b.author,
        b.price,
        b.sale_price,
        b.thumbnail,
        b.stock,
        c.slug,
        c.category_id,
        c.name as category_name
      FROM books b
      LEFT JOIN categories c ON b.category_id = c.category_id
      WHERE c.slug = :slug AND b.status = 1
      ORDER BY b.created_at DESC
      LIMIT :limit
    ");
    $stmt->bindValue(':slug', $categorySlug);
    $stmt->bindValue(':limit', (int)$limit, \PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll();
  }

  // --- ADMIN METHODS ---

  public function getAdminAll($search = '', $category = '', $limit = 10, $offset = 0)
  {
    $query = "
      SELECT 
        b.*,
        c.name as category_name
      FROM books b
      LEFT JOIN categories c ON b.category_id = c.category_id
      WHERE 1=1
    ";

    $params = [];
    if (!empty($search)) {
      $query .= " AND (b.title LIKE ? OR b.author LIKE ?)";
      $params[] = '%' . $search . '%';
      $params[] = '%' . $search . '%';
    }

    if (!empty($category)) {
      $query .= " AND b.category_id = ?";
      $params[] = $category;
    }

    $query .= " ORDER BY b.created_at DESC LIMIT ? OFFSET ?";
    
    $stmt = $this->conn->prepare($query);
    
    $paramIndex = 1;
    foreach ($params as $param) {
      $stmt->bindValue($paramIndex++, $param, PDO::PARAM_STR);
    }
    
    $stmt->bindValue($paramIndex++, (int)$limit, PDO::PARAM_INT);
    $stmt->bindValue($paramIndex++, (int)$offset, PDO::PARAM_INT);
    
    $stmt->execute();
    return $stmt->fetchAll();
  }

  public function countAdminAll($search = '', $category = '')
  {
    $query = "
      SELECT COUNT(*) as total
      FROM books b
      WHERE 1=1
    ";

    $params = [];
    if (!empty($search)) {
      $query .= " AND (b.title LIKE ? OR b.author LIKE ?)";
      $params[] = '%' . $search . '%';
      $params[] = '%' . $search . '%';
    }

    if (!empty($category)) {
      $query .= " AND b.category_id = ?";
      $params[] = $category;
    }

    $stmt = $this->conn->prepare($query);
    
    $paramIndex = 1;
    foreach ($params as $param) {
      $stmt->bindValue($paramIndex++, $param, PDO::PARAM_STR);
    }
    
    $stmt->execute();
    return $stmt->fetch()['total'] ?? 0;
  }

  public function getAdminById($id)
  {
    $stmt = $this->conn->prepare("
      SELECT 
        b.*,
        c.name as category_name
      FROM books b
      LEFT JOIN categories c ON b.category_id = c.category_id
      WHERE b.book_id = :id
      LIMIT 1
    ");
    $stmt->execute(['id' => $id]);
    return $stmt->fetch();
  }

  public function create($data)
  {
    try {
      $stmt = $this->conn->prepare("
        INSERT INTO books (
          category_id, title, author, publisher, price, sale_price, 
          description, thumbnail, weight, dimensions, cover_type, 
          stock, status, is_featured, is_bestseller, created_at, updated_at
        ) VALUES (
          :category_id, :title, :author, :publisher, :price, :sale_price, 
          :description, :thumbnail, :weight, :dimensions, :cover_type, 
          :stock, :status, :is_featured, :is_bestseller, NOW(), NOW()
        )
      ");

      $stmt->execute([
        ':category_id' => (int)$data['category_id'],
        ':title' => trim($data['title']),
        ':author' => trim($data['author']),
        ':publisher' => trim($data['publisher'] ?? ''),
        ':price' => (float)$data['price'],
        ':sale_price' => !empty($data['sale_price']) ? (float)$data['sale_price'] : null,
        ':description' => trim($data['description'] ?? ''),
        ':thumbnail' => trim($data['thumbnail'] ?? ''),
        ':weight' => trim($data['weight'] ?? ''),
        ':dimensions' => trim($data['dimensions'] ?? ''),
        ':cover_type' => $data['cover_type'] ?? 'Bìa mềm',
        ':stock' => (int)($data['stock'] ?? 0),
        ':status' => (int)($data['status'] ?? 1),
        ':is_featured' => (int)($data['is_featured'] ?? 0),
        ':is_bestseller' => (int)($data['is_bestseller'] ?? 0),
      ]);

      return [
        'ok' => true,
        'id' => $this->conn->lastInsertId(),
        'message' => 'Thêm sách thành công'
      ];
    } catch (Exception $e) {
      return ['ok' => false, 'message' => $e->getMessage()];
    }
  }

  public function update($id, $data)
  {
    try {
      $sql = "
        UPDATE books SET 
          category_id = :category_id,
          title = :title,
          author = :author,
          publisher = :publisher,
          price = :price,
          sale_price = :sale_price,
          description = :description,
          weight = :weight,
          dimensions = :dimensions,
          cover_type = :cover_type,
          stock = :stock,
          status = :status,
          is_featured = :is_featured,
          is_bestseller = :is_bestseller,
          updated_at = NOW()
      ";

      $params = [
        ':category_id' => (int)$data['category_id'],
        ':title' => trim($data['title']),
        ':author' => trim($data['author']),
        ':publisher' => trim($data['publisher'] ?? ''),
        ':price' => (float)$data['price'],
        ':sale_price' => !empty($data['sale_price']) ? (float)$data['sale_price'] : null,
        ':description' => trim($data['description'] ?? ''),
        ':weight' => trim($data['weight'] ?? ''),
        ':dimensions' => trim($data['dimensions'] ?? ''),
        ':cover_type' => $data['cover_type'] ?? 'Bìa mềm',
        ':stock' => (int)($data['stock'] ?? 0),
        ':status' => (int)($data['status'] ?? 1),
        ':is_featured' => (int)($data['is_featured'] ?? 0),
        ':is_bestseller' => (int)($data['is_bestseller'] ?? 0),
        ':id' => $id
      ];

      if (!empty($data['thumbnail'])) {
        $sql .= ", thumbnail = :thumbnail";
        $params[':thumbnail'] = $data['thumbnail'];
      }

      $sql .= " WHERE book_id = :id";

      $stmt = $this->conn->prepare($sql);
      $stmt->execute($params);

      return ['ok' => true, 'message' => 'Cập nhật sách thành công'];
    } catch (Exception $e) {
      return ['ok' => false, 'message' => $e->getMessage()];
    }
  }

  public function delete($id)
  {
    try {
      $stmt = $this->conn->prepare("DELETE FROM books WHERE book_id = :id");
      $stmt->execute(['id' => $id]);
      return ['ok' => true, 'message' => 'Xóa sách thành công'];
    } catch (Exception $e) {
      return ['ok' => false, 'message' => 'Không thể xóa sách này vì đã có đơn hàng hoặc liên kết khác ràng buộc.'];
    }
  }

  // --- IMAGE MANAGEMENT ---
  public function getBookImages($bookId)
  {
    $stmt = $this->conn->prepare("SELECT * FROM book_images WHERE book_id = :book_id ORDER BY created_at ASC");
    $stmt->execute(['book_id' => $bookId]);
    return $stmt->fetchAll();
  }

  public function addBookImage($bookId, $imageUrl)
  {
    $stmt = $this->conn->prepare("INSERT INTO book_images (book_id, image_url) VALUES (:book_id, :image_url)");
    return $stmt->execute(['book_id' => $bookId, 'image_url' => $imageUrl]);
  }

  public function deleteBookImage($imageId)
  {
    $stmt = $this->conn->prepare("DELETE FROM book_images WHERE image_id = :image_id");
    return $stmt->execute(['image_id' => $imageId]);
  }

  public function getBookImageById($imageId)
  {
    $stmt = $this->conn->prepare("SELECT * FROM book_images WHERE image_id = :image_id LIMIT 1");
    $stmt->execute(['image_id' => $imageId]);
    return $stmt->fetch();
  }

  // --- DATALIST HELPERS ---
  public function getDistinctAuthors()
  {
    $stmt = $this->conn->prepare("SELECT DISTINCT author FROM books WHERE author != '' ORDER BY author ASC");
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_COLUMN);
  }

  public function getDistinctPublishers()
  {
    $stmt = $this->conn->prepare("SELECT DISTINCT publisher FROM books WHERE publisher != '' ORDER BY publisher ASC");
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_COLUMN);
  }
}