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

// Tambah calon
function tambahCalon(event) {
    // event.preventDefault();
    // let formulir = document.getElementById("tambahcalon");
    // let formData = new FormData(formulir);
    // // formData.append("buatcalon", "true");
    // formData.forEach((value, key) => {
    //     // console.log(key, value);
    // });
    // const xhr = new XMLHttpRequest();
    // xhr.open("POST", "../../backend/calon-siswa.php", true);
    // xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    // xhr.onload = function () {
    //     if (xhr.status === 200) {
    //         console.log(xhr.responseText);
    //         // const respon = JSON.parse(xhr.responseText);
    //         if (respon.status === "error") {
    //             showPopup("Kesalahan", "Internal Server Error", "", "Oke");
    //             console.log(respon.pesan);
    //         } else if (respon.status === "gagal") {
    //             showPopup("Gagal", respon.pesan, "", "Oke");
    //         } else {
    //             showPopup("Berhasil", respon.pesan, "", "Oke");
    //         }
    //     }
    // };
    // xhr.send(formData);
}

//List semua calon
function listSemuaCalon() {
    let getXhr = new XMLHttpRequest();
    getXhr.open("GET", "../../backend/kelola-calon.php?listCalon=true", true);
    getXhr.setRequestHeader(
        "Content-Type",
        "application/x-www-form-urlencoded"
    );

    getXhr.onload = function () {
        if (getXhr.status === 200) {
            const dataCalon = JSON.parse(getXhr.responseText);
            let calonSiswa = document.getElementById("data-siswa");

            if (dataCalon.kesalahan) {
                calonSiswa.innerHTML = `
                <tr>
                    <td colspan="7">${dataCalon.kesalahan}</td>
                </tr>
                `;
            } else {
                let biodataCalon = "";

                dataCalon.forEach((biodata) => {
                    biodataCalon += `
                    <tr>
                        <td data-label="No" class="no">
                            <p class="text-wrap">${biodata.nomor}</p>
                        </td>
                        <td data-label="Nama Siswa" class="nama-siswa">
                            <p class="text-wrap">${biodata.nama}</p>
                        </td>
                        <td data-label="Jurusan" class="jurusan">
                            <p class="text-wrap">${biodata.jurusan}</p>
                        </td>
                        <td data-label="Gelombang" class="gelombang">
                            <p class="text-wrap">${biodata.gelombang}</p>
                        </td>
                        <td data-label="Tanggal" class="tanggal-daftar">
                            <p class="text-wrap">${biodata.daftar}</p>
                        </td>
                        <td
                            data-label="Nomor Telepon"
                            class="nomor-telepon"
                        >
                            <p class="text-wrap">${biodata.telepon}</p>
                        </td>
                        <td data-label="Keterangan" class="keterangan">
                            <p class="text-wrap">sukses</p>
                        </td>
                        <td data-label="Ubah Data" class="ubah-data">
                            <a href="edit-calon.html?idCalon=${biodata.id}">
                                <button name="edit-data" id="edit-data">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </button>
                            </a>
                            <button name="hapus-data" id="hapus-data" onclick="hapusCalon(${biodata.id}, '${biodata.nama}')">
                                <i class="fa-solid fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                    `;
                });

                calonSiswa.innerHTML = biodataCalon;
            }
        } else {
            showPopup("Error", "Failed to process data", "", "Ok");
        }
    };

    getXhr.send();
}

// List sebagian calon
function listCalon(batas) {
    let getXhr = new XMLHttpRequest();
    getXhr.open(
        "GET",
        "../../backend/kelola-calon.php?listCalon=true&limit=" +
            encodeURIComponent(batas),
        true
    );
    getXhr.setRequestHeader(
        "Content-Type",
        "application/x-www-form-urlencoded"
    );

    getXhr.onload = function () {
        if (getXhr.status === 200) {
            const dataCalon = JSON.parse(getXhr.responseText);
            let calonSiswa = document.getElementById("data-siswa");

            if (dataCalon.kesalahan) {
                calonSiswa.innerHTML = `
                <tr>
                    <td colspan="7">${dataCalon.kesalahan}</td>
                </tr>
                `;
            } else {
                let biodataCalon = "";

                dataCalon.forEach((biodata) => {
                    biodataCalon += `
                    <tr>
                        <td data-label="No" class="no">
                            <p class="text-wrap">${biodata.nomor}</p>
                        </td>
                        <td data-label="Nama Siswa" class="nama-siswa">
                            <p class="text-wrap">${biodata.nama}</p>
                        </td>
                        <td data-label="Jurusan" class="jurusan">
                            <p class="text-wrap">${biodata.jurusan}</p>
                        </td>
                        <td data-label="Gelombang" class="gelombang">
                            <p class="text-wrap">${biodata.gelombang}</p>
                        </td>
                        <td data-label="Tanggal" class="tanggal-daftar">
                            <p class="text-wrap">${biodata.daftar}</p>
                        </td>
                        <td
                            data-label="Nomor Telepon"
                            class="nomor-telepon"
                        >
                            <p class="text-wrap">${biodata.telepon}</p>
                        </td>
                        <td data-label="Keterangan" class="keterangan">
                            <p class="text-wrap">sukses</p>
                        </td>
                    </tr>
                    `;
                });

                calonSiswa.innerHTML = biodataCalon;
            }
        } else {
            showPopup("Error", "Failed to process data", "", "Ok");
        }
    };

    getXhr.send();
}

// Hapus calon
function hapusCalon(idCalon, namaCalon) {
    popupSubmit.setAttribute("onclick", "removeCalon(" + idCalon + ")");
    showPopup(
        "Peringatan",
        "Hapus calon dengan nama '" + namaCalon + "'?",
        "Batal",
        "Ok"
    );
}

function removeCalon(idCalon) {
    // buat koneksi xhttprequest
    let xhr = new XMLHttpRequest();
    xhr.open("POST", "../../backend/kelola-calon.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

    // terima respon back-end
    xhr.onload = function () {
        if (xhr.status === 200) {
            let respon = JSON.parse(xhr.responseText);
            if (respon.status === "berhasil") {
                togglePopup();
                popupSubmit.removeAttribute("onclick");
                listSemuaCalon();
            } else {
                alert("Kesalahan dalam menghapus calon siswa");
                console.log(respon.keterangan);
            }
        } else {
            alert("Kesalahan dalam menghubungkan ke server");
        }
    };

    // Kirim request ke back-end
    xhr.send("removeCalon=true&calonId=" + encodeURIComponent(idCalon));
}

// Sidebar Responsive
// Menu Mobile 480px
const menuToggle = document.querySelector(".menu-toggle");
const menuClose = document.querySelector(".menu-close");
const menuMobile = document.querySelector(".menu-mobile");

// Tampilkan menu saat tombol menu ditekan
menuToggle.addEventListener("click", () => {
    menuMobile.classList.add("active");
});

// Sembunyikan menu saat tombol close ditekan
menuClose.addEventListener("click", () => {
    menuMobile.classList.remove("active");
});
