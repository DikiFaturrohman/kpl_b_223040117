<?php
session_start(); // Harus paling atas
require_once '../functions.php'; // functions.php sudah panggil setCSRFToken()

// Pengecekan login dan role admin
if (!isset($_SESSION['loggedin']) || !isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    set_flash_message('login_error', 'Anda harus login sebagai admin untuk mengakses halaman ini.', 'danger');
    header("Location: ../login.php");
    exit();
}

log_activity("Admin dashboard diakses", ['admin_username' => $_SESSION['username']]);

// Jika ada keyword pencarian
if ($keyword_raw !== null && $keyword_raw !== '') {
    log_activity("Admin melakukan pencarian di dashboard", ['keyword' => $keyword_raw]);
}

// Jika query gagal (meskipun sudah ditangani di functions.php, ini contoh logging spesifik)
if ($stmt_halaman === false) {
    // log_error("Gagal mengambil data halaman dari database untuk admin dashboard"); // Redundan jika query() sudah log
    set_flash_message('dashboard_error', 'Gagal mengambil data halaman dari database.', 'danger');
}

$keyword_raw = isset($_GET['keyword']) ? trim($_GET['keyword']) : null;
$keyword_display = $keyword_raw ? htmlspecialchars($keyword_raw, ENT_QUOTES, 'UTF-8') : '';
$params = [];
$sql = "SELECT * FROM halaman";

if ($keyword_raw !== null && $keyword_raw !== '') {
    $sql .= " WHERE judul LIKE :keyword OR penulis LIKE :keyword OR kategori LIKE :keyword";
    $params[':keyword'] = "%" . $keyword_raw . "%";
}
$sql .= " ORDER BY tgl_isi DESC"; // Selalu urutkan, misalnya berdasarkan tanggal terbaru

$stmt_halaman = query($sql, $params);
$h_data = $stmt_halaman ? $stmt_halaman->fetchAll() : [];

if ($stmt_halaman === false) {
    set_flash_message('dashboard_error', 'Gagal mengambil data halaman dari database.', 'danger');
    // $h_data akan tetap array kosong, jadi tidak error di foreach
}

$csrf_token_dashboard = $_SESSION['csrf_token']; // Ambil token untuk form hapus
?>
<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard Admin - CAMPUS BLOG</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { font-family: 'Open Sans', sans-serif; background-color: #f8f9fa; }
        .container-admin { background: #fff; padding: 30px; border-radius: 10px; box-shadow: 0 0 15px rgba(0,0,0,0.1); margin-top: 20px;}
        .table img { width: 100px; height: auto; border-radius: 5px; object-fit: cover; }
        .action-buttons { display: flex; justify-content: center; gap: 5px; }
        .table th, .table td { vertical-align: middle; }
    </style>
</head>
<body>
    <?php include_once '../nav.php'; // Sertakan navigasi jika ada navigasi global atau khusus admin ?>

    <div class="container container-admin">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Dashboard Admin</h2>
            <div>
                <a href="tambah.php" class="btn btn-success"><i class="fas fa-plus"></i> Tambah Artikel</a>
                <button class="btn btn-info" onclick="window.print()"><i class="fas fa-print"></i> Cetak Halaman</button>
                {/* Tombol Logout sudah ada di nav.php */}
            </div>
        </div>

        <?= get_flash_message('dashboard_info'); ?>
        <?= get_flash_message('dashboard_error'); ?>
        <?= get_flash_message('artikel_success'); // Dari tambah/ubah/hapus ?>
        <?= get_flash_message('artikel_error'); // Dari tambah/ubah/hapus ?>


        <div class="mb-4">
            <form method="GET" class="d-flex" action="dasboard.php">
                <input type="text" class="form-control me-2" name="keyword" placeholder="Cari artikel berdasarkan judul, penulis, atau kategori..." value="<?= $keyword_display; ?>">
                <button class="btn btn-primary" type="submit"><i class="fas fa-search"></i> Cari</button>
                <?php if ($keyword_raw): ?>
                    <a href="dasboard.php" class="btn btn-secondary ms-2">Reset</a>
                <?php endif; ?>
            </form>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered table-striped text-center">
                <thead class="table-dark">
                    <tr>
                        <th>No</th>
                        <th>Gambar</th>
                        <th>Judul</th>
                        <th>Penulis</th>
                        <th>Kategori</th>
                        <th>Tanggal</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($h_data)): ?>
                        <?php $no = 1; ?>
                        <?php foreach ($h_data as $item): ?>
                        <tr>
                            <td><?= $no++; ?></td>
                            <td>
                                <?php if (!empty($item["gambar"])): ?>
                                    <img src="../img/<?= htmlspecialchars($item["gambar"]); ?>" alt="Gambar <?= htmlspecialchars($item["judul"]); ?>">
                                <?php else: ?>
                                    <small class="text-muted">Tanpa Gambar</small>
                                <?php endif; ?>
                            </td>
                            <td><?= htmlspecialchars($item["judul"]); ?></td>
                            <td><?= htmlspecialchars($item["penulis"]); ?></td>
                            <td><?= htmlspecialchars($item["kategori"]); ?></td>
                            <td><?= htmlspecialchars(date("d M Y, H:i", strtotime($item["tgl_isi"]))); ?></td>
                            <td>
                                <div class="action-buttons">
                                    <a href="ubah.php?id=<?= $item["id"]; ?>" class="btn btn-warning btn-sm" title="Ubah"><i class="fas fa-edit"></i></a>
                                    <form action="hapus.php" method="POST" style="display:inline;" onsubmit="return confirm('Yakin ingin menghapus artikel \'<?= htmlspecialchars(addslashes($item["judul"]), ENT_QUOTES) ?>\' ini?');">
                                        <input type="hidden" name="id" value="<?= $item["id"]; ?>">
                                        <input type="hidden" name="csrf_token" value="<?= $csrf_token_dashboard; ?>">
                                        <button type="submit" class="btn btn-danger btn-sm" title="Hapus"><i class="fas fa-trash"></i></button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                    <tr>
                        <td colspan="7">
                            Tidak ada data ditemukan
                            <?= ($keyword_raw !== null && $keyword_raw !== '') ? ' untuk kata kunci "<strong>' . $keyword_display . '</strong>"' : '.'; ?>
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        {/* Implementasi Paginasi jika diperlukan */}
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>