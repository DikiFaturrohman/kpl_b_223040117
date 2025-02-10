<?php
require '../functions.php';

$id = $_GET['id'];
$h = query("SELECT * FROM halaman WHERE id = $id")[0];

if (isset($_POST['ubah'])) {
    $result = ubah($_POST);

    if ($result) {
        echo "<script>alert('Data berhasil diubah');</script>";
        echo "<script>window.location.href = 'dashboard.php';</script>";
        exit;
    } else {
        echo "<script>alert('Terjadi kesalahan saat mengubah data');</script>";
    }
}
?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Ubah Data</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
</head>

<body>
    <div class="container">
        <h2 class="mt-5">Ubah Data</h2>
        <form method="POST" action="" enctype="multipart/form-data">
            <input type="hidden" name="id" value="<?= $h['id']; ?>">
            <input type="hidden" name="gambarLama" value="<?= $h['gambar']; ?>">
            <div class="mb-3">
                <label for="penulis" class="form-label">Penulis</label>
                <input type="text" class="form-control" id="penulis" name="penulis" required value="<?= $h['penulis']; ?>">
            </div>
            <div class="mb-3">
                <label for="judul" class="form-label">Judul</label>
                <input type="text" class="form-control" id="judul" name="judul" required value="<?= $h['judul']; ?>">
            </div>
            <div class="mb-3">
                <label for="kutipan" class="form-label">Kutipan</label>
                <input type="text" class="form-control" id="kutipan" name="kutipan" required value="<?= $h['kutipan']; ?>">
            </div>
            <div class="mb-3">
                <label for="isi" class="form-label">Isi</label>
                <textarea class="form-control" id="isi" name="isi" rows="3" required><?= $h['isi']; ?></textarea>
            </div>
            <div class="mb-3">
                <label for="kategori" class="form-label">Kategori</label>
                <input type="text" class="form-control" id="kategori" name="kategori" required value="<?= $h['kategori']; ?>">
            </div>
            <div class="mb-3">
                <label for="gambar" class="form-label">Gambar</label>
                <input type="file" class="form-control" id="gambar" name="gambar">
            </div>
            <button type="submit" class="btn btn-primary" name="ubah">Ubah Data</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguPnsJW0I+3Io2bmK+pXTy8nEDILZK1bZLqyXsLOaQ" crossorigin="anonymous">
    </script>
</body>

</html>