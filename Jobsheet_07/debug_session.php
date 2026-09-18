<?php
// JOBSHEET 7: Memulai session untuk mengakses data $_SESSION
session_start();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Debug Session — SIMPUS-Mini</title>
    <style>
        body {
            font-family: monospace;
            background-color: #1e1e1e;
            color: #4af626;
            padding: 20px;
        }
        .container {
            background-color: #2d2d2d;
            border: 1px solid #444;
            border-radius: 8px;
            padding: 15px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.5);
        }
        h2 {
            color: #fff;
            border-bottom: 1px solid #555;
            padding-bottom: 10px;
            margin-top: 0;
        }
        pre {
            font-size: 14px;
            line-height: 1.5;
            white-space: pre-wrap;
            word-wrap: break-word;
        }
        a {
            color: #00bcd4;
            text-decoration: none;
        }
        a:hover {
            text-decoration: underline;
        }Berikut adalah kode lengkap untuk file **`debug_session.php`**:

```php
<?php
// Pastikan session sudah diaktifkan sebelum mengakses $_SESSION
session_start();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Debug Session</title>
    <style>
        body {
            font-family: monospace;
            padding: 20px;
            background-color: #f4f4f4;
        }
        pre {
            background: #272822;
            color: #f8f8f2;
            padding: 15px;
            border-radius: 5px;
            overflow-x: auto;
        }
    </style>
</head>
<body>

    <h2>Isi $_SESSION Saat Ini:</h2>
    <pre><?php print_r($_SESSION); ?></pre>

</body>
</html>