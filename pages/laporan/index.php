<?php
require_once __DIR__ . '/../../includes/db_connect.php';
require_once '../../includes/auth_check.php';
require_once '../../includes/header.php';
require_once '../../includes/sidebar.php';
require_once '../../includes/functions.php';

$db = getDB();
$stmt = $db->query("SELECT a.*, k.nama as kategori_nama FROM aset a 
                    LEFT JOIN kategori k ON a.kategori_id = k.id 
                    ORDER BY a.kode ASC");
$laporan = $stmt->fetchAll();

$totalHarga = $db->query("SELECT SUM(harga) FROM aset")->fetchColumn();
?>

<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Laporan Inventaris</h1>
                </div>
            </div>
        </div>
    </div>

    <div class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title mb-0">Laporan Aset</h3>
                    <div>
                        <!-- ✅ Hanya tombol Excel yang ditampilkan -->
                        <a href="export_excel.php" class="btn btn-success btn-sm">
                            <i class="fas fa-file-excel me-1"></i> Excel
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead class="table-light">
                                <tr>
                                    <th>No</th>
                                    <th>Kode Aset</th>
                                    <th>Nama Aset</th>
                                    <th>Kategori</th>
                                    <th>Status</th>
                                    <th>Harga</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($laporan as $index => $item): ?>
                                <tr>
                                    <td><?= $index + 1 ?></td>
                                    <td><?= htmlspecialchars($item['kode']) ?></td>
                                    <td><?= htmlspecialchars($item['nama']) ?></td>
                                    <td><?= htmlspecialchars($item['kategori_nama'] ?? '-') ?></td>
                                    <td><?= getAsetStatusBadge($item['status']) ?></td>
                                    <td><?= rupiah($item['harga']) ?></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="5" class="text-end">Total Nilai Aset:</th>
                                    <th><?= rupiah($totalHarga) ?></th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once '../../includes/footer.php'; ?>