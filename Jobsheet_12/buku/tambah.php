<?php
// [Jobsheet 10] Guard Auth
require __DIR__ . '/../includes/auth.php';
$page_title = "Tambah Buku";
// [Jobsheet 01 & 03] Header Layout
include __DIR__ . '/../includes/header.php';

// [Jobsheet 07] Flash Session
$flash = $_SESSION['flash'] ?? null;
unset($_SESSION['flash']);
?>
        <section>
            <h2>Tambah Buku</h2>

            <?php if ($flash): ?>
                <p class="flash flash-<?php echo $flash['type']; ?>"><?php echo $flash['pesan']; ?></p>
            <?php endif; ?>

            <!-- [Jobsheet 01] Form Input HTML -->
            <form id="form-tambah" method="post" action="proses_tambah.php">
                <!-- [Jobsheet 11] Proteksi CSRF -->
                <?php echo csrf_field(); ?>
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
                    <!-- [Jobsheet 01] Dropdown Option -->
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
<?php include __DIR__ . '/../includes/footer.php'; ?>