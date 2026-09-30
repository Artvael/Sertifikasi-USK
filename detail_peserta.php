<?php
require 'koneksi.php';
if (!isset($_GET['id'])) { header("Location: peserta.php"); exit; }

$stmt = $koneksi->prepare("SELECT p.*, s.nama_skema FROM peserta p JOIN skema s ON p.id_skema = s.id_skema WHERE p.id_peserta = ?");
$stmt->execute([$_GET['id']]);
$row = $stmt->fetch();
?>
<!DOCTYPE html>
<html>
<head><link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"></head>
<body class="p-4">
    <div class="container" style="max-width: 500px;">
        <div class="card p-3">
            <h3>Detail Peserta</h3>
            <p><strong>Nama:</strong> <?= $row['nama_peserta'] ?></p>
            <p><strong>Email:</strong> <?= $row['email'] ?></p>
            <p><strong>Telepon:</strong> <?= $row['telepon'] ?></p>
            <p><strong>Skema:</strong> <?= $row['nama_skema'] ?></p>
            <a href="peserta.php" class="btn btn-secondary w-100">Kembali</a>
        </div>
    </div>
</body>
</html>