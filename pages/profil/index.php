<?php
// pages/profil/index.php
require_once __DIR__ . '/../../includes/db_connect.php';
require_once __DIR__ . '/../../includes/auth_check.php';
require_once __DIR__ . '/../../includes/header.php';
require_once __DIR__ . '/../../includes/sidebar.php';
require_once __DIR__ . '/../../includes/functions.php';

$db = getDB();
$user_id = $_SESSION['user_id'];

// Ambil data user
$stmt = $db->prepare("SELECT id, nama_lengkap, username, role, foto, created_at FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch();

if (!$user) {
    die("Pengguna tidak ditemukan!");
}

// Tentukan path foto
$foto_path = 'assets/image/default-avatar.png';
if (!empty($user['foto'])) {
    $foto_cek = __DIR__ . '/../../assets/uploads/profil/' . $user['foto'];
    if (file_exists($foto_cek)) {
        $foto_path = 'assets/uploads/profil/' . $user['foto'];
    }
}

$error = '';
$success = '';

// Proses upload foto
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'upload_foto') {
    if (!isset($_FILES['foto']) || $_FILES['foto']['error'] !== UPLOAD_ERR_OK) {
        $error = "Tidak ada file yang diupload atau terjadi error.";
    } else {
        $file = $_FILES['foto'];
        $allowed_types = ['image/jpeg', 'image/png', 'image/jpg'];
        $max_size = 2 * 1024 * 1024; // 2 MB

        if (!in_array($file['type'], $allowed_types)) {
            $error = "Hanya file gambar (JPG/PNG) yang diizinkan.";
        } elseif ($file['size'] > $max_size) {
            $error = "Ukuran file maksimal 2 MB.";
        } else {
            // Hapus foto lama jika ada
            if (!empty($user['foto'])) {
                $old_file = __DIR__ . '/../../assets/uploads/profil/' . $user['foto'];
                if (file_exists($old_file)) {
                    unlink($old_file);
                }
            }

            // Generate nama file unik
            $file_ext = pathinfo($file['name'], PATHINFO_EXTENSION);
            $new_filename = 'profil_' . $user_id . '_' . time() . '.' . $file_ext;
            $upload_path = __DIR__ . '/../../assets/uploads/profil/' . $new_filename;

            if (move_uploaded_file($file['tmp_name'], $upload_path)) {
                $stmt = $db->prepare("UPDATE users SET foto = ? WHERE id = ?");
                $stmt->execute([$new_filename, $user_id]);
                $_SESSION['foto'] = $new_filename;
                $success = "Foto profil berhasil diupload!";
                $foto_path = 'assets/uploads/profil/' . $new_filename;
                $user['foto'] = $new_filename;
            } else {
                $error = "Gagal menyimpan file. Pastikan folder upload bisa ditulis.";
            }
        }
    }
}

// Proses update profil
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'update_profil') {
    $nama_lengkap = trim($_POST['nama_lengkap'] ?? '');
    $username = trim($_POST['username'] ?? '');

    if (empty($nama_lengkap) || empty($username)) {
        $error = "Nama lengkap dan username wajib diisi!";
    } else {
        $stmt = $db->prepare("SELECT id FROM users WHERE username = ? AND id != ?");
        $stmt->execute([$username, $user_id]);
        if ($stmt->fetch()) {
            $error = "Username sudah digunakan oleh pengguna lain!";
        } else {
            $stmt = $db->prepare("UPDATE users SET nama_lengkap = ?, username = ? WHERE id = ?");
            $stmt->execute([$nama_lengkap, $username, $user_id]);
            $_SESSION['nama_lengkap'] = $nama_lengkap;
            $_SESSION['username'] = $username;
            $success = "Profil berhasil diperbarui!";
            $user['nama_lengkap'] = $nama_lengkap;
            $user['username'] = $username;
        }
    }
}

// Proses update password
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'update_password') {
    $password_lama = $_POST['password_lama'] ?? '';
    $password_baru = $_POST['password_baru'] ?? '';
    $konfirmasi_password = $_POST['konfirmasi_password'] ?? '';

    if (empty($password_lama) || empty($password_baru) || empty($konfirmasi_password)) {
        $error = "Semua field password wajib diisi!";
    } elseif ($password_baru !== $konfirmasi_password) {
        $error = "Konfirmasi password tidak sesuai!";
    } else {
        $stmt = $db->prepare("SELECT password FROM users WHERE id = ?");
        $stmt->execute([$user_id]);
        $hashed_password = $stmt->fetchColumn();

        if (password_verify($password_lama, $hashed_password)) {
            $new_hashed_password = password_hash($password_baru, PASSWORD_DEFAULT);
            $stmt = $db->prepare("UPDATE users SET password = ? WHERE id = ?");
            $stmt->execute([$new_hashed_password, $user_id]);
            $success = "Password berhasil diubah!";
        } else {
            $error = "Password lama salah!";
        }
    }
}
?>

<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Profil Saya</h1>
                </div>
            </div>
        </div>
    </div>

    <div class="content">
        <div class="container-fluid">
            <?php if (!empty($error)): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <?= htmlspecialchars($error) ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>
            <?php if (!empty($success)): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <?= htmlspecialchars($success) ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <div class="row">
                <!-- Profil Card -->
                <div class="col-md-4">
                    <div class="card card-primary card-outline">
                        <div class="card-body box-profile">
                            <div class="text-center mb-3">
                                <img class="profile-user-img img-fluid img-circle border"
                                     src="<?= BASE_URL . $foto_path ?>"
                                     alt="Foto Profil"
                                     style="width: 120px; height: 120px; object-fit: cover; border: 3px solid #82bff7ff; box-shadow: 0 0 10px rgba(0,0,0,0.1);">
                            </div>

                            <h3 class="profile-username text-center"><?= htmlspecialchars($user['username']) ?></h3>
                            <p class="text-muted text-center">
                                <?php if ($user['role'] === 'admin'): ?>
                                    <span class="badge bg-danger">Admin</span>
                                <?php else: ?>
                                    <span class="badge bg-success">Staf</span>
                                <?php endif; ?>
                            </p>

                            <!-- ✅ DIPERBAIKI: Tambah titik dua (:) dan gunakan <span> agar teks hitam -->
                            <ul class="list-group list-group-unbordered mb-3">
                                <li class="list-group-item">
                                    <b>Nama :</b> <span class="float-right"><?= htmlspecialchars($user['nama_lengkap']) ?></span>
                                </li>
                                <li class="list-group-item">
                                    <b>Tanggal Bergabung :</b> 
                                    <span class="float-right"><?= date('d/m/Y', strtotime($user['created_at'])) ?></span>
                                </li>
                            </ul>

                            <!-- Form Upload Foto -->
                            <form method="POST" enctype="multipart/form-data" class="text-center">
                                <input type="hidden" name="action" value="upload_foto">
                                <div class="mb-2">
                                    <input type="file" name="foto" accept="image/*" class="form-control form-control-sm" required>
                                </div>
                                <button type="submit" class="btn btn-outline-primary btn-sm">
                                    <i class="fas fa-upload me-1"></i> Upload Foto
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Form Edit -->
                <div class="col-md-8">
                    <!-- Edit Profil -->
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Edit Profil</h3>
                        </div>
                        <div class="card-body">
                            <form method="POST">
                                <input type="hidden" name="action" value="update_profil">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Nama Lengkap</label>
                                            <input type="text" name="nama_lengkap" class="form-control" 
                                                   value="<?= htmlspecialchars($user['nama_lengkap']) ?>" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Username</label>
                                            <input type="text" name="username" class="form-control" 
                                                   value="<?= htmlspecialchars($user['username']) ?>" required>
                                        </div>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-1"></i> Simpan Perubahan
                                </button>
                            </form>
                        </div>
                    </div>

                    <!-- Ganti Password -->
                    <div class="card mt-4">
                        <div class="card-header">
                            <h3 class="card-title">Ganti Password</h3>
                        </div>
                        <div class="card-body">
                            <form method="POST">
                                <input type="hidden" name="action" value="update_password">
                                <div class="mb-3">
                                    <label class="form-label">Password Lama</label>
                                    <input type="password" name="password_lama" class="form-control" required>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Password Baru</label>
                                            <input type="password" name="password_baru" class="form-control" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Konfirmasi Password</label>
                                            <input type="password" name="konfirmasi_password" class="form-control" required>
                                        </div>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-warning">
                                    <i class="fas fa-key me-1"></i> Ubah Password
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>