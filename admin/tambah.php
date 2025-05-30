<?php
session_start();
require_once '../functions.php';

if (!isset($_SESSION['loggedin']) || !isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    log_activity("Akses tidak sah ke admin/tambah.php", ['reason' => 'Belum login atau bukan admin']); // LOG ACTIVITY
    set_flash_message('login_error', 'Anda harus login sebagai admin.', 'danger');
    header("Location: ../login.php");
    exit();
}

log_activity("Admin mengakses halaman tambah artikel", ['admin_username' => $_SESSION['username']]); // LOG ACTIVITY


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!isset($_POST['csrf_token']) || !checkCSRFToken($_POST['csrf_token'])) {
        set_flash_message('artikel_error', 'Sesi tidak valid atau telah kedaluwarsa. Silakan coba lagi.', 'danger');
        // Untuk menjaga input lama jika perlu
        $_SESSION['old_input_tambah'] = $_POST;

        log_activity("Admin gagal menambah artikel: CSRF token tidak valid", ['admin_username' => $_SESSION['username']]); // LOG ACTIVITY

        header("Location: tambah.php");
        exit();
    }

    $data_artikel = [
        'penulis' => trim($_POST['penulis']), // Admin bisa set penulis
        'judul' => trim($_POST['judul']),
        'kutipan' => trim($_POST['kutipan']),
        'isi' => trim($_POST['isi']),
        'kategori' => trim($_POST['kategori'])
    ];

    if (tambah_artikel($data_artikel)) { // Menggunakan fungsi tambah_artikel dari functions.php
        set_flash_message('artikel_success', 'Artikel berhasil ditambahkan!', 'success');
        header("Location: dasboard.php");
        exit();
    } else {
        // Pesan error sudah di-set oleh tambah_artikel() atau upload_gambar()
        $_SESSION['old_input_tambah'] = $_POST; // Simpan input untuk repopulate
        log_activity("Admin gagal menambah artikel: fungsi tambah_artikel mengembalikan false", ['admin_username' => $_SESSION['username'], 'data_input' => $data_artikel]); // LOG ACTIVITY
        header("Location: tambah.php");
        exit();
    }
}

$csrf_token_tambah = $_SESSION['csrf_token'];
$old_input = $_SESSION['old_input_tambah'] ?? [];
unset($_SESSION['old_input_tambah']);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Tambah Artikel Baru - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
     <style>
        body { font-family: 'Open Sans', sans-serif; background-color: #f8f9fa; }
        .container-form { background: #fff; padding: 30px; border-radius: 10px; box-shadow: 0 0 15px rgba(0,0,0,0.1); margin-top: 20px; max-width: 800px;}
    </style>
</head>
<body>
    <?php include_once '../nav.php'; ?>
    <div class="container container-form">
        <h2 class="mt-3 mb-4">Tambah Artikel Baru</h2>

        <?= get_flash_message('artikel_error'); ?>
        <?= get_flash_message('upload_error'); ?>

        <form method="POST" action="tambah.php" enctype="multipart/form-data">
            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf_token_tambah); ?>">
            <div class="mb-3">
                <label for="penulis" class="form-label">Penulis <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="penulis" name="penulis" value="<?= htmlspecialchars($old_input['penulis'] ?? $_SESSION['username']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="judul" class="form-label">Judul <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="judul" name="judul" value="<?= htmlspecialchars($old_input['judul'] ?? ''); ?>" required>
            </div>
            <div class="mb-3">
                <label for="kutipan" class="form-label">Kutipan (Opsional)</label>
                <textarea class="form-control" id="kutipan" name="kutipan" rows="2"><?= htmlspecialchars($old_input['kutipan'] ?? ''); ?></textarea>
            </div>
            <div class="mb-3">
                <label for="isi" class="form-label">Isi Artikel <span class="text-danger">*</span></label>
                <textarea class="form-control" id="isi" name="isi" rows="10" required><?= htmlspecialchars($old_input['isi'] ?? ''); ?></textarea>
            </div>
            <div class="mb-3">
                <label for="kategori" class="form-label">Kategori (Opsional)</label>
                <input type="text" class="form-control" id="kategori" name="kategori" value="<?= htmlspecialchars($old_input['kategori'] ?? ''); ?>">
            </div>
            <div class="mb-3">
                <label for="gambar" class="form-label">Gambar (Opsional, Max 5MB: jpg, jpeg, png, gif)</label>
                <input type="file" class="form-control" id="gambar" name="gambar">
            </div>
            <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Publikasikan Artikel</button>
            <a href="dasboard.php" class="btn btn-secondary">Batal</a>
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>