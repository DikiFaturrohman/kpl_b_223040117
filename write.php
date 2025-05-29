<?php
session_start();
require_once 'functions.php';

// Hanya user yang login (bukan admin) yang boleh akses halaman ini untuk menulis
// Admin menulis melalui admin panel. Jika admin juga boleh, sesuaikan logikanya.
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    set_flash_message('login_info', 'Anda harus login untuk menulis artikel.', 'info');
    header("Location: login.php");
    exit();
}
if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin') {
    set_flash_message('admin_info', 'Admin dapat menambah artikel melalui Admin Panel.', 'info');
    header("Location: admin/dasboard.php"); // Arahkan admin ke dashboard mereka
    exit();
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!isset($_POST['csrf_token']) || !checkCSRFToken($_POST['csrf_token'])) {
        set_flash_message('artikel_user_error', 'Sesi tidak valid atau telah kedaluwarsa. Silakan coba lagi.', 'danger');
        $_SESSION['old_input_write'] = $_POST;
        header("Location: write.php");
        exit();
    }

    $data_artikel = [
        'penulis' => $_SESSION['username'], // Penulis diambil dari sesi pengguna yang login
        'judul' => trim($_POST['judul']),
        'kutipan' => trim($_POST['kutipan']),
        'isi' => trim($_POST['isi']),
        'kategori' => trim($_POST['kategori'])
    ];

    // Menggunakan fungsi tambah_artikel dari functions.php
    // $_FILES['gambar'] akan otomatis terbaca oleh fungsi upload_gambar() yang dipanggil di dalam tambah_artikel()
    if (tambah_artikel($data_artikel)) {
        set_flash_message('artikel_user_success', 'Artikel Anda berhasil dipublikasikan!', 'success');
        header("Location: view.php"); // Arahkan ke halaman "Blog Saya"
        exit();
    } else {
        // Pesan error sudah di-set oleh tambah_artikel() atau upload_gambar()
        $_SESSION['old_input_write'] = $_POST; // Simpan input untuk repopulate
        header("Location: write.php");
        exit();
    }
}

$csrf_token_write = $_SESSION['csrf_token'];
$old_input = $_SESSION['old_input_write'] ?? [];
unset($_SESSION['old_input_write']);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tulis Artikel Baru - CAMPUS BLOG</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { font-family: 'Open Sans', sans-serif; background-color: #f8f9fa; color: #333; }
        .container-form { background: #fff; padding: 30px; border-radius: 10px; box-shadow: 0 0 20px rgba(0,0,0,0.1); margin-top: 30px; max-width: 800px;}
        .form-label { font-weight: 500; }
        .btn-custom-publish { background-color: #ffc107; border-color: #ffc107; color: #212529; }
        .btn-custom-publish:hover { background-color: #e0a800; border-color: #d39e00; }
    </style>
</head>
<body>
    <?php include_once 'nav.php'; ?>

    <div class="container container-form">
        <div class="text-center mb-4">
            <i class="fas fa-feather-alt fa-3x text-warning"></i>
            <h2 class="mt-2">Tulis Artikel Baru Anda</h2>
            <p class="text-muted">Bagikan ide, cerita, dan wawasan Anda kepada dunia.</p>
        </div>

        <?= get_flash_message('artikel_user_error'); ?>
        <?= get_flash_message('upload_error'); ?>

        <form action="write.php" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf_token_write); ?>">

            <div class="mb-3">
                <label for="judul" class="form-label">Judul Artikel <span class="text-danger">*</span></label>
                <input type="text" class="form-control form-control-lg" id="judul" name="judul" value="<?= htmlspecialchars($old_input['judul'] ?? ''); ?>" placeholder="Judul yang menarik..." required>
            </div>

            <div class="mb-3">
                <label for="kutipan" class="form-label">Kutipan Singkat (Opsional)</label>
                <textarea class="form-control" id="kutipan" name="kutipan" rows="2" placeholder="Ringkasan singkat atau kutipan menarik dari artikel Anda..."><?= htmlspecialchars($old_input['kutipan'] ?? ''); ?></textarea>
            </div>

            <div class="mb-3">
                <label for="isi" class="form-label">Isi Artikel <span class="text-danger">*</span></label>
                <textarea class="form-control" id="isi" name="isi" rows="12" placeholder="Tuliskan isi artikel Anda di sini..." required><?= htmlspecialchars($old_input['isi'] ?? ''); ?></textarea>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="kategori" class="form-label">Kategori (Opsional)</label>
                    <input type="text" class="form-control" id="kategori" name="kategori" value="<?= htmlspecialchars($old_input['kategori'] ?? ''); ?>" placeholder="Contoh: Teknologi, Opini, Tutorial">
                </div>
                <div class="col-md-6 mb-3">
                    <label for="gambar" class="form-label">Gambar Pendukung (Opsional, Max 5MB)</label>
                    <input type="file" class="form-control" id="gambar" name="gambar" accept="image/jpeg,image/png,image/gif">
                </div>
            </div>

            <hr class="my-4">

            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                <a href="view.php" class="btn btn-outline-secondary me-md-2"><i class="fas fa-times"></i> Batal</a>
                <button type="submit" class="btn btn-custom-publish btn-lg"><i class="fas fa-paper-plane"></i> Publikasikan Artikel</button>
            </div>
        </form>
    </div>

    <footer class="text-center text-muted py-4 mt-5">
        <small>&copy; <?= date("Y"); ?> CAMPUS BLOG. Semangat Berkarya!</small>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>