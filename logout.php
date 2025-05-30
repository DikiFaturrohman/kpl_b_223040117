<?php
// logout.php
session_start(); // WAJIB untuk mengakses dan menghancurkan sesi


if (file_exists('functions.php')) { // Cek jika file ada di root
    require_once 'functions.php';
} elseif (file_exists('../functions.php')) { // Cek jika file ada di parent (misal dari /admin)
    require_once '../functions.php';
} else {
    session_start(); // Fallback jika functions.php tidak ditemukan
}


$username_logout = $_SESSION['username'] ?? 'Unknown User';
$user_id_logout = $_SESSION['user_id'] ?? 'Unknown ID';

// Log sebelum sesi dihancurkan
if (function_exists('log_activity')) {
    log_activity("User logout", ['username_logged_out' => $username_logout, 'user_id_logged_out' => $user_id_logout]);
} else {
    error_log("User $username_logout (ID: $user_id_logout) logged out.");
}
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