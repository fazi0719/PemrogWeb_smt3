<?php
// [Jobsheet 10] Menghancurkan Session dan Clear Akun
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
session_destroy();
header('Location: login.php');
exit;