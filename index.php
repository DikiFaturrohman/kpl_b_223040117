<?php
session_start(); // Diperlukan jika nav.php atau fitur lain di halaman ini butuh sesi
require_once 'functions.php'; // functions.php sudah panggil setCSRFToken()

$keyword_raw = isset($_GET['keyword']) ? trim($_GET['keyword']) : null;
$params = [];
// Query dasar untuk mengambil artikel yang mungkin ingin ditampilkan (misal, yang sudah dipublikasi)
// Jika ada kolom status, tambahkan: " WHERE status = 'published'"
$sql = "SELECT * FROM halaman";

if ($keyword_raw !== null && $keyword_raw !== '') {
    // Jika sudah ada klausa WHERE (misal untuk status), gunakan AND, jika tidak, gunakan WHERE
    // if (strpos(strtolower($sql), 'where') === false) {
    //     $sql .= " WHERE (judul LIKE :keyword OR penulis LIKE :keyword OR kategori LIKE :keyword OR isi LIKE :keyword)";
    // } else {
    //     $sql .= " AND (judul LIKE :keyword OR penulis LIKE :keyword OR kategori LIKE :keyword OR isi LIKE :keyword)";
    // }
    // Untuk saat ini, asumsikan pencarian adalah filter utama jika ada
    log_activity("Pencarian artikel di halaman utama", ['keyword' => $keyword_raw]); // LOG ACTIVITY
    $sql .= " WHERE (judul LIKE :keyword OR penulis LIKE :keyword OR kategori LIKE :keyword OR isi LIKE :keyword)";
    $params[':keyword'] = "%" . $keyword_raw . "%";
}
$sql .= " ORDER BY tgl_isi DESC"; // Urutkan berdasarkan tanggal terbaru

// Pertimbangkan paginasi di sini jika artikel sangat banyak
// Misal: $limit = 10; $offset = (isset($_GET['page']) && is_numeric($_GET['page'])) ? ($_GET['page'] - 1) * $limit : 0;
// $sql .= " LIMIT :limit OFFSET :offset";
// $params[':limit'] = $limit;
// $params[':offset'] = $offset;

$stmt_halaman = query($sql, $params);
$daftar_halaman = $stmt_halaman ? $stmt_halaman->fetchAll() : [];

if ($stmt_halaman === false) {
    set_flash_message('index_error', 'Gagal mengambil data artikel dari database.', 'danger');
}

// Ambil CSRF token untuk form pencarian di nav.php (jika diperlukan di sana, meski GET tidak wajib CSRF)
$csrf_token_nav = $_SESSION['csrf_token'] ?? '';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="description" content="Selamat datang di CAMPUS BLOG, tempat berbagi inspirasi, wawasan, dan cerita menarik dari berbagai penulis.">
    <title>CAMPUS BLOG - Inspirasi Setiap Hari</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Merriweather:wght@400;700;900&family=Open+Sans:wght@300;400&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="css/style.css" /> {/* Pastikan file ini ada dan path-nya benar */}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <style>
        .card-img-top-custom {
            width: 100%;
            height: 200px; /* Atur tinggi gambar agar seragam */
            object-fit: cover; /* Pastikan gambar terpotong dengan baik, bukan distorsi */
        }
        .card-title a {
            color: inherit; /* Warna judul mengikuti warna teks card */
            text-decoration: none;
        }
        .card-title a:hover {
            color: #0d6efd; /* Warna link Bootstrap default saat hover */
        }
    </style>
</head>
<body>
    <?php include_once 'nav.php'; ?>

    <div class="container mt-4">
        <?= get_flash_message('index_error'); ?>
        <?= get_flash_message('login_info'); // Dari registrasi atau logout ?>
        <?= get_flash_message('artikel_user_success'); // Dari write.php, edit.php, delete.php user ?>
        <?= get_flash_message('artikel_user_error'); ?>


        <div class="row mt-5 mb-5">
            <div class="col-md-12 text-center p-5 rounded bg-light">
                <h1 class="display-4 fw-bold">Selamat Datang di <span class="text-warning">CAMPUS BLOG</span>!</h1>
                <p class="lead fs-4 my-4">
                    Sebuah tempat berbagi inspirasi, wawasan, dan cerita menarik. Blog ini hadir untuk menyajikan
                    berbagai artikel informatif dan menghibur dari para penulis berbakat.
                </p>
                <?php if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true && (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin')) : ?>
                    <a class="btn btn-warning btn-lg mt-3" href="write.php" role="button"><i class="fas fa-pen-to-square"></i> Mulai Menulis Artikel</a>
                <?php elseif (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] === false): ?>
                     <a class="btn btn-warning btn-lg mt-3" href="register.php" role="button">Gabung dan Mulai Menulis</a>
                <?php endif; ?>
            </div>
        </div>

        <div class="row mb-3">
            <div class="col">
                <h2 class="text-center" id="blog">Artikel Terbaru</h2>
                <hr class="my-4">
            </div>
        </div>

        <div class="row">
            <?php if (!empty($daftar_halaman)): ?>
                <?php foreach ($daftar_halaman as $h_item): ?>
                    <?php
                    // Filter aneh $h_item['id'] != 9 dihapus.
                    // Jika Anda butuh filter tertentu, lakukan di query SQL.
                    ?>
                    <div class="col-lg-4 col-md-6 mb-4 d-flex align-items-stretch">
                        <div class="card h-100 shadow-sm">
                            <a href="detail.php?id=<?= $h_item["id"] ?>">
                                <?php if (!empty($h_item['gambar'])): ?>
                                    <img src="img/<?= htmlspecialchars($h_item['gambar']); ?>" class="card-img-top card-img-top-custom" alt="<?= htmlspecialchars($h_item['judul']); ?>" />
                                <?php else: ?>
                                    <img src="img/placeholder.png" class="card-img-top card-img-top-custom" alt="Tidak ada gambar" /> 
                                <?php endif; ?>
                            </a>
                            <div class="card-body d-flex flex-column">
                                <h5 class="card-title">
                                    <a href="detail.php?id=<?= $h_item["id"] ?>"><?= htmlspecialchars($h_item['judul']); ?></a>
                                </h5>
                                <h6 class="card-subtitle mb-2 text-muted small">
                                    <i class="fas fa-user"></i> <?= htmlspecialchars($h_item['penulis']); ?> |
                                    <i class="fas fa-calendar-alt"></i> <?= htmlspecialchars(date("d M Y", strtotime($h_item['tgl_isi']))); ?>
                                    <?php if (!empty($h_item['kategori'])): ?>
                                    | <i class="fas fa-tag"></i> <?= htmlspecialchars($h_item['kategori']); ?>
                                    <?php endif; ?>
                                </h6>
                                <p class="card-text flex-grow-1"><?= htmlspecialchars(mb_strimwidth($h_item['kutipan'] ?: $h_item['isi'], 0, 120, "...")); ?></p>
                                <a href="detail.php?id=<?= $h_item["id"] ?>" class="btn btn-outline-primary mt-auto align-self-start">Baca Selengkapnya <i class="fas fa-arrow-right"></i></a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col text-center">
                    <p class="fs-5 text-muted">
                        Belum ada artikel yang dipublikasikan
                        <?= ($keyword_raw !== null && $keyword_raw !== '') ? ' untuk kata kunci "<strong>' . htmlspecialchars($keyword_raw) . '</strong>"' : '.'; ?>
                    </p>
                    <?php if($stmt_halaman === false && empty($daftar_halaman)) echo "<p class='text-danger'>Gagal memuat artikel saat ini.</p>"; ?>
                </div>
            <?php endif; ?>
        </div>
        
    </div>

    <footer id="footerPakeS" class="text-light py-4 mt-5 w-100" style="background-color: #00273c;">
        <div class="container">
            <div class="row align-items-center text-center text-md-start">
                <div class="col-md-6 mb-2 mb-md-0">
                    <p class="mb-0">&copy; <?= date("Y"); ?> <span class="text-warning">CAMPUS BLOG</span>. All Rights Reserved.</p>
                </div>
                <div class="col-md-6 d-flex justify-content-center justify-content-md-end">
                    <a href="#" class="text-light mx-2 fs-5" aria-label="Facebook"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" class="text-light mx-2 fs-5" aria-label="Instagram"><i class="fab fa-instagram"></i></a>
                    <a href="#" class="text-light mx-2 fs-5" aria-label="Twitter"><i class="fab fa-twitter"></i></a>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="js/script.js"></script> 
</body>
</html>