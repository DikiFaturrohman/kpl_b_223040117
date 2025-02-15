<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Artikel</title>
    <link rel="stylesheet" href="css/ditel.css"> 
</head>
<body>

<?php
require 'functions.php';
$id = $_GET["id"];
$rows = query("SELECT * FROM halaman WHERE id = $id");
?>

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
                    <p class="card-text"><strong>Kutipan:</strong> <?= $row['kutipan']; ?></p>
                    <p class="card-text"><?= nl2br($row['isi']); ?></p>
                </div>
            </div>
            <?php endforeach; ?>
            <div class="text-center mt-4">
                <button class="btn btn-danger" onclick="window.print()">Download</button>
            </div>
        </div>
    </div>
</div>

</body>
</html>
