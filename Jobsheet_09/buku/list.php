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

// JOBSHEET 9: Fitur Pagination & Pencarian Sisi Server
$perPage = 10; // latihan 2 jobsheet 9: Menampilkan 10 data per halaman
$page = max(1, (int) ($_GET['page'] ?? 1));
$offset = ($page - 1) * $perPage;
$keyword = trim($_GET['q'] ?? '');

if ($keyword !== '') {
    $hitung = $pdo->prepare("SELECT COUNT(*) FROM buku WHERE judul ILIKE :kw");
    $hitung->execute(['kw' => '%' . $keyword . '%']);
    $totalRows = $hitung->fetchColumn();

    $stmt = $pdo->prepare("SELECT * FROM buku WHERE judul ILIKE :kw ORDER BY id DESC LIMIT :limit OFFSET :offset");
    $stmt->bindValue('kw', '%' . $keyword . '%');
} else {
    $totalRows = $pdo->query("SELECT COUNT(*) FROM buku")->fetchColumn();
    $stmt = $pdo->prepare("SELECT * FROM buku ORDER BY id DESC LIMIT :limit OFFSET :offset");
}
$stmt->bindValue('limit', $perPage, PDO::PARAM_INT);
$stmt->bindValue('offset', $offset, PDO::PARAM_INT);
$stmt->execute();

$daftarBuku = $stmt->fetchAll(PDO::FETCH_ASSOC);
$totalPages = max(1, (int) ceil($totalRows / $perPage));
?>
        <section>
            <h2>Daftar Buku</h2>

            <!-- JOBSHEET 7: Menampilkan flash message jika ada -->
            <?php if ($flash): ?>
                <p class="flash flash-<?php echo $flash['type']; ?>"><?php echo $flash['pesan']; ?></p>
            <?php endif; ?>

            <!-- JOBSHEET 9: Form Pencarian Server (Name="q") -->
            <div class="search-box">
                <form method="get" action="list.php">
                    <span>
                        <label for="search-input">Cari Judul Buku</label><br>
                        <input type="text" id="search-input" name="q" value="<?php echo $keyword; ?>" placeholder="Ketik judul buku...">
                    </span>
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
                        <!-- Modifikasi Latihanmu tetap dipertahankan -->
                        <th>Kategori</th>
                        <th>Tanggal Ditambahkan</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($daftarBuku)): ?>
                    <tr>
                        <td colspan="7">Tidak ada data buku yang cocok.</td>
                    </tr>
                    <?php else: ?>
                        <?php foreach ($daftarBuku as $buku): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($buku['judul']); ?></td>
                            <td><?php echo htmlspecialchars($buku['pengarang']); ?></td>
                            <td><?php echo htmlspecialchars($buku['tahun']); ?></td>
                            <td><?php echo htmlspecialchars($buku['stok']); ?></td>
                            <td><?php echo htmlspecialchars($buku['kategori']); ?></td>
                            <td><?php echo htmlspecialchars($buku['tanggal_ditambahkan']); ?></td>
                            <td>
                                <!-- JOBSHEET 9: Tautan Edit dan Form POST Hapus -->
                                <a href="edit.php?id=<?php echo $buku['id']; ?>" class="btn-edit">Edit</a>
                                <form class="form-hapus" method="post" action="hapus.php">
                                    <input type="hidden" name="id" value="<?php echo $buku['id']; ?>">
                                    <button type="submit" class="btn-hapus">Hapus</button>
                                </form>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
            </div>

            <!-- JOBSHEET 9: Navigasi Angka Halaman (Pagination) -->
            <nav class="pagination">
                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                <a href="list.php?page=<?php echo $i; ?><?php echo $keyword !== '' ? '&q=' . urlencode($keyword) : ''; ?>"
                   class="<?php echo $i === $page ? 'active' : ''; ?>"><?php echo $i; ?></a>
                <?php endfor; ?>
            </nav>
        </section>

<?php
// JOBSHEET 7: Menggunakan include untuk memuat footer & script pendukung
include __DIR__ . '/../includes/footer.php';
?>