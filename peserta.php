<?php
session_start();
require 'koneksi.php';
if (!isset($_SESSION['admin'])) { header("Location: index.php"); exit; }

// Tambah & Hapus dalam satu blok ringkas
if (isset($_POST['tambah'])) {
    $koneksi->prepare("INSERT INTO peserta (nama_peserta, email, telepon, id_skema) VALUES (?, ?, ?, ?)")
            ->execute([$_POST['nama_peserta'], $_POST['email'], $_POST['telepon'], $_POST['id_skema']]);
    header("Location: peserta.php"); exit;
}
if (isset($_GET['hapus'])) {
    $koneksi->prepare("DELETE FROM peserta WHERE id_peserta = ?")->execute([$_GET['hapus']]);
    header("Location: peserta.php"); exit;
}

// Logika Pencarian
$keyword = isset($_GET['keyword']) ? trim($_GET['keyword']) : '';
if ($keyword !== '') {
    $stmt = $koneksi->prepare("SELECT * FROM peserta WHERE nama_peserta LIKE ? OR email LIKE ? OR telepon LIKE ?");
    $stmt->execute(["%$keyword%", "%$keyword%", "%$keyword%"]);
} else {
    $stmt = $koneksi->query("SELECT * FROM peserta");
}
?>
<!DOCTYPE html>
<html>
<head><link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"></head>
<body class="p-4">
    <div class="container">
        <h2>Data Peserta <a href="logout.php" class="btn btn-sm btn-danger float-end">Logout</a></h2>
        
        <!-- Form Tambah Cepat -->
        <form method="POST" class="row g-2 mb-3">
            <div class="col-3"><input type="text" name="nama_peserta" class="form-control" placeholder="Nama" required></div>
            <div class="col-3"><input type="email" name="email" class="form-control" placeholder="Email" required></div>
            <div class="col-2"><input type="text" name="telepon" class="form-control" placeholder="Telepon" required></div>
            <div class="col-2">
                <select name="id_skema" class="form-select" required>
                    <?php foreach($koneksi->query("SELECT * FROM skema") as $s) echo "<option value='{$s['id_skema']}'>{$s['nama_skema']}</option>"; ?>
                </select>
            </div>
            <div class="col-2"><button type="submit" name="tambah" class="btn btn-success w-100">Tambah</button></div>
        </form>

        <!-- Form Pencarian -->
        <form method="GET" class="row g-2 mb-3">
            <div class="col-10">
                <input type="text" name="keyword" class="form-control" placeholder="Cari berdasarkan nama, email, atau telepon..." value="<?= htmlspecialchars($keyword) ?>">
            </div>
            <div class="col-2 d-flex gap-1">
                <button type="submit" class="btn btn-primary w-100">Cari</button>
                <?php if ($keyword !== ''): ?>
                    <a href="peserta.php" class="btn btn-secondary">Reset</a>
                <?php endif; ?>
            </div>
        </form>

        <table class="table table-bordered">
            <thead class="table-dark"><tr><th>No</th><th>Nama</th><th>Email</th><th>Telepon</th><th>Aksi</th></tr></thead>
            <tbody>
                <?php 
                $no = 1;
                while($row = $stmt->fetch()): 
                ?>
                <tr>
                    <td><?= $no++ ?></td>
                    <td><?= htmlspecialchars($row['nama_peserta']) ?></td>
                    <td><?= htmlspecialchars($row['email']) ?></td>
                    <td><?= htmlspecialchars($row['telepon']) ?></td>
                    <td>
                        <a href="detail_peserta.php?id=<?= $row['id_peserta'] ?>" class="btn btn-sm btn-info">Detail</a>
                        <a href="edit_peserta.php?id=<?= $row['id_peserta'] ?>" class="btn btn-sm btn-warning">Edit</a>
                        <a href="peserta.php?hapus=<?= $row['id_peserta'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Hapus?')">Hapus</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>
</html>