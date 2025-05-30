<?php
session_start();
require_once '../functions.php';

if (!isset($_SESSION['loggedin']) || !isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    log_activity("Akses tidak sah ke admin/ubah.php", ['reason' => 'Belum login atau bukan admin']); // LOG ACTIVITY
    set_flash_message('login_error', 'Anda harus login sebagai admin.', 'danger');
    header("Location: ../login.php");
    exit();
}

$id_artikel = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$id_artikel) {
    log_error("Admin mencoba akses ubah.php dengan ID artikel tidak valid", ['id_attempted' => $_GET['id'] ?? 'N/A', 'admin_username' => $_SESSION['username']]); // LOG ERROR

    set_flash_message('artikel_error', 'ID Artikel tidak valid.', 'danger');
    header("Location: dasboard.php");
    exit();
}


log_activity("Admin mengakses halaman ubah artikel", ['admin_username' => $_SESSION['username'], 'id_artikel' => $id_artikel]); // LOG ACTIVITY


// Ambil data artikel yang akan diubah
$stmt_artikel = query("SELECT * FROM halaman WHERE id = :id", [':id' => $id_artikel]);
$artikel = $stmt_artikel ? $stmt_artikel->fetch() : null;

if (!$artikel) {
    log_error("Admin mencoba mengubah artikel yang tidak ditemukan", ['id_artikel' => $id_artikel, 'admin_username' => $_SESSION['username']]); // LOG ERROR

    set_flash_message('artikel_error', 'Artikel tidak ditemukan.', 'danger');
    header("Location: dasboard.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!isset($_POST['csrf_token']) || !checkCSRFToken($_POST['csrf_token'])) {
        set_flash_message('artikel_error', 'Sesi tidak valid atau telah kedaluwarsa. Silakan coba lagi.', 'danger');
        $_SESSION['old_input_ubah'] = $_POST;
        log_activity("Admin gagal mengubah artikel: CSRF token tidak valid", ['id_artikel' => $id_artikel, 'admin_username' => $_SESSION['username']]); // LOG ACTIVITY
        
        header("Location: ubah.php?id=" . $id_artikel);
        exit();
    }

    $data_update = [
        'id' => $id_artikel,
        'penulis' => trim($_POST['penulis']),
        'judul' => trim($_POST['judul']),
        'kutipan' => trim($_POST['kutipan']),
        'isi' => trim($_POST['isi']),
        'kategori' => trim($_POST['kategori']),
        'gambarLama' => $artikel['gambar'] // Kirim nama gambar lama ke fungsi ubah
    ];

    if (ubah_artikel($data_update)) { // Menggunakan fungsi ubah_artikel dari functions.php
        set_flash_message('artikel_success', 'Artikel berhasil diperbarui!', 'success');
        header("Location: dasboard.php");
        exit();
    } else {
        // Pesan error sudah di-set oleh ubah_artikel() atau upload_gambar()
        $_SESSION['old_input_ubah'] = $_POST;
        log_activity("Admin gagal mengubah artikel: fungsi ubah_artikel mengembalikan false", ['id_artikel' => $id_artikel, 'admin_username' => $_SESSION['username'], 'data_input' => $data_update]); // LOG ACTIVITY

        header("Location: ubah.php?id=" . $id_artikel);
        exit();
    }
}

$csrf_token_ubah = $_SESSION['csrf_token'];
$old_input = $_SESSION['old_input_ubah'] ?? $artikel; // Jika ada old_input dari error, gunakan itu, jika tidak, gunakan data dari DB
unset($_SESSION['old_input_ubah']);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Ubah Artikel - <?= htmlspecialchars($artikel['judul']); ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { font-family: 'Open Sans', sans-serif; background-color: #f8f9fa; }
        .container-form { background: #fff; padding: 30px; border-radius: 10px; box-shadow: 0 0 15px rgba(0,0,0,0.1); margin-top: 20px; max-width: 800px;}
        .current-image { max-width: 200px; height: auto; margin-top: 10px; border-radius: 5px; border: 1px solid #ddd; }
    </style>
</head>
<body>
    <?php include_once '../nav.php'; ?>
    <div class="container container-form">
        <h2 class="mt-3 mb-4">Ubah Artikel: "<?= htmlspecialchars($artikel['judul']); ?>"</h2>

        <?= get_flash_message('artikel_error'); ?>
        <?= get_flash_message('upload_error'); ?>

        <form method="POST" action="ubah.php?id=<?= $id_artikel; ?>" enctype="multipart/form-data">
            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf_token_ubah); ?>">
            <div class="mb-3">
                <label for="penulis" class="form-label">Penulis <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="penulis" name="penulis" value="<?= htmlspecialchars($old_input['penulis'] ?? ''); ?>" required>
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
                <label for="gambar" class="form-label">Ganti Gambar (Opsional, Max 5MB)</label>
                <input type="file" class="form-control" id="gambar" name="gambar">
                <?php if (!empty($artikel['gambar'])): ?>
                    <p class="mt-2">Gambar saat ini: <br>
                    <img src="../img/<?= htmlspecialchars($artikel['gambar']); ?>" alt="Gambar saat ini" class="current-image"></p>
                <?php endif; ?>
            </div>
            <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Simpan Perubahan</button>
            <a href="dasboard.php" class="btn btn-secondary">Batal</a>
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>