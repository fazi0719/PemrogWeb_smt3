<?php

// 1. Menghubungkan dengan database
require_once __DIR__ . '/includes/koneksi.php';

// 2. Menentukan lokasi file buku.json
$jsonFile = __DIR__ . '/data/buku.json';

if (!file_exists($jsonFile)) {
    exit("Gagal: Berkas buku.json tidak ditemukan.");
}

// Membaca isi file JSON
$jsonData = file_get_contents($jsonFile);

// Mengubah JSON menjadi array PHP
$dataBuku = json_decode($jsonData, true);

if (!is_array($dataBuku)) {
    exit("Gagal: Format JSON tidak valid atau data kosong.");
}

try {

    // 3. Menyiapkan query INSERT
    $sql = "INSERT INTO buku
            (judul, pengarang, tahun, isbn, stok, kategori)
            VALUES
            (:judul, :pengarang, :tahun, :isbn, :stok, :kategori)";

    $stmt = $pdo->prepare($sql);

    $berhasil = 0;

    // Memulai transaksi
    $pdo->beginTransaction();

    // 4. Memasukkan data satu per satu
    foreach ($dataBuku as $buku) {

        $stmt->execute([
            ':judul'     => $buku['judul'] ?? '',
            ':pengarang' => $buku['pengarang'] ?? '',
            ':tahun'     => $buku['tahun'] ?? null,
            ':isbn'      => $buku['isbn'] ?? '',
            ':stok'      => $buku['stok'] ?? 0,
            ':kategori'  => $buku['kategori'] ?? ''
        ]);

        $berhasil++;
    }

    // Menyimpan perubahan
    $pdo->commit();

    echo "Migrasi berhasil! Total data dipindahkan: " . $berhasil;

} catch (PDOException $e) {

    // Membatalkan transaksi jika terjadi error
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }

    echo "Terjadi kesalahan database: " . $e->getMessage();
}