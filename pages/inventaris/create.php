<?php
require_once __DIR__ . '/../../includes/db_connect.php';
require_once __DIR__ . '/../../includes/auth_check.php';
require_once __DIR__ . '/../../includes/functions.php';

$db = getDB();
$kategori = $db->query("SELECT * FROM kategori ORDER BY nama")->fetchAll();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {
        $kode = trim($_POST['kode']);
        $nama = trim($_POST['nama']);
        $kategori_id = $_POST['kategori_id'] ?: null;
        $tanggal_perolehan = $_POST['tanggal_perolehan'] ?: null;
        $harga = $_POST['harga'] ?: 0;
        $deskripsi = trim($_POST['deskripsi']);
        $status = $_POST['status'];

        $stmt = $db->prepare("INSERT INTO aset (kode, nama, kategori_id, tanggal_perolehan, harga, deskripsi, status) 
                              VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$kode, $nama, $kategori_id, $tanggal_perolehan, $harga, $deskripsi, $status]);

        flash("Aset berhasil ditambahkan!", "success");
        redirect("pages/inventaris/");
    } catch (Exception $e) {
        flash("Error: " . $e->getMessage(), "danger");
    }
}
?>

<?php require_once __DIR__ . '/../../includes/header.php'; ?>
<?php require_once __DIR__ . '/../../includes/sidebar.php'; ?>

<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Tambah Aset Baru</h1>
                </div>
            </div>
        </div>
    </div>

    <div class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Form Tambah Aset</h3>
                </div>
                <div class="card-body">
                    <form method="POST">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Kode Aset *</label>
                                    <input type="text" name="kode" class="form-control" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Nama Aset *</label>
                                    <input type="text" name="nama" class="form-control" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Kategori</label>
                                    <select name="kategori_id" class="form-select">
                                        <option value="">Pilih kategori</option>
                                        <?php foreach ($kategori as $k): ?>
                                        <option value="<?= $k['id'] ?>"><?= htmlspecialchars($k['nama']) ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                <div class="mb-3">
                                    <label class="form-label">Lokasi</label>
                                    <input type="text" name="lokasi" class="form-control" placeholder="Contoh: Ruang Admin / Gudang / Lab 1" required>
                                </div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Tanggal Perolehan</label>
                                    <input type="date" name="tanggal_perolehan" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Harga (Rp)</label>
                                    <input type="number" name="harga" class="form-control" min="0" placeholder="0">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Status *</label>
                                    <select name="status" class="form-select" required>
                                        <option value="Aktif">Aktif</option>
                                        <option value="Rusak">Rusak</option>
                                        <option value="Kadaluarsa">Kadaluarsa</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Deskripsi</label>
                                    <textarea name="deskripsi" class="form-control" rows="4"></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-1"></i> Simpan Aset
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

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>