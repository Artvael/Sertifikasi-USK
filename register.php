<?php
require 'koneksi.php';

// Jika tombol daftar diklik
if (isset($_POST['register'])) {
    $nama     = $_POST['nama_peserta'];
    $user     = $_POST['username'];
    $pass     = $_POST['password'];
    $email    = $_POST['email'];
    $telepon  = $_POST['telepon'];
    $id_skema = $_POST['id_skema'];

    // 1. Cek apakah username sudah pernah terdaftar
    $cek = $koneksi->prepare("SELECT * FROM peserta WHERE username = ?");
    $cek->execute([$user]);

    if ($cek->rowCount() > 0) {
        $error = "Username sudah dipakai, pilih username lain!";
    } else {
        // 2. Simpan data user baru ke tabel peserta
        $stmt = $koneksi->prepare("INSERT INTO peserta (nama_peserta, username, password, email, telepon, id_skema) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$nama, $user, $pass, $email, $telepon, $id_skema]);

        // Arahkan ke halaman login dengan pesan sukses
        header("Location: login.php?pesan=daftar_sukses");
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Registrasi Akun Peserta</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light py-5">
    <div class="container" style="max-width: 450px;">
        <div class="card p-4 shadow-sm">
            <h3 class="text-center fw-bold mb-3">Registrasi Peserta</h3>

            <?php if (isset($error)): ?>
                <div class="alert alert-danger"><?= $error ?></div>
            <?php endif; ?>

            <form method="POST">
                <div class="mb-2">
                    <label class="form-label">Nama Lengkap</label>
                    <input type="text" name="nama_peserta" class="form-control" required>
                </div>
                <div class="mb-2">
                    <label class="form-label">Username</label>
                    <input type="text" name="username" class="form-control" required>
                </div>
                <div class="mb-2">
                    <label class="form-label">Password</label>
                    <input type="password" name="password" class="form-control" required>
                </div>
                <div class="mb-2">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" required>
                </div>
                <div class="mb-2">
                    <label class="form-label">No. Telepon / WA</label>
                    <input type="text" name="telepon" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Pilih Skema Sertifikasi</label>
                    <select name="id_skema" class="form-select" required>
                        <option value="">-- Pilih Skema --</option>
                        <?php 
                        $skema = $koneksi->query("SELECT * FROM skema");
                        while ($s = $skema->fetch()) {
                            echo "<option value='{$s['id_skema']}'>{$s['nama_skema']}</option>";
                        }
                        ?>
                    </select>
                </div>
                
                <button type="submit" name="register" class="btn btn-primary w-100">Daftar Sekarang</button>
            </form>

            <p class="text-center text-muted mt-3 mb-0">
                Sudah punya akun? <a href="login.php">Login di sini</a>
            </p>
        </div>
    </div>
</body>
</html>

