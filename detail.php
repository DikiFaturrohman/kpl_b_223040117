<?php
session_start();
require_once 'functions.php';

$id_artikel = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$id_artikel) {
    http_response_code(400); // Bad Request
    set_flash_message('index_error', 'ID Artikel tidak valid.', 'danger');
    header("Location: index.php");
    exit();
}

$stmt_artikel = query("SELECT * FROM halaman WHERE id = :id", [':id' => $id_artikel]);
$artikel = $stmt_artikel ? $stmt_artikel->fetch() : null;

if (!$artikel) {
    http_response_code(404);
    set_flash_message('index_error', 'Artikel yang Anda cari tidak ditemukan.', 'danger');
    header("Location: index.php");
    exit();
}

// Ambil Komentar
$stmt_komentar = query("SELECT * FROM komentar WHERE halaman_id = :halaman_id ORDER BY tanggal DESC", [':halaman_id' => $id_artikel]);
$komentars = $stmt_komentar ? $stmt_komentar->fetchAll() : [];

$username_komentar_form = isset($_SESSION['username']) ? htmlspecialchars($_SESSION['username']) : "Anonim";

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit_komentar'])) {
    if (!isset($_POST['csrf_token']) || !checkCSRFToken($_POST['csrf_token'])) {
        log_error("CSRF token tidak valid saat menambah komentar", ['artikel_id' => $id_artikel]);
        set_flash_message('comment_error', 'Sesi tidak valid atau telah kedaluwarsa. Komentar tidak ditambahkan.', 'danger');
        header("Location: detail.php?id=$id_artikel#comment-form");
        exit();
    }

    $halaman_id_post = filter_input(INPUT_POST, 'halaman_id', FILTER_VALIDATE_INT);
    $isi_komentar = trim($_POST["komentar"]);

    if ($halaman_id_post !== $id_artikel) {
         set_flash_message('comment_error', 'Terjadi kesalahan referensi artikel.', 'danger');
    } elseif (empty($isi_komentar)) {
        set_flash_message('comment_error', 'Komentar tidak boleh kosong.', 'danger');
    } else {
        $nama_pengirim_komentar = isset($_SESSION['username']) ? $_SESSION['username'] : 'Anonim'; // Gunakan username session jika login
        $sql_insert_komentar = "INSERT INTO komentar (halaman_id, nama, komentar, tanggal) VALUES (:halaman_id, :nama, :komentar, NOW())";
        $stmt_insert = query($sql_insert_komentar, [
            ':halaman_id' => $id_artikel,
            ':nama' => $nama_pengirim_komentar, // Nama dari session atau 'Anonim'
            ':komentar' => htmlspecialchars($isi_komentar) // Sanitasi sebelum simpan
        ]);

        if ($stmt_insert && $stmt_insert->rowCount() > 0) {
            set_flash_message('comment_success', 'Komentar berhasil ditambahkan.', 'success');
            log_activity("Komentar berhasil ditambahkan", ['artikel_id' => $id_artikel, 'komentator' => $nama_pengirim_komentar]);
        } else {
            set_flash_message('comment_error', 'Gagal menambahkan komentar karena masalah teknis.', 'danger');
            log_error("Gagal menambahkan komentar ke DB", ['artikel_id' => $id_artikel, 'komentator' => $nama_pengirim_komentar]);
        }
    }
    header("Location: detail.php?id=$id_artikel#comment-list"); // Redirect ke daftar komentar
    exit;
}
$csrf_token_detail = $_SESSION['csrf_token'];
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($artikel['judul']); ?> - CAMPUS BLOG</title>
    <meta name="description" content="<?= htmlspecialchars($artikel['kutipan'] ?: mb_strimwidth($artikel['isi'], 0, 150, "...")); ?>">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/detail.css"> {/* Pastikan path CSS `detail.css` atau ganti namanya */}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .article-content img { max-width: 100%; height: auto; border-radius: 0.25rem; margin-bottom: 1rem; }
        .comment-container { border-left: 3px solid #0d6efd; }
    </style>
</head>
<body>
    <?php include_once 'nav.php'; ?>

    <div class="container mt-4">
        <div class="row">
            <div class="col-lg-8 mx-auto">
                 <nav aria-label="breadcrumb" class="mb-3">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                        <?php if(!empty($artikel['kategori'])): ?>
                        <li class="breadcrumb-item"><a href="index.php?keyword=<?= urlencode($artikel['kategori']); ?>"><?= htmlspecialchars($artikel['kategori']); ?></a></li>
                        <?php endif; ?>
                        <li class="breadcrumb-item active" aria-current="page"><?= htmlspecialchars(mb_strimwidth($artikel['judul'],0,50,"...")); ?></li>
                    </ol>
                </nav>

                <article class="card shadow-sm mb-5">
                    <?php if (!empty($artikel['gambar'])): ?>
                    <img src="img/<?= htmlspecialchars($artikel['gambar']); ?>" class="card-img-top" alt="Gambar <?= htmlspecialchars($artikel['judul']); ?>" style="max-height: 400px; object-fit: cover;">
                    <?php endif; ?>
                    <div class="card-body p-4">
                        <h1 class="card-title display-5 mb-3"><?= htmlspecialchars($artikel['judul']); ?></h1>
                        <div class="text-muted mb-3">
                            <small><i class="fas fa-user"></i> Oleh: <?= htmlspecialchars($artikel['penulis']); ?></small> |
                            <small><i class="fas fa-calendar-alt"></i> <?= htmlspecialchars(date("l, d F Y H:i", strtotime($artikel['tgl_isi']))); ?></small>
                            <?php if(!empty($artikel['kategori'])): ?>
                            | <small><i class="fas fa-tag"></i> Kategori: <?= htmlspecialchars($artikel['kategori']); ?></small>
                            <?php endif; ?>
                        </div>
                        <hr>
                        <?php if(!empty($artikel['kutipan'])): ?>
                        <p class="lead fst-italic">"<?= htmlspecialchars($artikel['kutipan']); ?>"</p>
                        <hr>
                        <?php endif; ?>
                        <div class="article-content fs-5">
                            <?= nl2br(htmlspecialchars($artikel['isi'])); // Pertimbangkan menggunakan Markdown parser jika kontennya Markdown ?>
                        </div>
                    </div>
                </article>

                <?php if (isset($_SESSION['loggedin']) && ($_SESSION['username'] === $artikel['penulis'] || (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'))): ?>
                <div class="mb-4 p-3 border rounded bg-light">
                    <h5>Aksi Artikel</h5>
                    <a href="edit.php?id=<?= $artikel['id']; ?>" class="btn btn-sm btn-outline-warning me-2"><i class="fas fa-edit"></i> Edit Artikel Ini</a>
                    <form action="delete.php" method="POST" style="display:inline;" onsubmit="return confirm('Anda yakin ingin menghapus artikel \'<?= htmlspecialchars(addslashes($artikel["judul"]), ENT_QUOTES) ?>\' ini? Aksi ini tidak dapat diurungkan.');">
                        <input type="hidden" name="id" value="<?= $artikel["id"]; ?>">
                        <input type="hidden" name="csrf_token" value="<?= $csrf_token_detail; ?>">
                        <button type="submit" class="btn btn-sm btn-outline-danger"><i class="fas fa-trash"></i> Hapus Artikel Ini</button>
                    </form>
                </div>
                <?php endif; ?>


                <section class="comment-input-container mt-5 p-4 border rounded bg-light shadow-sm" id="comment-form">
                    <h4><i class="fas fa-comments"></i> Tinggalkan Komentar</h4>
                    <hr class="my-3">
                    <?= get_flash_message('comment_success'); ?>
                    <?= get_flash_message('comment_error'); ?>

                    <form id="comment-form-element" method="post" action="detail.php?id=<?= $id_artikel; ?>#comment-form">
                        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf_token_detail); ?>">
                        <input type="hidden" name="halaman_id" value="<?= $id_artikel; ?>">
                        <?php if (!isset($_SESSION['loggedin'])): ?>
                        <div class="mb-3">
                            <label for="nama_komentar" class="form-label">Nama Anda (Opsional):</label>
                            <input type="text" class="form-control" id="nama_komentar" name="nama_pengirim_manual" placeholder="Anonim" value="<?= htmlspecialchars($_POST['nama_pengirim_manual'] ?? ''); ?>">
                        </div>
                        <?php endif; ?>
                        <div class="mb-3">
                            <label for="isi_komentar" class="form-label">Komentar Anda <span class="text-danger">*</span>:</label>
                            <textarea class="form-control" id="isi_komentar" name="komentar" placeholder="Tulis komentar Anda di sini..." rows="4" required><?= htmlspecialchars($_POST['komentar'] ?? ''); ?></textarea>
                        </div>
                        <button type="submit" name="submit_komentar" id="submit-comment" class="btn btn-primary"><i class="fas fa-paper-plane"></i> Kirim Komentar</button>
                    </form>
                </section>