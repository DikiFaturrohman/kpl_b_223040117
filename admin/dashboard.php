<?php
session_start();
require '../functions.php';

// Ambil data halaman dari database
$h = [];
if (isset($_GET['keyword'])) {
    $keyword = $_GET['keyword'];
    $query = "SELECT * FROM halaman WHERE judul LIKE '%$keyword%' OR penulis LIKE '%$keyword%' OR kategori LIKE '%$keyword%'";
} else {
    $query = "SELECT * FROM halaman";
}

$h = query($query) ?? []; // Pastikan variabel $h selalu berupa array
?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard Admin - CAMPUS NEWS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Open Sans', sans-serif;
            background-color: #f8f9fa;
        }
        .container {
            background: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .table img {
            width: 100px;
            height: auto;
            border-radius: 5px;
        }
    </style>
</head>

<body>
    <div class="container mt-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Dashboard Admin</h2>
            <div>
                <a href="tambah.php" class="btn btn-success">Tambah Data</a>
                <button class="btn btn-danger" onclick="window.print()">Download</button>
            </div>
        </div>

        <div class="mb-3">
            <form method="GET" class="d-flex" action="dashboard.php">
                <input type="text" class="form-control me-2" name="keyword" placeholder="Cari...">
                <button class="btn btn-primary" type="submit">Cari</button>
            </form>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered table-striped text-center">
                <thead class="table-dark">
                    <tr>
                        <th>No</th>
                        <th>Gambar</th>
                        <th>Penulis</th>
                        <th>Judul</th>
                        <th>Kutipan</th>
                        <th>Isi</th>
                        <th>Tanggal</th>
                        <th>Kategori</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($h)): ?>
                        <?php $no = 1; ?>
                        <?php foreach ($h as $item) : ?>
                            <tr>
                                <td><?= $no; ?></td>
                                <td><img src="../img/<?= $item["gambar"]; ?>" alt="Gambar"></td>
                                <td><?= $item["penulis"]; ?></td>
                                <td><?= $item["judul"]; ?></td>
                                <td><?= $item["kutipan"]; ?></td>
                                <td><?= substr($item["isi"], 0, 50) . '...'; ?></td>
                                <td><?= $item["tgl_isi"]; ?></td>
                                <td><?= $item["kategori"]; ?></td>
                                <td>
                                    <a href="ubah.php?id=<?= $item["id"]; ?>" class="btn btn-warning btn-sm">Ubah</a>
                                    <a href="hapus.php?id=<?= $item["id"]; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus?')">Hapus</a>
                                </td>
                            </tr>
                            <?php $no++; ?>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="9">Tidak ada data ditemukan</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
