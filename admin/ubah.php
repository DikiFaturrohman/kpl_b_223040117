<?php
require '../functions.php';

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if ($id === false || $id === null) {
    die("ID tidak valid.");
}

$h = query("SELECT * FROM halaman WHERE id = :id", [':id' => $id])[0];
if (!$h) {
    die("Data tidak ditemukan.");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!checkCSRFToken($_POST['csrf_token'])) {
        die("CSRF token tidak valid.");
    }

    $_POST['id'] = $id;  // Make sure the ID is passed for the update
    if (ubah($_POST)) {
        echo "<script>alert('Data berhasil diubah'); window.location.href='dashboard.php';</script>";
    } else {
        echo "<script>alert('Terjadi kesalahan saat mengubah data');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Ubah Data</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container">
        <h2 class="mt-5">Ubah Data</h2>
        <form method="POST" action="" enctype="multipart/form-data">
            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
            <input type="hidden" name="id" value="<?= $h['id']; ?>">
            <input type="hidden" name="gambarLama" value="<?= $h['gambar']; ?>">
            <div class="mb-3">
                <label for="penulis" class="form-label">Penulis</label>
                <input type="text" class="form-control" id="penulis" name="penulis" required
                    value="<?= htmlspecialchars($h['penulis']); ?>">
            </div>
            <div class="mb-3">
                <label for="judul" class="form-label">Judul</label>
                <input type="text" class="form-control" id="judul" name="judul" required
                    value="<?= htmlspecialchars($h['judul']); ?>">
            </div>
            <div class="mb-3">
                <label for="kutipan" class="form-label">Kutipan</label>
                <input type="text" class="form-control" id="kutipan" name="kutipan" required
                    value="<?= htmlspecialchars($h['kutipan']); ?>">
            </div>
            <div class="mb-3">
                <label for="isi" class="form-label">Isi</label>
                <textarea class="form-control" id="isi" name="isi" rows="3"
                    required><?= htmlspecialchars($h['isi']); ?></textarea>
            </div>
            <div class="mb-3">
                <label for="kategori" class="form-label">Kategori</label>
                <input type="text" class="form-control" id="kategori" name="kategori" required
                    value="<?= htmlspecialchars($h['kategori']); ?>">
            </div>
            <div class="mb-3">
                <label for="gambar" class="form-label">Gambar</label>
                <input type="file" class="form-control" id="gambar" name="gambar">
            </div>
            <button type="submit" class="btn btn-primary" name="ubah">Ubah Data</button>
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>