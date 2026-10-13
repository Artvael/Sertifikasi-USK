<?php
require 'koneksi.php';
// Proteksi halaman: Wajib login admin
if (!isset($_SESSION['admin'])) { header("Location: login.php"); exit; }

// Pastikan ada parameter ID di URL
if (!isset($_GET['id'])) { header("Location: peserta.php"); exit; }

// Proses Update saat tombol Simpan diklik
if (isset($_POST['update'])) {
    $nama     = trim($_POST['nama_peserta']);
    $email    = trim($_POST['email']);
    $telepon  = trim($_POST['telepon']);
    $id_skema = $_POST['id_skema'];

    if (!preg_match("/^[a-zA-Z\s]+$/", $nama)) {
        $error = "Nama hanya boleh berupa huruf dan spasi, tidak boleh angka!";
    } elseif (!preg_match("/^[0-9]{10,15}$/", $telepon)) {
        $error = "Nomor telepon harus berupa angka antara 10 sampai 15 digit!";
    } else {
        $koneksi->prepare("UPDATE peserta SET nama_peserta=?, email=?, telepon=?, id_skema=? WHERE id_peserta=?")
                ->execute([$nama, $email, $telepon, $id_skema, $_GET['id']]);
        header("Location: peserta.php?pesan=update_sukses");
        exit;
    }
}

// Ambil data peserta lama berdasarkan ID
$p = $koneksi->prepare("SELECT * FROM peserta WHERE id_peserta = ?");
$p->execute([$_GET['id']]);
$row = $p->fetch();

// Jika data tidak ditemukan
if (!$row) { header("Location: peserta.php"); exit; }
?>
<!DOCTYPE html>
<html>
<head>
    <title>Edit Peserta</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-4">
    <div class="container" style="max-width: 400px;">
        <h3 class="mb-3">Edit Peserta</h3>

        <?php if (isset($error)): ?>
            <div class="alert alert-danger py-2 mb-3"><?= $error ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="mb-2">
                <label class="form-label small text-muted">Nama Peserta (Hanya Huruf)</label>
                <input type="text" name="nama_peserta" class="form-control" value="<?= htmlspecialchars($row['nama_peserta']) ?>" required
                       pattern="[a-zA-Z\s]+"
                       title="Nama hanya boleh berupa huruf dan spasi, tidak boleh mengandung angka"
                       oninput="this.value = this.value.replace(/[^a-zA-Z\s]/g, '')">
            </div>
            <div class="mb-2">
                <label class="form-label small text-muted">Email</label>
                <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($row['email']) ?>" required>
            </div>
            <div class="mb-2">
                <label class="form-label small text-muted">No. Telepon (10 - 15 digit)</label>
                <input type="tel" name="telepon" class="form-control" value="<?= htmlspecialchars($row['telepon']) ?>" required
                       maxlength="15" minlength="10"
                       pattern="[0-9]{10,15}"
                       title="Nomor telepon harus berupa angka antara 10 hingga 15 digit"
                       oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 15)">
            </div>
            <div class="mb-3">
                <label class="form-label small text-muted">Pilih Skema</label>
                <select name="id_skema" class="form-select" required>
                    <?php foreach($koneksi->query("SELECT * FROM skema") as $s) {
                        $sel = $s['id_skema'] == $row['id_skema'] ? 'selected' : '';
                        echo "<option value='{$s['id_skema']}' $sel>{$s['nama_skema']}</option>";
                    } ?>
                </select>
            </div>
            <button type="submit" name="update" class="btn btn-warning w-100">Simpan Perubahan</button>
            <a href="peserta.php" class="btn btn-secondary w-100 mt-2">Batal</a>
        </form>
    </div>
</body>
</html>