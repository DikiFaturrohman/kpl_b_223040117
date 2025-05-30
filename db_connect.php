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
    // Tidak perlu log sukses koneksi di sini, terlalu verbose
} catch (\PDOException $e) {
    // Fungsi log_error mungkin belum tersedia jika db_connect.php di-include sebelum functions.php
    // yang mendefinisikannya. Maka, gunakan error_log() dasar di sini sebagai fallback.
    $logMessage = "Koneksi Database Gagal: " . $e->getMessage();
    error_log($logMessage); // Log ke error log PHP default

    // Jika functions.php sudah di-load dan log_error() tersedia:
    if (function_exists('log_error')) {
        log_error("Koneksi Database Gagal", [], $e);
    }
    
    // Tampilkan pesan error generik ke user atau redirect ke halaman error.
    // Jangan tampilkan $e->getMessage() di produksi.
    die("Tidak dapat terhubung ke database. Silakan coba lagi nanti atau hubungi administrator.");
}
?>