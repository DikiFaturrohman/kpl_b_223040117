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
    <title>CAMPUS NEWS</title>
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
    <!-- navbar -->
    <?php include('nav.php') ?>
    <!-- end navbar -->

    <!-- home -->

    <!-- Internasional News -->

    <section class="Internasional section-margin" id="internasional">
        <div class="container text-center">
            <div class="row">
                <div class="col">
                    <h1 class="heading text-center">Internasional News</h1>
                </div>
            </div>
            <div class="row mt-4">
                <div class="col populer d-lg-flex justify-content-lg-between">
                    <h3 class="heading text-center">Internasional News</h3>
                </div>
            </div>
            <div class="row mt-4 internasional-populer">
                <div class="col">
                    <!-- Card -->
                    <div class="container">
                        <div class="row">
                            <?php $count = 0; ?>
                            <?php foreach ($halaman as $h) : ?>
                            <?php if ($h['id'] != 9 && $count <= 3) : ?>
                            <div class="card mx-3 d-inline-block" style="width: 18rem;">
                                <img src="img/<?= $h['gambar']; ?>" class="card-img-top" alt="" />
                                <div class="card-body">
                                    <h5 class="card-title"><?= $h['judul']; ?></h5>
                                    <p class="card-text"><?= $h['kutipan']; ?></p>
                                    <a href="ditel.php?id=<?= $h["id"] ?>" class="btn btn-primary">Selengkapnya</a>
                                </div>
                            </div>
                            <?php $count++; ?>
                            <?php endif; ?>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <!-- <div class="card d-inline-block" style="width: 18rem">
            <img src="img/LulusanTerbaik.jpg" class="card-img-top" alt="..." />
            <div class="card-body">
              <h5 class="card-title"></h5>
              <p class="card-text">
                Jadi Wisudawan Doktor Termuda dan Terbaik Unair, Maria Lulus
                di Usia 24 Tahun.
              </p>
              <a href="#" class="btn btn-primary">Selengkapnya</a>
            </div>
          </div>
          <div class="card d-inline-block" style="width: 18rem">
            <img src="img/STEI.jpg" class="card-img-top" alt="..." />
            <div class="card-body">
              <h5 class="card-title"></h5>
              <p class="card-text">
                STEI ITB Borong 3 Juara Ajang Huawei ICT Competition Tingkat
                Dunia
              </p>
              <a href="#" class="btn btn-primary">Selengkapnya</a>
            </div>
          </div>
          <div class="card d-inline-block" style="width: 18rem">
            <img src="img/pancasila.jpeg" class="card-img-top" alt="..." />
            <div class="card-body">
              <h5 class="card-title"></h5>
              <p class="card-text">
                Alumni Universitas Pancasila Gelar Turnamen Golf untuk Anak
                Yatim.
              </p>
              <a href="#" class="btn btn-primary">Selengkapnya</a>
            </div>
          </div> -->
                    <!-- End Card -->

                    <!-- Nasional news -->
                    <section class="service section-margin" id="nasional">
                        <div class="container">
                            <div class="row">
                                <div class="col text-center">
                                    <h1 class="heading">Nasional News</h1>
                                    <p class="subheading"></p>
                                </div>
                            </div>
                            <div class="row mt-5">
                                <?php $count = 0; ?>
                                <?php foreach ($halaman1 as $h) : ?>
                                <?php if ($h['id'] >= 14 && $count <= 2) : ?>
                                <div class="col-lg-4">
                                    <div
                                        class="card-service rounded-3 d-flex justify-content-between align-items-center p-1">
                                        <div class="img service">
                                            <img src="img/<?= $h['gambar']; ?>" class="img-fluid" alt="" />
                                            <p class="mt-4">
                                                <?= $h['kutipan']; ?>
                                            </p>
                                            <a href="ditel.php?id=<?= $h["id"] ?>"
                                                class="btn btn-primary">Selengkapnya</a>
                                        </div>
                                    </div>
                                </div>
                                <?php $count++; ?>
                                <?php endif; ?>
                                <?php endforeach; ?>
                            </div>

                        </div>
                    </section>

                    <!-- Populer News -->

                    <section class="Tranding" id="populer">
                        <div class="container">
                            <div class="row">
                                <div class="col-text-center py-5">
                                    <h1 class="heading">Populer News</h1>
                                    <p class="subheading"></p>
                                </div>

                                <?php $count = 0; ?>
                                <?php foreach ($halaman1 as $h) : ?>
                                <?php if ($h['id'] >= 17 && $count <= 4) : ?>
                                <div class="col-lg-4">
                                    <div
                                        class="card-service rounded-3 d-flex justify-content-between align-items-center p-1">
                                        <div class="img service">
                                            <img src="img/<?= $h['gambar']; ?>" class="img-fluid" alt="" />
                                            <p class="mt-4">
                                                <?= $h['kutipan']; ?>
                                            </p>
                                            <a href="ditel.php?id=<?= $h["id"] ?>"
                                                class="btn btn-primary">Selengkapnya</a>
                                        </div>
                                    </div>
                                </div>
                                <?php $count++; ?>
                                <?php endif; ?>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </section>
                </div>
            </div>
        </div>
    </section>

    <!-- footer -->
    <section id="footerPakeS">
        <div class="container">
            <div class="row my-3">
                <div class="col-6">
                    <h3>contact person</h3>
                    <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Dolore beatae harum amet doloremque
                        ducimus architecto voluptatibus, numquam quas fuga doloribus est, necessitatibus, vitae omnis id
                        nam sequi assumenda libero error.</p>
                </div>
                <div class="footerContainer col-6 d-flex flex-row-reverse">

                    <div class="socialIcon">
                        <a href=""><i style="font-size: 50px !important;" class="fa-brands fa-instagram"><span
                                    style="display:none;">facebook</span></i></a>
                        <button class="btn btn-danger" onclick="window.print()">download</button>
                    </div>



                </div>
            </div>
        </div>

    </section>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-qKXV1j0HvMUeCBQ+QVp7JcfGl760yU08IQ+GpUo5hlbpg51QRiuqHAJz8+BrxE/N" crossorigin="anonymous">
    </script>

    <!-- Swiper JS -->
    <script src="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.js"></script>

    <script src="/tubes/js/script.js"></script>
</body>

</html>