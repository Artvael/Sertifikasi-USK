<?php
require 'koneksi.php';

// Jika admin sudah login, langsung arahkan ke dashboard
if (isset($_SESSION['admin'])) {
    header("Location: dashboard.php");
    exit;
}

// Proses form login saat tombol diklik
if (isset($_POST['login'])) {
    $user = $_POST['username'];
    $pass = $_POST['password'];

    // Prepared Statement: Mengamankan query dari SQL Injection
    $stmt = $koneksi->prepare("SELECT * FROM admin WHERE username = ?");
    $stmt->execute([$user]);
    $data = $stmt->fetch();

    // Validasi data akun dan kecocokan password
    if ($data && $pass == $data['password']) {
        $_SESSION['admin'] = $data['username']; // Simpan session login
        header("Location: dashboard.php");
        exit;
    } else {
        $error = "Username atau Password salah!";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Login Sistem</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5" style="max-width: 400px;">
        <div class="card p-4">
            <h3 class="text-center">Login Admin</h3>
            <?php if(isset($error)) echo "<div class='alert alert-danger'>$error</div>"; ?>
            
            <form method="POST">
                <div class="mb-3">
                    <label>Username</label>
                    <input type="text" name="username" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label>Password</label>
                    <input type="password" name="password" class="form-control" required>
                </div>
                <button type="submit" name="login" class="btn btn-primary w-100">Login</button>
            </form>
        </div>
    </div>
</body>
</html>