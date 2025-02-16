<?php
session_start();
require_once 'functions.php';

$id = $_GET["id"];
$rows = query("SELECT * FROM halaman WHERE id = $id");
$komentars = query("SELECT * FROM komentar WHERE halaman_id = $id ORDER BY tanggal DESC");

// Ambil username dari sesi login
$username = isset($_SESSION['username']) ? $_SESSION['username'] : "Anonim";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $halaman_id = $_POST["halaman_id"];
    $komentar = $_POST["komentar"];

    // Validasi komentar tidak kosong
    if (!empty($komentar)) {
        $query = "INSERT INTO komentar (halaman_id, nama, komentar, tanggal) VALUES ('$halaman_id', '$username', '$komentar', NOW())";
        mysqli_query($conn, $query);
    }

    // Redirect agar form tidak mengirim ulang saat halaman di-refresh
    header("Location: ditel.php?id=$halaman_id");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Artikel</title>
    <link rel="stylesheet" href="css/ditel.css">
</head>
<body>

<div class="container">
    <div class="row">
        <div class="col-md-8 mx-auto">
            <?php foreach ($rows as $row) : ?>
            <div class="card">
                <a href="index.php" class="back-button">&larr; Kembali</a>
                <img src="img/<?= $row['gambar']; ?>" class="card-img-top" alt="Gambar Artikel">
                <div class="card-body">
                    <h1 class="card-title"><?= $row['judul']; ?></h1>
                    <hr>
                    <p class="card-text"><?= nl2br($row['isi']); ?></p>
                </div>
            </div>
            <?php endforeach; ?>

            <!-- Form Input Komentar -->
            <div class="comment-input-container mt-4">
                <form id="comment-form" method="post">
                    <input type="hidden" name="halaman_id" value="<?= $id; ?>">
                    <input type="text" class="comment-input" name="komentar" placeholder="Tambahkan komentar..." required>
                    <button type="submit" id="submit-comment" class="comment-submit">Kirim</button>
                </form>
            </div>

            <!-- Tampilan Daftar Komentar -->
            <div class="row mt-4">
                <div class="col-md-8 offset-md-2">
                    <h3>Komentar:</h3>
                    <div id="comment-list">
                        <?php if (empty($komentars)) : ?>
                            <p>Belum ada komentar.</p>
                        <?php else : ?>
                            <?php foreach ($komentars as $komentar) : ?>
                                <div class="comment-container">
                                    <div class="comment-text">
                                        <div class="comment-content">
                                            <strong class="comment-author"><?= htmlspecialchars($komentar['nama']); ?></strong><br>
                                            <?= htmlspecialchars($komentar['komentar']); ?>
                                        </div>
                                        <div class="comment-actions">
                                            <button><i class="fa fa-heart"></i></button>
                                            <button><i class="fa fa-reply"></i></button>
                                            <small><?= date("d M Y H:i", strtotime($komentar['tanggal'])); ?></small>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <div class="text-center mt-4">
                <button class="btn btn-danger" onclick="window.print()">Download</button>
            </div>
        </div>
    </div>
</div>

</body>
</html>
