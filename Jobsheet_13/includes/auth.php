<?php
// [Jobsheet 10] Guard Clause: Di-include di paling atas halaman internal
// untuk mencegah akses tanpa login (redirect otomatis ke login.php).
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id'])) {
    header('Location: ../auth/login.php');
    exit;
}