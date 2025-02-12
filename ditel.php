<?php
require 'functions.php';
$id = $_GET["id"];
$rows = query("SELECT * FROM halaman WHERE id = $id");
?>

<div class="container mt-5">
    <div class="row">
        <div class="col-md-8 mx-auto">
            <?php foreach ($rows as $row) : ?>
            <div class="card h-100 shadow-sm">
                <img src="img/<?= $row['gambar']; ?>" class="card-img-top img-fluid rounded" alt="Gambar Artikel">
                <div class="card-body">
                    <h1 class="card-title text-center"><?= $row['judul']; ?></h1>
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