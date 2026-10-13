<?php
require 'koneksi.php';

// Jika tombol daftar diklik
if (isset($_POST['register'])) {
    $nama     = trim($_POST['nama_peserta']);
    $user     = trim($_POST['username']);
    $pass     = $_POST['password'];
    $email    = trim($_POST['email']);
    $telepon  = trim($_POST['telepon']);
    $id_skema = $_POST['id_skema'];

    // 1. Validasi Nama: Hanya boleh huruf dan spasi (tidak boleh angka)
    if (!preg_match("/^[a-zA-Z\s]+$/", $nama)) {
        $error = "Nama hanya boleh berupa huruf dan spasi, tidak boleh mengandung angka!";
    // 2. Validasi Nomor Telepon: Harus angka, minimal 10 digit dan maksimal 15 digit
    } elseif (!preg_match("/^[0-9]{10,15}$/", $telepon)) {
        $error = "Nomor telepon harus berupa angka antara 10 hingga 15 digit!";
    } else {
        // 3. Cek apakah username sudah pernah terdaftar
        $cek = $koneksi->prepare("SELECT * FROM peserta WHERE username = ?");
        $cek->execute([$user]);

        if ($cek->rowCount() > 0) {
            $error = "Username sudah dipakai, pilih username lain!";
        } else {
            // 4. Simpan data user baru ke tabel peserta
            $stmt = $koneksi->prepare("INSERT INTO peserta (nama_peserta, username, password, email, telepon, id_skema) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->execute([$nama, $user, $pass, $email, $telepon, $id_skema]);

            // Arahkan ke halaman login dengan pesan sukses
            header("Location: login.php?pesan=daftar_sukses");
            exit;
        }
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
                    <label class="form-label">Nama Lengkap <small class="text-muted">(Hanya Huruf)</small></label>
                    <input type="text" name="nama_peserta" class="form-control" required
                           pattern="[a-zA-Z\s]+"
                           title="Nama hanya boleh berupa huruf dan spasi, tidak boleh mengandung angka"
                           oninput="this.value = this.value.replace(/[^a-zA-Z\s]/g, '')">
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
                    <label class="form-label">No. Telepon / WA <small class="text-muted">(Max 15 digit)</small></label>
                    <input type="tel" name="telepon" class="form-control" required
                           maxlength="15" minlength="10"
                           pattern="[0-9]{10,15}"
                           title="Nomor telepon harus berupa angka antara 10 hingga 15 digit"
                           oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 15)">
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
            <div class="text-center mt-2">
                <a href="index.php" class="text-decoration-none small text-secondary">← Kembali ke Beranda</a>
            </div>
        </div>
    </div>
</body>
</html>

