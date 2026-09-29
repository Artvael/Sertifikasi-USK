<?php
require 'koneksi.php';

if(isset($_POST['login'])) {
    $user = $_POST['username'];
    $pass = $_POST['password'];

    $stmt = $koneksi->prepare("SELECT * FROM admin WHERE username = ?");
    $stmt->execute([$user]);
    $data = $stmt->fetch();

    // Validasi input dan cek password
    if($data && password_verify($pass, $data['password'])) {
        $_SESSION['admin'] = $data['nama'];
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