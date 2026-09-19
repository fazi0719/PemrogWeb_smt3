<?php
// JOBSHEET 7: Memulai session untuk seluruh halaman
session_start();

// JOBSHEET 7: Perhitungan path relatif otomatis ($base) agar tautan selalu benar
$__jobsheetRoot = dirname(__DIR__);
$__scriptDir = dirname($_SERVER['SCRIPT_FILENAME']);
$__rel = ltrim(str_replace('\\', '/', substr($__scriptDir, strlen($__jobsheetRoot))), '/');
$base = $__rel === '' ? '' : str_repeat('../', substr_count($__rel, '/') + 1);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SIMPUS-Mini<?php echo isset($page_title) ? ' | ' . $page_title : ''; ?></title>
    <link rel="stylesheet" href="<?php echo $base; ?>assets/css/style.css">
</head>
<body>
    <header>
        <h1>SIMPUS-Mini</h1>
        <!-- JOBSHEET 5: Tombol hamburger menu -->
        <button type="button" id="nav-toggle-btn" class="nav-toggle-label" aria-label="Menu">&#9776;</button>
        <nav>
            <ul>
                <li><a href="<?php echo $base; ?>index.php">Beranda</a></li>
                <li><a href="<?php echo $base; ?>buku/list.php">Daftar Buku</a></li>
                <li><a href="<?php echo $base; ?>buku/tambah.php">Tambah Buku</a></li>
                <li><a href="<?php echo $base; ?>anggota/list.php">Daftar Anggota</a></li>
                <li><a href="<?php echo $base; ?>anggota/tambah.php">Tambah Anggota</a></li>
                <!-- Tambahan Tombol Reset Data -->
                <li>
                    <a href="<?php echo $base; ?>reset.php" 
                    onclick="return confirm('Apakah Anda yakin ingin mengosongkan seluruh data session?');" 
                    style="background-color: #dc3545; color: white; padding: 5px 10px; border-radius: 4px; font-weight: bold; margin-left: 10px;">
                    Reset Data
                    </a>
                </li>
            </ul>
        </nav>
    </header>

    <main>