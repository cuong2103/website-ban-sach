
<?php
require_once './views/components/header.php';
require_once './views/components/sidebar.php';
?><?php
$currentPage = $page;
$successMessage = Message::get('success');
$errorMessage = Message::get('error');

$validationErrors = $_SESSION['validation_errors'] ?? [];
unset($_SESSION['validation_errors']);
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý Danh mục | Admin</title>
    <link rel="stylesheet" href="<?php echo UPLOADS_URL; ?>../assets/common.css">
    <style>
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        .header-section {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }

        .header-section h1 {
            margin: 0;
            color: #333;
        }

        .btn-primary {
            background-color: #007bff;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
        }

        .btn-primary:hover {
            background-color: #0056b3;
        }

        .search-box {
            margin-bottom: 20px;
            display: flex;
            gap: 10px;
        }

        .search-box input {
            flex: 1;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        .search-box button {
            padding: 10px 20px;
            background-color: #6c757d;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        .table th {
            background-color: #f8f9fa;
            padding: 15px;
            text-align: left;
            border-bottom: 2px solid #dee2e6;
            font-weight: 600;
            color: #495057;
        }

        .table td {
            padding: 15px;
            border-bottom: 1px solid #dee2e6;
        }

        .table tbody tr:hover {
            background-color: #f8f9fa;
        }

        .status {
            padding: 5px 10px;
            border-radius: 3px;
            font-size: 12px;
            font-weight: 600;
        }

        .status.active {
            background-color: #d4edda;
            color: #155724;
        }

        .status.inactive {
            background-color: #f8d7da;
            color: #721c24;
        }

        .actions {
            display: flex;
            gap: 10px;
        }

        .btn-edit,
        .btn-delete {
            padding: 6px 12px;
            border: none;
            border-radius: 3px;
            cursor: pointer;
            font-size: 12px;
            text-decoration: none;
            display: inline-block;
        }

        .btn-edit {
            background-color: #28a745;
            color: white;
        }

        .btn-edit:hover {
            background-color: #218838;
        }

        .btn-delete {
            background-color: #dc3545;
            color: white;
        }

        .btn-delete:hover {
            background-color: #c82333;
        }

        .message {
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 5px;
            display: none;
        }

        .message.success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
            display: block;
        }

        .message.error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
            display: block;
        }

        .pagination {
            display: flex;
            justify-content: center;
            gap: 5px;
            margin-top: 20px;
        }

        .pagination a,
        .pagination span {
            padding: 8px 12px;
            border: 1px solid #ddd;
            border-radius: 3px;
            text-decoration: none;
            color: #007bff;
        }

        .pagination a:hover {
            background-color: #f8f9fa;
        }

        .pagination .active {
            background-color: #007bff;
            color: white;
            border-color: #007bff;
        }

        .pagination .disabled {
            color: #999;
            cursor: not-allowed;
        }

        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: #999;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header-section">
            <h1>Quản lý Danh mục</h1>
            <a href="<?php echo BASE_URL; ?>admin-categories-create" class="btn-primary">+ Thêm danh mục</a>
        </div>

        <?php if ($successMessage): ?>
            <div class="message success"><?php echo $successMessage; ?></div>
        <?php endif; ?>

        <?php if ($errorMessage): ?>
            <div class="message error"><?php echo $errorMessage; ?></div>
        <?php endif; ?>

        <div class="search-box">
            <form method="GET" style="display: flex; gap: 10px; flex: 1;">
                <input type="text" name="search" placeholder="Tìm kiếm theo tên hoặc slug..."
                    value="<?php echo htmlspecialchars($search); ?>" required>
                <button type="submit">Tìm kiếm</button>
                <?php if (!empty($search)): ?>
                    <a href="<?php echo BASE_URL; ?>admin-categories"
                        style="padding: 10px 20px; background-color: #6c757d; color: white; border-radius: 5px; text-decoration: none;">Xóa
                        bộ lọc</a>
                <?php endif; ?>
            </form>
        </div>

        <?php if (empty($categories)): ?>
            <div class="empty-state">
                <p>Không có danh mục nào.</p>
            </div>
        <?php else: ?>
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Tên danh mục</th>
                        <th>Slug</th>
                        <th>Mô tả</th>
                        <th>Trạng thái</th>
                        <th>Ngày tạo</th>
                        <th>Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($categories as $category): ?>
                        <tr>
                            <td><?php echo $category['id']; ?></td>
                            <td><?php echo htmlspecialchars($category['name']); ?></td>
                            <td><code><?php echo htmlspecialchars($category['slug']); ?></code></td>
                            <td><?php echo htmlspecialchars(substr($category['description'] ?? '', 0, 50)); ?></td>
                            <td>
                                <span class="status <?php echo $category['status'] == 1 ? 'active' : 'inactive'; ?>">
                                    <?php echo $category['status'] == 1 ? 'Hoạt động' : 'Khóa'; ?>
                                </span>
                            </td>
                            <td><?php echo date('d/m/Y H:i', strtotime($category['created_at'])); ?></td>
                            <td>
                                <div class="actions">
                                    <a href="<?php echo BASE_URL; ?>admin-categories-edit?id=<?php echo $category['id']; ?>"
                                        class="btn-edit">Sửa</a>
                                    <a href="<?php echo BASE_URL; ?>admin-categories-delete?id=<?php echo $category['id']; ?>"
                                        class="btn-delete" onclick="return confirm('Bạn chắc chắn muốn xóa?');">Xóa</a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <?php if ($totalPages > 1): ?>
                <div class="pagination">
                    <?php if ($currentPage > 1): ?>
                        <a
                            href="<?php echo BASE_URL; ?>admin-categories?page=1<?php echo !empty($search) ? '&search=' . urlencode($search) : ''; ?>">Đầu</a>
                        <a
                            href="<?php echo BASE_URL; ?>admin-categories?page=<?php echo $currentPage - 1; ?><?php echo !empty($search) ? '&search=' . urlencode($search) : ''; ?>">Trước</a>
                    <?php endif; ?>

                    <?php
                    $start = max(1, $currentPage - 2);
                    $end = min($totalPages, $currentPage + 2);

                    for ($i = $start; $i <= $end; $i++):
                        ?>
                        <?php if ($i == $currentPage): ?>
                            <span class="active"><?php echo $i; ?></span>
                        <?php else: ?>
                            <a
                                href="<?php echo BASE_URL; ?>admin-categories?page=<?php echo $i; ?><?php echo !empty($search) ? '&search=' . urlencode($search) : ''; ?>"><?php echo $i; ?></a>
                        <?php endif; ?>
                    <?php endfor; ?>

                    <?php if ($currentPage < $totalPages): ?>
                        <a
                            href="<?php echo BASE_URL; ?>admin-categories?page=<?php echo $currentPage + 1; ?><?php echo !empty($search) ? '&search=' . urlencode($search) : ''; ?>">Sau</a>
                        <a
                            href="<?php echo BASE_URL; ?>admin-categories?page=<?php echo $totalPages; ?><?php echo !empty($search) ? '&search=' . urlencode($search) : ''; ?>">Cuối</a>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</body>

</html>