<?php
// 1. Jalankan session untuk mengakses data sesi yang aktif
session_start();

// 2. Kosongkan semua isi variabel $_SESSION
$_SESSION = array();

// 3. Hancurkan sesi di server
session_destroy();

// 4. Mulai sesi baru khusus untuk mengirim flash message ke pengguna
session_start();
$_SESSION['flash'] = [
    'type' => 'success',
    'pesan' => 'Seluruh data session berhasil dikosongkan.'
];

// 5. Dialihkan kembali ke Beranda
header('Location: index.php');
exit;