<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require __DIR__ . '/../includes/koneksi.php';

// latihan 3
$max_attempts = 3;
if (($_SESSION['login_attempts'] ?? 0) >= $max_attempts) {$_SESSION['flash'] = ['type' => 'error', 'pesan' => 'Terlalu banyak percobaan gagal. Akses formulir login dikunci.'];
    header('Location: login.php');
    exit;
}

$username = trim($_POST['username'] ?? '');
$password = $_POST['password'] ?? '';

$stmt = $pdo->prepare("SELECT * FROM users WHERE username = :username");
$stmt->execute(['username' => $username]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if ($user && password_verify($password, $user['password'])) {
    // Reset counter percobaan gagal jika login berhasil
    unset($_SESSION['login_attempts']);
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['nama'] = $user['nama'];
    $_SESSION['role'] = $user['role'];

    // Latihan 2: Logika "Ingat Saya" (Remember Me)
    if (isset($_POST['remember_me'])) {
        // 1. Buat token acak yang aman
        $token = bin2hex(random_bytes(32));
        $token_hash = hash('sha256', $token);
        
        // 2. Tentukan masa berlaku cookie (30 hari)
        $expires_timestamp = time() + (30 * 24 * 60 * 60);
        $expires_datetime = date('Y-m-d H:i:s', $expires_timestamp);

        // 3. Simpan hash token ke database
        $stmt_token = $pdo->prepare("UPDATE users SET remember_token = :token, token_expires = :expires WHERE id = :id");
        $stmt_token->execute([
            'token'   => $token_hash,
            'expires' => $expires_datetime,
            'id'      => $user['id']
        ]);

        // 4. Kirim cookie ke browser
        setcookie('remember_token', $token, [
            'expires'  => $expires_timestamp,
            'path'     => '/',
            'httponly' => true,  // Keamanan: Mencegah pencurian token via JavaScript (XSS)
            'samesite' => 'Lax'   // Keamanan: Mencegah serangan CSRF
        ]);
    }
    header('Location: ../index.php');
    exit;
}

// Latihan 3: Tambah 1 ke counter kegagalan jika login salah
$_SESSION['login_attempts'] = ($_SESSION['login_attempts'] ?? 0) + 1;
$sisa = $max_attempts - $_SESSION['login_attempts'];

if ($sisa > 0) {
    $_SESSION['flash'] = ['type' => 'error', 'pesan' => "Username atau password salah. Sisa percobaan: {$sisa}"];
} else {
    $_SESSION['flash'] = ['type' => 'error', 'pesan' => "Terlalu banyak percobaan gagal. Akses formulir login dikunci!"];
}

header('Location: login.php');
exit;
