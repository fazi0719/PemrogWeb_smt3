<?php
// [Jobsheet 10] Guard Auth
require __DIR__ . '/../includes/auth.php';
$page_title = "Edit Anggota";
include __DIR__ . '/../includes/header.php';
// [Jobsheet 08] Koneksi Database
require __DIR__ . '/../includes/koneksi.php';

$flash = $_SESSION['flash'] ?? null;
unset($_SESSION['flash']);

// [Jobsheet 09] Ambil ID dari URL
$id = $_GET['id'] ?? null;
if (!$id) {
    header('Location: list.php');
    exit;
}

// [Jobsheet 09 & 11] Ambil data lama dengan Prepared Statement
$stmt = $pdo->prepare("SELECT * FROM anggota WHERE id = :id");
$stmt->execute(['id' => $id]);
$anggota = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$anggota) {
    header('Location: list.php');
    exit;
}
?>
        <section>
            <h2>Edit Anggota</h2>

            <?php if ($flash): ?>
                <p class="flash flash-<?php echo $flash['type']; ?>"><?php echo $flash['pesan']; ?></p>
            <?php endif; ?>

            <form id="form-tambah" method="post" action="proses_edit.php">
                <!-- [Jobsheet 11] CSRF Field -->
                <?php echo csrf_field(); ?>
                <!-- [Jobsheet 09] Primary Key Hidden Input -->
                <input type="hidden" name="id" value="<?php echo (int) $anggota['id']; ?>">
                <p>
                    <label for="nama">Nama</label><br>
                    <!-- [Jobsheet 11] Sanitasi Output e() Mencegah XSS -->
                    <input type="text" id="nama" name="nama" value="<?php echo e($anggota['nama']); ?>" required>
                </p>
                <p>
                    <label for="no_anggota">No. Anggota</label><br>
                    <input type="text" id="no_anggota" name="no_anggota" value="<?php echo e($anggota['no_anggota']); ?>" required>
                </p>
                <p>
                    <label for="alamat">Alamat</label><br>
                    <input type="text" id="alamat" name="alamat" value="<?php echo e($anggota['alamat']); ?>">
                </p>
                <p>
                    <label for="no_hp">No. HP</label><br>
                    <input type="text" id="no_hp" name="no_hp" value="<?php echo e($anggota['no_hp']); ?>">
                </p>
                <p>
                    <button type="submit">Update</button>
                </p>
            </form>
        </section>
<?php include __DIR__ . '/../includes/footer.php'; ?>