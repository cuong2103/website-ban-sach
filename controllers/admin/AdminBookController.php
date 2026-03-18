<?php

class AdminBookController
{
    private $bookModel;
    private $categoryModel;

    public function __construct()
    {
        $this->bookModel = new BookModel();
        $this->categoryModel = new CategoryModel();
    }

    public function list()
    {
        requireAdmin();

        $search = trim($_GET['search'] ?? '');
        $category = trim($_GET['category'] ?? '');
        $status_filter = $_GET['status_filter'] ?? '';
        $page = (int)($_GET['page'] ?? 1);
        $page = $page < 1 ? 1 : $page;
        $limit = 10;
        $offset = ($page - 1) * $limit;

        $books = $this->bookModel->getAdminAll($search, $category, $status_filter, $limit, $offset);
        $total = $this->bookModel->countAdminAll($search, $category, $status_filter);
        $totalPages = ceil($total / $limit);

        $categories = $this->categoryModel->getAll();

        require_once './views/admin/books/list.php';
    }

    public function create()
    {
        requireAdmin();

        $categories = $this->categoryModel->getAll();
        $authors = $this->bookModel->getDistinctAuthors();
        $publishers = $this->bookModel->getDistinctPublishers();
        
        $old = $_SESSION['old'] ?? [];
        $errors = $_SESSION['errors'] ?? [];
        unset($_SESSION['old'], $_SESSION['errors']);

        require_once './views/admin/books/create.php';
    }

    public function store()
    {
        requireAdmin();

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $data = [
                'category_id' => $_POST['category_id'] ?? '',
                'title'       => $_POST['title'] ?? '',
                'author'      => $_POST['author'] ?? '',
                'publisher'   => $_POST['publisher'] ?? '',
                'price'       => $_POST['price'] ?? '',
                'sale_price'  => $_POST['sale_price'] ?? '',
                'description' => $_POST['description'] ?? '',
                'weight'      => $_POST['weight'] ?? '',
                'dimensions'  => $_POST['dimensions'] ?? '',
                'cover_type'  => $_POST['cover_type'] ?? 'Bìa mềm',
                'stock'       => $_POST['stock'] ?? 0,
                'status'      => isset($_POST['status']) ? 1 : 0,
                'is_featured'    => isset($_POST['is_featured']) ? 1 : 0,
                'is_bestseller'  => isset($_POST['is_bestseller']) ? 1 : 0,
            ];

            // Validation
            $rules = [
                'title'       => 'required|max:255',
                'category_id' => 'required|numeric',
                'author'      => 'required|max:150',
                'price'       => 'required|numeric',
            ];
            $errors = validate($data, $rules);

            // Kiểm tra giá khuyến mãi phải nhỏ hơn giá gốc
            if (!empty($data['sale_price']) && is_numeric($data['sale_price']) && is_numeric($data['price'])) {
                if ((float)$data['sale_price'] >= (float)$data['price']) {
                    $errors['sale_price'][] = 'Giá khuyến mãi phải nhỏ hơn giá gốc.';
                }
                if ((float)$data['sale_price'] < 0) {
                    $errors['sale_price'][] = 'Giá khuyến mãi không được âm.';
                }
            }

            if (!empty($errors)) {
                $_SESSION['errors'] = $errors;
                $_SESSION['old'] = $_POST;
                Message::set('error', 'Vui lòng kiểm tra lại thông tin!');
                redirect('admin-books-create');
            }

            // Thumbnail Upload
            $thumbnailPath = '';
            if (isset($_FILES['thumbnail']) && $_FILES['thumbnail']['error'] == UPLOAD_ERR_OK) {
                $thumbnailPath = uploadFile($_FILES['thumbnail'], '/uploads/books/');
                if (!$thumbnailPath) {
                    $_SESSION['old'] = $_POST;
                    Message::set('error', 'Ảnh bìa không hợp lệ hoặc quá lớn. Chỉ chấp nhận JPG, PNG, WEBP.');
                    redirect('admin-books-create');
                }
            }
            $data['thumbnail'] = $thumbnailPath;

            $result = $this->bookModel->create($data);

            if ($result['ok']) {
                $bookId = $result['id'];

                // Upload Gallery Images
                if (isset($_FILES['gallery_images']) && count($_FILES['gallery_images']['name']) > 0) {
                    $totalFiles = count($_FILES['gallery_images']['name']);
                    for ($i = 0; $i < $totalFiles; $i++) {
                        if ($_FILES['gallery_images']['error'][$i] == UPLOAD_ERR_OK) {
                            $file = [
                                'name'     => $_FILES['gallery_images']['name'][$i],
                                'type'     => $_FILES['gallery_images']['type'][$i],
                                'tmp_name' => $_FILES['gallery_images']['tmp_name'][$i],
                                'error'    => $_FILES['gallery_images']['error'][$i],
                                'size'     => $_FILES['gallery_images']['size'][$i]
                            ];
                            $imgPath = uploadFile($file, '/uploads/books/');
                            if ($imgPath) {
                                $this->bookModel->addBookImage($bookId, $imgPath);
                            }
                        }
                    }
                }

                Message::set('success', 'Thêm sách thành công!');
                redirect('admin-books');
            } else {
                $_SESSION['old'] = $_POST;
                Message::set('error', $result['message']);
                redirect('admin-books-create');
            }
        }
    }

    public function edit()
    {
        requireAdmin();

        $id = $_GET['id'] ?? 0;
        $book = $this->bookModel->getAdminById($id);

        if (!$book) {
            Message::set('error', 'Sách không tồn tại!');
            redirect('admin-books');
        }

        $categories = $this->categoryModel->getAll();
        $authors = $this->bookModel->getDistinctAuthors();
        $publishers = $this->bookModel->getDistinctPublishers();
        $bookImages = $this->bookModel->getBookImages($id);
        
        $old = $_SESSION['old'] ?? $book;
        $errors = $_SESSION['errors'] ?? [];
        unset($_SESSION['old'], $_SESSION['errors']);

        require_once './views/admin/books/edit.php';
    }

    public function update()
    {
        requireAdmin();

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id = $_POST['book_id'] ?? 0;
            $book = $this->bookModel->getAdminById($id);
            
            if (!$book) {
                Message::set('error', 'Sách không tồn tại!');
                redirect('admin-books');
            }

            $data = [
                'category_id' => $_POST['category_id'] ?? '',
                'title'       => $_POST['title'] ?? '',
                'author'      => $_POST['author'] ?? '',
                'publisher'   => $_POST['publisher'] ?? '',
                'price'       => $_POST['price'] ?? '',
                'sale_price'  => $_POST['sale_price'] ?? '',
                'description' => $_POST['description'] ?? '',
                'weight'      => $_POST['weight'] ?? '',
                'dimensions'  => $_POST['dimensions'] ?? '',
                'cover_type'  => $_POST['cover_type'] ?? 'Bìa mềm',
                'stock'       => $_POST['stock'] ?? 0,
                'status'      => isset($_POST['status']) ? 1 : 0,
                'is_featured'    => isset($_POST['is_featured']) ? 1 : 0,
                'is_bestseller'  => isset($_POST['is_bestseller']) ? 1 : 0,
            ];

            // Validation
            $rules = [
                'title'       => 'required|max:255',
                'category_id' => 'required|numeric',
                'author'      => 'required|max:150',
                'price'       => 'required|numeric',
            ];
            $errors = validate($data, $rules);

            // Kiểm tra giá khuyến mãi phải nhỏ hơn giá gốc
            if (!empty($data['sale_price']) && is_numeric($data['sale_price']) && is_numeric($data['price'])) {
                if ((float)$data['sale_price'] >= (float)$data['price']) {
                    $errors['sale_price'][] = 'Giá khuyến mãi phải nhỏ hơn giá gốc.';
                }
                if ((float)$data['sale_price'] < 0) {
                    $errors['sale_price'][] = 'Giá khuyến mãi không được âm.';
                }
            }

            if (!empty($errors)) {
                $_SESSION['errors'] = $errors;
                $_SESSION['old'] = $_POST;
                Message::set('error', 'Vui lòng kiểm tra lại thông tin!');
                redirect('admin-books-edit&id=' . $id);
            }

            // Xử lý xóa thumbnail cũ (khi admin click "Xóa ảnh bìa")
            if (!empty($_POST['delete_thumbnail']) && $book['thumbnail']) {
                deleteFile($book['thumbnail']);
                $data['thumbnail'] = '';
            }

            // Thumbnail Upload (ảnh mới)
            if (isset($_FILES['thumbnail']) && $_FILES['thumbnail']['error'] == UPLOAD_ERR_OK) {
                $thumbnailPath = uploadFile($_FILES['thumbnail'], '/uploads/books/');
                if ($thumbnailPath) {
                    // Xóa ảnh cũ
                    if ($book['thumbnail']) deleteFile($book['thumbnail']);
                    $data['thumbnail'] = $thumbnailPath;
                }
            }

            $result = $this->bookModel->update($id, $data);

            if ($result['ok']) {
                // Handle removing explicitly deleted images
                if (!empty($_POST['delete_images'])) {
                    foreach ($_POST['delete_images'] as $imgId) {
                        $img = $this->bookModel->getBookImageById($imgId);
                        if ($img && $img['book_id'] == $id) {
                            deleteFile($img['image_url']);
                            $this->bookModel->deleteBookImage($imgId);
                        }
                    }
                }

                // Upload New Gallery Images
                if (isset($_FILES['gallery_images']) && count($_FILES['gallery_images']['name']) > 0) {
                    $totalFiles = count($_FILES['gallery_images']['name']);
                    for ($i = 0; $i < $totalFiles; $i++) {
                        if ($_FILES['gallery_images']['error'][$i] == UPLOAD_ERR_OK) {
                            $file = [
                                'name'     => $_FILES['gallery_images']['name'][$i],
                                'type'     => $_FILES['gallery_images']['type'][$i],
                                'tmp_name' => $_FILES['gallery_images']['tmp_name'][$i],
                                'error'    => $_FILES['gallery_images']['error'][$i],
                                'size'     => $_FILES['gallery_images']['size'][$i]
                            ];
                            $imgPath = uploadFile($file, '/uploads/books/');
                            if ($imgPath) {
                                $this->bookModel->addBookImage($id, $imgPath);
                            }
                        }
                    }
                }

                Message::set('success', 'Cập nhật sách thành công!');
                redirect('admin-books');
            } else {
                $_SESSION['old'] = $_POST;
                Message::set('error', $result['message']);
                redirect('admin-books-edit&id=' . $id);
            }
        }
    }

    public function delete()
    {
        requireAdmin();

        $id = $_GET['id'] ?? 0;
        $book = $this->bookModel->getAdminById($id);

        if (!$book) {
            Message::set('error', 'Sách không tồn tại!');
            redirect('admin-books');
        }

        // Lấy gallery trước khi xóa record
        $thumbnail = $book['thumbnail'];
        $gallery = $this->bookModel->getBookImages($id);

        $result = $this->bookModel->delete($id);
        if ($result['ok']) {
            if ($thumbnail) deleteFile($thumbnail);
            foreach ($gallery as $img) {
                deleteFile($img['image_url']);
            }
            Message::set('success', 'Đã xóa sách "' . $book['title'] . '" thành công!');
        } else {
            Message::set('error', $result['message']);
        }

        redirect('admin-books');
    }

    public function detail()
    {
        requireAdmin();

        $id = $_GET['id'] ?? 0;
        $book = $this->bookModel->getAdminById($id);

        if (!$book) {
            Message::set('error', 'Sách không tồn tại!');
            redirect('admin-books');
        }

        $bookImages = $this->bookModel->getBookImages($id);
        
        require_once './views/admin/books/detail.php';
    }

    public function toggleStatus()
    {
        requireAdmin();

        $id = $_GET['id'] ?? 0;
        $book = $this->bookModel->getAdminById($id);

        if (!$book) {
            Message::set('error', 'Sách không tồn tại!');
            redirect('admin-books');
        }

        $result = $this->bookModel->toggleStatus($id);
        if ($result['ok']) {
            $newStatus = $book['status'] == 1 ? 'ẩn' : 'hiển thị';
            Message::set('success', 'Đã chuyển sách "' . $book['title'] . '" sang trạng thái ' . $newStatus . '!');
        } else {
            Message::set('error', $result['message']);
        }

        // Redirect về trang list và giữ nguyên filter
        $back = '';
        if (!empty($_GET['search'])) $back .= '&search=' . urlencode($_GET['search']);
        if (!empty($_GET['category'])) $back .= '&category=' . urlencode($_GET['category']);
        if (!empty($_GET['page'])) $back .= '&page=' . (int)$_GET['page'];

        redirect('admin-books' . $back);
    }
}
