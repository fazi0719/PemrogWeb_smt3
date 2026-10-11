<?php
// [Jobsheet 10] Guard Auth
require __DIR__ . '/../includes/auth.php';
// [Jobsheet 11] Proteksi CSRF
require __DIR__ . '/../includes/csrf.php';
// [Jobsheet 08] Koneksi Database
require __DIR__ . '/../includes/koneksi.php';

// [Jobsheet 11] Verifikasi CSRF Token
csrf_verify();

$anggotaId = $_POST['anggota_id'] ?? '';
$bukuId = $_POST['buku_id'] ?? '';

if ($anggotaId === '' || $bukuId === '') {
    $_SESSION['flash'] = ['type' => 'error', 'pesan' => 'Anggota dan buku wajib dipilih.'];
    header('Location: tambah.php');
    exit;
}

// latihan 1: VALIDASI BISNIS: Cek peminjaman terlambat > 14 hari
$cekTerlambat = $pdo->prepare(
    "SELECT COUNT(*) FROM peminjaman 
     WHERE anggota_id = :anggota_id 
       AND status = 'dipinjam' 
       AND (CURRENT_DATE - tanggal_pinjam) > 14"
);
$cekTerlambat->execute(['anggota_id' => $anggotaId]);
$jumlahTerlambat = $cekTerlambat->fetchColumn();

if ($jumlahTerlambat > 0) {
    $_SESSION['flash'] = [
        'type' => 'error', 
        'pesan' => 'Anggota tidak dapat meminjam buku baru karena memiliki peminjaman yang terlambat lebih dari 14 hari.'
    ];
    header('Location: tambah.php');
    exit;
}

try {
    // [Jobsheet 12] Memulai Database Transaction
    $pdo->beginTransaction();

    // [Jobsheet 12] FOR UPDATE: Mengunci baris buku agar tidak diubah transaksi lain saat dicek
    $cek = $pdo->prepare("SELECT stok FROM buku WHERE id = :id FOR UPDATE");
    $cek->execute(['id' => $bukuId]);
    $buku = $cek->fetch(PDO::FETCH_ASSOC);

    if (!$buku || $buku['stok'] < 1) {
        throw new Exception('Stok buku tidak tersedia.');
    }

    // [Jobsheet 12] Catat transaksi ke tabel peminjaman
    $insert = $pdo->prepare(
        "INSERT INTO peminjaman (buku_id, anggota_id, tanggal_pinjam,tanggal_jatuh_tempo, status)
         VALUES (:buku_id, :anggota_id, CURRENT_DATE, CURRENT_DATE + INTERVAL '14 days','dipinjam')"
    );
    $insert->execute(['buku_id' => $bukuId, 'anggota_id' => $anggotaId]);

    // [Jobsheet 12] Kurangi stok buku
    $update = $pdo->prepare("UPDATE buku SET stok = stok - 1 WHERE id = :id");
    $update->execute(['id' => $bukuId]);

    // [Jobsheet 12] Simpan permanen perubahan jika kedua query berhasil
    $pdo->commit();

    $_SESSION['flash'] = ['type' => 'success', 'pesan' => 'Peminjaman berhasil dicatat.'];
    header('Location: ../index.php');
    exit;
} catch (Exception $e) {
    // [Jobsheet 12] Batalkan seluruh perubahan jika terjadi gagal/error
    $pdo->rollBack();
    $_SESSION['flash'] = ['type' => 'error', 'pesan' => 'Gagal mencatat peminjaman: ' . $e->getMessage()];
    header('Location: tambah.php');
    exit;
}