<?php
// [Jobsheet 10] Guard Auth: Memastikan halaman hanya bisa diakses setelah login
require __DIR__ . '/../includes/auth.php';
$page_title = "Daftar Anggota";
// [Jobsheet 07] Includes layout header & koneksi database PDO
include __DIR__ . '/../includes/header.php';
require __DIR__ . '/../includes/koneksi.php';

// [Jobsheet 07] Menampilkan dan menghapus flash message session
$flash = $_SESSION['flash'] ?? null;
unset($_SESSION['flash']);

// [Jobsheet 09] Logika Pagination (Pembatasan data per halaman)
$perPage = 5;
$page = max(1, (int) ($_GET['page'] ?? 1));
$offset = ($page - 1) * $perPage;
// [Jobsheet 09] Fitur Pencarian Data
$keyword = trim($_GET['q'] ?? '');

if ($keyword !== '') {
    // [Jobsheet 08 & 09] Prepared Statement PDO + Klausa ILIKE (Case-insensitive PostgreSQL)
    $hitung = $pdo->prepare("SELECT COUNT(*) FROM anggota WHERE nama ILIKE :kw OR no_anggota ILIKE :kw OR no_hp ILIKE :kw");
    $hitung->execute(['kw' => '%' . $keyword . '%']);
    $totalRows = $hitung->fetchColumn();

    $stmt = $pdo->prepare("SELECT * FROM anggota WHERE nama ILIKE :kw OR no_anggota ILIKE :kw OR no_hp ILIKE :kw ORDER BY id DESC LIMIT :limit OFFSET :offset");
    $stmt->bindValue('kw', '%' . $keyword . '%');
} else {
    $totalRows = $pdo->query("SELECT COUNT(*) FROM anggota")->fetchColumn();
    $stmt = $pdo->prepare("SELECT * FROM anggota ORDER BY id DESC LIMIT :limit OFFSET :offset");
}
$stmt->bindValue('limit', $perPage, PDO::PARAM_INT);
$stmt->bindValue('offset', $offset, PDO::PARAM_INT);
$stmt->execute();

$daftarAnggota = $stmt->fetchAll(PDO::FETCH_ASSOC);
$totalPages = max(1, (int) ceil($totalRows / $perPage));
?>
        <section>
            <h2>Daftar Anggota</h2>

            <?php if ($flash): ?>
                <p class="flash flash-<?php echo $flash['type']; ?>"><?php echo e($flash['pesan']); ?></p>
            <?php endif; ?>

            <!-- [Jobsheet 01 & 04] UI Form Pencarian -->
            <div class="search-box">
                <form method="get" action="list.php">
                    <span>
                        <label for="search-input">Cari Anggota</label><br>
                        <!-- [Jobsheet 11] Fungsi e() untuk mencegah XSS pada pencetakan keyword -->
                        <input type="text" id="search-input" name="q" value="<?php echo e($keyword); ?>" placeholder="Ketik nama, no anggota, atau no hp...">
                    </span>
                    <button type="submit">Cari</button>
                </form>
            </div>

            <!-- [Jobsheet 03] Tabel Responsif -->
            <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>No. Anggota</th>
                        <th>Nama</th>
                        <th>Alamat</th>
                        <th>No. HP</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($daftarAnggota)): ?>
                    <tr>
                        <td colspan="5">Tidak ada data anggota yang cocok.</td>
                    </tr>
                    <?php else: ?>
                        <?php foreach ($daftarAnggota as $anggota): ?>
                        <tr>
                            <!-- [Jobsheet 11] Proteksi XSS pada keluaran tabel -->
                            <td><?php echo e($anggota['no_anggota']); ?></td>
                            <td><?php echo e($anggota['nama']); ?></td>
                            <td><?php echo e($anggota['alamat']); ?></td>
                            <td><?php echo e($anggota['no_hp']); ?></td>
                            <td>
                                <a href="edit.php?id=<?php echo $anggota['id']; ?>" class="btn-edit">Edit</a>
                                <!-- [Jobsheet 09 & 11] Form Hapus aman menggunakan POST & CSRF Token -->
                                <form class="form-hapus" method="post" action="hapus.php">
                                    <input type="hidden" name="id" value="<?php echo $anggota['id']; ?>">
                                    <?php echo csrf_field(); ?>
                                    <button type="submit" class="btn-hapus">Hapus</button>
                                </form>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
            </div>

            <!-- [Jobsheet 09] UI Navigasi Pagination -->
            <nav class="pagination">
                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                <a href="list.php?page=<?php echo $i; ?><?php echo $keyword !== '' ? '&q=' . urlencode($keyword) : ''; ?>"
                   class="<?php echo $i === $page ? 'active' : ''; ?>"><?php echo $i; ?></a>
                <?php endfor; ?>
            </nav>
        </section>
<?php include __DIR__ . '/../includes/footer.php'; ?>