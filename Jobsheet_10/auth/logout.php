<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
// latihan 2: Hapus token di database dan cookie saat logout
require_once __DIR__ . '/../includes/koneksi.php';

// 1. Hapus token di database jika user sedang login
if (isset($_SESSION['user_id'])) {
    $stmt = $pdo->prepare("UPDATE users SET remember_token = NULL, token_expires = NULL WHERE id = :id");
    $stmt->execute(['id' => $_SESSION['user_id']]);
}

// 2. Hapus cookie 'remember_token' dari browser
if (isset($_COOKIE['remember_token'])) {
    setcookie('remember_token', '', [
        'expires' => time() - 3600,
        'path'    => '/'
    ]);
}

// Hapus semua data session
$_SESSION = [];
session_destroy();

header('Location: login.php');
exit;
