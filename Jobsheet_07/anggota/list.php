<?php
// JOBSHEET 7: Menentukan judul halaman untuk header dinamis
$page_title = "Daftar Anggota";

// JOBSHEET 7: Menggunakan include untuk memuat header & navigasi modular
include __DIR__ . '/../includes/header.php';

// JOBSHEET 7: Mengambil flash message dari session lalu langsung menghapusnya (sekali tampil)
$flash = $_SESSION['flash'] ?? null;
unset($_SESSION['flash']);

// JOBSHEET 7: Mengambil data anggota yang tersimpan di $_SESSION['anggota']
$daftarAnggota = $_SESSION['anggota'] ?? [];
?>
        <section>
            <h2>Daftar Anggota</h2>

            <!-- JOBSHEET 7: Menampilkan flash message (sukses/error) jika ada -->
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
                        <!-- Modifikasi: Kolom ke-5 (Tanggal Bergabung) dari jobsheet sebelumnya -->
                        <th>Tanggal Bergabung</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- JOBSHEET 7: Pengecekan jika data session anggota masih kosong -->
                    <?php if (empty($daftarAnggota)): ?>
                    <tr>
                        <!-- Modifikasi: colspan="6" disesuaikan dengan total 6 kolom -->
                        <td colspan="6">Belum ada data anggota. Silakan tambah lewat menu "Tambah Anggota".</td>
                    </tr>
                    <?php else: ?>
                        <!-- JOBSHEET 7: Render baris tabel secara dinamis dari array $_SESSION menggunakan foreach PHP -->
                        <?php foreach ($daftarAnggota as $anggota): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($anggota['no_anggota'] ?? ''); ?></td>
                            <td><?php echo htmlspecialchars($anggota['nama'] ?? ''); ?></td>
                            <td><?php echo htmlspecialchars($anggota['alamat'] ?? ''); ?></td>
                            <td><?php echo htmlspecialchars($anggota['no_hp'] ?? ''); ?></td>
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
include __DIR__ . '/../includes/footer.php'; 
?>