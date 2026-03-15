<?php
$validationErrors = $_SESSION['validation_errors'] ?? [];
$old = $_SESSION['old'] ?? [];

unset($_SESSION['validation_errors']);
unset($_SESSION['old']);

// Nếu không có old data, lấy từ $flashSale
if (empty($old)) {
    $old = [
        'name' => $flashSale['name'] ?? '',
        'start_time' => $flashSale['start_time'] ?? '',
        'end_time' => $flashSale['end_time'] ?? '',
        'status' => $flashSale['status'] ?? 1
    ];
}

$successMessage = Message::get('success');
$errorMessage = Message::get('error');
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sửa Flash Sale | Admin</title>
    <link rel="stylesheet" href="<?php echo UPLOADS_URL; ?>../assets/common.css">
    <style>
        .container {
            max-width: 1000px;
            margin: 0 auto;
            padding: 20px;
        }

        .form-header {
            margin-bottom: 30px;
        }

        .form-header h1 {
            color: #333;
            margin: 0 0 10px 0;
        }

        .form-header a {
            color: #FF6B35;
            text-decoration: none;
        }

        .form-header a:hover {
            text-decoration: underline;
        }

        .grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 30px;
            margin-bottom: 30px;
        }

        .form-section {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        .form-section h2 {
            margin-top: 0;
            margin-bottom: 20px;
            color: #333;
            font-size: 16px;
            border-bottom: 2px solid #FF6B35;
            padding-bottom: 10px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #333;
        }

        input[type="text"],
        input[type="datetime-local"],
        select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 14px;
            font-family: Arial, sans-serif;
            box-sizing: border-box;
        }

        input[type="text"]:focus,
        input[type="datetime-local"]:focus,
        select:focus {
            outline: none;
            border-color: #FF6B35;
            box-shadow: 0 0 0 3px rgba(255, 107, 53, 0.25);
        }

        .form-error {
            color: #721c24;
            font-size: 12px;
            margin-top: 5px;
        }

        .form-group.error input,
        .form-group.error select {
            border-color: #dc3545;
        }

        .form-actions {
            display: flex;
            gap: 10px;
            margin-top: 30px;
        }

        .btn {
            flex: 1;
            padding: 12px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
            font-weight: 600;
            text-decoration: none;
            display: inline-block;
            text-align: center;
        }

        .btn-submit {
            background-color: #FF6B35;
            color: white;
        }

        .btn-submit:hover {
            background-color: #E55A24;
        }

        .btn-cancel {
            background-color: #6c757d;
            color: white;
        }

        .btn-cancel:hover {
            background-color: #5a6268;
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

        .items-list {
            margin-top: 20px;
            max-height: 400px;
            overflow-y: auto;
        }

        .item-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 12px;
            border: 1px solid #e0e0e0;
            border-radius: 5px;
            margin-bottom: 10px;
            background: #f9f9f9;
        }

        .item-info {
            flex: 1;
        }

        .item-title {
            font-weight: 600;
            color: #333;
            margin-bottom: 5px;
        }

        .item-details {
            font-size: 12px;
            color: #666;
        }

        .item-actions {
            display: flex;
            gap: 10px;
        }

        .btn-small {
            padding: 6px 12px;
            border: none;
            border-radius: 3px;
            cursor: pointer;
            font-size: 12px;
            text-decoration: none;
            display: inline-block;
        }

        .btn-delete-item {
            background-color: #dc3545;
            color: white;
        }

        .btn-delete-item:hover {
            background-color: #c82333;
        }

        .add-item-form {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }

        .add-item-form h3 {
            margin-top: 0;
            color: #333;
            font-size: 14px;
        }

        .empty-state {
            text-align: center;
            padding: 40px 20px;
            color: #999;
            background: #f9f9f9;
            border-radius: 5px;
        }

        .helper-text {
            font-size: 12px;
            color: #6c757d;
            margin-top: 5px;
        }

        @media (max-width: 768px) {
            .grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="form-header">
            <h1>Sửa Flash Sale</h1>
            <a href="<?php echo BASE_URL; ?>admin-flash-sales">← Quay lại danh sách</a>
        </div>

        <?php if ($successMessage): ?>
            <div class="message success"><?php echo $successMessage; ?></div>
        <?php endif; ?>

        <?php if ($errorMessage): ?>
            <div class="message error"><?php echo $errorMessage; ?></div>
        <?php endif; ?>

        <div class="grid">
            <!-- Thông tin Flash Sale -->
            <div class="form-section">
                <h2>Thông tin Flash Sale</h2>
                <form method="POST" action="<?php echo BASE_URL; ?>admin-flash-sales-update">
                    <input type="hidden" name="id" value="<?php echo $flashSale['id']; ?>">

                    <!-- Tên flash sale -->
                    <div class="form-group <?php echo isset($validationErrors['name']) ? 'error' : ''; ?>">
                        <label for="name">Tên Flash Sale <span style="color: red;">*</span></label>
                        <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($old['name']); ?>"
                            placeholder="Nhập tên flash sale" required>
                        <?php if (isset($validationErrors['name'])): ?>
                            <div class="form-error"><?php echo $validationErrors['name']; ?></div>
                        <?php endif; ?>
                    </div>

                    <!-- Thời gian bắt đầu -->
                    <div class="form-group <?php echo isset($validationErrors['start_time']) ? 'error' : ''; ?>">
                        <label for="start_time">Thời gian bắt đầu <span style="color: red;">*</span></label>
                        <input type="datetime-local" id="start_time" name="start_time"
                            value="<?php echo str_replace(' ', 'T', $old['start_time']); ?>" required>
                        <?php if (isset($validationErrors['start_time'])): ?>
                            <div class="form-error"><?php echo $validationErrors['start_time']; ?></div>
                        <?php endif; ?>
                    </div>

                    <!-- Thời gian kết thúc -->
                    <div class="form-group <?php echo isset($validationErrors['end_time']) ? 'error' : ''; ?>">
                        <label for="end_time">Thời gian kết thúc <span style="color: red;">*</span></label>
                        <input type="datetime-local" id="end_time" name="end_time"
                            value="<?php echo str_replace(' ', 'T', $old['end_time']); ?>" required>
                        <?php if (isset($validationErrors['end_time'])): ?>
                            <div class="form-error"><?php echo $validationErrors['end_time']; ?></div>
                        <?php endif; ?>
                    </div>

                    <!-- Trạng thái -->
                    <div class="form-group">
                        <label for="status">Trạng thái <span style="color: red;">*</span></label>
                        <select id="status" name="status" required>
                            <option value="1" <?php echo $old['status'] == 1 ? 'selected' : ''; ?>>Hoạt động</option>
                            <option value="0" <?php echo $old['status'] == 0 ? 'selected' : ''; ?>>Khóa</option>
                        </select>
                    </div>

                    <button type="submit" class="btn btn-submit" style="width: 100%;">Cập nhật Flash Sale</button>
                </form>
            </div>

            <!-- Thêm sách vào Flash Sale -->
            <div class="form-section">
                <h2>Thêm sách vào Flash Sale</h2>
                <div class="add-item-form">
                    <form method="POST" action="<?php echo BASE_URL; ?>admin-flash-sales-add-item">
                        <input type="hidden" name="flash_sale_id" value="<?php echo $flashSale['id']; ?>">

                        <div class="form-group">
                            <label for="book_id">Chọn sách <span style="color: red;">*</span></label>
                            <select id="book_id" name="book_id">
                                <option value="">-- Chọn sách --</option>
                                <?php foreach ($books as $book): ?>
                                    <option value="<?php echo $book['id']; ?>">
                                        <?php echo htmlspecialchars($book['title']); ?> -
                                        <?php echo htmlspecialchars($book['author']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="discount_percent">Giảm giá (%) <span style="color: red;">*</span></label>
                            <input type="text" id="discount_percent" name="discount_percent" placeholder="Nhập %"
                                value="0" required>
                            <div class="helper-text">Giá bán = Giá gốc × (100 - %)</div>
                        </div>

                        <div class="form-group">
                            <label for="sale_price">Giá sale (₫) <span style="color: red;">*</span></label>
                            <input type="text" id="sale_price" name="sale_price" placeholder="Nhập giá" required>
                        </div>

                        <div class="form-group">
                            <label for="stock_limit">Giới hạn số lượng</label>
                            <input type="text" id="stock_limit" name="stock_limit"
                                placeholder="Để trống nếu không giới hạn" value="0">
                            <div class="helper-text">0 = Không giới hạn</div>
                        </div>

                        <button type="submit" class="btn btn-submit" style="width: 100%;">Thêm sách</button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Danh sách sách trong Flash Sale -->
        <div class="form-section" style="margin-top: 30px;">
            <h2>Sách trong Flash Sale (<?php echo count($items); ?> sách)</h2>

            <?php if (empty($items)): ?>
                <div class="empty-state">
                    <p>Chưa có sách nào trong flash sale này.</p>
                </div>
            <?php else: ?>
                <div class="items-list">
                    <?php foreach ($items as $item): ?>
                        <div class="item-row">
                            <div class="item-info">
                                <div class="item-title"><?php echo htmlspecialchars($item['title']); ?></div>
                                <div class="item-details">
                                    Tác giả: <?php echo htmlspecialchars($item['author']); ?> |
                                    Giá gốc: <?php echo number_format($item['price']); ?>₫ |
                                    Giá sale: <?php echo number_format($item['sale_price']); ?>₫ |
                                    Giảm: <?php echo $item['discount_percent']; ?>%
                                    <?php if ($item['stock_limit'] > 0): ?>
                                        | Giới hạn: <?php echo $item['stock_limit']; ?> cái
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="item-actions">
                                <a href="<?php echo BASE_URL; ?>admin-flash-sales-remove-item?item_id=<?php echo $item['id']; ?>&flash_sale_id=<?php echo $flashSale['id']; ?>"
                                    class="btn-small btn-delete-item"
                                    onclick="return confirm('Bạn chắc chắn muốn xóa sách này khỏi flash sale?');">
                                    Xóa
                                </a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>

        <div style="margin-top: 30px;">
            <a href="<?php echo BASE_URL; ?>admin-flash-sales" class="btn btn-cancel" style="display: inline-block;">←
                Quay lại</a>
        </div>
    </div>
</body>

</html>