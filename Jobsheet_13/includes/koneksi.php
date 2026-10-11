<?php
// [Jobsheet 13] Membaca konfigurasi database dari includes/config.php
$config = require __DIR__ . '/config.php';

try {
    // [Jobsheet 08] Inisialisasi PDO PostgreSQL dengan error handling exception
    $pdo = new PDO(
        "pgsql:host={$config['db_host']};port={$config['db_port']};dbname={$config['db_name']}",
        $config['db_user'],
        $config['db_pass']
    );
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Koneksi database gagal: " . $e->getMessage());
}