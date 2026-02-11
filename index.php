<?php
// index.php (di root)
session_start(); // ✅ WAJIB: mulai session di awal

// Load konfigurasi
require_once __DIR__ . '/config.php';

// Jika user sudah login → arahkan ke dashboard
if (isset($_SESSION['user_id'])) {
    header("Location: pages/dashboard/index.php");
    exit();
} else {
    // Jika belum login → arahkan ke halaman login
    header("Location: pages/auth/login.php");
    exit();
}
?>