

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

// ===== Konfirmasi hapus (front-end only, belum ke server) =====
// Memakai event delegation di document karena baris tabel sekarang
// dirender dinamis via fetch (lihat buku.js/anggota.js) sehingga
// tombol .btn-hapus belum tentu ada saat DOMContentLoaded.
function initHapusConfirm() {
    document.addEventListener("click", function (e) {
        // latihan 4: Tambahkan console.log di sini untuk memantau elemen yang diklik:
        console.log("Elemen yang diklik:", e.target);
        
        const btn = e.target.closest(".btn-hapus");
        if (!btn) return;

        const row = btn.closest("tr");
        const nama = row ? row.querySelector("td")?.textContent : "data ini";
        const yakin = confirm("Yakin ingin menghapus \"" + nama + "\"?");
        if (yakin && row) {
            row.remove();
            perbaruiCounter(); // Latihan 4 tetap jalan
        }
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

// Latihan 5: Refactor Validasi Menggunakan Array & Loop forEach
function initValidasiForm() {
    const form = document.getElementById("form-tambah");
    if (!form) return;

     // Daftar aturan validasi (Nama selector, fungsi validasi, dan pesan error)
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
            // Opsional: jika kosong diizinkan; jika ada isi, wajib angka dan tanda hubung
            valid: (val) => val.trim() === "" || /^[0-9-]+$/.test(val.trim()),
            pesan: "ISBN hanya boleh berisi angka dan tanda hubung (-)."
        }
    ];

    form.addEventListener("submit", function (e) {
        let valid = true;

        aturanValidasi.forEach(function (aturan) {
            const el = form.querySelector(aturan.selector);
            if (!el) return; // Lewati jika elemen tidak ada di form halaman saat ini

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

// Entry Point: Menjalankan semua fungsi saat DOM siap
document.addEventListener("DOMContentLoaded", function () {
    initNavToggle();
    initHapusConfirm();
    initTableFilter();
    initValidasiForm();
    perbaruiCounter(); // latihan 4: Memperbarui counter saat halaman dimuat
});


// Latihan 2 Jobsheet 6: Fungsi Generik
async function muatDataTabel(url, keys) {
    const tbody = document.querySelector(".table-responsive table tbody");
    const loading = document.getElementById("loading-indicator");
    if (!tbody) return;

    if (loading) loading.style.display = "block";
    tbody.innerHTML = "";

    try {
        // Simulasi delay jaringan agar loading indicator terlihat
        await new Promise((resolve) => setTimeout(resolve, 600));

        const res = await fetch(url);
        if (!res.ok) {
            throw new Error("Gagal mengambil data (status " + res.status + ")");
        }

        const data = await res.json();

        data.forEach(function (item) {
            const tr = document.createElement("tr");

            // Render setiap nilai kolom secara dinamis sesuai parameter keys
            let kolomHtml = "";
            keys.forEach(function (key) {
                const nilai = item[key] !== undefined && item[key] !== "" ? item[key] : "-";
                kolomHtml += "<td>" + nilai + "</td>";
            });

            // Kolom tombol aksi di paling kanan
            kolomHtml +=
                "<td>" +
                "<button type=\"button\">Edit</button> " +
                "<button type=\"button\" class=\"btn-hapus\">Hapus</button>" +
                "</td>";

            tr.innerHTML = kolomHtml;
            tbody.appendChild(tr);
        });

        // Perbarui counter tabel setelah data berhasil masuk
        if (typeof perbaruiCounter === "function") {
            perbaruiCounter();
        }
    } catch (err) {
        tbody.innerHTML =
            "<tr><td colspan=\"" + (keys.length + 1) + "\">Gagal memuat data: " + err.message + "</td></tr>";
    } finally {
        if (loading) loading.style.display = "none";
    }
}