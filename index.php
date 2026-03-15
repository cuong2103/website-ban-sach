<?php

require_once './config/env.php'; // Khai báo biến môi trường
require_once './commons/function.php'; // Hàm hỗ trợ
require_once './commons/helperTree.php'; // Hàm hỗ trợ
require_once './commons/message.php'; // Hàm hỗ trợ
require_once './config/autoload.php'; //Tự động require Controller và Models

// Disable browser/proxy caching for dynamic pages.
if (!headers_sent()) {
	header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
	header('Cache-Control: post-check=0, pre-check=0', false);
	header('Pragma: no-cache');
	header('Expires: 0');
}
//Router
require_once './routers/web.php';