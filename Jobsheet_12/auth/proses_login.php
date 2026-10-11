<?php
// [Jobsheet 10] Session Start
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
// [Jobsheet 11] CSRF & [Jobsheet 08] Koneksi
require __DIR__ . '/../includes/csrf.php';
require __DIR__ . '/../includes/koneksi.php';

csrf_verify();

$username = trim($_POST['username'] ?? '');
$password = $_POST['password'] ?? '';

// [Jobsheet 10 & 11] Query User dengan Prepared Statement
$stmt = $pdo->prepare("SELECT * FROM users WHERE username = :username");
$stmt->execute(['username' => $username]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// [Jobsheet 10] Verifikasi Password Hash
if ($user && password_verify($password, $user['password'])) {
    // [Jobsheet 10 & 11] Prevent Session Fixation
    session_regenerate_id(true);

    $_SESSION['user_id'] = $user['id'];
    $_SESSION['nama'] = $user['nama'];
    $_SESSION['role'] = $user['role'];
    header('Location: ../index.php');
    exit;
}

$_SESSION['flash'] = ['type' => 'error', 'pesan' => 'Username atau password salah.'];
header('Location: login.php');
exit;