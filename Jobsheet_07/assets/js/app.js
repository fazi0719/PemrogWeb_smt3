// ===== 1. Menu Hamburger (JS-driven) =====
function initNavToggle() {
    const toggleBtn = document.getElementById("nav-toggle-btn");
    const nav = document.querySelector("header nav");
    if (!toggleBtn || !nav) return;

    toggleBtn.addEventListener("click", function () {
        nav.classList.toggle("nav-open");
    });
}

// ===== 2. Counter Jumlah Baris Tersisa (Modifikasi) =====
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
        // Jangan hitung baris jika sedang disembunyikan filter ATAU jika berupa pesan "Belum ada data"
        if (row.style.display !== "none" && !row.querySelector("td[colspan]")) {
            tampil++;
        }
    });

    counter.textContent = `Menampilkan ${tampil} dari ${total} data`;
}

// ===== 3. Konfirmasi Hapus Baris Tabel =====
function initHapusConfirm() {
    document.addEventListener("click", function (e) {
        console.log("Elemen yang diklik:", e.target);

        const btn = e.target.closest(".btn-hapus");
        if (!btn) return;

        const row = btn.closest("tr");
        const nama = row ? row.querySelector("td")?.textContent : "data ini";
        const yakin = confirm("Yakin ingin menghapus \"" + nama + "\"?");
        if (yakin && row) {
            row.remove();
            perbaruiCounter();
        }
    });
}

// ===== 4. Filter / Pencarian Tabel Real-Time =====
function initTableFilter() {
    const input = document.getElementById("search-input");
    const table = document.querySelector(".table-responsive table");
    if (!input || !table) return;

    input.addEventListener("keyup", function () {
        const keyword = input.value.toLowerCase();
        const rows = table.querySelectorAll("tbody tr");
        rows.forEach(function (row) {
            // Mengambil elemen kolom pertama (td pertama) untuk pencarian nama/judul
            const kolomPertama = row.querySelector("td");
            const teks = kolomPertama ? kolomPertama.textContent.toLowerCase() : "";
            row.style.display = teks.includes(keyword) ? "" : "none";
        });
        perbaruiCounter();
    });
}

// ===== 5. Validasi Form (Client-Side) =====
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

// Refactor Validasi Menggunakan Array & Loop forEach (Modifikasi)
function initValidasiForm() {
    const form = document.getElementById("form-tambah");
    if (!form) return;

    const aturanValidasi = [
        {
            selector: "[name='judul'], [name='nama']",
            valid: (val) => val.trim() !== "",
            pesan: "Field ini wajib diisi."
        },
        {
            selector: "[name='no_anggota']",
            valid: (val) => val.trim() !== "",
            pesan: "Field ini wajib diisi."
        },
        {
            selector: "[name='pengarang']",
            valid: (val) => val.trim() !== "",
            pesan: "Field ini wajib diisi."
        },
        {
            selector: "[name='tahun']",
            valid: (val) => {
                const nilai = parseInt(val, 10);
                return !isNaN(nilai) && nilai >= 1900 && nilai <= 2026;
            },
            pesan: "Tahun harus di antara 1900–2026."
        },
        {
            selector: "[name='stok']",
            valid: (val) => {
                const nilai = parseInt(val, 10);
                return !isNaN(nilai) && nilai >= 0;
            },
            pesan: "Stok tidak boleh bernilai negatif."
        },
        {
            selector: "[name='isbn']",
            valid: (val) => val.trim() === "" || /^[0-9-]+$/.test(val.trim()),
            pesan: "ISBN hanya boleh berisi angka dan tanda hubung (-)."
        }
    ];

    form.addEventListener("submit", function (e) {
        let valid = true;

        aturanValidasi.forEach(function (aturan) {
            const el = form.querySelector(aturan.selector);
            if (!el) return;

            if (!aturan.valid(el.value)) {
                tampilkanError(el, aturan.pesan);
                valid = false;
            } else {
                hapusError(el);
            }
        });

        if (!valid) {
            e.preventDefault();
        }
    });
}

// ===== Entry Point: Menjalankan Fungsi saat DOM Siap =====
document.addEventListener("DOMContentLoaded", function () {
    initNavToggle();
    initHapusConfirm();
    initTableFilter();
    initValidasiForm();
    perbaruiCounter();
});