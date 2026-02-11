<aside class="main-sidebar">
    <a href="<?= BASE_URL ?>" class="sidebar-brand">
        <i class="fas fa-boxes me-2"></i>
        <span>Inventaris Kantor</span>
    </a>

    <?php $role = $_SESSION['role'] ?? 'staff'; ?>

    <div class="sidebar" style="padding: 15px;">

        <!-- User Info -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="info">
                <span class="d-block">
                    <?= $_SESSION['nama_lengkap'] ?? 'Pengguna' ?>
                </span>
                <small class="text-muted">
                    <?= ucfirst($role) ?>
                </small>
            </div>
        </div>

        <nav class="mt-3">
            <ul class="nav nav-pills nav-sidebar flex-column" role="menu">

                <!-- Dashboard (semua role) -->
                <li class="nav-item">
                    <a href="<?= BASE_URL ?>pages/dashboard/index.php"
                       class="nav-link <?= strpos($_SERVER['REQUEST_URI'], 'dashboard') !== false ? 'active' : '' ?>">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>Dashboard</p>
                    </a>
                </li>

                <!-- Inventaris (semua role) -->
                <li class="nav-item">
                    <a href="<?= BASE_URL ?>pages/inventaris/"
                       class="nav-link <?= strpos($_SERVER['REQUEST_URI'], 'inventaris') !== false ? 'active' : '' ?>">
                        <i class="nav-icon fas fa-cube"></i>
                        <p>Inventaris</p>
                    </a>
                </li>

                <!-- 🔒 ADMIN ONLY -->
                <?php if ($role === 'admin'): ?>

                    <li class="nav-item">
                        <a href="<?= BASE_URL ?>pages/kategori/"
                           class="nav-link <?= strpos($_SERVER['REQUEST_URI'], 'kategori') !== false ? 'active' : '' ?>">
                            <i class="nav-icon fas fa-tags"></i>
                            <p>Kategori</p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="<?= BASE_URL ?>pages/laporan/"
                           class="nav-link <?= strpos($_SERVER['REQUEST_URI'], 'laporan') !== false ? 'active' : '' ?>">
                            <i class="nav-icon fas fa-file-alt"></i>
                            <p>Laporan</p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="<?= BASE_URL ?>pages/users/"
                           class="nav-link <?= strpos($_SERVER['REQUEST_URI'], 'users') !== false ? 'active' : '' ?>">
                            <i class="nav-icon fas fa-users"></i>
                            <p>Manajemen Pengguna</p>
                        </a>
                    </li>

                <?php endif; ?>

                <!-- Profil (semua role) -->
                <li class="nav-item">
                    <a href="<?= BASE_URL ?>pages/profil/"
                       class="nav-link <?= strpos($_SERVER['REQUEST_URI'], 'profil') !== false ? 'active' : '' ?>">
                        <i class="nav-icon fas fa-user-circle"></i>
                        <p>Profil</p>
                    </a>
                </li>

            </ul>
        </nav>
    </div>
</aside>
