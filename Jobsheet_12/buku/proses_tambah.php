<?php
// [Jobsheet 10] Guard Auth
require __DIR__ . '/../includes/auth.php';
// [Jobsheet 11] Proteksi CSRF
require __DIR__ . '/../includes/csrf.php';
// [Jobsheet 08] Koneksi Database
require __DIR__ . '/../includes/koneksi.php';

// [Jobsheet 11] Verifikasi CSRF
csrf_verify();

// [Jobsheet 07] Sanitasi Input
$judul = trim($_POST['judul'] ?? '');
$pengarang = trim($_POST['pengarang'] ?? '');
$tahun = $_POST['tahun'] ?? '';
$isbn = trim($_POST['isbn'] ?? '');
$stok = $_POST['stok'] ?? '';
$kategori = trim($_POST['kategori'] ?? '');

// [Jobsheet 07] Validasi Server-Side
$errors = [];
if ($judul === '') {
    $errors[] = "Judul wajib diisi.";
}
if ($pengarang === '') {
    $errors[] = "Pengarang wajib diisi.";
}
if (!is_numeric($tahun) || $tahun < 1900 || $tahun > 2026) {
    $errors[] = "Tahun harus di antara 1900-2026.";
}
if (!is_numeric($stok) || $stok < 0) {
    $errors[] = "Stok tidak boleh negatif.";
}

if (!empty($errors)) {
    $_SESSION['flash'] = ['type' => 'error', 'pesan' => implode(' ', $errors)];
    header('Location: tambah.php');
    exit;
}

// [Jobsheet 08 & 11] Prepared Statement INSERT
$stmt = $pdo->prepare(
    "INSERT INTO buku (judul, pengarang, tahun, isbn, stok, kategori)
     VALUES (:judul, :pengarang, :tahun, :isbn, :stok, :kategori)
     RETURNING id"
);
$stmt->execute([
    'judul' => $judul,
    'pengarang' => $pengarang,
    'tahun' => (int) $tahun,
    'isbn' => $isbn,
    'stok' => (int) $stok,
    'kategori' => $kategori,
]);

// [Jobsheet 07] Redirect Sukses
$_SESSION['flash'] = ['type' => 'success', 'pesan' => 'Buku berhasil ditambahkan.'];
header('Location: list.php');
exit;