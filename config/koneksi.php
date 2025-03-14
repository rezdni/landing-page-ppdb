<?php
// Konfigurasi database
$host = 'localhost';
$dbname = 'db_sekolah';
$username = 'rezadani';
$password = ']@L-tyL)PE9A0Vh1';

try {
    // Membuat koneksi ke database dengan PDO
    $dsn = "mysql:host=$host;dbname=$dbname;charset=utf8mb4";
    $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, // Aktifkan mode exception untuk error handling
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, // Mode fetch default adalah array asosiatif
        PDO::ATTR_EMULATE_PREPARES => false, // Nonaktifkan prepared statements emulasi
    ];

    $pdo = new PDO($dsn, $username, $password, $options);
} catch (PDOException $e) {
    // Tangani kesalahan koneksi
    echo "Koneksi gagal: " . $e->getMessage();
    exit;
}
?>
