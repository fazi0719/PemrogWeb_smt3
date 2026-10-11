<?php
// [Jobsheet 10] Guard Auth
require __DIR__ . '/../includes/auth.php';
// [Jobsheet 11] Proteksi CSRF
require __DIR__ . '/../includes/csrf.php';
// [Jobsheet 08] Koneksi Database
require __DIR__ . '/../includes/koneksi.php';

// [Jobsheet 09 & 11] Mencegah Akses GET
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: list.php');
    exit;
}

csrf_verify();

$id = $_POST['id'] ?? null;
if ($id) {
    // [Jobsheet 09 & 11] Prepared Statement DELETE
    $stmt = $pdo->prepare("DELETE FROM buku WHERE id = :id");
    $stmt->execute(['id' => $id]);
    $_SESSION['flash'] = ['type' => 'success', 'pesan' => 'Buku berhasil dihapus.'];
}

header('Location: list.php');
exit;