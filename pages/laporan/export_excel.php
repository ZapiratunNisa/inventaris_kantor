<?php
require_once __DIR__ . '/../../includes/db_connect.php';
require_once __DIR__ . '/../../includes/auth_check.php';

$db = getDB();
$stmt = $db->query("SELECT a.kode, a.nama, k.nama as kategori, a.status, a.harga 
                    FROM aset a 
                    LEFT JOIN kategori k ON a.kategori_id = k.id 
                    ORDER BY a.kode ASC");
$laporan = $stmt->fetchAll();

$totalHarga = $db->query("SELECT SUM(harga) FROM aset")->fetchColumn();

// Set header agar diunduh sebagai file Excel
header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=laporan_inventaris_" . date('Y-m-d') . ".xls");
header("Pragma: no-cache");
header("Expires: 0");

echo '<table border="1" cellspacing="0" cellpadding="5">';
echo '<tr>
        <th>No</th>
        <th>Kode Aset</th>
        <th>Nama Aset</th>
        <th>Kategori</th>
        <th>Status</th>
        <th>Harga (Rp)</th>
      </tr>';

foreach ($laporan as $index => $row) {
    echo '<tr>';
    echo '<td align="center">' . ($index + 1) . '</td>';
    echo '<td>' . htmlspecialchars($row['kode']) . '</td>';
    echo '<td>' . htmlspecialchars($row['nama']) . '</td>';
    echo '<td>' . htmlspecialchars($row['kategori'] ?? '-') . '</td>';
    echo '<td>' . htmlspecialchars($row['status']) . '</td>';
    echo '<td align="right">' . number_format($row['harga'], 0, ',', '.') . '</td>';
    echo '</tr>';
}

echo '<tr>';
echo '<td colspan="5" align="right"><b>Total Nilai Aset:</b></td>';
echo '<td align="right"><b>' . number_format($totalHarga, 0, ',', '.') . '</b></td>';
echo '</tr>';

echo '</table>';
exit();
?>