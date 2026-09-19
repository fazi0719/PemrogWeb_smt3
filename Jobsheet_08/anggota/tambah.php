<?php
// JOBSHEET 7: Menentukan judul halaman untuk header dinamis
$page_title = "Tambah Anggota";

// JOBSHEET 7: Menggunakan include untuk memuat header & navigasi modular
include __DIR__ . '/../includes/header.php';

// JOBSHEET 7: Mengambil flash message dari session lalu menghapusnya (sekali tampil)
$flash = $_SESSION['flash'] ?? null;
unset($_SESSION['flash']);
?>
        <section>
            <h2>Tambah Anggota</h2>

            <!-- JOBSHEET 7: Menampilkan flash message jika terjadi error/sukses -->
            <?php if ($flash): ?>
                <p class="flash flash-<?php echo $flash['type']; ?>"><?php echo $flash['pesan']; ?></p>
            <?php endif; ?>

            <!-- JOBSHEET 7: Menambahkan method="post" dan action="proses_tambah.php" -->
            <!-- JOBSHEET 5: novalidate dan id="form-tambah" tetap dipertahankan untuk JS -->
            <form id="form-tambah" method="post" action="proses_tambah.php" novalidate>
                <p>
                    <label for="nama">Nama</label><br>
                    <input type="text" id="nama" name="nama" required>
                </p>
                <p>
                    <label for="no_anggota">No. Anggota</label><br>
                    <input type="text" id="no_anggota" name="no_anggota" required>
                </p>
                <p>
                    <label for="alamat">Alamat</label><br>
                    <input type="text" id="alamat" name="alamat">
                </p>
                <p>
                    <label for="no_hp">No. HP</label><br>
                    <input type="text" id="no_hp" name="no_hp">
                </p>
                <!-- Modifikasi: Input Email dari jobsheet sebelumnya tetap dipertahankan -->
                <p>
                    <label for="email">Email</label><br>
                    <input type="email" id="email" name="email">
                </p>
                <p>
                    <button type="submit">Simpan</button>
                </p>
            </form>
        </section>
<?php 
// JOBSHEET 7: Menggunakan include untuk memuat footer & script pendukung
include __DIR__ . '/../includes/footer.php'; 
?>