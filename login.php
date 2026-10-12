<?php
require 'koneksi.php';

// Jika sudah login, langsung arahkan ke dashboard masing-masing
if (isset($_SESSION['admin'])) {
    header("Location: dashboard.php");
    exit;
}
if (isset($_SESSION['user'])) {
    header("Location: dashboard_user.php");
    exit;
}

// Proses form login saat tombol diklik
if (isset($_POST['login'])) {
    $user = $_POST['username'];
    $pass = $_POST['password'];

    // 1. Cek apakah akun Admin
    $stmt = $koneksi->prepare("SELECT * FROM admin WHERE username = ? AND password = ?");
    $stmt->execute([$user, $pass]);
    $admin = $stmt->fetch();

    if ($admin) {
        $_SESSION['admin'] = $admin['username'];
        header("Location: dashboard.php");
        exit;
    }

    // 2. Jika bukan admin, cek apakah akun Peserta (User)
    $stmt = $koneksi->prepare("SELECT * FROM peserta WHERE username = ? AND password = ?");
    $stmt->execute([$user, $pass]);
    $peserta = $stmt->fetch();

    if ($peserta) {
        $_SESSION['user'] = $peserta;
        header("Location: dashboard_user.php");
        exit;
    }

    $error = "Username atau Password salah!";
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login Sistem</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light d-flex align-items-center justify-content-center vh-100">
    <div class="container" style="max-width: 400px;">
        <div class="card p-4 shadow-sm">
            <h3 class="text-center fw-bold mb-3">Login Sistem</h3>

            <?php if (isset($_GET['pesan']) && $_GET['pesan'] == 'daftar_sukses'): ?>
                <div class="alert alert-success">Pendaftaran berhasil! Silakan login.</div>
            <?php endif; ?>

            <?php if (isset($error)): ?>
                <div class="alert alert-danger"><?= $error ?></div>
            <?php endif; ?>
            
            <form method="POST">
                <div class="mb-3">
                    <label class="form-label">Username</label>
                    <input type="text" name="username" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Password</label>
                    <input type="password" name="password" class="form-control" required>
                </div>
                <button type="submit" name="login" class="btn btn-primary w-100">Login</button>
            </form>

            <p class="text-center text-muted mt-3 mb-0">
                Belum punya akun? <a href="register.php">Daftar Akun</a>
            </p>
        </div>
    </div>
</body>
</html>