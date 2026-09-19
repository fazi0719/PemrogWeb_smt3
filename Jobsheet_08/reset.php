<?php
// JOBSHEET 7: Memulai session
session_start();

// JOBSHEET 8: Menghubungkan dengan database PostgreSQL
require __DIR__ . '/includes/koneksi.php';

try {
    // JOBSHEET 8: Menghapus seluruh data buku dan anggota dari database
    $pdo->exec("TRUNCATE TABLE buku, anggota RESTART IDENTITY");

    // JOBSHEET 7: Mengosongkan data session
    $_SESSION = [];

    // Membuat flash message
    $_SESSION['flash'] = [
        'type' => 'success',
        'pesan' => 'Seluruh data buku dan anggota berhasil direset.'
    ];

} catch (PDOException $e) {
    $_SESSION['flash'] = [
        'type' => 'error',
        'pesan' => 'Data gagal direset.'
    ];
}

// Kembali ke halaman beranda
header('Location: index.php');
exit;