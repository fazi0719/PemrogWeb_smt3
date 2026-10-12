<?php
// [Jobsheet 10] Guard Auth
require __DIR__ . '/../includes/auth.php';
// [Jobsheet 11] Proteksi CSRF
require __DIR__ . '/../includes/csrf.php';
// [Jobsheet 08 & 13] Koneksi Database
require __DIR__ . '/../includes/koneksi.php';

csrf_verify();

$anggotaId = $_POST['anggota_id'] ?? '';
$bukuId = $_POST['buku_id'] ?? '';


if ($anggotaId === '' || $bukuId === '') {
    $_SESSION['flash'] = ['type' => 'error', 'pesan' => 'Anggota dan buku wajib dipilih.'];
    header('Location: tambah.php');
    exit;
}

try {
    // latihan 2
    // --- VALIDASI BISNIS: Cek Keterlambatan Anggota > 14 Hari ---
    $sql_cek_terlambat = "
        SELECT COUNT(*) AS total_terlambat
        FROM peminjaman
        WHERE anggota_id = :anggota_id 
          AND status = 'dipinjam' 
          AND CURRENT_DATE - tanggal_pinjam > 14
    ";
    
    $stmt_cek = $pdo->prepare($sql_cek_terlambat);
    $stmt_cek->execute(['anggota_id' => $anggotaId]);
    $row_cek = $stmt_cek->fetch(PDO::FETCH_ASSOC);

    if ($row_cek && $row_cek['total_terlambat'] > 0) {
        $_SESSION['flash'] = [
            'type' => 'error', 
            'pesan' => 'Transaksi ditolak! Anggota ini memiliki keterlambatan pengembalian lebih dari 14 hari.'
        ];
        header("Location: tambah.php");
        exit;
    }

    // [Jobsheet 12] Memulai Database Transaction
    $pdo->beginTransaction();

    // [Jobsheet 12] FOR UPDATE: Mengunci baris buku untuk mencegah race condition
    $cek = $pdo->prepare("SELECT stok FROM buku WHERE id = :id FOR UPDATE");
    $cek->execute(['id' => $bukuId]);
    $buku = $cek->fetch(PDO::FETCH_ASSOC);

    if (!$buku || $buku['stok'] < 1) {
        throw new Exception('Stok buku tidak tersedia.');
    }

    // [Jobsheet 12] Catat transaksi peminjaman standar
    $insert = $pdo->prepare(
        "INSERT INTO peminjaman (buku_id, anggota_id, tanggal_pinjam, status)
         VALUES (:buku_id, :anggota_id, CURRENT_DATE, 'dipinjam')"
    );
    $insert->execute(['buku_id' => $bukuId, 'anggota_id' => $anggotaId]);

    // [Jobsheet 12] Kurangi stok buku
    $update = $pdo->prepare("UPDATE buku SET stok = stok - 1 WHERE id = :id");
    $update->execute(['id' => $bukuId]);

    // [Jobsheet 12] Commit perubahan
    $pdo->commit();

    $_SESSION['flash'] = ['type' => 'success', 'pesan' => 'Peminjaman berhasil dicatat.'];
    header('Location: ../index.php');
    exit;
} catch (Exception $e) {
    // Rollback HANYA jika ada transaksi aktif
    if ($pdo->inTransaction()) {
    // [Jobsheet 12] Rollback seluruh perubahan jika terjadi gagal/error
    $pdo->rollBack();
    $_SESSION['flash'] = ['type' => 'error', 'pesan' => 'Gagal mencatat peminjaman: ' . $e->getMessage()];
    header('Location: tambah.php');
    exit;
}
}