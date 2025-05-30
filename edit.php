<?php
session_start();
require_once 'functions.php';

// Pastikan pengguna sudah login
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    log_activity("Akses tidak sah ke edit.php (user): belum login"); // LOG ACTIVITY

    set_flash_message('login_info', 'Anda harus login untuk mengedit artikel.', 'info');
    header("Location: login.php");
    exit();
}
// Admin tidak diarahkan dari sini, karena mereka punya dashboard sendiri.
if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin') {
    log_activity("Admin mencoba akses edit.php (user), diarahkan ke panel admin", ['admin_username' => $_SESSION['username']]); // LOG ACTIVITY
  
    set_flash_message('admin_info_edit', 'Admin dapat mengedit artikel melalui Admin Panel.', 'info');
    header("Location: admin/dasboard.php");
    exit();
}

$id_artikel = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$id_artikel) {
    log_error("User mencoba akses edit.php dengan ID artikel tidak valid", ['id_attempted' => $_GET['id'] ?? 'N/A', 'username' => $_SESSION['username']]); // LOG ERROR
 
    set_flash_message('artikel_user_error', 'ID Artikel tidak valid untuk diedit.', 'danger');
    header("Location: view.php"); // Kembali ke daftar artikel pengguna
    exit();
}

log_activity("User mengakses halaman edit artikel", ['username' => $_SESSION['username'], 'id_artikel' => $id_artikel]); // LOG ACTIVITY


// Ambil data artikel yang akan diedit, pastikan milik user yang login
$username_session = $_SESSION['username'];
$stmt_artikel = query("SELECT * FROM halaman WHERE id = :id AND penulis = :penulis", [':id' => $id_artikel, ':penulis' => $username_session]);
$artikel = $stmt_artikel ? $stmt_artikel->fetch() : null;

if (!$artikel) {
    log_error("User mencoba mengedit artikel yang tidak ditemukan atau bukan miliknya", ['id_artikel' => $id_artikel, 'username' => $username_session]); // LOG ERROR

    set_flash_message('artikel_user_error', 'Artikel tidak ditemukan atau Anda tidak berhak mengeditnya.', 'danger');
    header("Location: view.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!isset($_POST['csrf_token']) || !checkCSRFToken($_POST['csrf_token'])) {
        set_flash_message('artikel_user_error', 'Sesi tidak valid atau telah kedaluwarsa. Silakan coba lagi.', 'danger');
        $_SESSION['old_input_edit_user'] = $_POST;
        log_activity("User gagal mengedit artikel: CSRF token tidak valid", ['id_artikel' => $id_artikel, 'username' => $_SESSION['username']]); // LOG ACTIVITY

        header("Location: edit.php?id=" . $id_artikel);
        exit();
    }

    $data_update = [
        'id' => $id_artikel,
        'penulis' => $username_session, // Penulis tidak bisa diubah oleh user, tetap dari sesi
        'judul' => trim($_POST['judul']),
        'kutipan' => trim($_POST['kutipan']),
        'isi' => trim($_POST['isi']),
        'kategori' => trim($_POST['kategori']),
        'gambarLama' => $artikel['gambar'] // Kirim nama gambar lama ke fungsi ubah
        // 'current_user_role' => 'user' // Flag jika fungsi ubah_artikel butuh info role
    ];

    // Menggunakan fungsi ubah_artikel dari functions.php
    // Fungsi ini harus memastikan bahwa user hanya bisa update artikelnya sendiri jika ada parameter penulis
    // atau pengecekan kepemilikan sudah dilakukan sebelum memanggilnya.
    // Di sini, query SELECT di awal sudah memastikan kepemilikan.
    if (ubah_artikel($data_update)) {
        set_flash_message('artikel_user_success', 'Artikel Anda berhasil diperbarui!', 'success');
        header("Location: view.php");
        exit();
    } else {
        // Pesan error sudah di-set oleh ubah_artikel() atau upload_gambar()
        $_SESSION['old_input_edit_user'] = $_POST;
        log_activity("User gagal mengedit artikel: fungsi ubah_artikel mengembalikan false", ['id_artikel' => $id_artikel, 'username' => $username_session, 'data_input' => $data_update]); // LOG ACTIVITY
 
        header("Location: edit.php?id=" . $id_artikel);
        exit();
    }
}

$csrf_token_edit_user = $_SESSION['csrf_token'];
// Jika ada old_input dari error validasi sebelumnya, gunakan itu. Jika tidak, gunakan data dari DB.
$form_data = $_SESSION['old_input_edit_user'] ?? $artikel;
unset($_SESSION['old_input_edit_user']);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Artikel: <?= htmlspecialchars($artikel['judul']); ?> - CAMPUS BLOG</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { font-family: 'Open Sans', sans-serif; background-color: #f8f9fa; color: #333; }
        .container-form { background: #fff; padding: 30px; border-radius: 10px; box-shadow: 0 0 20px rgba(0,0,0,0.1); margin-top: 30px; max-width: 800px;}
        .form-label { font-weight: 500; }
        .current-image { max-width: 180px; height: auto; margin-top: 8px; border-radius: 5px; border: 1px solid #ccc; }
        .btn-custom-update { background-color: #198754; border-color: #198754; color:white; }
        .btn-custom-update:hover { background-color: #157347; border-color: #146c43; }
    </style>
</head>
<body>
    <?php include_once 'nav.php'; ?>

    <div class="container container-form">
        <div class="text-center mb-4">
            <i class="fas fa-edit fa-3x text-success"></i>
            <h2 class="mt-2">Edit Artikel Anda</h2>
            <p class="text-muted">Perbarui detail artikel "<strong><?= htmlspecialchars($artikel['judul']); ?></strong>".</p>
        </div>

        <?= get_flash_message('artikel_user_error'); ?>
        <?= get_flash_message('upload_error'); ?>

        <form method="POST" action="edit.php?id=<?= $id_artikel; ?>" enctype="multipart/form-data">
            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf_token_edit_user); ?>">

            <div class="mb-3">
                <label for="judul" class="form-label">Judul Artikel <span class="text-danger">*</span></label>
                <input type="text" class="form-control form-control-lg" id="judul" name="judul" value="<?= htmlspecialchars($form_data['judul'] ?? ''); ?>" required>
            </div>

            <div class="mb-3">
                <label for="kutipan" class="form-label">Kutipan Singkat (Opsional)</label>
                <textarea class="form-control" id="