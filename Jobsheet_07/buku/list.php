<?php
// JOBSHEET 7: Menentukan judul halaman untuk header dinamis
$page_title = "Daftar Buku";

// JOBSHEET 7: Menggunakan include untuk memuat header & navigasi modular
include __DIR__ . '/../includes/header.php';

// JOBSHEET 7: Mengambil flash message dari session lalu langsung menghapusnya
$flash = $_SESSION['flash'] ?? null;
unset($_SESSION['flash']);

// JOBSHEET 7: Mengambil data buku yang tersimpan di $_SESSION['buku']
$daftarBuku = $_SESSION['buku'] ?? [];
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
                    <!-- JOBSHEET 7: Pengecekan jika data session buku masih kosong -->
                    <?php if (empty($daftarBuku)): ?>
                    <tr>
                        <!-- Modifikasi: colspan="6" disesuaikan dengan total 6 kolom -->
                        <td colspan="6">Belum ada data buku. Silakan tambah lewat menu "Tambah Buku".</td>
                    </tr>
                    <?php else: ?>
                        <!-- JOBSHEET 7: Render baris tabel secara dinamis dari array $_SESSION['buku'] -->
                        <?php foreach ($daftarBuku as $buku): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($buku['judul'] ?? ''); ?></td>
                            <td><?php echo htmlspecialchars($buku['pengarang'] ?? ''); ?></td>
                            <td><?php echo (int) ($buku['tahun'] ?? 0); ?></td>
                            <td><?php echo (int) ($buku['stok'] ?? 0); ?></td>
                            <td><?php echo htmlspecialchars($buku['kategori'] ?? '-'); ?></td>
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