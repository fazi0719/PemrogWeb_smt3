# Jobsheet CSS – Perubahan Layout dan Responsivitas

### 1. Perubahan Skema Warna
Mengubah warna tema dari `#1d5b8a` (biru tua) menjadi `#2e7d32` (hijau tua). Warna tersebut digunakan secara konsisten pada header, judul section, statistik, header tabel, tombol submit, dan link.

### 2. Menambah Kolom Statistik
Menambahkan kartu statistik **"Buku Terlambat"** pada HTML. Layout grid diubah dari:
css
repeat(3, 1fr)
menjadi:
css
repeat(4, 1fr)
Sehingga empat kartu dapat ditampilkan dalam satu baris.

### 3. Menambah Tombol Detail
Menambahkan tombol Detail di antara tombol Edit dan hapus. Karena `:first-of-type` dan `:last-of-type` menentukan elemen berdasarkan posisi, digunakan `class` khusus untuk mengatur warna masing-masing tombol agar sesuai dengan fungsinya.

### 4. Pengujian Responsivitas
Mengecilkan ukuran jendela browser secara bertahap untuk menguji responsivitas navbar. Properti `flex-wrap: wrap` membuat menu berpindah ke baris berikutnya ketika lebar layar tidak mencukupi.

