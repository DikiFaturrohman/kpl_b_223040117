<?php
session_start();
require_once '../functions.php';

if (!isset($_SESSION['loggedin']) || !isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    set_flash_message('login_error', 'Anda harus login sebagai admin.', 'danger');
    header("Location: ../login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!isset($_POST['csrf_token']) || !checkCSRFToken($_POST['csrf_token'])) {
        set_flash_message('artikel_error', 'Sesi tidak valid atau telah kedaluwarsa. Gagal menghapus.', 'danger');
        header("Location: dasboard.php");
        exit();
    }

    $id_artikel = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
    if (!$id_artikel) {
        set_flash_message('artikel_error', 'ID Artikel tidak valid untuk dihapus.', 'danger');
        header("Location: dasboard.php");
        exit();
    }

    // Panggil fungsi hapus_artikel dari functions.php
    // Argumen ketiga true menandakan ini adalah operasi admin
    if (hapus_artikel($id_artikel, null, true)) {
        set_flash_message('artikel_success', 'Artikel berhasil dihapus!', 'success');
    } else {
        // Pesan error sudah di-set oleh hapus_artikel()
    }
    header("Location: dasboard.php");
    exit();

} else {
    // Jika diakses via GET, redirect atau tampilkan error
    set_flash_message('artikel_error', 'Permintaan tidak valid.', 'danger');
    header("Location: dasboard.php");
    exit();
}
?>