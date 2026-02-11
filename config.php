<?php
// Cegah redefine constant
if (!defined('DB_HOST')) {
    define('DB_HOST', 'localhost');
    define('DB_USER', 'root');
    define('DB_PASS', '');
    define('DB_NAME', 'inventaris_kantor');
    define('BASE_URL', 'http://inventaris_kantor.test/');
}

// Cek session hanya jika belum aktif
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>
