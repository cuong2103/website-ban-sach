<?php

// Biến môi trường, dùng chung toàn hệ thống
// Khai báo dưới dạng HẰNG SỐ để không phải dùng $GLOBALS

define('BASE_URL', 'http://quan-li-agile.test/');
define('UPLOADS_URL', BASE_URL . 'uploads/');
define('DB_HOST', 'localhost');
define('DB_PORT', 3306);
define('DB_USERNAME', 'root');
define('DB_PASSWORD', '');
define('DB_NAME', 'quan_li_agile');  // Tên database

define('PATH_ROOT', __DIR__ . '/../');
