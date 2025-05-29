<?php
// logout.php
session_start(); // WAJIB untuk mengakses dan menghancurkan sesi

// 1. Kosongkan semua variabel sesi.
$_SESSION = [];

// 2. Hapus cookie sesi jika digunakan.
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// 3. Hancurkan sesi.
session_destroy();

// Redirect ke halaman utama atau halaman login
header("Location: index.php");
exit(); // Pastikan tidak ada kode lain yang dieksekusi setelah redirect
?>