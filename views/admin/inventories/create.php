<?php
$validationErrors = $_SESSION['validation_errors'] ?? [];
$old = $_SESSION['old'] ?? [];

unset($_SESSION['validation_errors']);
unset($_SESSION['old']);
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thêm Kho Hàng | Admin</title>
    <link rel="stylesheet" href="<?php echo UPLOADS_URL; ?>../assets/common.css">
    <style>
        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }

        .form-header {
            margin-bottom: 30px;
        }

        .form-header h1 {
            color: #333;
            margin: 0 0 10px 0;
            font-size: 28px;
        }

        .form-header a {
            color: #FF6B35;
            text-decoration: none;
            font-size: 14px;
        }

        .form-header a:hover {
            text-decoration: underline;
        }

        .form-section {
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #333;
            font-size: 14px;
        }

        input[type="text"],
        input[type="number"],
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
        input[type="number"]:focus,
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
            transition: background-color 0.3s;
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

        .helper-text {
            font-size: 12px;
            color: #6c757d;
            margin-top: 5px;
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
            .container {
                padding: 10px;
            }

            .form-section {
                padding: 20px;
            }

            .form-actions {
                flex-direction: column;
            }

            .btn {
                flex: unset;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="form-header">
            <h1>Thêm Kho Hàng</h1>
            <a href="<?php echo BASE_URL; ?>admin-inventories">← Quay lại danh sách</a>
        </div>

        <div class="form-section">
            <form method="POST" action="<?php echo BASE_URL; ?>admin-inventories-store">
                <!-- Chọn sách -->
                <div class="form-group <?php echo isset($validationErrors['book_id']) ? 'error' : ''; ?>">
                    <label for="book_id">Chọn Sách <span style="color: red;">*</span></label>
                    <select id="book_id" name="book_id" required>
                        <option value="">-- Chọn sách --</option>
                        <?php foreach ($books as $book): ?>
                            <option value="<?php echo $book['id']; ?>" <?php echo old('book_id') == $book['id'] ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($book['title']); ?> -
                                <?php echo htmlspecialchars($book['author']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <?php if (isset($validationErrors['book_id'])): ?>
                        <div class="form-error"><?php echo $validationErrors['book_id']; ?></div>
                    <?php endif; ?>
                </div>

                <!-- Số lượng tồn kho -->
                <div class="form-group <?php echo isset($validationErrors['stock_quantity']) ? 'error' : ''; ?>">
                    <label for="stock_quantity">Số Lượng Tồn Kho <span style="color: red;">*</span></label>
                    <input type="number" id="stock_quantity" name="stock_quantity"
                        value="<?php echo old('stock_quantity', 0); ?>" min="0" placeholder="Nhập số lượng" required>
                    <?php if (isset($validationErrors['stock_quantity'])): ?>
                        <div class="form-error"><?php echo $validationErrors['stock_quantity']; ?></div>
                    <?php endif; ?>
                    <div class="helper-text">Số lượng hiện có trong kho</div>
                </div>

                <!-- Số lượng nhập kho -->
                <div class="form-group <?php echo isset($validationErrors['imported_quantity']) ? 'error' : ''; ?>">
                    <label for="imported_quantity">Số Lượng Nhập Kho <span style="color: red;">*</span></label>
                    <input type="number" id="imported_quantity" name="imported_quantity"
                        value="<?php echo old('imported_quantity', 0); ?>" min="0" placeholder="Nhập số lượng" required>
                    <?php if (isset($validationErrors['imported_quantity'])): ?>
                        <div class="form-error"><?php echo $validationErrors['imported_quantity']; ?></div>
                    <?php endif; ?>
                    <div class="helper-text">Tổng số lượng đã nhập vào kho tính từ lúc đầu</div>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-submit">Thêm Kho Hàng</button>
                    <a href="<?php echo BASE_URL; ?>admin-inventories" class="btn btn-cancel">Hủy</a>
                </div>
            </form>
        </div>
    </div>
</body>

</html>