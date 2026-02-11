<?php
require_once __DIR__ . '/../../includes/db_connect.php';
require_once '../../includes/auth_check.php';
require_once '../../includes/functions.php';

$db = getDB();

if ($_GET['id']) {
    try {
        $stmt = $db->prepare("DELETE FROM kategori WHERE id = ?");
        $stmt->execute([$_GET['id']]);
        flash("Kategori berhasil dihapus!", "success");
    } catch (Exception $e) {
        flash("Error: " . $e->getMessage(), "danger");
    }
}

redirect("pages/kategori/");
?>