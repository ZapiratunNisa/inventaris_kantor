CREATE DATABASE IF NOT EXISTS inventaris_kantor CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE inventaris_kantor;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    nama_lengkap VARCHAR(100) NOT NULL,
    role ENUM('admin', 'staff') DEFAULT 'staff',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Buat tabel kategori
CREATE TABLE kategori (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(100) NOT NULL,
    deskripsi TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Buat tabel aset
CREATE TABLE aset (
    id INT AUTO_INCREMENT PRIMARY KEY,
    kode VARCHAR(50) UNIQUE NOT NULL,
    nama VARCHAR(255) NOT NULL,
    kategori_id INT NULL,
    tanggal_perolehan DATE NULL,
    harga DECIMAL(15,2) DEFAULT 0,
    deskripsi TEXT,
    status ENUM('Aktif', 'Rusak', 'Kadaluarsa') DEFAULT 'Aktif',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (kategori_id) REFERENCES kategori(id) ON DELETE SET NULL
) ENGINE=InnoDB;

-- Data contoh users
INSERT INTO users (username, password, nama_lengkap, role) VALUES
('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Administrator', 'admin'),
('staff', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Staff Inventaris', 'staff');

-- Data contoh kategori
INSERT INTO kategori (nama, deskripsi) VALUES
('Elektronik', 'Perangkat elektronik kantor'),
('Furnitur', 'Perabot kantor'),
('Alat Tulis', 'Perlengkapan kantor');

-- Data contoh aset
INSERT INTO aset (kode, nama, kategori_id, tanggal_perolehan, harga, status) VALUES
('AS-001', 'Komputer Desktop', 1, '2025-01-15', 8500000, 'Aktif'),
('AS-002', 'Meja Kerja', 2, '2025-02-20', 1200000, 'Aktif'),
('AS-003', 'Printer Laser', 1, '2025-03-10', 2300000, 'Rusak');