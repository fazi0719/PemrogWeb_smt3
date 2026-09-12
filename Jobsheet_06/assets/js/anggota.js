document.addEventListener("DOMContentLoaded", function () {
    const keysAnggota = ["no_anggota", "nama", "alamat", "no_hp", "tgl_bergabung"];

    // Muat data anggota via fungsi generik
    muatDataTabel("../data/anggota.json", keysAnggota);
});