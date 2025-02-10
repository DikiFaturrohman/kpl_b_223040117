<?php
session_start();
require '../functions.php';
$halaman = query("SELECT * FROM halaman");

// Check if a keyword is provided in the URL
if (isset($_GET['keyword'])) {
    $keyword = $_GET['keyword'];
    $query = "SELECT * FROM halaman WHERE judul LIKE '%$keyword%' OR penulis LIKE '%$keyword%' OR kategori LIKE '%$keyword%'";
} else {
    $query = "SELECT * FROM halaman";
}

// Execute the query to retrieve data
$h = query($query);

?>
<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>CAMPUS NEWS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
</head>

<body>


    <div class="container mt-3 py-5">
        <div class="row">
            <div class="col-md-6 mb-3">
                <h2>tambah</h2>
                <a href="tambah.php" class="btn btn-success">tambah data!</a>
            </div>
            <div class="col-md-6">
                <button class="btn btn-danger" onclick="window.print()">download</button>
            </div>
            <div class="mb-3">
                <div class="col-md-6">
                    <form method="GET"
                        action="dashboard.php<?= isset($_GET['keyword']) ? '?keyword=' . $_GET['keyword'] : ''; ?>">

                        <div class="input-group">
                            <input type="text" class="form-control" name="keyword" placeholder="Search...">
                            <button class="btn btn-primary" type="submit">Search</button>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>no</th>
                    <th>penulis</th>
                    <th>judul</th>
                    <th>kutipan</th>
                    <th>isi</th>
                    <th>gammbar</th>
                    <th>tgl</th>
                    <th>kategori</th>
                    <th>aksi</th>
                </tr>
            </thead>
            <!-- ... -->
            <tbody>
                <?php $pengulangan = 1; ?>
                <?php foreach ($h as $item) : ?>
                <tr>
                    <th scope="row"><?= $pengulangan ?></th>
                    <td><img src="../img/<?= $item["gambar"]; ?>" alt="foto" style="width: 150px;"></td>
                    <td><?= $item["penulis"]; ?></td>
                    <td><?= $item["judul"]; ?></td>
                    <td><?= $item["kutipan"]; ?></td>
                    <td><?= $item["isi"]; ?></td>
                    <td><?= $item["tgl_isi"]; ?></td>
                    <td><?= $item["kategori"]; ?></td>
                    <td>
                        <a class="btn btn-warning" href="ubah.php?id=<?= $item["id"]; ?>">ubah</a> <span>
                            |
                        </span>
                        <a class="btn btn-danger" href="hapus.php?id=<?= $item["id"]; ?>"
                            onclick="return confirm('yakin?')">hapus</a>
                    </td>
                </tr>
                <?php
                    $pengulangan++;
                endforeach ?>
            </tbody>
            <!-- ... -->

        </table>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous">
    </script>
</body>

</html>