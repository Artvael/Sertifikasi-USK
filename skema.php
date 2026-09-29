<?php
require 'koneksi.php';
if(!isset($_SESSION['admin'])) { header("Location: index.php"); exit; }

// Proses Tambah Data
if(isset($_POST['tambah'])) {
    $kode = $_POST['kode_skema'];
    $nama = $_POST['nama_skema'];

    $stmt = $koneksi->prepare("INSERT INTO skema (kode_skema, nama_skema) VALUES (?, ?)");
    $stmt->execute([$kode, $nama]);
    header("Location: skema.php");
}

// Proses Hapus Data
if(isset($_GET['hapus'])) {
    $stmt = $koneksi->prepare("DELETE FROM skema WHERE id_skema = ?");
    $stmt->execute([$_GET['hapus']]);
    header("Location: skema.php");
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Kelola Skema</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-4">
    <div class="container">
        <div class="d-flex justify-content-between mb-3">
            <h2>Data Skema Sertifikasi</h2>
            <a href="dashboard.php" class="btn btn-secondary">Kembali ke Dashboard</a>
        </div>

        <!-- Form Tambah Skema -->
        <form method="POST" class="row g-3 mb-4 card p-3">
            <div class="col-md-4">
                <input type="text" name="kode_skema" class="form-control" placeholder="Kode Skema (Misal: JWD)" required>
            </div>
            <div class="col-md-5">
                <input type="text" name="nama_skema" class="form-control" placeholder="Nama Skema" required>
            </div>
            <div class="col-md-3">
                <button type="submit" name="tambah" class="btn btn-success w-100">Tambah Skema</button>
            </div>
        </form>

        <!-- Tabel Tampil Data Skema -->
        <table class="table table-bordered">
            <thead class="table-dark">
                <tr>
                    <th>No</th>
                    <th>Kode Skema</th>
                    <th>Nama Skema</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $stmt = $koneksi->query("SELECT * FROM skema");
                $no = 1;
                while($row = $stmt->fetch()) { ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td><?= htmlspecialchars($row['kode_skema']) ?></td>
                        <td><?= htmlspecialchars($row['nama_skema']) ?></td>
                        <td>
                            <a href="skema.php?hapus=<?= $row['id_skema'] ?>" 
                               class="btn btn-sm btn-danger" 
                               onclick="return confirm('Hapus skema ini?')">Hapus</a>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</body>
</html>