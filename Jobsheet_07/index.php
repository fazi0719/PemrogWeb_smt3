<?php
// JOBSHEET 7: Menentukan judul halaman untuk header dinamis
$page_title = "Beranda";

// JOBSHEET 7: Menggunakan include untuk memuat header & navigasi modular
include __DIR__ . '/includes/header.php';

// JOBSHEET 7: Menghitung total data dinamis dari $_SESSION
$totalBuku = count($_SESSION['buku'] ?? []);
$totalAnggota = count($_SESSION['anggota'] ?? []);
?>
        <section>
            <h2>Selamat Datang di Sistem Perpustakaan Mini</h2>
            <p>Aplikasi sederhana untuk mengelola data buku dan anggota perpustakaan.</p>
            
            <!-- Modifikasi: Informasi Sistem dari latihan sebelumnya -->
            <pre>INFO_SISTEM: Versi_Aplikasi="1.0.0-Jobsheet7" | Modul_Aktif=["Autentikasi", "Katalog_Buku", "Manajemen_Anggota", "Sirkulasi_Peminjaman"] | Server_Environment="Production-Localhost"</pre>
        </section>

        <!-- Ringkasan Statistik (Dinamis dari Session & Grid 4 Kartu) -->
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
            <!-- Modifikasi: Kartu Keempat dari jobsheet sebelumnya -->
            <article>
                <h3>Buku Terlambat</h3>
                <p>0</p>
            </article>
        </section>
<?php 
// JOBSHEET 7: Menggunakan include untuk memuat footer & script pendukung
include __DIR__ . '/includes/footer.php'; 
?>