<?php
// [Jobsheet 10] Guard Auth
require __DIR__ . '/../includes/auth.php';
// [Jobsheet 11] Verifikasi Token CSRF
require __DIR__ . '/../includes/csrf.php';
// [Jobsheet 08 & 13] Koneksi DB PDO dari config.php
require __DIR__ . '/../includes/koneksi.php';

csrf_verify();

// [Jobsheet 07] Sanitasi input POST
$nama = trim($_POST['nama'] ?? '');
$noAnggota = trim($_POST['no_anggota'] ?? '');
$alamat = trim($_POST['alamat'] ?? '');
$noHp = trim($_POST['no_hp'] ?? '');

// [Jobsheet 07] Validasi Server-side
$errors = [];
if ($nama === '') {
    $errors[] = "Nama wajib diisi.";
}
if ($noAnggota === '') {
    $errors[] = "No. Anggota wajib diisi.";
}

if (!empty($errors)) {
    $_SESSION['flash'] = ['type' => 'error', 'pesan' => implode(' ', $errors)];
    header('Location: tambah.php');
    exit;
}

try {
// [Jobsheet 08] Insert Data via Prepared Statements
$stmt = $pdo->prepare(
    "INSERT INTO anggota (nama, no_anggota, alamat, no_hp)
     VALUES (:nama, :no_anggota, :alamat, :no_hp)"
);
$stmt->execute([
    'nama' => $nama,
    'no_anggota' => $noAnggota,
    'alamat' => $alamat,
    'no_hp' => $noHp,
]);

$_SESSION['flash'] = ['type' => 'success', 'pesan' => 'Anggota berhasil ditambahkan.'];
header('Location: list.php');
exit;

} catch (PDOException $e) {
    // Menangkap error jika No. Anggota sudah terdaftar
    $_SESSION['flash'] = [
        'type' => 'error', 
        'pesan' => 'Gagal: No. Anggota ' . htmlspecialchars($noAnggota) . ' sudah terdaftar!'
    ];
    header('Location: tambah.php');
    exit;
}