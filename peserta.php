<?php
require 'koneksi.php';
// Proteksi halaman
if(!isset($_SESSION['admin'])) { header("Location: index.php"); exit; }

// Proses Tambah Data
if(isset($_POST['tambah'])) {
    $nama = $_POST['nama_peserta'];
    $email = $_POST['email'];
    $telp = $_POST['telepon'];
    $id_skema = $_POST['id_skema'];

    $stmt = $koneksi->prepare("INSERT INTO peserta (nama_peserta, email, telepon, id_skema) VALUES (?, ?, ?, ?)");
    if($stmt->execute([$nama, $email, $telp, $id_skema])) {
        header("Location: peserta.php");
    }
}

// Proses Hapus
if(isset($_GET['hapus'])) {
    $stmt = $koneksi->prepare("DELETE FROM peserta WHERE id_peserta = ?");
    $stmt->execute([$_GET['hapus']]);
    header("Location: peserta.php");
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Kelola Peserta</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-4">
    <div class="container">
        <div class="d-flex justify-content-between mb-3">
            <h2>Data Peserta</h2>
            <a href="logout.php" class="btn btn-danger">Logout (<?= $_SESSION['admin'] ?>)</a>
        </div>

        <!-- Form Tambah -->
        <form method="POST" class="row g-3 mb-4 card p-3">
            <div class="col-md-3">
                <input type="text" name="nama_peserta" class="form-control" placeholder="Nama Peserta" required>
            </div>
            <div class="col-md-3">
                <input type="email" name="email" class="form-control" placeholder="Email" required>
            </div>
            <div class="col-md-3">
                <select name="id_skema" class="form-control" required>
                    <option value="">-- Pilih Skema --</option>
                    <?php
                    // Mengambil data skema untuk dropdown (Relasi)
                    $skema = $koneksi->query("SELECT * FROM skema")->fetchAll();
                    foreach($skema as $s) {
                        echo "<option value='{$s['id_skema']}'>{$s['nama_skema']}</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="col-md-3">
                <button type="submit" name="tambah" class="btn btn-success w-100">Tambah Data</button>
            </div>
        </form>

        <!-- Tabel Tampil Data -->
        <table class="table table-bordered">
            <thead class="table-dark">
                <tr>
                    <th>No</th>
                    <th>Nama</th>
                    <th>Email</th>
                    <th>Skema Sertifikasi</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Query JOIN untuk menampilkan nama skema dari tabel relasi
                $query = "SELECT peserta.*, skema.nama_skema FROM peserta 
                          JOIN skema ON peserta.id_skema = skema.id_skema";
                
                // Fitur Pencarian Sederhana
                if(isset($_GET['cari'])) {
                    $cari = $_GET['cari'];
                    $query .= " WHERE nama_peserta LIKE '%$cari%'";
                }

                $stmt = $koneksi->query($query);
                $no = 1;
                while($row = $stmt->fetch()) { ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td><?= htmlspecialchars($row['nama_peserta']) ?></td>
                        <td><?= htmlspecialchars($row['email']) ?></td>
                        <td><?= htmlspecialchars($row['nama_skema']) ?></td>
                        <td>
                            <a href="peserta.php?hapus=<?= $row['id_peserta'] ?>" 
                               class="btn btn-sm btn-danger" 
                               onclick="return confirm('Yakin hapus data ini?')">Hapus</a>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</body>
</html>