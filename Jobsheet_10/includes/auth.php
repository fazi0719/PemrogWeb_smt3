<?php
// Guard clause: di-include di baris paling atas setiap halaman yang
// membutuhkan login (sebelum header.php mengeluarkan output apa pun),
// agar header('Location: ...') masih bisa dipanggil.
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// LATIHAN 2: Pengecekan Cookie "Ingat Saya" untuk Auto-Login
if (!isset($_SESSION['user_id']) && isset($_COOKIE['remember_token'])) {
    require_once __DIR__ . '/koneksi.php';
    
    $token = $_COOKIE['remember_token'];
    $token_hash = hash('sha256', $token);

    // Cari user berdasarkan token yang belum expired
    $stmt = $pdo->prepare("SELECT * FROM users WHERE remember_token = :token AND token_expires > NOW()");
    $stmt->execute(['token' => $token_hash]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        // Buat session baru secara otomatis
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['nama'] = $user['nama'];
        $_SESSION['role'] = $user['role'];
    }
}

if (!isset($_SESSION['user_id'])) {
    header('Location: ../auth/login.php');
    exit;
}
// soal 1
function require_role($allowed_role) {
    if (!isset($_SESSION['role']) || $_SESSION['role'] !== $allowed_role) {
        $_SESSION['flash_error'] = "Akses ditolak: Anda tidak memiliki izin untuk tindakan ini.";
        header("Location: /Jobsheet_10/anggota/list.php");
        exit;
    }
}