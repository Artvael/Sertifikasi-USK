<?php
session_start();
session_destroy(); // Menghapus seluruh session login
header("Location: index.php"); // Mengalihkan ke halaman login
exit;
?>