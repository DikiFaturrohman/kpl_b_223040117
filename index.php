<?php
require 'functions.php';
$halaman = query("SELECT * FROM halaman");
$halaman1 = query("SELECT * FROM halaman");


?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>CAMPUS BLOG</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-aFq/bzH65dt+w6FI2ooMVUpc+21e0SRygnTpmBvdBgSdnuTN7QbdgL+OapgHtvPp" crossorigin="anonymous" />
    <!-- font -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
        href="https://fonts.googleapis.com/css2?family=Merriweather:wght@400;700;900&family=Open+Sans:wght@300;400&display=swap"
        rel="stylesheet" />
    <link rel="stylesheet" href="css/style copy.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
        integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />

</head>

<body>
    <!-- Navbar -->
    <?php include('nav.php'); ?>

    <!-- Home -->
    <div class="container">
        <div class="row">
        </div>

        <!-- Jumbotron -->
        <div class="row mt-5">
            <div class="jumbotron mt-5">
                <h1 class="display-3">Selamat Datang!</h1>
                <hr class="my-5">
                <h3>
                    Sebuah tempat berbagi inspirasi, wawasan, dan cerita menarik. Blog ini hadir untuk menyajikan
                    berbagai artikel informatif dan menghibur, mulai dari teknologi, gaya hidup, hingga pengalaman
                    sehari-hari yang penuh makna.
                    <br><br>
                    Kami percaya bahwa tulisan memiliki kekuatan untuk menghubungkan orang, membuka pikiran, dan
                    memberikan perspektif baru. Oleh karena itu, kami berkomitmen menghadirkan konten berkualitas yang
                    tidak hanya menghibur, tetapi juga memberikan nilai tambah bagi pembaca.
                </h3>
                <a class="btn btn-warning btn-lg mt-5" href="#" role="button">Mulai Menulis</a>
                <hr class="my-5">
            </div>
        </div>

        <!-- Blog Section -->
        <div class="col populer d-lg-flex justify-content-lg-between">
            <h2 class="text-center mt-5" id="blog">Blog</h2>
            <hr class="my-5">
        </div>

        <!-- Blog Cards -->
        <div class="row mt-4 internasional-populer">
            <div class="col">
                <div class="container">
                    <div class="row">
                        <?php $count = 0; ?>
                        <?php foreach ($halaman as $h) : ?>
                        <?php if ($h['id'] != 9 && $count <= 3) : ?>
                        <div class="col-md-3 mb-4">
                            <div class="card h-100">
                                <img src="img/<?= $h['gambar']; ?>" class="card-img-top" alt="" />
                                <div class="card-body d-flex flex-column">
                                    <h5 class="card-title"><?= $h['judul']; ?></h5>
                                    <h6 class="card-subtitle mb-2 text-muted">Card subtitle</h6>
                                    <p class="card-text"><?= $h['kutipan']; ?></p>
                                    <a href="ditel.php?id=<?= $h["id"] ?>"
                                        class="btn btn-warning mt-auto">Selengkapnya</a>
                                </div>
                            </div>
                        </div>
                        <?php $count++; ?>
                        <?php endif; ?>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer id="footerPakeS" style="background-color: rgb(0,39,60);" class="text-light py-3 mt-5">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6 text-center text-md-start">
                    <p class="mb-0">&copy; 2025 <span class="text-warning">CAMPUS BLOG</span>. All Rights Reserved.</p>
                </div>
                <div class="col-md-6 d-flex justify-content-md-end justify-content-center">
                    <a href="#" class="text-light mx-2"><i class="fab fa-facebook"></i></a>
                    <a href="#" class="text-light mx-2"><i class="fab fa-instagram"></i></a>
                </div>
            </div>
        </div>
    </footer>

    <!-- Scripts -->
    <script src="https://kit.fontawesome.com/YOUR_KIT_CODE.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-qKXV1j0HvMUeCBQ+QVp7JcfGl760yU08IQ+GpUo5hlbpg51QRiuqHAJz8+BrxE/N" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.js"></script>
    <script src="/tubes/js/script.js"></script>
</body>

</html>