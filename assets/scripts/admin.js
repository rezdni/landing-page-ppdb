// Pengguna

// Tampilkan pengguna
function showUsers() {
    let xhr = new XMLHttpRequest();
    xhr.open("GET", "../../backend/kelola-pengguna.php?listPengguna=true", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

    xhr.onload = function () {
        if (xhr.status === 200) {
            let listPengguna = document.getElementById("list-pengguna");
            listPengguna.innerHTML = xhr.responseText;
        } else {
            alert("Failed to process data");
        }
    }
    
    xhr.send();
}

// Hapus akun pengguna
function hapusPengguna(idPengguna, namaPengguna) {
    if (confirm("Anda yakin ingin menghapus akun " + namaPengguna + "?")) {
        // buat koneksi xhttprequest
        let xhr = new XMLHttpRequest();
        xhr.open("POST", "../../backend/kelola-pengguna.php", true);
        xhr.setRequestHeader(
            "Content-Type",
            "application/x-www-form-urlencoded"
        );

        // terima respon back-end
        xhr.onload = function () {
            if (xhr.status === 200) {
                let respon = JSON.parse(xhr.responseText);
                if (respon.status === "berhasil") {
                    alert("Akun berhasil dihapus");
                    showUsers();
                } else {
                    alert("Kesalahan dalam menghapus akun");
                    console.log(respon.keterangan);
                }
            } else {
                alert("Kesalahan dalam menghubungkan ke server");
            }
        };

        // Kirim request ke back-end
        xhr.send("removeUser=true&userId=" + encodeURIComponent(idPengguna));
    }
}

// Berita

// Tombol form berita
let formBerita = document.getElementById("buat-berita");
function toggleBuatBerita() {
    if (formBerita.classList.contains("show")) {
        formBerita.classList.remove("show")
    } else {
        formBerita.classList.add("show");
    }
}

// list berita
function listBerita() {
    let xhr = new XMLHttpRequest();
    xhr.open("GET", "../../backend/kelola-pengumuman.php?listPengumuman=true", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

    xhr.onload = function () {
        if (xhr.status === 200) {
            let listBerita = document.querySelector(".list-berita");
            listBerita.innerHTML = xhr.responseText;
        } else {
            alert("Failed to process data");
        }
    }
    
    xhr.send();
}

// simpan berita
function simpanBerita(elmn, event) {
    event.preventDefault();

    let judulBerita = elmn.querySelector("#judul-berita").value;
    let isiBerita = elmn.querySelector("#isi-berita").value;

    // buat koneksi xhttprequest
    let xhr = new XMLHttpRequest();
    xhr.open("POST", "../../backend/kelola-pengumuman.php", true);
    xhr.setRequestHeader(
        "Content-Type",
        "application/x-www-form-urlencoded"
    );

    // terima respon back-end
    xhr.onload = function () {
        if (xhr.status === 200) {
            let respon = JSON.parse(xhr.responseText);
            if (respon.status === "berhasil") {
                alert("Berita berhasil dibuat");
                listBerita();
                toggleBuatBerita();
            } else {
                alert("Kesalahan dalam menghapus akun");
                console.log(respon.keterangan);
            }
        } else {
            alert("Kesalahan dalam menghubungkan ke server");
        }
    };

    xhr.send(
        "buatBerita=true&judulBerita=" +
        encodeURIComponent(judulBerita) +
        "&isiBerita=" +
        encodeURIComponent(isiBerita)
    );
}

// Hapus berita
function hapusBerita(idBerita) {
        // buat koneksi xhttprequest
        let xhr = new XMLHttpRequest();
        xhr.open("POST", "../../backend/kelola-pengumuman.php", true);
        xhr.setRequestHeader(
            "Content-Type",
            "application/x-www-form-urlencoded"
        );

        // terima respon back-end
        xhr.onload = function () {
            if (xhr.status === 200) {
                let respon = JSON.parse(xhr.responseText);
                if (respon.status === "berhasil") {
                    alert("Berita berhasil dihapus");
                    listBerita();
                } else {
                    alert("Kesalahan dalam menghapus berita");
                    console.log(respon.keterangan);
                }
            } else {
                alert("Kesalahan dalam menghubungkan ke server");
            }
        };

        // Kirim request ke back-end
        xhr.send("hapusBerita=true&idBerita=" + encodeURIComponent(idBerita));
    
}