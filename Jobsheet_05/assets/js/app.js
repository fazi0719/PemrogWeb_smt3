// Deskripsi: Interaktivitas DOM & Event SIMPUS-Mini (Jobsheet 5)

// 1. Menu Hamburger (JS-driven menggantikan checkbox hack)
function initNavToggle() {
    const toggleBtn = document.getElementById("nav-toggle-btn");
    const nav = document.querySelector("header nav");
    if (!toggleBtn || !nav) return;

    toggleBtn.addEventListener("click", function () {
        nav.classList.toggle("nav-open");
    });
}

// Latihan 4: Counter Jumlah Baris Tersisa
function perbaruiCounter() {
    const table = document.querySelector(".table-responsive table");
    const searchBox = document.querySelector(".search-box");
    if (!table || !searchBox) return;

    let counter = document.getElementById("table-counter");
    if (!counter) {
        counter = document.createElement("p");
        counter.id = "table-counter";
        counter.style.fontSize = "0.9rem";
        counter.style.color = "#55677a";
        counter.style.margin = "0.5rem 0 1rem 0";
        counter.style.fontWeight = "500";
        searchBox.insertAdjacentElement("afterend", counter);
    }

    const rows = table.querySelectorAll("tbody tr");
    const total = rows.length;
    let tampil = 0;

    rows.forEach(function (row) {
        if (row.style.display !== "none") {
            tampil++;
        }
    });

    counter.textContent = `Menampilkan ${tampil} dari ${total} data`;
}
// 2. Konfirmasi Hapus Data pada Tabel (Sisi Klien/Front-end, belum ke server)
function initHapusConfirm() {
    document.querySelectorAll(".btn-hapus").forEach(function (btn) {
        btn.addEventListener("click", function () {
            const row = btn.closest("tr");
            const nama = row ? row.querySelector("td")?.textContent : "data ini";
            const yakin = confirm("Yakin ingin menghapus \"" + nama + "\"?");
            if (yakin && row) {
                row.remove();
                // latihan 4 
                perbaruiCounter();
            }
        });
    });
}

// 3. Filter/Pencarian Tabel Real-Time
function initTableFilter() {
    const input = document.getElementById("search-input");
    const table = document.querySelector(".table-responsive table");
    if (!input || !table) return;

    input.addEventListener("keyup", function () {
        const keyword = input.value.toLowerCase();
        const rows = table.querySelectorAll("tbody tr");
        rows.forEach(function (row) {
            // latihan 3: Mengambil hanya elemen kolom pertama (td pertama)
            const kolomPertama = row.querySelector("td");
            const teks = kolomPertama ? kolomPertama.textContent.toLowerCase() : "";
            row.style.display = teks.includes(keyword) ? "" : "none";
        });
        // latihan 4: Memperbarui counter setelah filter
        perbaruiCounter();
    });
}

// 4. Validasi Form (Client-Side)
function tampilkanError(input, pesan) {
    hapusError(input);
    const span = document.createElement("span");
    span.className = "error";
    span.textContent = pesan;
    input.insertAdjacentElement("afterend", span);
}

function hapusError(input) {
    const next = input.nextElementSibling;
    if (next && next.classList.contains("error")) {
        next.remove();
    }
}

function initValidasiForm() {
    const form = document.getElementById("form-tambah");
    if (!form) return;

    form.addEventListener("submit", function (e) {
        let valid = true;

        // Validasi Judul Buku atau Nama Anggota
        const judul = form.querySelector("[name='judul'], [name='nama']");
        if (judul && judul.value.trim() === "") {
            tampilkanError(judul, "Field ini wajib diisi.");
            valid = false;
        } else if (judul) {
            hapusError(judul);
        }

        // Validasi Pengarang
        const pengarang = form.querySelector("[name='pengarang']");
        if (pengarang && pengarang.value.trim() === "") {
            tampilkanError(pengarang, "Field ini wajib diisi.");
            valid = false;
        } else if (pengarang) {
            hapusError(pengarang);
        }

        // Validasi Tahun Terbit
        const tahun = form.querySelector("[name='tahun']");
        if (tahun) {
            const nilai = parseInt(tahun.value, 10);
            if (isNaN(nilai) || nilai < 1900 || nilai > 2026) {
                tampilkanError(tahun, "Tahun harus di antara 1900-2026.");
                valid = false;
            } else {
                hapusError(tahun);
            }
        }

        // Validasi Stok
        const stok = form.querySelector("[name='stok']");
        if (stok) {
            const nilaiStok = parseInt(stok.value, 10);
            if (isNaN(nilaiStok) || nilaiStok < 0) {
                tampilkanError(stok, "Stok tidak boleh bernilai negatif.");
                valid = false;
            } else {
                hapusError(stok);
            }
        }

        // LATIHAN 1 Validasi ISBN (Opsional, tapi jika diisi hanya angka & tanda hubung)
        const isbn = form.querySelector("[name='isbn']");
        if (isbn && isbn.value.trim() !== "") {
            const isbnRegex = /^[0-9-]+$/;
            if (!isbnRegex.test(isbn.value.trim())) {
                tampilkanError(isbn, "ISBN hanya boleh berisi angka dan tanda hubung (-).");
                valid = false;
            } else {
                hapusError(isbn);
            }
        } else if (isbn) {
            hapusError(isbn);
        }

        if (!valid) {
            e.preventDefault();
        }
    });
}

// Entry Point: Menjalankan semua fungsi saat DOM siap
document.addEventListener("DOMContentLoaded", function () {
    initNavToggle();
    initHapusConfirm();
    initTableFilter();
    initValidasiForm();
    perbaruiCounter(); // latihan 4: Memperbarui counter saat halaman dimuat
});