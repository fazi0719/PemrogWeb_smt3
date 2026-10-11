<?php
// [Jobsheet 10] Guard Auth: Memastikan hanya pengguna yang sudah login yang dapat mengakses form ini
require __DIR__ . '/../includes/auth.php';

$page_title = "Tambah Anggota";

// [Jobsheet 01 & 03] Menampilkan struktur Layout Header utama
include __DIR__ . '/../includes/header.php';

// [Jobsheet 07] Menangkap & menghapus flash message dari session setelah ditampilkan
$flash = $_SESSION['flash'] ?? null;
unset($_SESSION['flash']);
?>
        <section>
            <h2>Tambah Anggota</h2>

            <!-- [Jobsheet 07 & 11] Menampilkan pesan Flash Error (berwarna merah) jika validasi gagal/duplikat -->
            <?php if ($flash): ?>
                <p class="flash flash-<?php echo $flash['type']; ?>"><?php echo e($flash['pesan']); ?></p>
            <?php endif; ?>

            <!-- [Jobsheet 01 & 04] Form penambahan data dengan method POST ke proses_tambah.php -->
            <form id="form-tambah" method="post" action="proses_tambah.php">
                <!-- [Jobsheet 11] Proteksi CSRF: Menyisipkan input hidden token csrf -->
                <?php echo csrf_field(); ?>
                <p>
                    <label for="nama">Nama</label><br>
                    <input type="text" id="nama" name="nama" required>
                </p>
                <p>
                    <!-- [Jobsheet 08 & 12] Kolom Unik: No. Anggota tidak boleh ganda -->
                    <label for="no_anggota">No. Anggota (Kode Unik)</label><br>
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
                <p>
                    <button type="submit">Simpan</button>
                </p>
            </form>
        </section>
<?php include __DIR__ . '/../includes/footer.php'; ?>