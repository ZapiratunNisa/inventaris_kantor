<?php
function rupiah($angka) {
    return "Rp " . number_format($angka, 0, ',', '.');
}

function redirect($url) {
    header("Location: " . BASE_URL . $url);
    exit();
}

function flash($message, $type = 'info') {
    $_SESSION['flash_message'] = $message;
    $_SESSION['flash_type'] = $type;
}

function showFlash() {
    if (isset($_SESSION['flash_message'])) {
        $type = $_SESSION['flash_type'] ?? 'info';
        $message = $_SESSION['flash_message'];
        echo "<div class='alert alert-$type alert-dismissible fade show' role='alert'>
                $message
                <button type='button' class='btn-close' data-bs-dismiss='alert'></button>
              </div>";
        unset($_SESSION['flash_message']);
        unset($_SESSION['flash_type']);
    }
}

function getAsetStatusBadge($status) {
    $badgeClass = match($status) {
        'Aktif' => 'bg-teal',
        'Rusak' => 'bg-amber',
        'Kadaluarsa' => 'bg-rose',
        default => 'bg-gray'
    };
    return "<span class='badge $badgeClass'>$status</span>";
}
?>