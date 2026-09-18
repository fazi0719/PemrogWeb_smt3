<?php
// JOBSHEET 7: Memulai session untuk mengakses $_SESSION
session_start();

// JOBSHEET 7: Mengambil data yang dikirim melalui metode POST + trim spasi
$nama = trim($_POST['nama'] ?? '');
$no_anggota = trim($_POST['no_anggota'] ?? '');
$alamat = trim($_POST['alamat'] ?? '');
$no_hp = trim($_POST['no_hp'] ?? '');
// Modifikasi: Mengambil input email dari form
$email = trim($_POST['email'] ?? '');

// JOBSHEET 7: Validasi Server-Side
$errors = [];

if ($nama === '') {
    $errors[] = "Nama wajib diisi.";
}
// Tambahan: Validasi minimal panjang nama
// latihan 2
if ($nama !== '' && strlen($nama) < 3) {
    $errors[] = "Nama anggota minimal harus 3 karakter.";
}

if ($no_anggota === '') {
    $errors[] = "Nomor anggota wajib diisi.";
}

// Modifikasi Validasi Email (opsional: jika diisi, pastikan format email valid)
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

// JOBSHEET 7: Memastikan array $_SESSION['anggota'] sudah dibuat
if (!isset($_SESSION['anggota'])) {
    $_SESSION['anggota'] = [];
}

// JOBSHEET 7: Menyimpan data anggota baru ke dalam array session
$_SESSION['anggota'][] = [
    'nama' => $nama,
    'no_anggota' => $no_anggota,
    'alamat' => $alamat,
    'no_hp' => $no_hp,
    'email' => $email,
    'tgl_bergabung' => date('Y-m-d') // Menyimpan tanggal hari ini otomatis
];

// JOBSHEET 7: Set flash message sukses lalu redirect ke list.php
// latihan 2 //
$_SESSION['flash'] = [
    'type' => 'success',
    'pesan' => 'Anggota berhasil ditambahkan.'
];

header('Location: list.php');
exit;