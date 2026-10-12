<?php
// Mulai session jika belum aktif agar session otomatis tersedia di seluruh halaman
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$host = 'localhost';
$user = 'root';
$pass = '';
$db   = 'db_sertifikasi';

try {
    // Membuat koneksi ke database MySQL menggunakan PDO
    $koneksi = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    // Mengaktifkan mode exception error agar kesalahan query dapat terdeteksi dengan jelas
    $koneksi->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Koneksi Database Gagal: " . $e->getMessage()); 
}
?>