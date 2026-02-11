<?php
require_once __DIR__ . '/../../includes/db_connect.php';
require_once '../../includes/auth_check.php';
require_once '../../includes/functions.php';

$db = getDB();

$id = $_GET['id'] ?? 0;
$stmt = $db->prepare("SELECT * FROM kategori WHERE id = ?");
$stmt->execute([$id]);
$item = $stmt->fetch();

if (!$item) {
    redirect("pages/kategori/");
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {
        $nama = trim($_POST['nama']);
        $deskripsi = trim($_POST['deskripsi']);

        $stmt = $db->prepare("UPDATE kategori SET nama = ?, deskripsi = ? WHERE id = ?");
        $stmt->execute([$nama, $deskripsi, $id]);

        flash("Kategori berhasil diupdate!", "success");
        redirect("pages/kategori/");
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
                    <h1 class="m-0">Edit Kategori</h1>
                </div>
            </div>
        </div>
    </div>

    <div class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Form Edit Kategori</h3>
                </div>
                <div class="card-body">
                    <form method="POST">
                        <div class="mb-3">
                            <label class="form-label">Nama Kategori *</label>
                            <input type="text" name="nama" class="form-control" value="<?= htmlspecialchars($item['nama']) ?>" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Deskripsi</label>
                            <textarea name="deskripsi" class="form-control" rows="3"><?= htmlspecialchars($item['deskripsi']) ?></textarea>
                        </div>
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-1"></i> Update Kategori
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