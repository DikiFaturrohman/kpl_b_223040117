<?php
session_start();
require_once 'functions.php';

// Pastikan pengguna sudah login
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    log_activity("Akses tidak sah ke view.php (Blog Saya): belum login"); // LOG ACTIVITY

    set_flash_message('login_info', 'Anda harus login untuk melihat halaman ini.', 'info');
    header("Location: login.php");
    exit();
}
// Admin tidak diarahkan dari sini, karena mereka punya dashboard sendiri.
// Halaman ini khusus untuk user melihat artikelnya.

$username_session = $_SESSION['username'];
log_activity("User mengakses halaman 'Blog Saya'", ['username' => $username_session]); // LOG ACTIVITY

// Ambil artikel yang ditulis oleh pengguna yang sedang login
$sql = "SELECT * FROM halaman WHERE penulis = :username ORDER BY tgl_isi DESC";
$stmt_halaman = query($sql, [':username' => $username_session]);
$daftar_artikel_pengguna = $stmt_halaman ? $stmt_halaman->fetchAll() : [];

if ($stmt_halaman === false) {
    set_flash_message('artikel_user_error', 'Gagal mengambil data artikel Anda dari database.', 'danger');
}

$csrf_token_view = $_SESSION['csrf_token']; // Untuk form hapus
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blog Saya - <?= htmlspecialchars($username_session); ?> - CAMPUS BLOG</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { font-family: 'Open Sans', sans-serif; background-color: #f8f9fa; }
        .container-view { background: #fff; padding: 30px; border-radius: 10px; box-shadow: 0 0 15px rgba(0,0,0,0.1); margin-top: 20px;}
        .table img { width: 80px; height: 60px; border-radius: 5px; object-fit: cover; }
        .action-buttons { display: flex; justify-content: start; gap: 8px; }
        .table th, .table td { vertical-align: middle; }
    </style>
</head>
<body>
    <?php include_once 'nav.php'; ?>

    <div class="container container-view">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2><i class="fas fa-newspaper"></i> Blog Saya (<?= htmlspecialchars($username_session); ?>)</h2>
            <a href="write.php" class="btn btn-success"><i class="fas fa-plus"></i> Tulis Artikel Baru</a>
        </div>

        <?= get_flash_message('artikel_user_success'); ?>
        <?= get_flash_message('artikel_user_error'); ?>
        <?= get_flash_message('login_info'); ?>


        <?php if (empty($daftar_artikel_pengguna) && $stmt_halaman !== false): ?>
            <div class="alert alert-info text-center">
                <h4 class="alert-heading"><i class="fas fa-info-circle"></i> Belum Ada Artikel</h4>
                <p>Anda belum menulis artikel apapun. Ayo mulai bagikan ide Anda!</p>
                <hr>
                <a href="write.php" class="btn btn-primary"><i class="fas fa-pen-to-square"></i> Mulai Menulis Sekarang</a>
            </div>
        <?php elseif (!empty($daftar_artikel_pengguna)): ?>
            <div class="table-responsive">
                <table class="table table-hover table-striped">
                    <thead class="table-light">
                        <tr>
                            <th>No.</th>
                            <th>Gambar</th>
                            <th>Judul</th>
                            <th>Kategori</th>
                            <th>Tanggal Publikasi</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1; ?>
                        <?php foreach ($daftar_artikel_pengguna as $artikel): ?>
                        <tr>
                            <td><?= $no++; ?></td>
                            <td>
                                <?php if (!empty($artikel["gambar"])): ?>
                                    <img src="img/<?= htmlspecialchars($artikel["gambar"]); ?>" alt="Gambar <?= htmlspecialchars($artikel["judul"]); ?>">
                                <?php else: ?>
                                    <small class="text-muted"><em>N/A</em></small>
                                <?php endif; ?>
                            </td>
                            <td>
                                <a href="detail.php?id=<?= $artikel['id']; ?>" title="Lihat Artikel: <?= htmlspecialchars($artikel['judul']); ?>">
                                    <?= htmlspecialchars($artikel["judul"]); ?>
                                </a>
                            </td>
                            <td><?= htmlspecialchars($artikel["kategori"] ?: '-'); ?></td>
                            <td><?= htmlspecialchars(date("d M Y, H:i", strtotime($artikel["tgl_isi"]))); ?></td>
                            <td class="text-center">
                                <div class="action-buttons">
                                    <a href="detail.php?id=<?= $artikel["id"]; ?>" class="btn btn-info btn-sm" title="Lihat"><i class="fas fa-eye"></i></a>
                                    <a href="edit.php?id=<?= $artikel["id"]; ?>" class="btn btn-warning btn-sm" title="Edit"><i class="fas fa-edit"></i></a>
                                    <form action="delete.php" method="POST" style="display:inline;" onsubmit="return confirm('Anda yakin ingin menghapus artikel \'<?= htmlspecialchars(addslashes($artikel["judul"]), ENT_QUOTES) ?>\' ini? Aksi ini tidak dapat diurungkan.');">
                                        <input type="hidden" name="id" value="<?= $artikel["id"]; ?>">
                                        <input type="hidden" name="csrf_token" value="<?= $csrf_token_view; ?>">
                                        <button type="submit" class="btn btn-danger btn-sm" title="Hapus"><i class="fas fa-trash"></i></button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>

    <footer class="text-center text-muted py-4 mt-5">
        <small>&copy; <?= date("Y"); ?> CAMPUS BLOG.</small>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>