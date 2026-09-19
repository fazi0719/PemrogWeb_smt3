<?php
// JOBSHEET 7: Menentukan judul halaman untuk header dinamis
$page_title = "Daftar Anggota";

// JOBSHEET 7: Menggunakan include untuk memuat header & navigasi modular
include __DIR__ . '/../includes/header.php';

// JOBSHEET 8: Menghubungkan halaman dengan database PostgreSQL
require __DIR__ . '/../includes/koneksi.php';

// JOBSHEET 7: Mengambil flash message dari session lalu langsung menghapusnya
$flash = $_SESSION['flash'] ?? null;
unset($_SESSION['flash']);

// JOBSHEET 8: Mengambil data anggota dari database PostgreSQL
$daftarAnggota = $pdo->query("SELECT * FROM anggota ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);
?>
        <section>
            <h2>Daftar Anggota</h2>

            <!-- JOBSHEET 7: Menampilkan flash message jika ada -->
            <?php if ($flash): ?>
                <p class="flash flash-<?php echo $flash['type']; ?>"><?php echo $flash['pesan']; ?></p>
            <?php endif; ?>

            <!-- JOBSHEET 5: Kolom pencarian real-time (tetap dipertahankan) -->
            <div class="search-box">
                <label for="search-input">Cari Nama Anggota</label>
                <input type="text" id="search-input" placeholder="Ketik nama anggota...">
            </div>

            <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>No. Anggota</th>
                        <th>Nama</th>
                        <th>Alamat</th>
                        <th>No. HP</th>

                        <!-- Modifikasi: Kolom Email dari jobsheet sebelumnya -->
                        <th>Email</th>

                        <!-- Modifikasi: Kolom Tanggal Bergabung dari jobsheet sebelumnya -->
                        <th>Tanggal Bergabung</th>

                        <th>Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    <?php if (empty($daftarAnggota)): ?>
                    <tr>
                        <!-- Modifikasi: colspan="7" karena terdapat 7 kolom -->
                        <td colspan="7">Belum ada data anggota. Silakan tambah lewat menu "Tambah Anggota".</td>
                    </tr>
                    <?php else: ?>

                        <!-- JOBSHEET 8: Menampilkan data anggota dari database PostgreSQL -->
                        <?php foreach ($daftarAnggota as $anggota): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($anggota['no_anggota'] ?? ''); ?></td>
                            <td><?php echo htmlspecialchars($anggota['nama'] ?? ''); ?></td>
                            <td><?php echo htmlspecialchars($anggota['alamat'] ?? ''); ?></td>
                            <td><?php echo htmlspecialchars($anggota['no_hp'] ?? ''); ?></td>

                            <!-- Modifikasi: Menampilkan Email dari jobsheet sebelumnya -->
                            <td><?php echo htmlspecialchars($anggota['email'] ?? '-'); ?></td>

                            <!-- Modifikasi: Menampilkan Tanggal Bergabung dari jobsheet sebelumnya -->
                            <td><?php echo htmlspecialchars($anggota['tgl_bergabung'] ?? '-'); ?></td>

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
include __DIR__ . '/../includes/footer.php';?>