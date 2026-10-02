<?php
// Guard clause: di-include di baris paling atas setiap halaman yang
// membutuhkan login (sebelum header.php mengeluarkan output apa pun),
// agar header('Location: ...') masih bisa dipanggil.
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id'])) {
    header('Location: ../auth/login.php');
    exit;
}
// soal 1
function require_role($allowed_role) {
    if (!isset($_SESSION['role']) || $_SESSION['role'] !== $allowed_role) {
        $_SESSION['flash_error'] = "Akses ditolak: Anda tidak memiliki izin untuk tindakan ini.";
        header("Location: /Jobsheet_10/anggota/list.php");
        exit;
    }
}