<?php
// JOBSHEET 7: Menentukan judul halaman untuk header dinamis
$page_title = "Edit Buku";

// JOBSHEET 7: Menggunakan include untuk memuat header & navigasi modular
include __DIR__ . '/../includes/header.php';

// JOBSHEET 8: Menghubungkan PHP dengan database PostgreSQL
require __DIR__ . '/../includes/koneksi.php';

// JOBSHEET 7: Mengambil flash message dari session lalu menghapusnya
$flash = $_SESSION['flash'] ?? null;
unset($_SESSION['flash']);

// JOBSHEET 9: Mengambil ID buku dari parameter URL ($_GET['id'])
$id = $_GET['id'] ?? null;
if (!$id) {
    header('Location: list.php');
    exit;
}

// JOBSHEET 9: Ambil data lama buku dari database
$stmt = $pdo->prepare("SELECT * FROM buku WHERE id = :id");
$stmt->execute(['id' => $id]);
$buku = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$buku) {
    header('Location: list.php');
    exit;
}
?>
        <section>
            <h2>Edit Buku</h2>

            <!-- JOBSHEET 7: Menampilkan flash message jika ada -->
            <?php if ($flash): ?>
                <p class="flash flash-<?php echo $flash['type']; ?>"><?php echo $flash['pesan']; ?></p>
            <?php endif; ?>

            <form id="form-tambah" method="post" action="proses_edit.php">
                <!-- JOBSHEET 9: Field tersembunyi untuk membawa ID buku -->
                <input type="hidden" name="id" value="<?php echo $buku['id']; ?>">
                
                <p>
                    <label for="judul">Judul</label><br>
                    <input type="text" id="judul" name="judul" value="<?php echo $buku['judul']; ?>" required>
                </p>
                <p>
                    <label for="pengarang">Pengarang</label><br>
                    <input type="text" id="pengarang" name="pengarang" value="<?php echo $buku['pengarang']; ?>" required>
                </p>
                <p>
                    <label for="tahun">Tahun Terbit</label><br>
                    <input type="number" id="tahun" name="tahun" min="1900" max="2026" value="<?php echo $buku['tahun']; ?>" required>
                </p>
                <p>
                    <label for="isbn">ISBN</label><br>
                    <input type="text" id="isbn" name="isbn" value="<?php echo $buku['isbn']; ?>">
                </p>
                <p>
                    <label for="stok">Stok</label><br>
                    <input type="number" id="stok" name="stok" min="0" value="<?php echo $buku['stok']; ?>" required>
                </p>
                <p>
                    <label for="kategori">Kategori</label><br>
                    <select id="kategori" name="kategori">
                        <?php foreach (['fiksi' => 'Fiksi', 'non-fiksi' => 'Non-Fiksi', 'referensi' => 'Referensi'] as $value => $label): ?>
                        <option value="<?php echo $value; ?>" <?php echo $buku['kategori'] === $value ? 'selected' : ''; ?>><?php echo $label; ?></option>
                        <?php endforeach; ?>
                    </select>
                </p>
                <p>
                    <button type="submit">Update</button>
                </p>
            </form>
        </section>

<?php 
// JOBSHEET 7: Menggunakan include untuk memuat footer & script pendukung
include __DIR__ . '/../includes/footer.php'; 
?>