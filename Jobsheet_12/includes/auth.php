<?php
// [Jobsheet 10] Guard Clause: Di-include paling atas pada halaman yang membutuhkan login.
// Memastikan session aktif dan pengguna sudah terautentikasi sebelum bisa mengakses halaman.
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id'])) {
    header('Location: ../auth/login.php');
    exit;
}