<?php
// JOBSHEET 7: Memulai session untuk menyimpan flash message
session_start();

// JOBSHEET 8: Menghubungkan PHP dengan database PostgreSQL
require __DIR__ . '/../includes/koneksi.php';

// JOBSHEET 9: Mengambil ID anggota & data input form dari POST
$id = $_POST['id'] ?? null;
$nama = trim($_POST['nama'] ?? '');
$noAnggota = trim($_POST['no_anggota'] ?? '');
$alamat = trim($_POST['alamat'] ?? '');
$noHp = trim($_POST['no_hp'] ?? '');

// JOBSHEET 9: Memastikan ID ada sebelum memproses perubahan
if (!$id) {
    header('Location: list.php');
    exit;
}

// JOBSHEET 7: Validasi Server-Side
$errors = [];

if ($nama === '') {
    $errors[] = "Nama wajib diisi.";
}

if ($noAnggota === '') {
    $errors[] = "No. Anggota wajib diisi.";
}

// JOBSHEET 7: Jika ada error, kirim pesan error via flash session lalu balik ke edit.php
if (!empty($errors)) {
    $_SESSION['flash'] = [
        'type' => 'error',
        'pesan' => implode(' ', $errors)
    ];
    header('Location: edit.php?id=' . urlencode($id));
    exit;
}

// JOBSHEET 9: Mengubah data anggota di database menggunakan query UPDATE
$stmt = $pdo->prepare(
    "UPDATE anggota SET 
        nama = :nama, 
        no_anggota = :no_anggota,
        alamat = :alamat, 
        no_hp = :no_hp 
     WHERE id = :id"
);

// JOBSHEET 9: Mengirim nilai parameter ke query UPDATE
$stmt->execute([
    'nama'       => $nama,
    'no_anggota' => $noAnggota,
    'alamat'     => $alamat,
    'no_hp'      => $noHp,
    'id'         => $id,
]);

// JOBSHEET 7: Menampilkan pesan sukses melalui flash session
$_SESSION['flash'] = [
    'type' => 'success',
    'pesan' => 'Anggota berhasil diperbarui.'
];

// JOBSHEET 7: Mengarahkan pengguna kembali ke daftar anggota
header('Location: list.php');
exit;