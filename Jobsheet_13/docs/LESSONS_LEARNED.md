# Lessons Learned: Refleksi Pembelajaran Pemrograman Web (Jobsheet 1–13)

**Oleh:** Alya Fazilatun Nisa  
**Program Studi:** D-IV Sistem Informasi Bisnis — Politeknik Negeri Malang  

---

### Konsep Paling Menantang: Pengelolaan Keamanan Transaksi Database & State Management Terisolasi

Sepanjang menyelesaikan 13 jobsheet pada mata kuliah Pemrograman Web, topik yang paling menantang sekaligus krusial dalam pembangunan aplikasi web skala riil adalah **kombinasi antara Database Transaction (Jobsheet 12), Pengelolaan Environment Variables (Jobsheet 13), dan Proteksi Keamanan Sesi/CSRF (Jobsheet 10 & 11)**.

#### Mengapa Konsep Ini Paling Menantang?

1. **Pergeseran Pola Pikir dari CRUD Sederhana ke Transaksi Atomik (ACID)**  
   Pada awal praktikum, penulisan operasi database hanya berfokus pada eksekusi `INSERT` atau `UPDATE` secara langsung. Namun, saat mengimplementasikan fitur peminjaman buku pada aplikasi **SIMPUS-Mini**, satu aksi pengguna melibatkan multiple langkah kritis: penguncian baris data buku (`FOR UPDATE`), pembuatan record peminjaman baru, dan pengurangan stok buku secara simultan.  
   Menjaga sifat atomisitas agar tidak terjadi *race condition* atau inkonsistensi data ketika salah satu query gagal membutuhkan pemahaman mendalam tentang siklus `beginTransaction()`, `commit()`, serta penanganan *exception* menggunakan `inTransaction()` pada blok `rollBack()`.

2. **Abstraksi Konfigurasi dan Isolasi Variabel Sensitif**  
   Penerapan file `.env` dan `.env.example` mengajarkan pentingnya memisahkan kredensial database dari kode program utama. Tantangan utama di lapangan adalah memastikan pemisahan *environment* berjalan mulus di tingkat lokal tanpa membocorkan data sensitif ke repositori publik (Git) melalui kedisiplinan penggunaan `.gitignore`.

3. **Integritas Validasi Aturan Bisnis Multi-Layer**  
   Mengintegrasikan validasi aturan bisnis—seperti penolakan otomatis peminjaman bagi anggota yang terlambat mengembalikan buku lebih dari 14 hari—menuntut ketelitian kalkulasi di sisi DBMS (`CURRENT_DATE - tanggal_pinjam > 14`) serta pengiriman umpan balik pengguna yang responsif melalui sistem *Flash Message* berbasis sesi PHP.

---

### Pelajaran Utama (Key Takeaways) untuk UAS

* **Sensitivitas Jalur Eksekusi Kode (Error Handling):** Penanganan kesalahan tidak boleh sekadar menampilkan pesan error, melainkan harus menjamin status koneksi database kembali bersih dan aman sebelum mengarahkan pengguna kembali (*redirection*).
* **Keamanan Praktik Industri (Best Practices):** Penggunaan *prepared statements*, pencegahan CSRF, dan pemisahan `.env` dari *version control* bukan sekadar teori formal, melainkan pondasi utama dalam membangun perangkat lunak kelas produksi.
* **Kedisiplinan Alur Kerja Git:** Pengelolaan repositori yang rapi dengan *commit message* yang bermakna sangat membantu penelusuran histori perubahan aplikasi selama proses pengembangan.