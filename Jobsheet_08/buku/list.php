<?php
// JOBSHEET 7: Menentukan judul halaman untuk header dinamis
$page_title = "Daftar Buku";

// JOBSHEET 7: Menggunakan include untuk memuat header & navigasi modular
include __DIR__ . '/../includes/header.php';

// JOBSHEET 8: Menghubungkan halaman dengan database PostgreSQL
require __DIR__ . '/../includes/koneksi.php';

// JOBSHEET 7: Mengambil flash message dari session lalu langsung menghapusnya
$flash = $_SESSION['flash'] ?? null;
unset($_SESSION['flash']);

// LATIHAN 3 JOBSHEET 8: Pencarian buku di server
// Mengambil keyword dari kolom pencarian
$keyword = trim($_GET['keyword'] ?? '');

// Menyiapkan query pencarian menggunakan ILIKE
$stmt = $pdo->prepare(
    "SELECT * FROM buku
     WHERE judul ILIKE :keyword
     ORDER BY id DESC"
);

// Mengirim keyword ke parameter query
$stmt->execute([
    'keyword' => '%' . $keyword . '%'
]);

// Mengambil hasil query dari database
$daftarBuku = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
        <section>
            <h2>Daftar Buku</h2>

            <!-- JOBSHEET 7: Menampilkan flash message jika ada -->
            <?php if ($flash): ?>
                <p class="flash flash-<?php echo $flash['type']; ?>"><?php echo $flash['pesan']; ?></p>
            <?php endif; ?>

           <!-- LATIHAN 3 JOBSHEET 8: Kolom pencarian server-side -->
            <div class="search-box">
                <form method="GET">
                    <label for="search-input">Cari Judul Buku</label>
                    <input
                        type="text"
                        id="search-input"
                        name="keyword"
                        placeholder="Ketik judul buku..."
                        value="<?php echo htmlspecialchars($keyword); ?>"
                    >
                    <button type="submit">Cari</button>
                </form>
            </div>

            <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>Judul</th>
                        <th>Pengarang</th>
                        <th>Tahun</th>
                        <th>Stok</th>

                        <!-- Modifikasi: Kolom Kategori dari jobsheet sebelumnya -->
                        <th>Kategori</th>
                        <th>Tanggal Ditambahkan</th>
                        <th>Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    <?php if (empty($daftarBuku)): ?>
                    <tr>
                        <!-- Modifikasi: colspan diubah menjadi 6 karena ada kolom Kategori -->
                        <td colspan="7">Belum ada data buku. Silakan tambah lewat menu "Tambah Buku".</td>
                    </tr>
                    <?php else: ?>

                        <!-- JOBSHEET 8: Menampilkan data buku dari database PostgreSQL -->
                        <?php foreach ($daftarBuku as $buku): ?>
                        <tr>
                            <td><?php echo $buku['judul']; ?></td>
                            <td><?php echo $buku['pengarang']; ?></td>
                            <td><?php echo $buku['tahun']; ?></td>
                            <td><?php echo $buku['stok']; ?></td>

                            <!-- Modifikasi: Menampilkan kolom Kategori dari jobsheet sebelumnya -->
                            <td><?php echo $buku['kategori']; ?></td>
                            <!-- LATIHAN 2: Menampilkan tanggal ditambahkan --> 
                            <td><?php echo $buku['tanggal_ditambahkan']; ?></td>
                            <td>
                                <button type="button">Edit</button>
                                <button type="button" class="btn-hapus">Hapus</button>
                            </td>
                        </tr>
                        <?php endforeach; ?>

                    <?php endif; ?>
                </tbody>
            </table>
            </div>
        </section>

<?php
// JOBSHEET 7: Menggunakan include untuk memuat footer & script pendukung
include __DIR__ . '/../includes/footer.php';
?>