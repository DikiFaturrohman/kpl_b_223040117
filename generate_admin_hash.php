<?php
// generate_admin_hash.php

// 1. Ganti dengan password baru yang Anda inginkan untuk admin
$passwordAdminBaru = 'admin123'; // << GANTI INI DENGAN PASSWORD ANDA

// 2. Generate hash password
// PASSWORD_DEFAULT adalah algoritma hashing yang direkomendasikan dan paling aman saat ini (biasanya bcrypt).
// PHP akan otomatis mengelolanya jika ada algoritma baru yang lebih baik di masa depan.
$hashPasswordAdmin = password_hash($passwordAdminBaru, PASSWORD_DEFAULT);

// 3. Tampilkan hasilnya agar bisa Anda salin
echo "<h1>Generate Hash Password Admin</h1>";
echo "<p><strong>Password Plain Text (HANYA UNTUK REFERENSI ANDA, JANGAN SIMPAN INI):</strong><br>" . htmlspecialchars($passwordAdminBaru) . "</p>";
echo "<p><strong>Hash Password untuk Database (SALIN INI):</strong><br><textarea rows='3' cols='70' readonly>" . htmlspecialchars($hashPasswordAdmin) . "</textarea></p>";
echo "<hr>";
echo "<p><strong>PENTING:</strong> Setelah menyalin hash di atas dan memperbarui database, segera hapus file <code>generate_admin_hash.php</code> ini dari server Anda untuk keamanan.</p>";

// Anda juga bisa langsung menampilkan query UPDATE jika mau:
// $emailAdmin = 'admin@campusblog.com'; // Ganti jika email admin Anda berbeda
// echo "<h3>Contoh Query SQL UPDATE:</h3>";
// echo "<pre>UPDATE `users` SET `password` = '" . htmlspecialchars($hashPasswordAdmin) . "' WHERE `email` = '" . htmlspecialchars($emailAdmin) . "';</pre>";
?>