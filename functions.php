<?php


// require 'db_connect.php'; // Koneksi PDO

// function generateCSRFToken()
// {
//     return bin2hex(random_bytes(32));
// }

// function setCSRFToken()
// {
//     if (!isset($_SESSION['csrf_token'])) {
//         $_SESSION['csrf_token'] = generateCSRFToken();
//     }
// }

// function checkCSRFToken($token)
// {
//     if (!isset($_SESSION['csrf_token']) || $token !== $_SESSION['csrf_token']) {
//         return false; // Token tidak valid
//     }
//     return true; // Token valid
// }

// function query($query, $params = [])
// {
//     global $conn;
//     try {
//         $stmt = $conn->prepare($query);
//         $stmt->execute($params);
//         return $stmt->fetchAll(PDO::FETCH_ASSOC);
//     } catch (PDOException $e) {
//         error_log("PDO Exception in query function: " . $e->getMessage());
//         return false; // or handle the error as appropriate
//     }
// }

// function registrasi($data)
// {
//     global $conn;

//     $username = strtolower(stripslashes($data["username"]));
//     $email = filter_var($data["email"], FILTER_VALIDATE_EMAIL);

//     if ($email === false) {
//         echo "<script>alert('Format email tidak valid!');</script>";
//         return false;
//     }

//     $password = $data["password"];
//     $password2 = $data["password2"];

//     if ($password !== $password2) {
//         echo "<script>alert('Konfirmasi password tidak sesuai!');</script>";
//         return false;
//     }

//     $password = password_hash($password, PASSWORD_DEFAULT);
//     try {
//         $stmt = $conn->prepare("SELECT username FROM users WHERE username = :username");
//         $stmt->execute([':username' => $username]);

//         if ($stmt->fetch()) {
//             echo "<script>alert('Username sudah terdaftar!');</script>";
//             return false;
//         }

//         $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (:username, :email, :password)");
//         $stmt->execute([':username' => $username, ':email' => $email, ':password' => $password]);

//         return true; // Berhasil registrasi
//     } catch (PDOException $e) {
//         error_log("PDO Exception in registrasi function: " . $e->getMessage());
//         echo "<script>alert('Error saat registrasi: " . $e->getMessage() . "');</script>";
//         return false;
//     }
// }

// function login($email, $password)
// {
//     global $conn;

//     try {
//         $stmt = $conn->prepare("SELECT * FROM users WHERE email = :email");
//         $stmt->execute([':email' => $email]);
//         $row = $stmt->fetch(PDO::FETCH_ASSOC);

//         if ($row && password_verify($password, $row['password'])) {
//             return $row;
//         } else {
//             return false;
//         }
//     } catch (PDOException $e) {
//         error_log("PDO Exception in login function: " . $e->getMessage());
//         return false;
//     }
// }

// function tambah($data)
// {
//     global $conn;

//     $penulis = htmlspecialchars($data['penulis']);
//     $judul = htmlspecialchars($data['judul']);
//     $kutipan = htmlspecialchars($data['kutipan']);
//     $isi = htmlspecialchars($data['isi']);
//     $kategori = htmlspecialchars($data['kategori']);

//     $gambar = upload();
//     if (!$gambar) {
//         return false;
//     }

//     try {
//         $stmt = $conn->prepare("INSERT INTO halaman (penulis, judul, kutipan, isi, gambar, tgl_isi, kategori) 
//                                VALUES (:penulis, :judul, :kutipan, :isi, :gambar, NOW(), :kategori)");

//         $stmt->execute([
//             ':penulis' => $penulis,
//             ':judul' => $judul,
//             ':kutipan' => $kutipan,
//             ':isi' => $isi,
//             ':gambar' => $gambar,
//             ':kategori' => $kategori
//         ]);

//         return true;
//     } catch (PDOException $e) {
//         error_log("PDO Exception in tambah function: " . $e->getMessage());
//         echo "<script>alert('Error saat menambahkan data: " . $e->getMessage() . "');</script>";
//         return false;
//     }
// }

// function upload()
// {
//     if (!isset($_FILES['gambar']) || $_FILES['gambar']['error'] === UPLOAD_ERR_NO_FILE) {
//         echo "<script>alert('Pilih gambar terlebih dahulu');</script>";
//         return false;
//     }

//     $namafile = $_FILES['gambar']['name'];
//     $ukuranfile = $_FILES['gambar']['size'];
//     $error = $_FILES['gambar']['error'];
//     $tmpName = $_FILES['gambar']['tmp_name'];

//     $ekstensigambarValid = ['jpg', 'jpeg', 'png'];
//     $ekstensigambar = strtolower(pathinfo($namafile, PATHINFO_EXTENSION));

//     if (!in_array($ekstensigambar, $ekstensigambarValid)) {
//         echo "<script>alert('Yang Anda upload bukan gambar');</script>";
//         return false;
//     }

//     if ($ukuranfile > 5000000) {
//         echo "<script>alert('Ukuran gambar terlalu besar');</script>";
//         return false;
//     }

//     $namafilebaru = uniqid() . '.' . $ekstensigambar;
//     $targetPath = '../img/' . $namafilebaru;

//     if (move_uploaded_file($tmpName, $targetPath)) {
//         return $namafilebaru;
//     } else {
//         echo "<script>alert('Gagal mengupload gambar');</script>";
//         return false;
//     }
// }

// function hapus($id)
// {
//     global $conn;

//     try {
//         $stmt = $conn->prepare("DELETE FROM halaman WHERE id = :id");
//         $stmt->execute([':id' => $id]);
//         return true;
//     } catch (PDOException $e) {
//         error_log("PDO Exception in hapus function: " . $e->getMessage());
//         echo "<script>alert('Error saat menghapus data: " . $e->getMessage() . "');</script>";
//         return false;
//     }
// }

// function ubah($data)
// {
//     global $conn;

//     $id = $data['id'];
//     $gambarLama = $data['gambarLama'];

//     $penulis = htmlspecialchars($data['penulis']);
//     $judul = htmlspecialchars($data['judul']);
//     $kutipan = htmlspecialchars($data['kutipan']);
//     $isi = htmlspecialchars($data['isi']);
//     $kategori = htmlspecialchars($data['kategori']);

//     $gambar = $_FILES['gambar']['name'] ? upload() : $gambarLama; //Only upload new image if there is one

//     try {
//         $stmt = $conn->prepare("UPDATE halaman SET penulis=:penulis, judul=:judul, kutipan=:kutipan, isi=:isi, kategori=:kategori, gambar=:gambar WHERE id=:id");
//         $stmt->execute([
//             ':penulis' => $penulis,
//             ':judul' => $judul,
//             ':kutipan' => $kutipan,
//             ':isi' => $isi,
//             ':kategori' => $kategori,
//             ':gambar' => $gambar,
//             ':id' => $id
//         ]);

//         return true;
//     } catch (PDOException $e) {
//         error_log("PDO Exception in ubah function: " . $e->getMessage());
//         echo "<script>alert('Error saat mengubah data: " . $e->getMessage() . "');</script>";
//         return false;
//     }
// }

// setCSRFToken(); // Inisialisasi token CSRF



// functions.php

// Pastikan session_start() dipanggil di file yang meng-include ini, SEBELUM include.
// Atau, jika ingin functions.php menangani ini (kurang umum):
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once 'db_connect.php';

define('APP_ROOT_PATH', __DIR__); // Definisikan root path aplikasi

// --- Fungsi Pesan Flash (untuk feedback setelah redirect) ---
function set_flash_message($name, $message, $type = 'success') {
    $_SESSION['flash_messages'][$name] = ['message' => $message, 'type' => $type];
}

function get_flash_message($name) {
    if (isset($_SESSION['flash_messages'][$name])) {
        $message_data = $_SESSION['flash_messages'][$name];
        unset($_SESSION['flash_messages'][$name]);
        return "<div class='alert alert-{$message_data['type']} alert-dismissible fade show' role='alert'>
                    {$message_data['message']}
                    <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                </div>";
    }
    return '';
}

// --- Fungsi CSRF ---
function generateCSRFToken() {
    return bin2hex(random_bytes(32));
}

function setCSRFToken() {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = generateCSRFToken();
    }
    return $_SESSION['csrf_token'];
}

function checkCSRFToken($token, $regenerate = true) {
    if (isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token)) {
        if ($regenerate) {
            unset($_SESSION['csrf_token']); // Token hanya valid sekali
            setCSRFToken(); // Buat token baru untuk form berikutnya
        }
        return true;
    }
    // Jika token tidak valid, sebaiknya log upaya ini
    error_log("CSRF token mismatch. Submitted: $token, Session: " . ($_SESSION['csrf_token'] ?? 'NOT SET'));
    return false;
}
// Panggil untuk memastikan token selalu ada jika session sudah dimulai
setCSRFToken();


// --- Fungsi Database Query ---
function query($sql, $params = []) {
    global $conn;
    try {
        $stmt = $conn->prepare($sql);
        $stmt->execute($params);
        return $stmt; // Return statement untuk fleksibilitas (fetchAll, fetch, rowCount)
    } catch (PDOException $e) {
        error_log("PDO Exception in query function: " . $e->getMessage() . " SQL: " . $sql);
        return false;
    }
}

// --- Fungsi Registrasi ---
function registrasi($data) {
    global $conn;

    $username = strtolower(trim($data["username"]));
    $email = filter_var(trim($data["email"]), FILTER_VALIDATE_EMAIL);
    $password = $data["password"];
    $password2 = $data["password2"];

    if (empty($username) || empty($email) || empty($password)) {
        set_flash_message('register_error', 'Semua field wajib diisi.', 'danger');
        return false;
    }
    if (strlen($username) < 3) {
        set_flash_message('register_error', 'Username minimal 3 karakter.', 'danger');
        return false;
    }
    if ($email === false) {
        set_flash_message('register_error', 'Format email tidak valid.', 'danger');
        return false;
    }
    if (strlen($password) < 6) {
        set_flash_message('register_error', 'Password minimal 6 karakter.', 'danger');
        return false;
    }
    if ($password !== $password2) {
        set_flash_message('register_error', 'Konfirmasi password tidak sesuai.', 'danger');
        return false;
    }

    // Cek apakah username atau email sudah ada
    $stmt_check = query("SELECT username FROM users WHERE username = :username OR email = :email", [':username' => $username, ':email' => $email]);
    if ($stmt_check && $stmt_check->fetch()) {
        set_flash_message('register_error', 'Username atau Email sudah terdaftar.', 'danger');
        return false;
    }

    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    // Default role 'user'
    $role = 'user';

    $stmt_insert = query("INSERT INTO users (username, email, password, role) VALUES (:username, :email, :password, :role)", [
        ':username' => $username,
        ':email' => $email,
        ':password' => $hashed_password,
        ':role' => $role
    ]);

    if ($stmt_insert && $stmt_insert->rowCount() > 0) {
        return true;
    } else {
        set_flash_message('register_error', 'Registrasi gagal karena masalah teknis. Silakan coba lagi.', 'danger');
        return false;
    }
}

// --- Fungsi Login ---
function login($email, $password) {
    $stmt = query("SELECT * FROM users WHERE email = :email", [':email' => $email]);
    if ($stmt) {
        $user = $stmt->fetch();
        if ($user && password_verify($password, $user['password'])) {
            return $user; // Return data user jika berhasil
        }
    }
    return false; // Gagal login
}

// --- Fungsi Upload Gambar ---
function upload_gambar($file_input_name = 'gambar', $target_subdir = '') {
    if (!isset($_FILES[$file_input_name]) || $_FILES[$file_input_name]['error'] === UPLOAD_ERR_NO_FILE) {
        return null; // Tidak ada file diupload, bukan error
    }
    if ($_FILES[$file_input_name]['error'] !== UPLOAD_ERR_OK) {
        // Tangani error upload spesifik
        $upload_errors = [
            UPLOAD_ERR_INI_SIZE   => "File terlalu besar (server).",
            UPLOAD_ERR_FORM_SIZE  => "File terlalu besar (form).",
            UPLOAD_ERR_PARTIAL    => "Upload tidak selesai.",
            UPLOAD_ERR_NO_TMP_DIR => "Folder temporary tidak ditemukan.",
            UPLOAD_ERR_CANT_WRITE => "Gagal menulis file ke disk.",
            UPLOAD_ERR_EXTENSION  => "Ekstensi PHP menghentikan upload.",
        ];
        $error_message = $upload_errors[$_FILES[$file_input_name]['error']] ?? "Error upload tidak diketahui.";
        set_flash_message('upload_error', $error_message, 'danger');
        return false; // Error saat upload
    }

    $namafile = $_FILES[$file_input_name]['name'];
    $ukuranfile = $_FILES[$file_input_name]['size'];
    $tmpName = $_FILES[$file_input_name]['tmp_name'];

    $ekstensigambarValid = ['jpg', 'jpeg', 'png', 'gif'];
    $ekstensigambar = strtolower(pathinfo($namafile, PATHINFO_EXTENSION));

    if (!in_array($ekstensigambar, $ekstensigambarValid)) {
        set_flash_message('upload_error', 'File yang diupload bukan gambar (jpg, jpeg, png, gif).', 'danger');
        return false;
    }

    if ($ukuranfile > 5000000) { // Batas 5MB
        set_flash_message('upload_error', 'Ukuran gambar terlalu besar (maks 5MB).', 'danger');
        return false;
    }

    $namafilebaru = uniqid('img_', true) . '.' . $ekstensigambar;
    $targetDir = APP_ROOT_PATH . '/img/' . $target_subdir; // Path relatif dari root aplikasi
    if (!is_dir($targetDir)) {
        mkdir($targetDir, 0775, true); // Buat direktori jika belum ada
    }
    $targetPath = $targetDir . $namafilebaru;

    if (move_uploaded_file($tmpName, $targetPath)) {
        return $target_subdir . $namafilebaru; // Return path relatif dari img/
    } else {
        set_flash_message('upload_error', 'Gagal memindahkan file gambar yang diupload.', 'danger');
        error_log("Gagal move_uploaded_file untuk: " . $tmpName . " ke " . $targetPath);
        return false;
    }
}

// --- Fungsi Tambah Artikel ---
function tambah_artikel($data, $file_input_name = 'gambar') {
    // Validasi dasar
    if (empty($data['penulis']) || empty($data['judul']) || empty($data['isi'])) {
        set_flash_message('artikel_error', 'Penulis, Judul, dan Isi wajib diisi.', 'danger');
        return false;
    }

    $gambar_path = upload_gambar($file_input_name); // Akan return null jika tidak ada, false jika error upload

    if ($gambar_path === false) { // Jika upload error eksplisit
        // Pesan error sudah di-set oleh upload_gambar()
        return false;
    }

    $sql = "INSERT INTO halaman (penulis, judul, kutipan, isi, gambar, tgl_isi, kategori)
            VALUES (:penulis, :judul, :kutipan, :isi, :gambar, NOW(), :kategori)";
    $params = [
        ':penulis' => trim($data['penulis']),
        ':judul' => trim($data['judul']),
        ':kutipan' => trim($data['kutipan'] ?? ''),
        ':isi' => trim($data['isi']),
        ':gambar' => $gambar_path, // Bisa null
        ':kategori' => trim($data['kategori'] ?? '')
    ];

    $stmt = query($sql, $params);
    if ($stmt && $stmt->rowCount() > 0) {
        return true;
    } else {
        set_flash_message('artikel_error', 'Gagal menambahkan artikel karena masalah teknis.', 'danger');
        return false;
    }
}

// --- Fungsi Ubah Artikel ---
function ubah_artikel($data, $file_input_name = 'gambar') {
    if (empty($data['id']) || empty($data['penulis']) || empty($data['judul']) || empty($data['isi'])) {
        set_flash_message('artikel_error', 'ID, Penulis, Judul, dan Isi wajib diisi untuk mengubah artikel.', 'danger');
        return false;
    }

    $id = $data['id'];
    $gambarLama = $data['gambarLama'] ?? null;
    $gambar_path_db = $gambarLama; // Default ke gambar lama

    if (isset($_FILES[$file_input_name]) && $_FILES[$file_input_name]['error'] !== UPLOAD_ERR_NO_FILE) {
        // Ada upaya upload gambar baru
        $gambarBaruPath = upload_gambar($file_input_name);
        if ($gambarBaruPath === false) { // Error saat upload gambar baru
            return false; // Pesan error sudah diset oleh upload_gambar()
        }
        // Jika upload berhasil ($gambarBaruPath tidak false, bisa null jika tidak ada file atau nama file jika ada)
        if ($gambarBaruPath !== null) { // Ada gambar baru yang berhasil diupload
             $gambar_path_db = $gambarBaruPath;
            // Hapus gambar lama jika ada dan berbeda dengan yang baru
            if ($gambarLama && $gambarLama !== $gambar_path_db && file_exists(APP_ROOT_PATH . '/img/' . $gambarLama)) {
                unlink(APP_ROOT_PATH . '/img/' . $gambarLama);
            }
        }
        // Jika $gambarBaruPath adalah null (tidak ada file dipilih), $gambar_path_db tetap $gambarLama
    }


    $sql = "UPDATE halaman SET
                penulis = :penulis,
                judul = :judul,
                kutipan = :kutipan,
                isi = :isi,
                gambar = :gambar,
                kategori = :kategori
            WHERE id = :id";
    // Jika ini untuk user, tambahkan AND penulis = :session_username
    // if (isset($_SESSION['username']) && $data['current_user_role'] !== 'admin') {
    //     $sql .= " AND penulis = :current_user_penulis";
    //     $params_update[':current_user_penulis'] = $_SESSION['username'];
    // }


    $params_update = [
        ':penulis' => trim($data['penulis']),
        ':judul' => trim($data['judul']),
        ':kutipan' => trim($data['kutipan'] ?? ''),
        ':isi' => trim($data['isi']),
        ':gambar' => $gambar_path_db, // Bisa null atau path gambar baru/lama
        ':kategori' => trim($data['kategori'] ?? ''),
        ':id' => $id
    ];

    $stmt = query($sql, $params_update);
    // rowCount() bisa 0 jika data yang diupdate sama dengan data lama.
    // Jadi, berhasil jika tidak ada exception.
    if ($stmt !== false) {
        return true;
    } else {
        set_flash_message('artikel_error', 'Gagal mengubah artikel karena masalah teknis.', 'danger');
        return false;
    }
}

// --- Fungsi Hapus Artikel (dan gambarnya) ---
function hapus_artikel($id, $penulis_session = null, $is_admin = false) {
    global $conn; // Diperlukan untuk transaksi jika ada

    // Ambil nama gambar untuk dihapus
    $sql_select_gambar = "SELECT gambar, penulis FROM halaman WHERE id = :id";
    $stmt_select = query($sql_select_gambar, [':id' => $id]);
    $artikel = $stmt_select ? $stmt_select->fetch() : null;

    if (!$artikel) {
        set_flash_message('artikel_error', 'Artikel tidak ditemukan untuk dihapus.', 'danger');
        return false;
    }

    // Jika bukan admin, cek kepemilikan
    if (!$is_admin && ($penulis_session === null || $artikel['penulis'] !== $penulis_session)) {
        set_flash_message('artikel_error', 'Anda tidak berhak menghapus artikel ini.', 'danger');
        return false;
    }

    $gambar_artikel = $artikel['gambar'];

    // Hapus dari database
    $sql_delete = "DELETE FROM halaman WHERE id = :id";
    // Tambahkan kondisi penulis jika bukan admin, sudah dicek di atas jadi tidak perlu di query DELETE
    $stmt_delete = query($sql_delete, [':id' => $id]);

    if ($stmt_delete && $stmt_delete->rowCount() > 0) {
        // Hapus file gambar jika ada
        if (!empty($gambar_artikel) && file_exists(APP_ROOT_PATH . '/img/' . $gambar_artikel)) {
            unlink(APP_ROOT_PATH . '/img/' . $gambar_artikel);
        }
        // Hapus juga komentar terkait (opsional, tergantung kebutuhan)
        // query("DELETE FROM komentar WHERE halaman_id = :halaman_id", [':halaman_id' => $id]);
        return true;
    } else {
        set_flash_message('artikel_error', 'Gagal menghapus artikel dari database atau artikel sudah terhapus.', 'danger');
        return false;
    }
}
?>