<?php require_once '../../config.php'; ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Access Denied</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/style.css">
</head>
<body class="login-page">
    <div class="d-flex align-items-center justify-content-center" style="height: 100vh;">
        <div class="text-center">
            <div class="display-1 text-danger">403</div>
            <h2 class="text-white">Akses Ditolak!</h2>
            <p class="text-white-50">Anda tidak memiliki izin untuk mengakses halaman ini.</p>
            <a href="<?= BASE_URL ?>" class="btn btn-primary mt-3">
                <i class="fas fa-home me-1"></i> Kembali ke Dashboard
            </a>
        </div>
    </div>
</body>
</html>