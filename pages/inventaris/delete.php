<?php
require_once __DIR__ . '/../../includes/db_connect.php';
require_once __DIR__ . '/../../includes/auth_check.php';
require_once __DIR__ . '/../../includes/functions.php';

// Hanya admin yang boleh menghapus
if ($_SESSION['role'] !== 'admin') {
    flash("Anda tidak memiliki izin untuk menghapus data!", "warning");
    redirect("pages/inventaris/");
}

$db = getDB();

if ($_GET['id']) {
    try {
        $stmt = $db->prepare("DELETE FROM aset WHERE id = ?");
        $stmt->execute([$_GET['id']]);
        flash("Aset berhasil dihapus!", "success");
    } catch (Exception $e) {
        flash("Error: " . $e->getMessage(), "danger");
    }
}

redirect("pages/inventaris/");
?>