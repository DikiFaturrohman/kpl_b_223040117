<?php
// functions.php
require_once __DIR__ . '/vendor/autoload.php';
// Pastikan session_start() dipanggil di file yang meng-include ini, SEBELUM include.
// Atau, jika ingin functions.php menangani ini (kurang umum):
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Monolog\Formatter\LineFormatter;
use Monolog\Processor\WebProcessor;
use Monolog\Processor\MemoryUsageProcessor;
use Monolog\Processor\MemoryPeakUsageProcessor;

require_once 'db_connect.php';

define('APP_ROOT_PATH', __DIR__); // Definisikan root path aplikasi

// --- Konfigurasi Logging ---
define('LOGS_DIR', APP_ROOT_PATH . '/logs'); // Logs directory
if (!is_dir(LOGS_DIR)) {
    mkdir(LOGS_DIR, 0775, true); // Buat direktori jika belum ada
}

$loggerInstances = []; // Cache untuk instance logger

function get_logger($channelName = 'app', $logFile = 'app.log', $logLevel = Logger::DEBUG) {
    global $loggerInstances;

    if (isset($loggerInstances[$channelName])) {
        return $loggerInstances[$channelName];
    }

    try {
        $logger = new Logger($channelName);

        // Handler untuk menulis log ke file
        $streamHandler = new StreamHandler(LOGS_DIR . '/' . $logFile, $logLevel);

        // Formatter untuk kustomisasi format log
        $dateFormat = "Y-m-d H:i:s.u"; // Format tanggal dengan mikrodetik
        // Format output: [timestamp] channel.LEVEL: message {context} {extra}
        $outputFormat = "[%datetime%] %channel%.%level_name%: %message% %context% %extra%\n";
        $formatter = new LineFormatter($outputFormat, $dateFormat, true, true); // true ketiga untuk allowInlineLineBreaks, true keempat untuk ignoreEmptyContextAndExtra
        $streamHandler->setFormatter($formatter);

        $logger->pushHandler($streamHandler);

        // Processor untuk menambahkan data ekstra ke log (opsional tapi berguna)
        if (isset($_SERVER['REQUEST_METHOD'])) { // Hanya jika dalam konteks web request
            $logger->pushProcessor(new WebProcessor()); // Menambahkan IP, URL, HTTP method
        }
        $logger->pushProcessor(new MemoryUsageProcessor());
        $logger->pushProcessor(new MemoryPeakUsageProcessor());
        
        $loggerInstances[$channelName] = $logger;
        return $logger;

    } catch (\Exception $e) {
        // Fallback jika Monolog gagal diinisialisasi
        error_log("Gagal menginisialisasi logger '$channelName': " . $e->getMessage());
        // Mengembalikan logger dummy sederhana jika terjadi error
        return new class {
            public function info($message, array $context = []) { error_log("INFO: $message " . json_encode($context)); }
            public function error($message, array $context = []) { error_log("ERROR: $message " . json_encode($context)); }
            public function warning($message, array $context = []) { error_log("WARNING: $message " . json_encode($context)); }
            public function debug($message, array $context = []) { error_log("DEBUG: $message " . json_encode($context)); }
            // Tambahkan method lain jika perlu
        };
    }
}

// --- Fungsi Helper Logging ---
function log_activity($message, array $context = []) {
    $logger = get_logger('activity', 'activity.log', Logger::INFO);
    $userId = $_SESSION['user_id'] ?? 'guest_id';
    $username = $_SESSION['username'] ?? 'Guest';
    $context['user_id'] = $userId;
    $context['username'] = $username;
    if (isset($_SERVER['REMOTE_ADDR'])) {
        $context['ip_address'] = $_SERVER['REMOTE_ADDR'];
    }
    if (isset($_SERVER['REQUEST_URI'])) {
        $context['request_uri'] = $_SERVER['REQUEST_URI'];
    }
    $logger->info($message, $context);
}

function log_error($message, array $context = [], Throwable $exception = null) {
    $logger = get_logger('error', 'error.log', Logger::ERROR);
    if (isset($_SESSION['user_id'])) $context['user_id'] = $_SESSION['user_id'];
    if (isset($_SESSION['username'])) $context['username'] = $_SESSION['username'];
    if (isset($_SERVER['REMOTE_ADDR'])) $context['ip_address'] = $_SERVER['REMOTE_ADDR'];
    if (isset($_SERVER['REQUEST_URI'])) $context['request_uri'] = $_SERVER['REQUEST_URI'];

    if ($exception) {
        $context['exception'] = [
            'message' => $exception->getMessage(),
            'file'    => $exception->getFile(),
            'line'    => $exception->getLine(),
            'trace'   => $exception->getTraceAsString() // Bisa sangat panjang, pertimbangkan untuk memotongnya jika perlu
        ];
    }
    $logger->error($message, $context);
}

// --- Global Error and Exception Handlers ---
set_error_handler(function ($severity, $message, $file, $line) {
    if (!(error_reporting() & $severity)) {
        // Error code ini tidak termasuk dalam error_reporting
        return false;
    }
    log_error("PHP Error", ['severity' => $severity, 'message' => $message, 'file' => $file, 'line' => $line]);
    return false; // Biarkan handler error internal PHP juga berjalan jika bukan E_USER_ERROR dll.
});

set_exception_handler(function (Throwable $exception) {
    log_error("Uncaught Exception", [], $exception);

    // Di lingkungan produksi, tampilkan halaman error generik
    // Untuk pengembangan, Anda mungkin ingin menampilkan detail error
    if (ini_get('display_errors') === '1' || ini_get('display_errors') === 'On') {
        echo "<h1>Uncaught Exception</h1>";
        echo "<p>Message: " . htmlspecialchars($exception->getMessage()) . "</p>";
        echo "<p>File: " . htmlspecialchars($exception->getFile()) . " on line " . htmlspecialchars($exception->getLine()) . "</p>";
        echo "<pre>" . htmlspecialchars($exception->getTraceAsString()) . "</pre>";
    } else {
        http_response_code(500);
        echo "<h1>Terjadi Kesalahan Internal</h1><p>Kami mohon maaf, terjadi kesalahan pada server. Tim kami telah diberitahu.</p>";
    }
    exit;
});

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