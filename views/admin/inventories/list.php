<?php
$search = $_GET['search'] ?? '';
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý Kho Hàng | Admin</title>
    <link rel="stylesheet" href="<?php echo UPLOADS_URL; ?>../assets/common.css">
    <style>
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }

        .page-header h1 {
            margin: 0;
            color: #333;
            font-size: 28px;
        }

        .btn-primary {
            background-color: #FF6B35;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            text-decoration: none;
            cursor: pointer;
            font-size: 14px;
            font-weight: 600;
            display: inline-block;
        }

        .btn-primary:hover {
            background-color: #E55A24;
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
            font-size: 14px;
        }

        .search-box button {
            background-color: #FF6B35;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-weight: 600;
        }

        .search-box button:hover {
            background-color: #E55A24;
        }

        .table-wrapper {
            background: white;
            border-radius: 8px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th {
            background-color: #f5f5f5;
            padding: 15px;
            text-align: left;
            font-weight: 600;
            color: #333;
            border-bottom: 2px solid #FF6B35;
        }

        td {
            padding: 15px;
            border-bottom: 1px solid #eee;
        }

        tr:hover {
            background-color: #f9f9f9;
        }

        .actions {
            display: flex;
            gap: 10px;
        }

        .btn-edit {
            background-color: #28a745;
            color: white;
            padding: 8px 15px;
            border: none;
            border-radius: 4px;
            text-decoration: none;
            cursor: pointer;
            font-size: 12px;
            font-weight: 600;
        }

        .btn-edit:hover {
            background-color: #218838;
        }

        .btn-delete {
            background-color: #dc3545;
            color: white;
            padding: 8px 15px;
            border: none;
            border-radius: 4px;
            text-decoration: none;
            cursor: pointer;
            font-size: 12px;
            font-weight: 600;
        }

        .btn-delete:hover {
            background-color: #c82333;
        }

        .badge {
            display: inline-block;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }

        .badge-warning {
            background-color: #fff3cd;
            color: #856404;
        }

        .badge-success {
            background-color: #d4edda;
            color: #155724;
        }

        .badge-danger {
            background-color: #f8d7da;
            color: #721c24;
        }

        .pagination {
            display: flex;
            justify-content: center;
            gap: 10px;
            margin-top: 20px;
            padding: 20px 0;
        }

        .pagination a,
        .pagination span {
            padding: 8px 12px;
            border: 1px solid #ddd;
            border-radius: 4px;
            text-decoration: none;
            color: #333;
        }

        .pagination a:hover {
            background-color: #FF6B35;
            color: white;
            border-color: #FF6B35;
        }

        .pagination .active {
            background-color: #FF6B35;
            color: white;
            border-color: #FF6B35;
        }

        .empty-message {
            text-align: center;
            padding: 40px;
            color: #999;
        }

        .message {
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 5px;
        }

        .message.success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .message.error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        @media (max-width: 768px) {
            .page-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 15px;
            }

            .search-box {
                flex-direction: column;
            }

            table {
                font-size: 12px;
            }

            th,
            td {
                padding: 10px;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="page-header">
            <h1>Quản lý Kho Hàng</h1>
        </div>

        <?php if (Message::get('success')): ?>
            <div class="message success"><?php echo Message::get('success'); ?></div>
        <?php endif; ?>

        <?php if (Message::get('error')): ?>
            <div class="message error"><?php echo Message::get('error'); ?></div>
        <?php endif; ?>

        <div class="search-box">
            <form method="GET" style="display: flex; gap: 10px; flex: 1;">
                <input type="hidden" name="act" value="admin-inventories">
                <input type="text" name="search" placeholder="Tìm kiếm theo tên sách, tác giả, hoặc ID"
                    value="<?php echo htmlspecialchars($search); ?>">
                <button type="submit">Tìm kiếm</button>
            </form>
        </div>

        <div class="table-wrapper">
            <?php if (empty($inventories)): ?>
                <div class="empty-message">
                    <p>Chưa có kho hàng nào.</p>
                </div>
            <?php else: ?>
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Tên Sách</th>
                            <th>Tác Giả</th>
                            <th>Danh Mục</th>
                            <th>Giá</th>
                            <th>Tồn Kho</th>
                            <th>Nhập Kho</th>
                            <th>Cập Nhật</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($inventories as $inventory): ?>
                            <tr>
                                <td><?php echo $inventory['id']; ?></td>
                                <td><?php echo htmlspecialchars($inventory['title']); ?></td>
                                <td><?php echo htmlspecialchars($inventory['author']); ?></td>
                                <td><?php echo htmlspecialchars($inventory['category_name'] ?? 'N/A'); ?></td>
                                <td><?php echo number_format($inventory['price']); ?>₫</td>
                                <td>
                                    <?php if ($inventory['stock_quantity'] <= 5): ?>
                                        <span class="badge badge-danger"><?php echo $inventory['stock_quantity']; ?></span>
                                    <?php elseif ($inventory['stock_quantity'] <= 20): ?>
                                        <span class="badge badge-warning"><?php echo $inventory['stock_quantity']; ?></span>
                                    <?php else: ?>
                                        <span class="badge badge-success"><?php echo $inventory['stock_quantity']; ?></span>
                                    <?php endif; ?>
                                </td>
                                <td><?php echo $inventory['imported_quantity']; ?></td>
                                <td><?php echo date('d/m/Y H:i', strtotime($inventory['updated_at'])); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>

        <?php if ($totalPages > 1): ?>
            <div class="pagination">
                <?php if ($page > 1): ?>
                    <a
                        href="<?php echo BASE_URL; ?>admin-inventories?page=1<?php echo !empty($search) ? '&search=' . urlencode($search) : ''; ?>">Đầu</a>
                    <a
                        href="<?php echo BASE_URL; ?>admin-inventories?page=<?php echo $page - 1; ?><?php echo !empty($search) ? '&search=' . urlencode($search) : ''; ?>">Trước</a>
                <?php endif; ?>

                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                    <?php if ($i == $page): ?>
                        <span class="active"><?php echo $i; ?></span>
                    <?php else: ?>
                        <a
                            href="<?php echo BASE_URL; ?>admin-inventories?page=<?php echo $i; ?><?php echo !empty($search) ? '&search=' . urlencode($search) : ''; ?>"><?php echo $i; ?></a>
                    <?php endif; ?>
                <?php endfor; ?>

                <?php if ($page < $totalPages): ?>
                    <a
                        href="<?php echo BASE_URL; ?>admin-inventories?page=<?php echo $page + 1; ?><?php echo !empty($search) ? '&search=' . urlencode($search) : ''; ?>">Sau</a>
                    <a
                        href="<?php echo BASE_URL; ?>admin-inventories?page=<?php echo $totalPages; ?><?php echo !empty($search) ? '&search=' . urlencode($search) : ''; ?>">Cuối</a>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>
</body>

</html>