<?php
declare(strict_types=1);

define('APP_NAME', 'My Magazine');
define('BASE_URL', '/my-magazine'); // Change if your folder name differs.
define('ROOT_PATH', dirname(__DIR__));
define('UPLOAD_PATH', ROOT_PATH . '/uploads/articles/');

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_name('MYMAGAZINE_SID');
    session_start();
}

date_default_timezone_set('Africa/Lagos');

require_once ROOT_PATH . '/config/database.php';
require_once ROOT_PATH . '/includes/functions.php';
