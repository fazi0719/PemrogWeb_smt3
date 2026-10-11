<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
// [Jobsheet 10] Redirect jika sudah pernah login
if (isset($_SESSION['user_id'])) {
    header('Location: ../index.php');
    exit;
}

$page_title = "Login";
include __DIR__ . '/../includes/header.php';

$flash = $_SESSION['flash'] ?? null;
unset($_SESSION['flash']);
?>
        <section>
            <h2>Login Petugas</h2>

            <?php if ($flash): ?>
                <p class="flash flash-<?php echo $flash['type']; ?>"><?php echo e($flash['pesan']); ?></p>
            <?php endif; ?>

            <!-- [Jobsheet 10] Form Autentikasi User -->
            <form method="post" action="proses_login.php">
                <?php echo csrf_field(); ?>
                <p>
                    <label for="username">Username</label><br>
                    <input type="text" id="username" name="username" required>
                </p>
                <p>
                    <label for="password">Password</label><br>
                    <input type="password" id="password" name="password" required>
                </p>
                <p>
                    <button type="submit">Masuk</button>
                </p>
            </form>
            <p>Belum punya akun? <a href="register.php">Daftar di sini</a></p>
        </section>
<?php include __DIR__ . '/../includes/footer.php'; ?>