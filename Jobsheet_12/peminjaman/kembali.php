<?php
// [Jobsheet 10] Guard Auth
require __DIR__ . '/../includes/auth.php';
$page_title = "Pengembalian Buku";
include __DIR__ . '/../includes/header.php';
require __DIR__ . '/../includes/koneksi.php';

$flash = $_SESSION['flash'] ?? null;
unset($_SESSION['flash']);

$keyword = trim($_GET['q'] ?? '');

// [Latihan 2] Ambil peminjaman aktif beserta tanggal_jatuh_tempo & penanda terlambat
$sql = "SELECT p.id, a.nama AS nama_anggota, b.judul AS judul_buku, 
               p.tanggal_pinjam, p.tanggal_jatuh_tempo,
               (CURRENT_DATE > p.tanggal_jatuh_tempo) AS is_terlambat
        FROM peminjaman p
        JOIN anggota a ON p.anggota_id = a.id
        JOIN buku b ON p.buku_id = b.id
        WHERE p.status = 'dipinjam'";

if ($keyword !== '') {
    $sql .= " AND (b.judul ILIKE :kw OR a.nama ILIKE :kw)";
}

$sql .= " ORDER BY p.tanggal_pinjam ASC";

if ($keyword !== '') {
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['kw' => '%' . $keyword . '%']);
} else {
    $stmt = $pdo->query($sql);
}
$daftarAktif = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
        <section>
            <h2>Pengembalian Buku</h2>

            <?php if ($flash): ?>
                <p class="flash flash-<?php echo $flash['type']; ?>"><?php echo e($flash['pesan']); ?></p>
            <?php endif; ?>

            <div class="search-box">
                <form method="get" action="kembali.php">
                    <span>
                        <label for="search-input">Cari anggota/buku</label><br>
                        <input type="text" id="search-input" name="q" value="<?php echo e($keyword); ?>" placeholder="Nama anggota atau judul buku...">
                    </span>
                    <button type="submit">Cari</button>
                </form>
            </div>

            <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>Anggota</th>
                        <th>Buku</th>
                        <th>Tgl Pinjam</th>
                        <th>Tgl Jatuh Tempo</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($daftarAktif)): ?>
                    <tr>
                        <td colspan="5">Tidak ada peminjaman aktif.</td>
                    </tr>
                    <?php else: ?>
                        <?php foreach ($daftarAktif as $trx): ?>
                        <tr>
                            <td><?php echo e($trx['nama_anggota']); ?></td>
                            <td><?php echo e($trx['judul_buku']); ?></td>
                            <td><?php echo $trx['tanggal_pinjam']; ?></td>
                            <td>
                                <?php echo $trx['tanggal_jatuh_tempo']; ?>
                                <?php if ($trx['is_terlambat']): ?>
                                    <span style="color: red; font-weight: bold;">(Terlambat)</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <form method="post" action="proses_kembali.php">
                                    <?php echo csrf_field(); ?>
                                    <input type="hidden" name="id" value="<?php echo $trx['id']; ?>">
                                    <button type="submit">Kembalikan</button>
                                </form>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
            </div>
        </section>
<?php include __DIR__ . '/../includes/footer.php'; ?>