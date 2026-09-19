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

// JOBSHEET 8: Mengambil data buku dari database PostgreSQL
$daftarBuku = $pdo->query("SELECT * FROM buku ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);
?>
        <section>
            <h2>Daftar Buku</h2>

            <!-- JOBSHEET 7: Menampilkan flash message jika ada -->
            <?php if ($flash): ?>
                <p class="flash flash-<?php echo $flash['type']; ?>"><?php echo $flash['pesan']; ?></p>
            <?php endif; ?>

            <!-- JOBSHEET 5: Kolom pencarian real-time (tetap dipertahankan) -->
            <div class="search-box">
                <label for="search-input">Cari Judul Buku</label>
                <input type="text" id="search-input" placeholder="Ketik judul buku...">
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

                        <th>Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    <?php if (empty($daftarBuku)): ?>
                    <tr>
                        <!-- Modifikasi: colspan diubah menjadi 6 karena ada kolom Kategori -->
                        <td colspan="6">Belum ada data buku. Silakan tambah lewat menu "Tambah Buku".</td>
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