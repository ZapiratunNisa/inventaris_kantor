<?php
require_once __DIR__ . '/../../includes/db_connect.php';
require_once __DIR__ . '/../../includes/auth_check.php';
require_once __DIR__ . '/../../includes/header.php';
require_once __DIR__ . '/../../includes/sidebar.php';
require_once __DIR__ . '/../../includes/functions.php';

$db = getDB();

$totalAset       = $db->query("SELECT COUNT(*) FROM aset")->fetchColumn();
$asetAktif      = $db->query("SELECT COUNT(*) FROM aset WHERE status = 'Aktif'")->fetchColumn();
$asetRusak      = $db->query("SELECT COUNT(*) FROM aset WHERE status = 'Rusak'")->fetchColumn();
$asetKadaluarsa = $db->query("SELECT COUNT(*) FROM aset WHERE status = 'Kadaluarsa'")->fetchColumn();

/* ✅ ambil nama user yang benar */
$namaUser = $_SESSION['nama_lengkap'] ?? $_SESSION['nama'] ?? $_SESSION['username'] ?? 'User';
?>

<style>
/* efek hover biar modern */
.card-hover:hover{
    transform: translateY(-4px);
    transition: 0.2s;
}
</style>

<div class="content-wrapper">

    <!-- Header -->
    <div class="content-header">
        <div class="container-fluid">
            <h3 class="mb-1">
                Selamat Datang, <b><?= htmlspecialchars($namaUser) ?></b> 👋
            </h3>
            <p class="text-muted mb-0">Ringkasan statistik inventaris aset</p>
        </div>
    </div>


    <div class="content">
        <div class="container-fluid">

            <!-- ===================== -->
            <!-- Statistik Card Warna -->
            <!-- ===================== -->
            <div class="row mb-4">

                <!-- Total -->
                <div class="col-md-3">
                    <div class="card card-hover text-white bg-primary shadow-sm">
                        <div class="card-body text-center">
                            <i class="fas fa-boxes fa-2x mb-2"></i>
                            <h3><?= $totalAset ?></h3>
                            <p class="mb-0">Total Aset</p>
                        </div>
                    </div>
                </div>

                <!-- Aktif -->
                <div class="col-md-3">
                    <div class="card card-hover text-white bg-success shadow-sm">
                        <div class="card-body text-center">
                            <i class="fas fa-check-circle fa-2x mb-2"></i>
                            <h3><?= $asetAktif ?></h3>
                            <p class="mb-0">Aktif</p>
                        </div>
                    </div>
                </div>

                <!-- Rusak -->
                <div class="col-md-3">
                    <div class="card card-hover text-white bg-danger shadow-sm">
                        <div class="card-body text-center">
                            <i class="fas fa-times-circle fa-2x mb-2"></i>
                            <h3><?= $asetRusak ?></h3>
                            <p class="mb-0">Rusak</p>
                        </div>
                    </div>
                </div>

                <!-- Kadaluarsa -->
                <div class="col-md-3">
                    <div class="card card-hover bg-warning text-dark shadow-sm">
                        <div class="card-body text-center">
                            <i class="fas fa-clock fa-2x mb-2"></i>
                            <h3><?= $asetKadaluarsa ?></h3>
                            <p class="mb-0">Kadaluarsa</p>
                        </div>
                    </div>
                </div>

            </div>


            <!-- ===================== -->
            <!-- Bar Chart -->
            <!-- ===================== -->
            <div class="row">
                <div class="col-md-12">
                    <div class="card shadow-sm">
                        <div class="card-header">
                            <b>Grafik Status Aset</b>
                        </div>
                        <div class="card-body">
                            <canvas id="barChart" height="110"></canvas>
                        </div>
                    </div>
                </div>
            </div>


        </div>
    </div>
</div>


<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
new Chart(document.getElementById('barChart'), {
    type: 'bar',
    data: {
        labels: ['Aktif', 'Rusak', 'Kadaluarsa'],
        datasets: [{
            label: 'Jumlah Aset',
            data: [
                <?= $asetAktif ?>,
                <?= $asetRusak ?>,
                <?= $asetKadaluarsa ?>
            ],
            backgroundColor: [
                '#28a745',
                '#dc3545',
                '#ffc107'
            ]
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: { display: false }
        },
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    precision: 0
                }
            }
        }
    }
});
</script>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>
