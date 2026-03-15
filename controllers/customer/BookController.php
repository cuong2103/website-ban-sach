<?php
class BookController
{
  private $bookModel;

  public function __construct()
  {
    $this->bookModel = new BookModel();
  }

  public function list()
  {
    $search = trim($_GET['search'] ?? '');
    $category = trim($_GET['category'] ?? '');
    
    // Get price range first
    $priceRange = $this->bookModel->getPriceRange();
    
    $minPrice = (int)($_GET['min_price'] ?? $priceRange['min']);
    $maxPrice = (int)($_GET['max_price'] ?? $priceRange['max']);
    $page = (int)($_GET['page'] ?? 1);
    $limit = 12;
    $offset = ($page - 1) * $limit;

    $books = $this->bookModel->getAll($search, $category, $minPrice, $maxPrice, $limit, $offset);
    $total = $this->bookModel->countAll($search, $category, $minPrice, $maxPrice);
    $totalPages = ceil($total / $limit);
    $categories = $this->bookModel->getCategories();

    require_once './views/customer/books.php';
  }
}