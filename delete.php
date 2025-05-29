<?php
session_start();
require_once 'functions.php';

// Pastikan pengguna sudah login
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    set_flash_message('login_info', 'Anda harus login untuk melakukan aksi ini.', 'info');
    header("Location: login.php");
    exit();
}
// Admin tidak menggunakan file ini untuk hapus, mereka punya admin/hapus.php
if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin') {
    set_flash_message('admin_info_delete', 'Admin dapat menghapus artikel melalui Admin Panel.', 'info');
    header("Location: admin/dasboard.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!isset($_POST['csrf_token']) || !checkCSRFToken($_POST['csrf_token'])) {
        set_flash_message('artikel_user_error', 'Sesi tidak valid atau telah kedaluwarsa. Gagal menghapus.', 'danger');
        header("Location: view.php"); // Redirect ke daftar artikel pengguna
        exit();
    }

    $id_artikel = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
    if (!$id_artikel) {
        set_flash_message('artikel_user_error', 'ID Artikel tidak valid untuk dihapus.', 'danger');
        header("Location: view.php");
        exit();
    }

    $username_session = $_SESSION['username'];

    // Panggil fungsi hapus_artikel dari functions.php
    // Argumen ketiga false (atau tidak ada) menandakan ini bukan operasi admin, jadi cek kepemilikan
    if (hapus_artikel($id_artikel, $username_session, false)) {
        set_flash_message('artikel_user_success', 'Artikel Anda berhasil dihapus!', 'success');
    } else {
        // Pesan error sudah di-set oleh hapus_artikel()
    }
    header("Location: view.php");
    exit();

} else {
    // Jika diakses via GET, redirect atau tampilkan error
    set_flash_message('artikel_user_error', 'Permintaan tidak valid untuk menghapus artikel.', 'danger');
    header("Location: view.php");
    exit();
}
?>