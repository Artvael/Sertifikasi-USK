<?php
session_start();
require 'koneksi.php';
if (!isset($_GET['id'])) { header("Location: peserta.php"); exit; }

if (isset($_POST['update'])) {
    $koneksi->prepare("UPDATE peserta SET nama_peserta=?, email=?, telepon=?, id_skema=? WHERE id_peserta=?")
            ->execute([$_POST['nama_peserta'], $_POST['email'], $_POST['telepon'], $_POST['id_skema'], $_GET['id']]);
    header("Location: peserta.php"); exit;
}

$p = $koneksi->prepare("SELECT * FROM peserta WHERE id_peserta = ?");
$p->execute([$_GET['id']]);
$row = $p->fetch();
?>
<!DOCTYPE html>
<html>
<head><link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"></head>
<body class="p-4">
    <div class="container" style="max-width: 400px;">
        <h3>Edit Peserta</h3>
        <form method="POST">
            <input type="text" name="nama_peserta" class="form-control mb-2" value="<?= $row['nama_peserta'] ?>" required>
            <input type="email" name="email" class="form-control mb-2" value="<?= $row['email'] ?>" required>
            <input type="text" name="telepon" class="form-control mb-2" value="<?= $row['telepon'] ?>" required>
            <select name="id_skema" class="form-select mb-3" required>
                <?php foreach($koneksi->query("SELECT * FROM skema") as $s) {
                    $sel = $s['id_skema'] == $row['id_skema'] ? 'selected' : '';
                    echo "<option value='{$s['id_skema']}' $sel>{$s['nama_skema']}</option>";
                } ?>
            </select>
            <button type="submit" name="update" class="btn btn-warning w-100">Simpan</button>
            <a href="peserta.php" class="btn btn-secondary w-100 mt-2">Batal</a>
        </form>
    </div>
</body>
</html>