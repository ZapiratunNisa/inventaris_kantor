<?php
require_once __DIR__ . '/../../includes/db_connect.php';
require_once '../../includes/auth_check.php';
require_once '../../includes/functions.php';

if ($_SESSION['role'] !== 'admin') {
    redirect('pages/errors/403.php');
}

$db = getDB();
$id = $_GET['id'] ?? 0;

// Cegah edit diri sendiri
if ($id == $_SESSION['user_id']) {
    flash("Tidak bisa mengedit akun sendiri!", "warning");
    redirect("pages/users/");
}

$stmt = $db->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$id]);
$user = $stmt->fetch();

if (!$user) {
    redirect("pages/users/");
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {
        $username = trim($_POST['username']);
        $nama_lengkap = trim($_POST['nama_lengkap']);
        $role = $_POST['role'];
        $password = $_POST['password'] ?? null;

        // Validasi username unik (kecuali untuk user ini)
        $check = $db->prepare("SELECT id FROM users WHERE username = ? AND id != ?");
        $check->execute([$username, $id]);
        if ($check->fetch()) {
            throw new Exception("Username sudah digunakan!");
        }

        if ($password) {
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $db->prepare("UPDATE users SET username = ?, password = ?, nama_lengkap = ?, role = ? WHERE id = ?");
            $stmt->execute([$username, $hashedPassword, $nama_lengkap, $role, $id]);
        } else {
            $stmt = $db->prepare("UPDATE users SET username = ?, nama_lengkap = ?, role = ? WHERE id = ?");
            $stmt->execute([$username, $nama_lengkap, $role, $id]);
        }

        flash("Pengguna berhasil diupdate!", "success");
        redirect("pages/users/");
    } catch (Exception $e) {
        flash("Error: " . $e->getMessage(), "danger");
    }
}
?>

<?php require_once '../../includes/header.php'; ?>
<?php require_once '../../includes/sidebar.php'; ?>

<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Edit Pengguna</h1>
                </div>
            </div>
        </div>
    </div>

    <div class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Form Edit Pengguna</h3>
                </div>
                <div class="card-body">
                    <form method="POST">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Username *</label>
                                    <input type="text" name="username" class="form-control" value="<?= htmlspecialchars($user['username']) ?>" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Password Baru (Kosongkan jika tidak diubah)</label>
                                    <input type="password" name="password" class="form-control" minlength="6">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Nama Lengkap *</label>
                                    <input type="text" name="nama_lengkap" class="form-control" value="<?= htmlspecialchars($user['nama_lengkap']) ?>" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Role *</label>
                                    <select name="role" class="form-select" required>
                                        <option value="staff" <?= $user['role'] == 'staff' ? 'selected' : '' ?>>Staff</option>
                                        <option value="admin" <?= $user['role'] == 'admin' ? 'selected' : '' ?>>Admin</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-1"></i> Update Pengguna
                            </button>
                            <a href="index.php" class="btn btn-secondary">
                                <i class="fas fa-arrow-left me-1"></i> Kembali
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once '../../includes/footer.php'; ?>