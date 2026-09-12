// Latihan 2 Jobsheet 6: Menggunakan Fungsi Generik
document.addEventListener("DOMContentLoaded", function () {
    // latihan 3: menambahkan properti "kategori" ke daftar keysBuku
    const keysBuku = ["judul", "pengarang", "tahun", "stok", "kategori"];

    // 1. Muat data pertama kali saat halaman dibuka
    muatDataTabel("../data/buku.json", keysBuku);

    // 2. Pasang event listener ke tombol Muat Ulang
    const btnReload = document.getElementById("btn-muat-ulang");
    if (btnReload) {
        btnReload.addEventListener("click", function () {
            // Panggil fungsi generik yang sama
            muatDataTabel("../data/buku.json", keysBuku);
        });
    }
});