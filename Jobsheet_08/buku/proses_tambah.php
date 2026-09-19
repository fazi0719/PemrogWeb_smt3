<?php

// JOBSHEET 7: Memulai session untuk menyimpan flash message
session_start();

// JOBSHEET 8: Menghubungkan PHP dengan database PostgreSQL
require __DIR__ . '/../includes/koneksi.php';

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

// JOBSHEET 7: Modifikasi validasi ISBN
if ($isbn !== '' && !preg_match('/^[0-9-]+$/', $isbn)) {
    $errors[] = "ISBN hanya boleh berisi angka dan tanda hubung (-).";
}

// JOBSHEET 7: Jika terdapat error, simpan pesan ke flash session
// lalu kembali ke halaman tambah.php
if (!empty($errors)) {
    $_SESSION['flash'] = [
        'type' => 'error',
        'pesan' => implode(' ', $errors)
    ];

    header('Location: tambah.php');
    exit;
}

// JOBSHEET 8: Menyimpan data buku ke database PostgreSQL
// menggunakan prepared statement
$stmt = $pdo->prepare(
    "INSERT INTO buku (judul, pengarang, tahun, isbn, stok, kategori)
     VALUES (:judul, :pengarang, :tahun, :isbn, :stok, :kategori)
     RETURNING id"
);

// JOBSHEET 8: Mengirim nilai data ke parameter query
$stmt->execute([
    'judul'     => $judul,
    'pengarang' => $pengarang,
    'tahun'     => (int) $tahun,
    'isbn'      => $isbn,
    'stok'      => (int) $stok,
    'kategori'  => $kategori,
]);

// JOBSHEET 7: Menampilkan pesan berhasil melalui flash session
$_SESSION['flash'] = [
    'type' => 'success',
    'pesan' => 'Buku berhasil ditambahkan.'
];

// JOBSHEET 7: Mengarahkan pengguna ke halaman daftar buku
header('Location: list.php');
exit;