<?php
$validationErrors = $_SESSION['validation_errors'] ?? [];
$old = $_SESSION['old'] ?? [];

unset($_SESSION['validation_errors']);
unset($_SESSION['old']);

// Nếu không có old data, lấy từ $category
if (empty($old)) {
    $old = [
        'name' => $category['name'] ?? '',
        'slug' => $category['slug'] ?? '',
        'description' => $category['description'] ?? '',
        'status' => $category['status'] ?? 1
    ];
}
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sửa danh mục | Admin</title>
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
            color: #007bff;
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
        textarea,
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
        textarea:focus,
        select:focus {
            outline: none;
            border-color: #007bff;
            box-shadow: 0 0 0 3px rgba(0, 123, 255, 0.25);
        }

        textarea {
            resize: vertical;
            min-height: 120px;
        }

        .form-error {
            color: #721c24;
            font-size: 12px;
            margin-top: 5px;
        }

        .form-group.error input,
        .form-group.error textarea,
        .form-group.error select {
            border-color: #dc3545;
        }

        .form-group.error input:focus,
        .form-group.error textarea:focus,
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
            background-color: #28a745;
            color: white;
        }

        .btn-submit:hover {
            background-color: #218838;
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
            <h1>Sửa danh mục</h1>
            <a href="<?php echo BASE_URL; ?>admin-categories">← Quay lại danh sách</a>
        </div>

        <form method="POST" action="<?php echo BASE_URL; ?>admin-categories-update">
            <input type="hidden" name="id" value="<?php echo $category['id']; ?>">

            <!-- Tên danh mục -->
            <div class="form-group <?php echo isset($validationErrors['name']) ? 'error' : ''; ?>">
                <label for="name">Tên danh mục <span style="color: red;">*</span></label>
                <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($old['name']); ?>"
                    placeholder="Nhập tên danh mục" required>
                <?php if (isset($validationErrors['name'])): ?>
                    <div class="form-error"><?php echo $validationErrors['name']; ?></div>
                <?php endif; ?>
            </div>

            <!-- Slug -->
            <div class="form-group <?php echo isset($validationErrors['slug']) ? 'error' : ''; ?>">
                <label for="slug">Slug <span style="color: red;">*</span></label>
                <input type="text" id="slug" name="slug" value="<?php echo htmlspecialchars($old['slug']); ?>"
                    placeholder="Nhập slug" required>
                <?php if (isset($validationErrors['slug'])): ?>
                    <div class="form-error"><?php echo $validationErrors['slug']; ?></div>
                <?php endif; ?>
                <div class="helper-text">Slug chỉ có thể chứa chữ thường, số và dấu gạch ngang</div>
            </div>

            <!-- Mô tả -->
            <div class="form-group">
                <label for="description">Mô tả</label>
                <textarea id="description" name="description"
                    placeholder="Nhập mô tả danh mục"><?php echo htmlspecialchars($old['description']); ?></textarea>
                <div class="helper-text">Mục này không bắt buộc</div>
            </div>

            <!-- Trạng thái -->
            <div class="form-group">
                <label for="status">Trạng thái <span style="color: red;">*</span></label>
                <select id="status" name="status" required>
                    <option value="1" <?php echo $old['status'] == 1 ? 'selected' : ''; ?>>Hoạt động</option>
                    <option value="0" <?php echo $old['status'] == 0 ? 'selected' : ''; ?>>Khóa</option>
                </select>
            </div>

            <!-- Nút hành động -->
            <div class="form-actions">
                <button type="submit" class="btn btn-submit">Cập nhật danh mục</button>
                <a href="<?php echo BASE_URL; ?>admin-categories" class="btn btn-cancel">Hủy</a>
            </div>
        </form>
    </div>
</body>

</html>