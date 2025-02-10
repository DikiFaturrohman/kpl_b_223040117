<?php
require 'functions.php';
$id = $_GET["id"];
$rows = query("SELECT * FROM halaman WHERE id = $id");

?>

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
                    </div>
                    <?php $count++; ?>
                <?php endforeach; ?>
                <button class="btn btn-danger" onclick="window.print()">download</button>
            </div>
        </div>
    </div>
</div>