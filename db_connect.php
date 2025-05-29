<?php
// $host = 'localhost';
// $db = 'news';
// $user = 'root';
// $pass = '';
// $charset = 'utf8mb4';

// $dsn = "mysql:host=$host;dbname=$db;charset=$charset";
// $options = [
//     PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
//     PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
//     PDO::ATTR_EMULATE_PREPARES   => false,
// ];

// try {
//     $conn = new PDO($dsn, $user, $pass, $options);
// } catch (\PDOException $e) {
//     throw new \PDOException($e->getMessage(), (int)$e->getCode());
// }






// db_connect.php

// SANGAT DISARANKAN: Di lingkungan produksi, gunakan environment variables
// untuk menyimpan kredensial database, bukan hardcode seperti ini.
$host = 'localhost';
$db   = 'news'; // Sesuaikan dengan nama database Anda
$user = 'root'; // Sesuaikan dengan username database Anda
$pass = '';     // Sesuaikan dengan password database Anda
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $conn = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    // Di lingkungan produksi, jangan tampilkan error detail ke user.
    // Log error ini ke file atau sistem logging.
    error_log("Koneksi Database Gagal: " . $e->getMessage());
    // Tampilkan pesan error generik ke user atau redirect ke halaman error.
    die("Tidak dapat terhubung ke database. Silakan coba lagi nanti.");
}
?>