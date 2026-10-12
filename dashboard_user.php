<?php
require 'koneksi.php';

// Proteksi halaman: Pastikan hanya User (Peserta) yang bisa masuk
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

$user = $_SESSION['user'];

// Ambil informasi skema yang diambil oleh user ini
$stmt = $koneksi->prepare("SELECT s.kode_skema, s.nama_skema FROM peserta p JOIN skema s ON p.id_skema = s.id_skema WHERE p.id_peserta = ?");
$stmt->execute([$user['id_peserta']]);
$my_skema = $stmt->fetch();

// Ambil seluruh daftar skema yang tersedia di lembaga untuk informasi user
$daftar_skema = $koneksi->query("SELECT * FROM skema");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Peserta</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light p-4">
    <div class="container" style="max-width: 800px;">
        
        <!-- Bar Atas: Sambutan & Logout -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h3 class="fw-bold mb-0">Halo, <?= $user['nama_peserta'] ?>! 👋</h3>
                <small class="text-muted">Selamat datang di Panel Peserta Sertifikasi</small>
            </div>
            <a href="logout.php" class="btn btn-outline-danger">Logout</a>
        </div>

        <div class="row g-4">
            <!-- Kartu 1: Data Diri & Status Pendaftaran -->
            <div class="col-md-6">
                <div class="card p-3 shadow-sm h-100">
                    <h5 class="fw-bold text-primary mb-3">📋 Status Pendaftaran Saya</h5>
                    <ul class="list-unstyled mb-3">
                        <li class="mb-2"><strong>Nama:</strong> <?= $user['nama_peserta'] ?></li>
                        <li class="mb-2"><strong>Email:</strong> <?= $user['email'] ?></li>
                        <li class="mb-2"><strong>No. HP:</strong> <?= $user['telepon'] ?></li>
                        <li class="mb-2"><strong>Skema Dipilih:</strong> <?= $my_skema ? $my_skema['nama_skema'] : 'Belum memilih' ?></li>
                    </ul>
                    <div>
                        <span class="badge bg-success p-2">Status: Terdaftar (Menunggu Asesmen)</span>
                    </div>
                </div>
            </div>

            <!-- Kartu 2: Informasi Skema yang Tersedia -->
            <div class="col-md-6">
                <div class="card p-3 shadow-sm h-100">
                    <h5 class="fw-bold text-primary mb-3">📚 Skema Sertifikasi Tersedia</h5>
                    <table class="table table-bordered table-sm">
                        <thead class="table-light">
                            <tr>
                                <th>Kode</th>
                                <th>Nama Skema</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($s = $daftar_skema->fetch()): ?>
                                <tr>
                                    <td><?= $s['kode_skema'] ?></td>
                                    <td><?= $s['nama_skema'] ?></td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
</body>
</html>

