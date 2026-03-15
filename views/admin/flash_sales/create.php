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
    <title>Tạo Flash Sale | Admin</title>
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
        }

        .form-header a {
            color: #FF6B35;
            text-decoration: none;
        }

        .form-header a:hover {
            text-decoration: underline;
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

        .form-group.error input:focus,
        .form-group.error select:focus {
            box-shadow: 0 0 0 3px rgba(220, 53, 69, 0.25);
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

        .helper-text {
            font-size: 12px;
            color: #6c757d;
            margin-top: 5px;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="form-header">
            <h1>Tạo Flash Sale mới</h1>
            <a href="<?php echo BASE_URL; ?>admin-flash-sales">← Quay lại danh sách</a>
        </div>

        <form method="POST" action="<?php echo BASE_URL; ?>admin-flash-sales-store">
            <!-- Tên flash sale -->
            <div class="form-group <?php echo isset($validationErrors['name']) ? 'error' : ''; ?>">
                <label for="name">Tên Flash Sale <span style="color: red;">*</span></label>
                <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($old['name'] ?? ''); ?>"
                    placeholder="Nhập tên flash sale (ví dụ: Flash Sale Thứ 6)" required>
                <?php if (isset($validationErrors['name'])): ?>
                    <div class="form-error"><?php echo $validationErrors['name']; ?></div>
                <?php endif; ?>
            </div>

            <!-- Thời gian bắt đầu -->
            <div class="form-group <?php echo isset($validationErrors['start_time']) ? 'error' : ''; ?>">
                <label for="start_time">Thời gian bắt đầu <span style="color: red;">*</span></label>
                <input type="datetime-local" id="start_time" name="start_time"
                    value="<?php echo htmlspecialchars($old['start_time'] ?? ''); ?>" required>
                <?php if (isset($validationErrors['start_time'])): ?>
                    <div class="form-error"><?php echo $validationErrors['start_time']; ?></div>
                <?php endif; ?>
                <div class="helper-text">Ngày giờ bắt đầu của flash sale</div>
            </div>

            <!-- Thời gian kết thúc -->
            <div class="form-group <?php echo isset($validationErrors['end_time']) ? 'error' : ''; ?>">
                <label for="end_time">Thời gian kết thúc <span style="color: red;">*</span></label>
                <input type="datetime-local" id="end_time" name="end_time"
                    value="<?php echo htmlspecialchars($old['end_time'] ?? ''); ?>" required>
                <?php if (isset($validationErrors['end_time'])): ?>
                    <div class="form-error"><?php echo $validationErrors['end_time']; ?></div>
                <?php endif; ?>
                <div class="helper-text">Ngày giờ kết thúc của flash sale (phải sau thời gian bắt đầu)</div>
            </div>

            <!-- Trạng thái -->
            <div class="form-group">
                <label for="status">Trạng thái <span style="color: red;">*</span></label>
                <select id="status" name="status" required>
                    <option value="1" <?php echo ($old['status'] ?? 1) == 1 ? 'selected' : ''; ?>>Hoạt động</option>
                    <option value="0" <?php echo ($old['status'] ?? 1) == 0 ? 'selected' : ''; ?>>Khóa</option>
                </select>
            </div>

            <!-- Nút hành động -->
            <div class="form-actions">
                <button type="submit" class="btn btn-submit">Tạo Flash Sale</button>
                <a href="<?php echo BASE_URL; ?>admin-flash-sales" class="btn btn-cancel">Hủy</a>
            </div>
        </form>
    </div>
</body>

</html>