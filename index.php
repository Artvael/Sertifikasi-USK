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
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Selamat Datang - Sistem Sertifikasi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light d-flex align-items-center justify-content-center vh-100">

    <!-- Kontainer di tengah tanpa box/card -->
    <div class="container text-center" style="max-width: 700px;">
        
        <!-- Judul Selamat Datang -->
        <h1 class="display-5 fw-bold text-dark mb-3">Selamat Datang</h1>
        
        <!-- Penjelasan tentang Lembaga -->
        <p class="lead text-secondary mb-4">
            Selamat datang di Sistem Pengelolaan Sertifikasi Profesi. Lembaga kami berkomitmen menyelenggarakan uji kompetensi keahlian yang terstandarisasi untuk menjamin kualitas, profesionalisme, dan daya saing kompetensi peserta di dunia industri.
        </p>

        <!-- Tombol Aksi (Login & Register) -->
        <div class="d-flex justify-content-center gap-3">
            <a href="login.php" class="btn btn-primary btn-lg px-4 shadow-sm">Login</a>
            <a href="register.php" class="btn btn-outline-primary btn-lg px-4">Register</a>
        </div>

    </div>

</body>
</html>