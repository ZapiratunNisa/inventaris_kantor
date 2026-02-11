<?php
require_once __DIR__ . '/../includes/auth_check.php';
// Pastikan BASE_URL sudah didefinisikan di config.php
if (!defined('BASE_URL')) {
    define('BASE_URL', '/');
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>InventarisKantor</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css  " rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css  ">
    <link href="https://fonts.googleapis.com/css2?family=Inter  :wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/style.css?v=<?= time() ?>">
    <style>
        /* Minimal style untuk sidebar scrollable */
        html, body {
            height: 100%;
            overflow-x: hidden;
        }
        .wrapper {
            display: flex;
            min-height: 100vh;
        }
        .main-sidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            width: 230px;
            overflow-y: auto;
            background: linear-gradient(90deg, #5430f8ff, #2a7596);
            z-index: 1000;
        }
        .content-wrapper {
            margin-left: 230px;
            padding-top: 56px;
            width: calc(100% - 230px);
        }
        .main-header {
            position: fixed;
            top: 0;
            left: 230px;
            right: 0;
            z-index: 1001;
            background: #fff;
            box-shadow: 0 2px 5px #9210101a;
        }
        .small-box { border-radius: 8px; box-shadow: 0 4px 8px rgba(0,0,0,0.1); }
        .bg-indigo { background: linear-gradient(135deg, #667eea, #764ba2); color: white; }
        .bg-teal { background: linear-gradient(135deg, #45b7d1, #2a7596); color: white; }
        .bg-amber { background: linear-gradient(135deg, #f093fb, #f5576c); color: white; }
        .bg-rose { background: linear-gradient(135deg, #fa709a, #fee140); color: white; }
        .nav-sidebar .nav-link {
            color: #c2c7d0;
            transition: all 0.3s;
            padding: 10px 15px;
            border-radius: 6px;
            margin: 4px 10px;
        }
        .nav-sidebar .nav-link:hover,
        .nav-sidebar .nav-link.active {
            color: white;
            background: linear-gradient(90deg, #215bc6ff, #0b2c62ff);
        }
        .sidebar-brand {
            display: block;
            padding: 15px 20px;
            font-weight: bold;
            color: white;
            text-decoration: none;
            border-bottom: 1px solid #e6edf5ff;
        }
        .user-panel .info a {
            color:  #eef2f8ff !important;
            font-weight: 600;
        }
        .user-panel small {
            color: #eef2f8ff !important;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <nav class="main-header navbar navbar-expand navbar-white navbar-light">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
                </li>
            </ul>
            <ul class="navbar-nav ms-auto">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                        <i class="fas fa-user"></i> <?= $_SESSION['nama_lengkap'] ?? 'Pengguna' ?>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="<?= BASE_URL ?>pages/auth/logout.php">
                            <i class="fas fa-sign-out-alt me-2"></i> Logout
                        </a></li>
                    </ul>
                </li>
            </ul>
        </nav>