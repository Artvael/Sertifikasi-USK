<?php
require 'koneksi.php';
// Proteksi halaman: Wajib login admin
if (!isset($_SESSION['admin'])) { header("Location: login.php"); exit; }

// 1. Logika Tambah Data Peserta
if (isset($_POST['tambah'])) {
    $nama     = trim($_POST['nama_peserta']);
    $email    = trim($_POST['email']);
    $telepon  = trim($_POST['telepon']);
    $id_skema = $_POST['id_skema'];

    // Validasi nama hanya huruf & telepon max 15 digit
    if (!preg_match("/^[a-zA-Z\s]+$/", $nama)) {
        header("Location: peserta.php?error=nama_invalid");
        exit;
    } elseif (!preg_match("/^[0-9]{10,15}$/", $telepon)) {
        header("Location: peserta.php?error=telepon_invalid");
        exit;
    } else {
        $koneksi->prepare("INSERT INTO peserta (nama_peserta, email, telepon, id_skema) VALUES (?, ?, ?, ?)")
                ->execute([$nama, $email, $telepon, $id_skema]);
        header("Location: peserta.php?pesan=tambah_sukses");
        exit;
    }
}

// 2. Logika Hapus Data Peserta
if (isset($_GET['hapus'])) {
    $koneksi->prepare("DELETE FROM peserta WHERE id_peserta = ?")->execute([$_GET['hapus']]);
    header("Location: peserta.php");
    exit;
}

// 3. Logika Pencarian & Tampil Data
$keyword = isset($_GET['keyword']) ? trim($_GET['keyword']) : '';
if ($keyword !== '') {
    // Cari berdasarkan nama, email, atau telepon menggunakan operator LIKE
    $stmt = $koneksi->prepare("SELECT * FROM peserta WHERE nama_peserta LIKE ? OR email LIKE ? OR telepon LIKE ?");
    $stmt->execute(["%$keyword%", "%$keyword%", "%$keyword%"]);
} else {
    // Tampilkan semua data jika tidak ada pencarian
    $stmt = $koneksi->query("SELECT * FROM peserta");
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
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2>Data Peserta</h2>
            <div class="d-flex gap-2">
                <a href="dashboard.php" class="btn btn-secondary">Kembali ke Dashboard</a>
                <a href="logout.php" class="btn btn-danger">Logout</a>
            </div>
        </div>

        <!-- Notifikasi Pesan -->
        <?php if (isset($_GET['error']) && $_GET['error'] == 'nama_invalid'): ?>
            <div class="alert alert-danger alert-dismissible fade show">Nama hanya boleh berupa huruf dan spasi, tidak boleh angka!</div>
        <?php elseif (isset($_GET['error']) && $_GET['error'] == 'telepon_invalid'): ?>
            <div class="alert alert-danger alert-dismissible fade show">Nomor telepon harus berupa angka antara 10 sampai 15 digit!</div>
        <?php elseif (isset($_GET['pesan']) && $_GET['pesan'] == 'tambah_sukses'): ?>
            <div class="alert alert-success alert-dismissible fade show">Data peserta berhasil ditambahkan!</div>
        <?php elseif (isset($_GET['pesan']) && $_GET['pesan'] == 'update_sukses'): ?>
            <div class="alert alert-success alert-dismissible fade show">Data peserta berhasil diperbarui!</div>
        <?php endif; ?>
        
        <!-- Form Tambah Cepat -->
        <form method="POST" class="row g-2 mb-3">
            <div class="col-3">
                <input type="text" name="nama_peserta" class="form-control" placeholder="Nama (Huruf saja)" required
                       pattern="[a-zA-Z\s]+"
                       title="Nama hanya boleh berupa huruf dan spasi, tidak boleh mengandung angka"
                       oninput="this.value = this.value.replace(/[^a-zA-Z\s]/g, '')">
            </div>
            <div class="col-3"><input type="email" name="email" class="form-control" placeholder="Email" required></div>
            <div class="col-2">
                <input type="tel" name="telepon" class="form-control" placeholder="Telepon (Max 15 digit)" required
                       maxlength="15" minlength="10"
                       pattern="[0-9]{10,15}"
                       title="Nomor telepon harus berupa angka antara 10 hingga 15 digit"
                       oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 15)">
            </div>
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