// Pengguna

// Tampilkan pengguna
function showUsers() {
    let xhr = new XMLHttpRequest();
    xhr.open(
        "GET",
        "../../backend/kelola-pengguna.php?listPengguna=true",
        true
    );
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

    xhr.onload = function () {
        if (xhr.status === 200) {
            let listPengguna = document.getElementById("list-pengguna");
            listPengguna.innerHTML = xhr.responseText;
        } else {
            showPopup("Error", "Failed to process data", "", "Ok");
        }
    };

    xhr.send();
}

// Hapus akun pengguna
function hapusPengguna(idPengguna, namaPengguna) {
    popupSubmit.setAttribute("onclick", "deleteUser(" + idPengguna + ")");
    showPopup(
        "Peringatan",
        "Anda yakin ingin menghapus akun " + namaPengguna + "?",
        "Batal",
        "Oke"
    );
}

function deleteUser(idPengguna) {
    // buat koneksi xhttprequest
    let xhr = new XMLHttpRequest();
    xhr.open("POST", "../../backend/kelola-pengguna.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

    // terima respon back-end
    xhr.onload = function () {
        if (xhr.status === 200) {
            let respon = JSON.parse(xhr.responseText);
            if (respon.status === "berhasil") {
                togglePopup();
                popupSubmit.removeAttribute("onclick");
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

// Berita

// Tombol form berita
let formBerita = document.getElementById("buat-berita");
function toggleBuatBerita() {
    if (formBerita.classList.contains("show")) {
        formBerita.classList.remove("show");
    } else {
        formBerita.classList.add("show");
    }
}

// list berita
function listBerita() {
    let xhr = new XMLHttpRequest();
    xhr.open(
        "GET",
        "../../backend/kelola-pengumuman.php?listPengumuman=true",
        true
    );
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

    xhr.onload = function () {
        if (xhr.status === 200) {
            let listBerita = document.querySelector(".list-berita");
            listBerita.innerHTML = xhr.responseText;
        } else {
            showPopup("Error", "Failed to process data", "", "Ok");
        }
    };

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
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

    // terima respon back-end
    xhr.onload = function () {
        if (xhr.status === 200) {
            let respon = JSON.parse(xhr.responseText);
            if (respon.status === "berhasil") {
                showPopup("Berhasil", "Berita berhasil dibuat", "", "Oke");
                listBerita();
                toggleBuatBerita();
            } else {
                showPopup(
                    "Kesalahan",
                    "Terjadi kesalahan dalam membuat berita",
                    "",
                    "Oke"
                );
                console.log(respon.keterangan);
            }
        } else {
            showPopup(
                "Kesalahan",
                "Kesalahan dalam menghubungkan ke server",
                "",
                "Oke"
            );
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
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

    // terima respon back-end
    xhr.onload = function () {
        if (xhr.status === 200) {
            let respon = JSON.parse(xhr.responseText);
            if (respon.status === "berhasil") {
                showPopup("Berhasil", "Berita berhasil dihapus", "", "Oke");
                listBerita();
            } else {
                showPopup(
                    "Kesalahan",
                    "Terjadi kesalahan dalam menghapus berita",
                    "",
                    "Oke"
                );
                console.log(respon.keterangan);
            }
        } else {
            showPopup(
                "Kesalahan",
                "Kesalahan dalam menghubungkan ke server",
                "",
                "Oke"
            );
        }
    };

    // Kirim request ke back-end
    xhr.send("hapusBerita=true&idBerita=" + encodeURIComponent(idBerita));
}
