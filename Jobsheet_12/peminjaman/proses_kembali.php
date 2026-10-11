<?php
// [Jobsheet 10] Guard Auth
require __DIR__ . '/../includes/auth.php';
// [Jobsheet 11] Proteksi CSRF
require __DIR__ . '/../includes/csrf.php';
// [Jobsheet 08] Koneksi Database
require __DIR__ . '/../includes/koneksi.php';

// [Jobsheet 11] Mencegah request selain POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: kembali.php');
    exit;
}

csrf_verify();

$id = $_POST['id'] ?? null;
if (!$id) {
    header('Location: kembali.php');
    exit;
}

try {
    // [Jobsheet 12] Memulai Database Transaction
    $pdo->beginTransaction();

    // [Jobsheet 12] FOR UPDATE: Mengunci baris peminjaman yang akan dikembalikan
    $stmt = $pdo->prepare("SELECT buku_id, status FROM peminjaman WHERE id = :id FOR UPDATE");
    $stmt->execute(['id' => $id]);
    $trx = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$trx || $trx['status'] !== 'dipinjam') {
        throw new Exception('Transaksi tidak ditemukan atau sudah dikembalikan.');
    }

    // [Jobsheet 12] Update status peminjaman & tanggal kembali
    $updatePeminjaman = $pdo->prepare(
        "UPDATE peminjaman SET status = 'dikembalikan', tanggal_kembali = CURRENT_DATE WHERE id = :id"
    );
    $updatePeminjaman->execute(['id' => $id]);

    // [Jobsheet 12] Tambah kembali stok buku
    $updateBuku = $pdo->prepare("UPDATE buku SET stok = stok + 1 WHERE id = :buku_id");
    $updateBuku->execute(['buku_id' => $trx['buku_id']]);

    // [Jobsheet 12] Commit transaksi
    $pdo->commit();
    $_SESSION['flash'] = ['type' => 'success', 'pesan' => 'Buku berhasil dikembalikan.'];
} catch (Exception $e) {
    // [Jobsheet 12] Rollback transaksi jika gagal
    $pdo->rollBack();
    $_SESSION['flash'] = ['type' => 'error', 'pesan' => 'Gagal memproses pengembalian: ' . $e->getMessage()];
}

header('Location: kembali.php');
exit;