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
}