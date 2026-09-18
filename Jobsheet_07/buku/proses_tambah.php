<?php
// JOBSHEET 7: Memulai session untuk mengakses $_SESSION
session_start();

// JOBSHEET 7: Mengambil data yang dikirim melalui metode POST + trim spasi
$judul = trim($_POST['judul'] ?? '');
$pengarang = trim($_POST['pengarang'] ?? '');
$tahun = $_POST['tahun'] ?? '';
$isbn = trim($_POST['isbn'] ?? '');
$stok = $_POST['stok'] ?? '';
$kategori = trim($_POST['kategori'] ?? '');

// JOBSHEET 7: Validasi Server-Side
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
// Modifikasi Validasi ISBN (opsional: jika diisi, pastikan angka dan tanda hubung)
// latihan 1 
if ($isbn !== '' && !preg_match('/^[0-9-]+$/', $isbn)) {
    $errors[] = "ISBN hanya boleh berisi angka dan tanda hubung (-).";
}

// JOBSHEET 7: Jika terdapat error, simpan pesan ke flash session lalu redirect balik ke tambah.php
if (!empty($errors)) {
    $_SESSION['flash'] = [
        'type' => 'error',
        'pesan' => implode(' ', $errors)
    ];
    header('Location: tambah.php');
    exit;
}

// JOBSHEET 7: Memastikan array $_SESSION['buku'] sudah dibuat
if (!isset($_SESSION['buku'])) {
    $_SESSION['buku'] = [];
}

// JOBSHEET 7: Menyimpan data buku baru ke dalam array session
$_SESSION['buku'][] = [
    'judul' => $judul,
    'pengarang' => $pengarang,
    'tahun' => (int) $tahun,
    'isbn' => $isbn,
    'stok' => (int) $stok,
    'kategori' => $kategori
];

// JOBSHEET 7: Set flash message sukses lalu redirect ke list.php
$_SESSION['flash'] = [
    'type' => 'success',
    'pesan' => 'Buku berhasil ditambahkan.'
];

header('Location: list.php');
exit;