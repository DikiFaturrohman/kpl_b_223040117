<?php


require 'db_connect.php'; // Koneksi PDO

function generateCSRFToken()
{
    return bin2hex(random_bytes(32));
}

function setCSRFToken()
{
    if (!isset($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = generateCSRFToken();
    }
}

function checkCSRFToken($token)
{
    if (!isset($_SESSION['csrf_token']) || $token !== $_SESSION['csrf_token']) {
        return false; // Token tidak valid
    }
    return true; // Token valid
}

function query($query, $params = [])
{
    global $conn;
    try {
        $stmt = $conn->prepare($query);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("PDO Exception in query function: " . $e->getMessage());
        return false; // or handle the error as appropriate
    }
}

function registrasi($data)
{
    global $conn;

    $username = strtolower(stripslashes($data["username"]));
    $email = filter_var($data["email"], FILTER_VALIDATE_EMAIL);

    if ($email === false) {
        echo "<script>alert('Format email tidak valid!');</script>";
        return false;
    }

    $password = $data["password"];
    $password2 = $data["password2"];

    if ($password !== $password2) {
        echo "<script>alert('Konfirmasi password tidak sesuai!');</script>";
        return false;
    }

    $password = password_hash($password, PASSWORD_DEFAULT);
    try {
        $stmt = $conn->prepare("SELECT username FROM users WHERE username = :username");
        $stmt->execute([':username' => $username]);

        if ($stmt->fetch()) {
            echo "<script>alert('Username sudah terdaftar!');</script>";
            return false;
        }

        $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (:username, :email, :password)");
        $stmt->execute([':username' => $username, ':email' => $email, ':password' => $password]);

        return true; // Berhasil registrasi
    } catch (PDOException $e) {
        error_log("PDO Exception in registrasi function: " . $e->getMessage());
        echo "<script>alert('Error saat registrasi: " . $e->getMessage() . "');</script>";
        return false;
    }
}

function login($email, $password)
{
    global $conn;

    try {
        $stmt = $conn->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->execute([':email' => $email]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row && password_verify($password, $row['password'])) {
            return $row;
        } else {
            return false;
        }
    } catch (PDOException $e) {
        error_log("PDO Exception in login function: " . $e->getMessage());
        return false;
    }
}

function tambah($data)
{
    global $conn;

    $penulis = htmlspecialchars($data['penulis']);
    $judul = htmlspecialchars($data['judul']);
    $kutipan = htmlspecialchars($data['kutipan']);
    $isi = htmlspecialchars($data['isi']);
    $kategori = htmlspecialchars($data['kategori']);

    $gambar = upload();
    if (!$gambar) {
        return false;
    }

    try {
        $stmt = $conn->prepare("INSERT INTO halaman (penulis, judul, kutipan, isi, gambar, tgl_isi, kategori) 
                               VALUES (:penulis, :judul, :kutipan, :isi, :gambar, NOW(), :kategori)");

        $stmt->execute([
            ':penulis' => $penulis,
            ':judul' => $judul,
            ':kutipan' => $kutipan,
            ':isi' => $isi,
            ':gambar' => $gambar,
            ':kategori' => $kategori
        ]);

        return true;
    } catch (PDOException $e) {
        error_log("PDO Exception in tambah function: " . $e->getMessage());
        echo "<script>alert('Error saat menambahkan data: " . $e->getMessage() . "');</script>";
        return false;
    }
}

function upload()
{
    if (!isset($_FILES['gambar']) || $_FILES['gambar']['error'] === UPLOAD_ERR_NO_FILE) {
        echo "<script>alert('Pilih gambar terlebih dahulu');</script>";
        return false;
    }

    $namafile = $_FILES['gambar']['name'];
    $ukuranfile = $_FILES['gambar']['size'];
    $error = $_FILES['gambar']['error'];
    $tmpName = $_FILES['gambar']['tmp_name'];

    $ekstensigambarValid = ['jpg', 'jpeg', 'png'];
    $ekstensigambar = strtolower(pathinfo($namafile, PATHINFO_EXTENSION));

    if (!in_array($ekstensigambar, $ekstensigambarValid)) {
        echo "<script>alert('Yang Anda upload bukan gambar');</script>";
        return false;
    }

    if ($ukuranfile > 5000000) {
        echo "<script>alert('Ukuran gambar terlalu besar');</script>";
        return false;
    }

    $namafilebaru = uniqid() . '.' . $ekstensigambar;
    $targetPath = '../img/' . $namafilebaru;

    if (move_uploaded_file($tmpName, $targetPath)) {
        return $namafilebaru;
    } else {
        echo "<script>alert('Gagal mengupload gambar');</script>";
        return false;
    }
}

function hapus($id)
{
    global $conn;

    try {
        $stmt = $conn->prepare("DELETE FROM halaman WHERE id = :id");
        $stmt->execute([':id' => $id]);
        return true;
    } catch (PDOException $e) {
        error_log("PDO Exception in hapus function: " . $e->getMessage());
        echo "<script>alert('Error saat menghapus data: " . $e->getMessage() . "');</script>";
        return false;
    }
}

function ubah($data)
{
    global $conn;

    $id = $data['id'];
    $gambarLama = $data['gambarLama'];

    $penulis = htmlspecialchars($data['penulis']);
    $judul = htmlspecialchars($data['judul']);
    $kutipan = htmlspecialchars($data['kutipan']);
    $isi = htmlspecialchars($data['isi']);
    $kategori = htmlspecialchars($data['kategori']);

    $gambar = $_FILES['gambar']['name'] ? upload() : $gambarLama; //Only upload new image if there is one

    try {
        $stmt = $conn->prepare("UPDATE halaman SET penulis=:penulis, judul=:judul, kutipan=:kutipan, isi=:isi, kategori=:kategori, gambar=:gambar WHERE id=:id");
        $stmt->execute([
            ':penulis' => $penulis,
            ':judul' => $judul,
            ':kutipan' => $kutipan,
            ':isi' => $isi,
            ':kategori' => $kategori,
            ':gambar' => $gambar,
            ':id' => $id
        ]);

        return true;
    } catch (PDOException $e) {
        error_log("PDO Exception in ubah function: " . $e->getMessage());
        echo "<script>alert('Error saat mengubah data: " . $e->getMessage() . "');</script>";
        return false;
    }
}

setCSRFToken(); // Inisialisasi token CSRF