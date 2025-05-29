<?php
session_start(); // Jika nav.php atau fitur lain butuh sesi
require_once 'functions.php';
// Tidak ada logika PHP khusus untuk halaman statis ini, kecuali jika Anda ingin mengambil data dinamis.
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tentang Kami - CAMPUS BLOG</title>
    <meta name="description" content="Pelajari lebih lanjut tentang CAMPUS BLOG, misi kami, dan tim di baliknya.">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css"> 
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { padding-top: 80px; /* Sesuaikan dengan tinggi navbar Anda */ }
        .page-header {
            background: #f8f9fa; /* Warna latar belakang header */
            padding: 4rem 0;
            margin-bottom: 3rem;
            text-align: center;
        }
        .page-header h1 {
            font-weight: 700;
            color: #343a40;
        }
        .content-section {
            padding: 2rem 0;
        }
        .team-member img {
            width: 150px;
            height: 150px;
            object-fit: cover;
            border-radius: 50%;
            margin-bottom: 1rem;
            border: 3px solid #ffc107; /* Warna aksen */
        }
    </style>
</head>
<body>
    <?php include_once 'nav.php'; ?>

    <header class="page-header">
        <div class="container">
            <h1>Tentang <span class="text-warning">CAMPUS BLOG</span></h1>
            <p class="lead">Mengenal lebih dekat platform berbagi inspirasi dan pengetahuan.</p>
        </div>
    </header>

    <main class="container content-section">
        <section class="mb-5">
            <div class="row">
                <div class="col-lg-8 mx-auto">
                    <h2 class="mb-3 display-6">Misi Kami</h2>
                    <p class="fs-5">Di CAMPUS BLOG, misi kami adalah menyediakan sebuah platform yang mudah diakses dan digunakan bagi para mahasiswa, dosen, dan alumni untuk berbagi ide, hasil penelitian, pengalaman, serta wawasan kreatif. Kami percaya bahwa setiap individu memiliki cerita dan pengetahuan berharga yang dapat menginspirasi dan mencerahkan orang lain.</p>
                    <p class="fs-5">Kami berkomitmen untuk membangun komunitas yang suportif, di mana diskusi yang konstruktif dan pertukaran ilmu pengetahuan dapat berkembang pesat.</p>
                </div>
            </div>
        </section>

        <section class="mb-5">
            <div class="row">
                <div class="col-lg-8 mx-auto">
                    <h2 class="mb-4 display-6">Apa yang Kami Tawarkan?</h2>
                    <ul class="list-unstyled fs-5">
                        <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i>Platform untuk publikasi artikel yang mudah.</li>
                        <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i>Jangkauan pembaca yang luas dari berbagai kalangan akademis.</li>
                        <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i>Fitur interaksi melalui komentar untuk diskusi yang lebih mendalam.</li>
                        <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i>Kategori yang beragam untuk menampung berbagai topik tulisan.</li>
                    </ul>
                </div>
            </div>
        </section>

        <section>
             <h2 class="mb-4 text-center display-6">Tim Kami (Contoh)</h2>
            <div class="row text-center">
                <div class="col-md-4 mb-4">
                    <img src="img/team/member1.jpg" alt="Anggota Tim 1" class="team-member"> 
                    <h4>Nama Anggota 1</h4>
                    <p class="text-muted">Founder & Lead Developer</p>
                </div>
                <div class="col-md-4 mb-4">
                    <img src="img/team/member2.jpg" alt="Anggota Tim 2" class="team-member">
                    <h4>Nama Anggota 2</h4>
                    <p class="text-muted">Content Strategist</p>
                </div>
                <div class="col-md-4 mb-4">
                    <img src="img/team/member3.jpg" alt="Anggota Tim 3" class="team-member">
                    <h4>Nama Anggota 3</h4>
                    <p class="text-muted">Community Manager</p>
                </div>
            </div>
        </section>
    </main>

    <?php include_once 'footer.php'; // Jika Anda punya file footer.php terpisah ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>