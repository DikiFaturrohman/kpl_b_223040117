<?php
require_once 'functions.php';
$id = $_GET["id"];
$rows = query("SELECT * FROM halaman WHERE id = $id");
$komentars = query("SELECT * FROM komentar WHERE halaman_id = $id ORDER BY tanggal DESC");
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $halaman_id = $_POST["halaman_id"];
    $komentar = $_POST["komentar"];

    // Validasi komentar
    if (!empty($komentar)) {
        $query = "INSERT INTO komentar (halaman_id, komentar, tanggal) VALUES ('$halaman_id', '$komentar', NOW())";
        mysqli_query($conn, $query);
    }

    // Redirect agar form tidak mengirim ulang saat halaman di-refresh
    header("Location: ditel.php?id=$halaman_id");
    exit;
}

?>
<form id="komentar-form" method="post">
<div class="row mt-4 internasional-populer">
    <div class="col">
        <!-- Card -->
        <div class="container">
            <div class="row">
                <?php $count = 0; ?>
                <?php foreach ($rows as $row) : ?>
                    <div class="card mx-3 d-inline-block" style="width: 18rem;">
                        <img src="img/<?= $row['gambar']; ?>" class="card-img-top" alt="" />
                        <div class="card-body">
                            <h1 class="card-title"><?= $row['judul']; ?></h1>
                            <p class="card-text"><?= $row['kutipan']; ?></p>
                            <p class="card-text"><?= $row['isi']; ?></p>
                        </div>
                        <!-- Input Komentar -->
<div class="comment-input-container">
    <form id="comment-form" method="post" action="" style="display: flex; flex-grow: 1;">
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
                        <img src="img/avatar_default.png" alt="Avatar" class="comment-avatar">
                        <div class="comment-text">
                            <div class="comment-content">
                                <span class="comment-author"><?= $komentar['nama']; ?></span>
                                <?= $komentar['komentar']; ?>
                            </div>
                            <div class="comment-actions">
                                <button><i class="fa fa-heart"></i></button>
                                <button><i class="fa fa-reply"></i></button>
                                <small><?= $komentar['tanggal']; ?></small>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</div>
                    </div>
                    <?php $count++; ?>
                <?php endforeach; ?>
                <button class="btn btn-danger" onclick="window.print()">download</button>
            </div>
        </div>
    </div>
</div>