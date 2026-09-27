<?php
// JOBSHEET 7: Memulai session untuk menyimpan flash message
session_start();

// JOBSHEET 8: Menghubungkan PHP dengan database PostgreSQL
require __DIR__ . '/../includes/koneksi.php';

// JOBSHEET 7: Mengambil data POST + trim spasi
$nama = trim($_POST['nama'] ?? '');
$noAnggota = trim($_POST['no_anggota'] ?? '');
$alamat = trim($_POST['alamat'] ?? '');
$noHp = trim($_POST['no_hp'] ?? '');

// Modifikasi Latihan: Mengambil input email
$email = trim($_POST['email'] ?? '');

// JOBSHEET 7: Validasi Server-Side
$errors = [];

if ($nama === '') {
    $errors[] = "Nama wajib diisi.";
}

// Latihan Jobsheet 7: Validasi minimal panjang nama (minimal 3 karakter)
if ($nama !== '' && strlen($nama) < 3) {
    $errors[] = "Nama anggota minimal harus 3 karakter.";
}

if ($noAnggota === '') {
    $errors[] = "No. Anggota wajib diisi.";
}

// Modifikasi Latihan: Validasi format email jika diisi
if ($email !== '' && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = "Format email tidak valid.";
}

// JOBSHEET 7: Jika terdapat error, simpan pesan error ke flash session lalu redirect ke tambah.php
if (!empty($errors)) {
    $_SESSION['flash'] = [
        'type' => 'error',
        'pesan' => implode(' ', $errors)
    ];
    header('Location: tambah.php');
    exit;
}

// JOBSHEET 8: Menyimpan data anggota ke database PostgreSQL menggunakan prepared statement
try {
    $stmt = $pdo->prepare(
        "INSERT INTO anggota (nama, no_anggota, alamat, no_hp, email, tgl_bergabung)
         VALUES (:nama, :no_anggota, :alamat, :no_hp, :email, :tgl_bergabung)
         RETURNING id"
    );

    $stmt->execute([
        'nama'          => $nama,
        'no_anggota'    => $noAnggota,
        'alamat'        => $alamat,
        'no_hp'         => $noHp,
        'email'         => $email,
        'tgl_bergabung' => date('Y-m-d') // Menambahkan tanggal bergabung otomatis
    ]);

    // JOBSHEET 7: Set flash message sukses
    $_SESSION['flash'] = [
        'type' => 'success',
        'pesan' => 'Anggota berhasil ditambahkan.'
    ];
} catch (PDOException $e) {
    // Latihan Jobsheet 8: Menangani error UNIQUE No. Anggota
    $_SESSION['flash'] = [
        'type' => 'error',
        'pesan' => 'No. Anggota sudah dipakai, gunakan nomor lain.'
    ];
}

// JOBSHEET 7: Mengarahkan pengguna kembali ke halaman daftar anggota
header('Location: list.php');
exit;