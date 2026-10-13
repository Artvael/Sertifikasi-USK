<?php
require 'koneksi.php';

// Proteksi halaman: Pastikan hanya User (Peserta) yang bisa masuk
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

$user = $_SESSION['user'];

// Logika Edit Nama oleh User (Hanya nama yang boleh diedit)
if (isset($_POST['update_nama'])) {
    $nama_baru = trim($_POST['nama_peserta']);

    // Validasi: nama hanya boleh huruf dan spasi, tidak boleh angka
    if (!preg_match("/^[a-zA-Z\s]+$/", $nama_baru)) {
        $error = "Nama hanya boleh berupa huruf dan spasi, tidak boleh angka!";
    } else {
        $stmt_update = $koneksi->prepare("UPDATE peserta SET nama_peserta = ? WHERE id_peserta = ?");
        $stmt_update->execute([$nama_baru, $user['id_peserta']]);

        // Perbarui sesi dan variabel user
        $_SESSION['user']['nama_peserta'] = $nama_baru;
        $user['nama_peserta'] = $nama_baru;
        $pesan_sukses = "Nama berhasil diperbarui!";
    }
}

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
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="fw-bold text-primary mb-0">📋 Status Pendaftaran Saya</h5>
                        <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#modalEditNama">
                            ✏️ Edit Nama
                        </button>
                    </div>

                    <?php if (isset($pesan_sukses)): ?>
                        <div class="alert alert-success py-2 mb-3"><?= $pesan_sukses ?></div>
                    <?php endif; ?>
                    <?php if (isset($error)): ?>
                        <div class="alert alert-danger py-2 mb-3"><?= $error ?></div>
                    <?php endif; ?>

                    <ul class="list-unstyled mb-3">
                        <li class="mb-2"><strong>Nama:</strong> <?= htmlspecialchars($user['nama_peserta']) ?></li>
                        <li class="mb-2"><strong>Email:</strong> <?= htmlspecialchars($user['email']) ?></li>
                        <li class="mb-2"><strong>No. HP:</strong> <?= htmlspecialchars($user['telepon']) ?></li>
                        <li class="mb-2"><strong>Skema Dipilih:</strong> <?= $my_skema ? htmlspecialchars($my_skema['nama_skema']) : 'Belum memilih' ?></li>
                    </ul>
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="badge bg-success p-2">Status: Terdaftar (Menunggu Asesmen)</span>
                        <small class="text-muted fst-italic" style="font-size: 0.75rem;">*Hanya nama yang dapat diubah</small>
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

    <!-- Modal Ubah Nama Peserta -->
    <div class="modal fade" id="modalEditNama" tabindex="-1" aria-labelledby="modalEditNamaLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form method="POST">
                    <div class="modal-header">
                        <h5 class="modal-title fw-bold" id="modalEditNamaLabel">Ubah Nama Lengkap</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Nama Lengkap Baru</label>
                            <input type="text" name="nama_peserta" class="form-control" 
                                   value="<?= htmlspecialchars($user['nama_peserta']) ?>" 
                                   required
                                   pattern="[a-zA-Z\s]+"
                                   title="Nama hanya boleh berupa huruf dan spasi"
                                   oninput="this.value = this.value.replace(/[^a-zA-Z\s]/g, '')">
                            <div class="form-text text-muted">Hanya boleh diisi huruf dan spasi (tidak boleh mengandung angka).</div>
                        </div>
                        <div class="alert alert-info py-2 small mb-0">
                            <strong>Informasi:</strong> Peserta hanya berhak memperbarui nama lengkap. Untuk perubahan email, nomor telepon, atau skema sertifikasi, silakan hubungi administrator.
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" name="update_nama" class="btn btn-primary">Simpan Nama</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

