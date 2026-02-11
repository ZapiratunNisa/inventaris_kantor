<?php
require_once __DIR__ . '/../../includes/db_connect.php';
require_once '../../includes/auth_check.php';
require_once '../../includes/functions.php';

if ($_SESSION['role'] !== 'admin') {
    redirect('pages/errors/403.php');
}

$db = getDB();

if ($_GET['id'] && $_GET['id'] != $_SESSION['user_id']) {
    try {
        $stmt = $db->prepare("DELETE FROM users WHERE id = ?");
        $stmt->execute([$_GET['id']]);
        flash("Pengguna berhasil dihapus!", "success");
    } catch (Exception $e) {
        flash("Error: " . $e->getMessage(), "danger");
    }
} else {
    flash("Tidak bisa menghapus akun sendiri!", "warning");
}

redirect("pages/users/");
?>