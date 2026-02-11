<?php
require_once __DIR__ . '/../config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: " . BASE_URL . "pages/auth/login.php");
    exit();
}

// Opsional: Cek role (jika ingin batasi akses)
// if ($_SESSION['role'] !== 'admin') {
//     header("Location: " . BASE_URL . "pages/errors/403.php");
//     exit();
// }
?>