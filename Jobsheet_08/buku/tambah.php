<?php
// JOBSHEET 7: Menentukan judul halaman untuk header dinamis
$page_title = "Tambah Buku";

// JOBSHEET 7: Menggunakan include untuk memuat header & navigasi modular
include __DIR__ . '/../includes/header.php';

// JOBSHEET 7: Mengambil flash message dari session lalu menghapusnya (sekali tampil)
$flash = $_SESSION['flash'] ?? null;
unset($_SESSION['flash']);
?>
        <section>
            <h2>Tambah Buku</h2>

            <!-- JOBSHEET 7: Menampilkan flash message jika terjadi error/sukses -->
            <?php if ($flash): ?>
                <p class="flash flash-<?php echo $flash['type']; ?>"><?php echo $flash['pesan']; ?></p>
            <?php endif; ?>

            <!-- JOBSHEET 7: Menambahkan method="post" dan action="proses_tambah.php" -->
            <!-- JOBSHEET 5: novalidate dan id="form-tambah" tetap dipertahankan untuk JS -->
            <form id="form-tambah" method="post" action="proses_tambah.php" novalidate>
                <p>
                    <label for="judul">Judul</label><br>
                    <input type="text" id="judul" name="judul" required>
                </p>
                <p>
                    <label for="pengarang">Pengarang</label><br>
                    <input type="text" id="pengarang" name="pengarang" required>
                </p>
                <p>
                    <label for="tahun">Tahun Terbit</label><br>
                    <input type="number" id="tahun" name="tahun" min="1900" max="2026" required>
                </p>
                <p>
                    <label for="isbn">ISBN</label><br>
                    <input type="text" id="isbn" name="isbn">
                </p>
                <p>
                    <label for="stok">Stok</label><br>
                    <input type="number" id="stok" name="stok" min="0" required>
                </p>
                <p>
                    <label for="kategori">Kategori</label><br>
                    <select id="kategori" name="kategori">
                        <option value="fiksi">Fiksi</option>
                        <option value="non-fiksi">Non-Fiksi</option>
                        <option value="referensi">Referensi</option>
                    </select>
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