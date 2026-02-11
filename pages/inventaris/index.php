<?php
require_once __DIR__ . '/../../includes/db_connect.php';
require_once __DIR__ . '/../../includes/auth_check.php';
require_once __DIR__ . '/../../includes/header.php';
require_once __DIR__ . '/../../includes/sidebar.php';
require_once __DIR__ . '/../../includes/functions.php';

$db = getDB();

/* =========================
   ✅ FITUR SEARCH + LOKASI
========================= */
$search = $_GET['search'] ?? '';

if ($search) {
    $stmt = $db->prepare("
        SELECT a.*, k.nama as kategori_nama 
        FROM aset a
        LEFT JOIN kategori k ON a.kategori_id = k.id
        WHERE 
            a.kode LIKE :search 
            OR a.nama LIKE :search
            OR a.lokasi LIKE :search
        ORDER BY a.kode ASC
    ");

    $stmt->execute([
        'search' => "%$search%"
    ]);
} else {
    $stmt = $db->query("
        SELECT a.*, k.nama as kategori_nama 
        FROM aset a
        LEFT JOIN kategori k ON a.kategori_id = k.id
        ORDER BY a.kode ASC
    ");
}

$inventaris = $stmt->fetchAll();
?>

<div class="content-wrapper">

    <div class="content-header">
        <div class="container-fluid">
            <h1 class="m-0">Manajemen Inventaris</h1>
        </div>
    </div>

    <div class="content">
        <div class="container-fluid">

            <?= showFlash(); ?>

            <div class="card">

                <!-- HEADER -->
                <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">

                    <h3 class="card-title mb-0">Daftar Inventaris</h3>

                    <div class="d-flex gap-2">

                        <!-- 🔍 SEARCH -->
                        <form method="GET" class="d-flex">
                            <input type="text"
                                   name="search"
                                   value="<?= htmlspecialchars($search) ?>"
                                   class="form-control form-control-sm me-2"
                                   placeholder="Cari kode / nama / lokasi">

                            <button class="btn btn-sm btn-secondary">
                                <i class="fas fa-search"></i>
                            </button>
                        </form>

                        <?php if ($_SESSION['role'] === 'admin'): ?>
                            <a href="create.php" class="btn btn-primary btn-sm">
                                <i class="fas fa-plus me-1"></i> Tambah Aset Baru
                            </a>
                        <?php endif; ?>

                    </div>
                </div>


                <div class="card-body">
                    <div class="table-responsive">

                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Kode</th>
                                    <th>Nama Aset</th>
                                    <th>Kategori</th>

                                    <!-- ✅ KOLOM BARU -->
                                    <th>Lokasi</th>

                                    <th>Tanggal Perolehan</th>
                                    <th>Harga</th>
                                    <th>Status</th>

                                    <?php if ($_SESSION['role'] === 'admin'): ?>
                                        <th>Aksi</th>
                                    <?php endif; ?>
                                </tr>
                            </thead>

                            <tbody>

                                <?php if (!$inventaris): ?>
                                    <tr>
                                        <td colspan="8" class="text-center text-muted">
                                            Data tidak ditemukan
                                        </td>
                                    </tr>
                                <?php endif; ?>

                                <?php foreach ($inventaris as $item): ?>
                                <tr>
                                    <td><?= htmlspecialchars($item['kode']) ?></td>
                                    <td><?= htmlspecialchars($item['nama']) ?></td>
                                    <td><?= htmlspecialchars($item['kategori_nama'] ?? '-') ?></td>

                                    <!-- ✅ TAMPIL LOKASI -->
                                    <td><?= htmlspecialchars($item['lokasi'] ?? '-') ?></td>

                                    <td><?= $item['tanggal_perolehan'] ? date('d/m/Y', strtotime($item['tanggal_perolehan'])) : '-' ?></td>
                                    <td><?= rupiah($item['harga']) ?></td>
                                    <td><?= getAsetStatusBadge($item['status']) ?></td>

                                    <?php if ($_SESSION['role'] === 'admin'): ?>
                                    <td>
                                        <a href="edit.php?id=<?= $item['id'] ?>" class="btn btn-sm btn-warning">
                                            <i class="fas fa-edit"></i>
                                        </a>

                                        <a href="delete.php?id=<?= $item['id'] ?>"
                                           class="btn btn-sm btn-danger"
                                           onclick="return confirm('Yakin ingin menghapus?')">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    </td>
                                    <?php endif; ?>
                                </tr>
                                <?php endforeach; ?>

                            </tbody>

                        </table>

                    </div>
                </div>

            </div>

        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>
