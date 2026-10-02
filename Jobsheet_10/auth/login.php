<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (isset($_SESSION['user_id'])) {
    header('Location: ../index.php');
    exit;
}

$page_title = "Login";
include __DIR__ . '/../includes/header.php';

$flash = $_SESSION['flash'] ?? null;
unset($_SESSION['flash']);

// latihan 3 Pembatasan Percobaan Login (Brute-Force Protection) 
// Cek status percobaan login gagal
$login_attempts = $_SESSION['login_attempts'] ?? 0;
$max_attempts = 3; // Batas maksimal percobaan gagal
$is_blocked = $login_attempts >= $max_attempts;
?>
        <section>
            <h2>Login Petugas</h2>

            <?php if ($flash): ?>
                <p class="flash flash-<?php echo $flash['type']; ?>"><?php echo $flash['pesan']; ?></p>
            <?php endif; ?>

            <?php if ($is_blocked): ?>
                <div style="background-color: #f8d7da; color: #721c24; padding: 12px; border-radius: 6px; margin-bottom: 15px; border: 1px solid #f5c6cb;">
                    <strong>Akses Terkunci Sementara!</strong><br>
                    Anda telah gagal login sebanyak <?php echo $login_attempts; ?> kali berturut-turut. Silakan coba beberapa saat lagi atau hubungi administrator.
                </div>
            <?php endif; ?>

            <form method="post" action="proses_login.php">
                <p>
                    <label for="username">Username</label><br>
                    <input type="text" id="username" name="username" required <?php echo $is_blocked ? 'disabled' : ''; ?>>
                </p>
                <p>
                    <label for="password">Password</label><br>
                    <input type="password" id="password" name="password" required <?php echo $is_blocked ? 'disabled' : ''; ?>>
                </p>
                <p>
                    <!-- latihan 2: menambahkan checkbox "Ingat Saya" -->
                    <label style="display: flex; align-items: center; gap: 8px; cursor: pointer;">
                        <input type="checkbox" name="remember_me" value="1" <?php echo $is_blocked ? 'disabled' : ''; ?>> Ingat Saya
                    </label>
                </p>
                <p>
                    <button type="submit" <?php echo $is_blocked ? 'disabled style="opacity: 0.6; cursor: not-allowed;"' : ''; ?>>Masuk</button>
                </p>
            </form>
            <p>Belum punya akun? <a href="register.php">Daftar di sini</a></p>
        </section>
<?php include __DIR__ . '/../includes/footer.php'; ?>
