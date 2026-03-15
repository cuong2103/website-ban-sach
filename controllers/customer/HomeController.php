<?php
class HomeController
{
  private $categoryModel;

  public function __construct()
  {
    $this->categoryModel = new CategoryModel();
  }

  public function home()
  {
    // Lấy danh mục hoạt động từ database
    $categories = $this->categoryModel->getActive();

    require_once './views/customer/home.php';
  }
}
