# Wireframe & User Flow — SIMPUS-Mini

Sub-CPMK: Merancang UI/UX aplikasi (proyek).

Halaman yang sudah ada (Beranda, Daftar/Tambah Buku, Daftar/Tambah Anggota — Jobsheet 1-3) belum mencakup fitur Login, Dashboard Petugas, dan Peminjaman/Pengembalian. Dokumen ini merancang wireframe untuk halaman-halaman tersebut sebelum diimplementasikan mulai Jobsheet 5 dan seterusnya.

## Aktor
- **Tamu**: hanya bisa melihat katalog buku (Beranda, Daftar Buku) tanpa login.
- **Petugas**: login untuk mengakses seluruh fitur CRUD dan transaksi peminjaman.

## User Flow — Peminjaman Buku

```
[Petugas Login] -> [Dashboard] -> [Pilih menu "Peminjaman Baru"]
        -> [Pilih Anggota] -> [Pilih Buku (stok > 0)]
        -> [Simpan] -> [Stok buku berkurang 1] -> [Kembali ke Dashboard]
```

## User Flow — Pengembalian Buku

```
[Dashboard] -> [Menu "Pengembalian"] -> [Cari transaksi aktif (anggota/buku)]
        -> [Tandai "Dikembalikan"] -> [Stok buku bertambah 1]
        -> [Kembali ke Dashboard]
```

## Wireframe: Halaman Login

```
+--------------------------------------+
|              SIMPUS-Mini             |
|--------------------------------------|
|                                      |
|        [ Login Petugas ]            |
|                                      |
|   Username : [______________]       |
|   Password : [______________]       |
|                                      |
|          [   Masuk   ]              |
|                                      |
|   Belum punya akun? Daftar di sini  |
+--------------------------------------+
```

## Wireframe: Dashboard Petugas

```
+-----------------------------------------------------+
| SIMPUS-Mini      Beranda | Buku | Anggota | Peminjaman | (Nama Petugas) Logout |
|-------------------------------------------------------|
|  [Total Buku]   [Total Anggota]   [Sedang Dipinjam]    |
|                                                         |
|  Aksi Cepat:                                           |
|  [ + Peminjaman Baru ]   [ + Pengembalian ]            |
|                                                         |
|  Transaksi Terbaru                                     |
|  --------------------------------------------------    |
|  Anggota | Buku | Tgl Pinjam | Status                  |
+-----------------------------------------------------+
```

## Wireframe: Form Peminjaman

```
+--------------------------------------+
|  Form Peminjaman Buku                |
|--------------------------------------|
|  Anggota : [ dropdown pilih anggota ]|
|  Buku    : [ dropdown, hanya stok>0 ]|
|  Tanggal Pinjam : [ auto: hari ini ] |
|                                      |
|          [  Simpan Peminjaman  ]    |
+--------------------------------------+
```

## Wireframe: Form Pengembalian

```
+--------------------------------------+
|  Pengembalian Buku                   |
|--------------------------------------|
|  Cari transaksi aktif:               |
|  [ nama anggota / judul buku ______ ]|
|                                      |
|  Anggota | Buku | Tgl Pinjam | [Kembalikan] |
+--------------------------------------+
```

## Wireframe: Riwayat Peminjaman per Anggota

```
+--------------------------------------+
|  Riwayat Peminjaman — Siti Aminah    |
|--------------------------------------|
|  Buku            | Pinjam   | Kembali | Status      |
|  Laskar Pelangi   | 01/07    | 10/07   | Selesai     |
|  Bumi Manusia      | 15/07    | -       | Dipinjam    |
+--------------------------------------+
```

## Konsistensi dengan Desain yang Sudah Berjalan
- Warna aksen, tipografi navbar, dan gaya tabel/kartu mengikuti `assets/css/style.css` yang sudah dibangun sejak Jobsheet 2-3.
- Navbar akan ditambah menu **Peminjaman** dan indikator status login (nama petugas / tombol Logout) mulai implementasi di Jobsheet 10.
- Edge case yang perlu ditangani saat implementasi: buku stok habis tidak boleh dipilih di form peminjaman; anggota dengan tunggakan terlambat divalidasi di Jobsheet 12 (tugas mandiri).

---

## Tugas dan Latihan Mandiri (Bab 6)
### Latihan 1: Wireframe Form Registrasi Anggota Baru (Aktor: Tamu)
```text
+--------------------------------------------------+
|                   SIMPUS-Mini                    |
|--------------------------------------------------|
|                                                  |
|           Registrasi Anggota Baru                |
|                                                  |
|   Nomor Anggota : [ AUTO-GENERATED ]             |
|   Nama Lengkap  : [____________________________] |
|   Email         : [____________________________] |
|   Nomor Telepon : [____________________________] |
|   Alamat        : [____________________________] |
|                                                  |
|   [ Daftar Sekarang ]    [ Batal ]               |
|                                                  |
|   Sudah memiliki akun anggota? Hubungi Petugas   |
+--------------------------------------------------+

### 2. User Flow Monitoring Peminjaman Terlambat (Aktor: Petugas)
[Petugas Login] -> [Dashboard Petugas] 
        -> [Klik Kartu Statistik "Buku Terlambat"] 
        -> [Sistem Menampilkan Daftar Transaksi Lewat Jatuh Tempo] 
        -> [Petugas Memilih Data Anggota Tertentu] 
        -> [Catat Denda Keterlambatan / Kirim Notifikasi Peringatan] 
        -> [Kembali ke Dashboard]

### 3. Identifikasi Edge Case Tambahan

1. **Peminjaman Buku yang Sama Berturut-turut oleh Anggota yang Sama**
   - **Kasus:** Petugas memproses peminjaman buku yang sama untuk anggota yang sama, padahal eksemplar sebelumnya belum dikembalikan.
   - **Aturan Sistem:** Sistem mengecek riwayat sirkulasi aktif. Jika statusnya masih `"Dipinjam"`, aksi simpan ditolak dengan pesan: *"Gagal: Anggota sedang meminjam buku ini."*

2. **Anggota Memiliki Tanggungan Keterlambatan**
   - **Kasus:** Anggota mengajukan peminjaman baru saat masih memegang buku lain yang melewati batas tanggal pengembalian.
   - **Aturan Sistem:** Tombol transaksi diblokir sampai buku yang terlambat dikembalikan dan denda (jika ada) diselesaikan.

3. **Stok Buku Mendadak Habis (*Race Condition*)**
   - **Kasus:** Sisa stok buku tinggal 1, lalu dibuka oleh dua sesi petugas pada waktu bersamaan. Petugas pertama menyimpan transaksi lebih dulu.
   - **Aturan Sistem:** Validasi ulang ketersediaan stok di sisi sistem sebelum kuota dikurangi, lalu tampilkan pesan: *"Stok buku telah habis."*