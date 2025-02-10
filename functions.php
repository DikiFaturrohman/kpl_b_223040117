<?php
// koneksi
$conn = mysqli_connect("localhost", "root", "", "news");

function query($query)
{
    global $conn;
    $result = mysqli_query($conn, $query);
    $rows = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $rows[] = $row;
    }
    return $rows;
}

function registrasi($data)
{
    global $conn;

    $username = strtolower(stripslashes($data["username"]));
    $email = mysqli_real_escape_string($conn, $data["email"]);
    $password = mysqli_real_escape_string($conn, $data["password"]);
    $password2 = mysqli_real_escape_string($conn, $data["password2"]);
    $role = isset($data["role"]) ? $data["role"] : ""; // Assign an empty string if the "role" key is not set

    // Cek username sudah ada atau belum
    $result = mysqli_query($conn, "SELECT username FROM users WHERE username = '$username'");
    if (mysqli_fetch_assoc($result)) {
        echo "<script>
        alert('Username sudah terdaftar!');
        </script>";
        return false;
    }

    // Cek konfirmasi password
    if ($password !== $password2) {
        echo "<script>
            alert('Konfirmasi password tidak sesuai!');
        </script>";
        return false;
    }

    // Enkripsi password
    $password = password_hash($password, PASSWORD_DEFAULT);

    // Tambahkan user baru ke database 
    mysqli_query($conn, "INSERT INTO users (username,email, password) VALUES ('$username','$email', '$password')");

    return mysqli_affected_rows($conn);
}

function login($email, $password)
{
    global $conn;
    $email = mysqli_real_escape_string($conn, $email);

    $query = "SELECT * FROM users WHERE email = '$email'";
    $result = mysqli_query($conn, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $hashedPassword = $row['password'];

        // Verifikasi password
        if (password_verify($password, $hashedPassword)) {
            // Password cocok, login berhasil
            return $row;
        } else {
            // Password tidak cocok
            return false;
        }
    } else {
        // Pengguna tidak ditemukan
        return false;
    }
}






function tambah($data)
{
    global $conn;

    $penulis = mysqli_real_escape_string($conn, $data['penulis']);
    $judul = mysqli_real_escape_string($conn, $data['judul']);
    $kutipan = mysqli_real_escape_string($conn, $data['kutipan']);
    $isi = mysqli_real_escape_string($conn, $data['isi']);
    $kategori = mysqli_real_escape_string($conn, $data['kategori']);

    // Upload gambar dan dapatkan nama file baru
    $gambar = upload();

    if (!$gambar) {
        return false; // Jika upload gambar gagal, hentikan fungsi tambah()
    }

    // Query SQL untuk memasukkan data ke dalam tabel
    $query = "INSERT INTO halaman (penulis, judul, kutipan, isi, gambar, tgl_isi, kategori) 
              VALUES ('$penulis', '$judul', '$kutipan', '$isi', '$gambar', NOW(), '$kategori')";

    mysqli_query($conn, $query);

    return mysqli_affected_rows($conn);
}


function upload()
{
    // Cek apakah input file 'gambar' ada
    if (!isset($_FILES['gambar'])) {
        var_dump($_FILES['gambar']);
        return "Gambar tidak ditemukan";
    }

    $namafile = $_FILES['gambar']['name'];
    $ukuranfile = $_FILES['gambar']['size'];
    $error = $_FILES['gambar']['error'];
    $tmpName = $_FILES['gambar']['tmp_name'];

    // Cek apakah tidak ada gambar yang diupload
    if ($error === 4) {
        return "Pilih gambar terlebih dahulu";
    }

    // Cek apakah yang diupload adalah gambar
    $ekstensigambarValid = ['jpg', 'jpeg', 'png'];
    $ekstensigambar = explode('.', $namafile);
    $ekstensigambar = strtolower(end($ekstensigambar));
    if (!in_array($ekstensigambar, $ekstensigambarValid)) {
        return "Yang Anda upload bukan gambar";
    }

    // Cek jika ukurannya terlalu besar
    if ($ukuranfile > 50000000) {
        return "Ukuran gambar terlalu besar";
    }

    // Lolos pengecekan, gambar siap diupload
    // Generate nama gambar baru
    $namafilebaru = uniqid();
    $namafilebaru .= '.';
    $namafilebaru .= $ekstensigambar;

    move_uploaded_file($tmpName, '../img/' . $namafilebaru);

    return $namafilebaru;
}
function hapus($id)
{
    global $conn;
    mysqli_query($conn, ("DELETE FROM halaman WHERE id = $id"));
    return mysqli_affected_rows($conn);
}
function ubah($data)
{
    global $conn;

    $id = $data['id'];
    $gambarLama = $data['gambarLama'];

    // Menghindari SQL Injection
    $penulis = $conn->real_escape_string($data['penulis']);
    $judul = $conn->real_escape_string($data['judul']);
    $kutipan = $conn->real_escape_string($data['kutipan']);
    $isi = $conn->real_escape_string($data['isi']);
    $kategori = $conn->real_escape_string($data['kategori']);

    // Memanggil fungsi upload untuk mengunggah gambar
    $gambarBaru = upload();
    if ($gambarBaru === "Gambar tidak ditemukan" || $gambarBaru === "Pilih gambar terlebih dahulu" || $gambarBaru === "Yang Anda upload bukan gambar" || $gambarBaru === "Ukuran gambar terlalu besar") {
        // Menggunakan gambar lama jika gagal mengunggah gambar baru
        $gambar = $gambarLama;
    } else {
        // Menghapus gambar lama jika berhasil mengunggah gambar baru
        if ($gambarLama != 'default.jpg') {
            unlink('../img/' . $gambarLama);
        }
        $gambar = $gambarBaru;
    }

    // Menyiapkan pernyataan SQL untuk memperbarui data di tabel halaman
    $sql = "UPDATE halaman SET penulis='$penulis', judul='$judul', kutipan='$kutipan', isi='$isi', kategori='$kategori', gambar='$gambar' WHERE id='$id'";

    if ($conn->query($sql) === TRUE) {
        return true;
    } else {
        return false;
    }

    // Menutup koneksi
    $conn->close();
}
