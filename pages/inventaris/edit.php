<?php
require_once __DIR__ . '/../../includes/db_connect.php';
require_once __DIR__ . '/../../includes/auth_check.php';
require_once __DIR__ . '/../../includes/functions.php';

$db = getDB();

$id = $_GET['id'] ?? 0;
$stmt = $db->prepare("SELECT * FROM aset WHERE id = ?");
$stmt->execute([$id]);
$item = $stmt->fetch();

if (!$item) {
    redirect("pages/inventaris/");
}

$kategori = $db->query("SELECT * FROM kategori ORDER BY nama")->fetchAll();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {
        $kode = trim($_POST['kode']);
        $nama = trim($_POST['nama']);
        $kategori_id = $_POST['kategori_id'] ?: null;

        // ✅ TAMBAHAN LOKASI
        $lokasi = trim($_POST['lokasi']);

        $tanggal_perolehan = $_POST['tanggal_perolehan'] ?: null;
        $harga = $_POST['harga'] ?: 0;
        $deskripsi = trim($_POST['deskripsi']);
        $status = $_POST['status'];

        // ✅ UPDATE QUERY SUDAH ADA LOKASI
        $stmt = $db->prepare("UPDATE aset SET 
            kode = ?, 
            nama = ?, 
            kategori_id = ?, 
            lokasi = ?, 
            tanggal_perolehan = ?, 
            harga = ?, 
            deskripsi = ?, 
            status = ? 
            WHERE id = ?");

        $stmt->execute([
            $kode,
            $nama,
            $kategori_id,
            $lokasi,
            $tanggal_perolehan,
            $harga,
            $deskripsi,
            $status,
            $id
        ]);

        flash("Aset berhasil diupdate!", "success");
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
            <h1 class="m-0">Edit Aset</h1>
        </div>
    </div>

    <div class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Form Edit Aset</h3>
                </div>

                <div class="card-body">
                    <form method="POST">
                        <div class="row">

                            <!-- KIRI -->
                            <div class="col-md-6">

                                <div class="mb-3">
                                    <label class="form-label">Kode Aset *</label>
                                    <input type="text" name="kode" class="form-control"
                                        value="<?= htmlspecialchars($item['kode']) ?>" required>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Nama Aset *</label>
                                    <input type="text" name="nama" class="form-control"
                                        value="<?= htmlspecialchars($item['nama']) ?>" required>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Kategori</label>
                                    <select name="kategori_id" class="form-select">
                                        <option value="">Pilih kategori</option>
                                        <?php foreach ($kategori as $k): ?>
                                            <option value="<?= $k['id'] ?>"
                                                <?= $k['id'] == $item['kategori_id'] ? 'selected' : '' ?>>
                                                <?= htmlspecialchars($k['nama']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>

                                <!-- ✅ INPUT LOKASI BARU -->
                                <div class="mb-3">
                                    <label class="form-label">Lokasi</label>
                                    <input type="text" name="lokasi" class="form-control"
                                        value="<?= htmlspecialchars($item['lokasi']) ?>"
                                        placeholder="Contoh: Ruang Lab 1 / Gudang / Kantor">
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Tanggal Perolehan</label>
                                    <input type="date" name="tanggal_perolehan" class="form-control"
                                        value="<?= $item['tanggal_perolehan'] ? date('Y-m-d', strtotime($item['tanggal_perolehan'])) : '' ?>">
                                </div>
                            </div>

                            <!-- KANAN -->
                            <div class="col-md-6">

                                <div class="mb-3">
                                    <label class="form-label">Harga (Rp)</label>
                                    <input type="number" name="harga" class="form-control"
                                        value="<?= $item['harga'] ?>">
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Status *</label>
                                    <select name="status" class="form-select" required>
                                        <option value="Aktif" <?= $item['status'] == 'Aktif' ? 'selected' : '' ?>>Aktif</option>
                                        <option value="Rusak" <?= $item['status'] == 'Rusak' ? 'selected' : '' ?>>Rusak</option>
                                        <option value="Kadaluarsa" <?= $item['status'] == 'Kadaluarsa' ? 'selected' : '' ?>>Kadaluarsa</option>
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Deskripsi</label>
                                    <textarea name="deskripsi" class="form-control"
                                        rows="4"><?= htmlspecialchars($item['deskripsi']) ?></textarea>
                                </div>

                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary">
                            Update Aset
                        </button>
                        <a href="index.php" class="btn btn-secondary">Kembali</a>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>
