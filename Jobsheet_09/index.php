<?php
// JOBSHEET 7: Menentukan judul halaman untuk header dinamis
$page_title = "Beranda";

// JOBSHEET 7: Menggunakan include untuk memuat header & navigasi modular
include __DIR__ . '/includes/header.php';

// JOBSHEET 8: Menghubungkan halaman dengan database PostgreSQL
require __DIR__ . '/includes/koneksi.php';

// JOBSHEET 8: Menghitung total data buku dari database PostgreSQL
$totalBuku = $pdo->query("SELECT COUNT(*) FROM buku")->fetchColumn();

// JOBSHEET 8: Menghitung total data anggota dari database PostgreSQL
$totalAnggota = $pdo->query("SELECT COUNT(*) FROM anggota")->fetchColumn();
?>
        <section>
            <h2>Selamat Datang di Sistem Perpustakaan Mini</h2>
            <p>Aplikasi sederhana untuk mengelola data buku dan anggota perpustakaan.</p>
        </section>

        <!-- Ringkasan Statistik -->
        <section>
            <h2>Ringkasan</h2>

            <article>
                <h3>Total Buku</h3>
                <p><?php echo $totalBuku; ?></p>
            </article>

            <article>
                <h3>Total Anggota</h3>
                <p><?php echo $totalAnggota; ?></p>
            </article>

            <article>
                <h3>Sedang Dipinjam</h3>
                <p>0</p>
            </article>

            <!-- Modifikasi Latihan: Kartu Keempat -->
            <article>
                <h3>Buku Terlambat</h3>
                <p>0</p>
            </article>
        </section>

<?php 
// JOBSHEET 7: Menggunakan include untuk memuat footer & script pendukung
include __DIR__ . '/includes/footer.php'; 
?>