// Pengguna
// Tampilkan pengguna
function showUsers() {
    let xhr = new XMLHttpRequest();
    xhr.open(
        "GET",
        "../../backend/kelola-pengguna.php?list_pengguna=true",
        true
    );
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

    xhr.onload = function () {
        if (xhr.status === 200) {
            let listPengguna = document.getElementById("list-pengguna");

            try {
                let = respon = JSON.parse(xhr.responseText);
                if (respon.status === "success") {
                    let listDataPengguna = "";
                    respon.data.forEach((element) => {
                        listDataPengguna += `
                        <li>
                            <div>
                                <span
                                    class="material-symbols-outlined dark-purple"
                                >
                                    person
                                </span>
                                <p>${element.nama}</p>
                            </div>
                            <div>
                                <p>${element.role}</p>
                                <a href="edit-akun.html?id_akun=${element.id}" class="material-symbols-outlined dark-green">
                                    edit
                                </a>
                                <span
                                    class="material-symbols-outlined dark-red"
                                    onclick="hapusPengguna(${element.id}, '${element.nama}')"
                                >
                                    delete
                                </span>
                            </div>
                        </li>
                        `;
                    });
                    listPengguna.innerHTML = listDataPengguna;
                } else {
                    listPengguna.innerHTML = `<h2>${respon.message}</h2>`;
                }
            } catch (errMsg) {
                showPopup("Kesalahan", "Terjadi kesalahan", "", "Oke");
                console.dir({
                    Kesalahan: errMsg,
                    "XMLHttpRequest Respon": xhr.responseText,
                });
            }
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
            try {
                let respon = JSON.parse(xhr.responseText);
                if (respon.status === "success") {
                    togglePopup();
                    popupSubmit.removeAttribute("onclick");
                    showUsers();
                } else {
                    showPopup(
                        "Kesalahan",
                        "Terjadi kesalahan dalam menghapus pengguna",
                        "",
                        "Oke"
                    );
                    console.log(respon.message);
                }
            } catch (errMsg) {
                showPopup("Kesalahan", "Terjadi kesalahan", "", "Oke");
                console.dir({
                    Kesalahan: errMsg,
                    "XMLHttpRequest Respon": xhr.responseText,
                });
            }
        } else {
            showPopup(
                "Kesalahan",
                "Terjadi kesalahan dalam menghubungkan ke server",
                "",
                "Oke"
            );
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
        "../../backend/kelola-pengumuman.php?list_pengumuman=true",
        true
    );
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

    xhr.onload = function () {
        if (xhr.status === 200) {
            let kolomBerita = document.querySelector(".list-berita");

            try {
                let listDataBerita = JSON.parse(xhr.responseText);
                if (listDataBerita.status === "success") {
                    let listIsiBerita = "";
                    listDataBerita.data.forEach((element) => {
                        listIsiBerita += `
                        <div class="berita hasil">
                            <h3>${element.judul}</h3>
                            <p>
                                ${element.isi_pengumuman}
                            </p>
                            <button name="hapus-berita" class="hapus-berita" onclick="hapusBerita(${element.id_pengumuman})">
                                Hapus
                                </button>
                        </div>
                        `;
                    });
                    kolomBerita.innerHTML = listIsiBerita;
                } else {
                    kolomBerita.innerHTML = `<h2>${listDataBerita.message}</h2>`;
                }
            } catch (errMsg) {
                showPopup("Kesalahan", "Terjadi kesalahan", "", "Oke");
                console.dir({
                    Kesalahan: errMsg,
                    "XMLHttpRequest Respon": xhr.responseText,
                });
            }
        } else {
            showPopup("Error", "Failed to process data", "", "Ok");
        }
    };

    xhr.send();
}

// simpan berita
function simpanBerita(elmn, event) {
    event.preventDefault();

    let requiredField = document.querySelectorAll(
        "input[required], textarea[required]"
    );
    let validasi = true;

    requiredField.forEach((element) => {
        if (!element.checkValidity()) {
            validasi = false;
        }
    });

    if (validasi) {
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
                try {
                    let respon = JSON.parse(xhr.responseText);
                    if (respon.status === "success") {
                        showPopup(
                            "Berhasil",
                            "Berita berhasil dibuat",
                            "",
                            "Oke"
                        );
                        listBerita();
                        toggleBuatBerita();
                    } else {
                        showPopup(
                            "Kesalahan",
                            "Terjadi kesalahan dalam membuat berita",
                            "",
                            "Oke"
                        );
                        console.log(respon.message);
                    }
                } catch (errMsg) {
                    showPopup("Kesalahan", "Terjadi kesalahan", "", "Oke");
                    console.dir({
                        Kesalahan: errMsg,
                        "XMLHttpRequest Respon": xhr.responseText,
                    });
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
    } else {
        showPopup("Kesalahan", "Mohon isi kolom yang di butuhkan", "", "Oke");
    }
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
            try {
                let respon = JSON.parse(xhr.responseText);
                if (respon.status === "success") {
                    showPopup("Berhasil", "Berita berhasil dihapus", "", "Oke");
                    listBerita();
                } else {
                    showPopup(
                        "Kesalahan",
                        "Terjadi kesalahan dalam menghapus berita",
                        "",
                        "Oke"
                    );
                    console.log(respon.message);
                }
            } catch (errMsg) {
                showPopup("Kesalahan", "Terjadi kesalahan", "", "Oke");
                console.dir({
                    Kesalahan: errMsg,
                    "XMLHttpRequest Respon": xhr.responseText,
                });
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

// Calon Siswa
// Data formulir
function kirimForm(postId, event, defaultNis) {
    // Cegah handle default
    event.preventDefault();

    // Ambil semua field
    let requiredField = document.querySelectorAll(
        "input[required], select[required]"
    );

    // Validasi
    let validasi = true;
    requiredField.forEach((element) => {
        if (!element.checkValidity()) {
            validasi = false;
        }
    });

    if (validasi) {
        const formulir = document.querySelector("form");
        const isiForm = new FormData(formulir);
        isiForm.append(postId, "true");

        kirimData(isiForm, defaultNis);
    } else {
        showPopup("Info", "Mohon isi kolom yang dibutuhkan", "", "Oke");
    }
}

// Kirim formulir
function kirimData(isi, defaultNis) {
    const xhr = new XMLHttpRequest();
    xhr.open(
        "POST",
        "../../backend/kelola-calon.php?default_nis=" + defaultNis,
        true
    );

    xhr.onload = function () {
        if (xhr.status === 200) {
            try {
                const respon = JSON.parse(xhr.responseText);
                if (respon.status === "error") {
                    if (
                        respon.code === "FILE_TOO_LARGE" ||
                        respon.code === "EXTENSION_NOT_ALLOWED"
                    ) {
                        // Jika file terlalu besar
                        showPopup("Kesalahan", respon.message, "", "Oke");
                        console.log(respon.message);
                    } else if (respon.code === 500) {
                        // Jika NISN sudah dimiliki siswa lain
                        if (respon.message.errorInfo[1] === 1062) {
                            showPopup(
                                "Info",
                                "Nomor NISN yang anda masukan sudah dimiliki siswa lain",
                                "",
                                "Oke"
                            );
                        } else {
                            showPopup(
                                "Kesalahan",
                                "Terjadi kesalahan",
                                "",
                                "Oke"
                            );
                            console.log(respon.message);
                        }
                    } else {
                        showPopup("Kesalahan", "Terjadi kesalahan", "", "Oke");
                        console.log(respon.message);
                    }
                } else {
                    // Pindahkan pengguna ke halaman biodata
                    if (window.location.href.includes("edit-calon")) {
                        showPopup(
                            "Berhasil",
                            "Biodata berhasil di update",
                            "",
                            "Oke"
                        );

                        /*
                        Keberadaan fungsi dibawah ini diluar dari file admin.js
                        namun untuk saat ini fungsi tersebut di panggil di sini.
                        Pemanggilan fungsi ini akan dipindahkan jika sudah menemukan cara lain.
                        */
                        muatData();
                    } else {
                        window.location.href =
                            "edit-calon.html?nis=" +
                            respon.data.no_nis +
                            "&status_change=" +
                            respon.message;
                    }
                }
            } catch (errMsg) {
                showPopup("Kesalahan", "Terjadi kesalahan", "", "Oke");
                console.dir({
                    Kesalahan: errMsg,
                    "XMLHttpRequest Respon": xhr.responseText,
                });
            }
        }
    };

    xhr.send(isi);
}

//List semua calon
function listSemuaCalon() {
    let getXhr = new XMLHttpRequest();
    getXhr.open("GET", "../../backend/kelola-calon.php?list_calon=true", true);
    getXhr.setRequestHeader(
        "Content-Type",
        "application/x-www-form-urlencoded"
    );

    getXhr.onload = function () {
        if (getXhr.status === 200) {
            try {
                let dataCalon = JSON.parse(getXhr.responseText);
                let calonSiswa = document.getElementById("data-siswa");

                if (dataCalon.status === "success") {
                    let biodataCalon = "";

                    dataCalon.data.forEach((biodata) => {
                        biodataCalon += `
                            <tr>
                                <td data-label="No" class="no">
                                    <p class="text-wrap">${biodata.nomor}</p>
                                </td>
                                <td data-label="Nama Siswa" class="nama-siswa">
                                    <p class="text-wrap">${
                                        biodata.nama_calon_siswa
                                    }</p>
                                </td>
                                <td data-label="Jurusan" class="jurusan">
                                    <p class="text-wrap">${biodata.jurusan}</p>
                                </td>
                                <td data-label="Gelombang" class="gelombang">
                                    <p class="text-wrap">${
                                        biodata.gelombang
                                    }</p>
                                </td>
                                <td data-label="Tanggal" class="tanggal-daftar">
                                    <p class="text-wrap">${konversiTanggal(
                                        biodata.tanggal_daftar
                                    )}</p>
                                </td>
                                <td
                                    data-label="Nomor Telepon"
                                    class="nomor-telepon"
                                >
                                    <p class="text-wrap">${
                                        biodata.no_telepon
                                    }</p>
                                </td>
                                <td data-label="Keterangan" class="keterangan">
                                    <p class="text-wrap">sukses</p>
                                </td>
                                <td data-label="Ubah Data" class="ubah-data">
                                    <a href="edit-calon.html?nis=${
                                        biodata.no_nis
                                    }">
                                        <button name="edit-data" id="edit-data">
                                            <i class="fa-solid fa-pen-to-square"></i>
                                        </button>
                                    </a>
                                    <button name="hapus-data" id="hapus-data" onclick="hapusCalon(${
                                        biodata.no_nis
                                    }, '${biodata.nama_calon_siswa}')">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                            `;
                    });

                    calonSiswa.innerHTML = biodataCalon;
                } else {
                    calonSiswa.innerHTML = `
                        <tr>
                            <td colspan="7">${dataCalon.message}</td>
                        </tr>
                        `;
                }
            } catch (errMsg) {
                showPopup("Kesalahan", "Terjadi kesalahan", "", "Oke");
                console.dir({
                    Kesalahan: errMsg,
                    "XMLHttpRequest Respon": xhr.responseText,
                });
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
        "../../backend/kelola-calon.php?list_calon=true&limit=" +
            encodeURIComponent(batas),
        true
    );
    getXhr.setRequestHeader(
        "Content-Type",
        "application/x-www-form-urlencoded"
    );

    getXhr.onload = function () {
        if (getXhr.status === 200) {
            try {
                let dataCalon = JSON.parse(getXhr.responseText);
                let calonSiswa = document.getElementById("data-siswa");

                if (dataCalon.status === "success") {
                    let biodataCalon = "";

                    dataCalon.data.forEach((biodata) => {
                        biodataCalon += `
                            <tr>
                                <td data-label="No" class="no">
                                    <p class="text-wrap">${biodata.nomor}</p>
                                </td>
                                <td data-label="Nama Siswa" class="nama-siswa">
                                    <p class="text-wrap">${
                                        biodata.nama_calon_siswa
                                    }</p>
                                </td>
                                <td data-label="Jurusan" class="jurusan">
                                    <p class="text-wrap">${biodata.jurusan}</p>
                                </td>
                                <td data-label="Gelombang" class="gelombang">
                                    <p class="text-wrap">${
                                        biodata.gelombang
                                    }</p>
                                </td>
                                <td data-label="Tanggal" class="tanggal-daftar">
                                    <p class="text-wrap">${konversiTanggal(
                                        biodata.tanggal_daftar
                                    )}</p>
                                </td>
                                <td
                                    data-label="Nomor Telepon"
                                    class="nomor-telepon"
                                >
                                    <p class="text-wrap">${
                                        biodata.no_telepon
                                    }</p>
                                </td>
                                <td data-label="Keterangan" class="keterangan">
                                    <p class="text-wrap">sukses</p>
                                </td>
                            </tr>
                            `;
                    });

                    calonSiswa.innerHTML = biodataCalon;
                } else {
                    calonSiswa.innerHTML = `
                        <tr>
                            <td colspan="7">${dataCalon.message}</td>
                        </tr>
                        `;
                }
            } catch (errMsg) {
                showPopup("Kesalahan", "Terjadi kesalahan", "", "Oke");
                console.dir({
                    Kesalahan: errMsg,
                    "XMLHttpRequest Respon": xhr.responseText,
                });
            }
        } else {
            showPopup("Error", "Failed to process data", "", "Ok");
        }
    };

    getXhr.send();
}

// Hapus calon
function hapusCalon(nisCalon, namaCalon) {
    popupSubmit.setAttribute("onclick", "removeCalon(" + nisCalon + ")");
    showPopup(
        "Peringatan",
        "Hapus calon dengan nama '" + namaCalon + "'?",
        "Batal",
        "Ok"
    );
}

function removeCalon(nisCalon) {
    // buat koneksi xhttprequest
    let xhr = new XMLHttpRequest();
    xhr.open("POST", "../../backend/kelola-calon.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

    // terima respon back-end
    xhr.onload = function () {
        if (xhr.status === 200) {
            try {
                let respon = JSON.parse(xhr.responseText);
                if (respon.status === "success") {
                    togglePopup();
                    popupSubmit.removeAttribute("onclick");
                    listSemuaCalon();
                } else {
                    showPopup(
                        "Kesalahan",
                        "Terjadi kesalahan dalam menghapus calon siswa",
                        "",
                        "Oke"
                    );
                    console.log(respon.message);
                }
            } catch (errMsg) {
                showPopup("Kesalahan", "Terjadi kesalahan", "", "Oke");
                console.dir({
                    Kesalahan: errMsg,
                    "XMLHttpRequest Respon": xhr.responseText,
                });
            }
        } else {
            showPopup(
                "Kesalahan",
                "Terjadi kesalahan dalam menghubungkan ke server",
                "",
                "Oke"
            );
        }
    };

    // Kirim request ke back-end
    xhr.send("removeCalon=true&nis=" + encodeURIComponent(nisCalon));
}

// Request data calon
function reqCalon(nis) {
    return new Promise((resolve, reject) => {
        const xhr = new XMLHttpRequest();
        xhr.open("GET", "../../backend/kelola-calon.php?nis=" + nis, true);
        xhr.setRequestHeader("Content-Type", "application/json");

        xhr.send();

        xhr.onload = () => {
            if (xhr.status === 200) {
                try {
                    let respon = JSON.parse(xhr.responseText);

                    if (respon.status === "success") {
                        const biodata = [
                            respon.data.nama_calon_siswa,
                            respon.data.tanggal_lahir,
                            respon.data.no_nis,
                            respon.data.jenis_kelamin,
                            respon.data.agama,
                            respon.data.sekolah_asal,
                            respon.data.kewarganegaraan,
                            respon.data.golongan_darah,
                            respon.data.alamat_tinggal,
                            respon.data.provinsi,
                            respon.data.kota_kabupaten,
                            respon.data.kecamatan,
                            respon.data.kelurahan,
                            respon.data.kode_post,
                            respon.data.no_telepon,
                            respon.data.jurusan,
                            respon.data.gelombang,
                        ];

                        resolve(biodata);
                    } else {
                        reject({
                            status: respon.status,
                            code: respon.code,
                            message: respon.message,
                        });
                    }
                } catch (errMsg) {
                    let kesalahan = {
                        Kesalahan: errMsg,
                        "XMLHttpRequest Respon": xhr.responseText,
                    };
                    reject({
                        status: "error",
                        code: 500,
                        message: kesalahan,
                    });
                }
            } else {
                reject({
                    status: "error",
                    code: 503,
                    message: "Gagal terhubung ke server",
                });
            }
        };
    });
}

// Request data ortu calon
function reqOrtuCalon(nis) {
    return new Promise((resolve, reject) => {
        const xhr = new XMLHttpRequest();
        xhr.open(
            "GET",
            "../../backend/kelola-calon.php?nis=" + nis + "&data_ortu=true",
            true
        );
        xhr.setRequestHeader("Content-Type", "application/json");

        xhr.send();

        xhr.onload = () => {
            if (xhr.status === 200) {
                try {
                    let respon = JSON.parse(xhr.responseText);

                    if (respon.status === "success") {
                        const dataOrtu = [
                            respon.data.nama_orang_tua,
                            respon.data.nomor_telepon_orang_tua,
                            respon.data.pekerjaan_orang_tua,
                            respon.data.alamat_orang_tua,
                        ];

                        resolve(dataOrtu);
                    } else {
                        reject({
                            status: respon.status,
                            code: respon.code,
                            message: respon.message,
                        });
                    }
                } catch (errMsg) {
                    let kesalahan = {
                        Kesalahan: errMsg,
                        "XMLHttpRequest Respon": xhr.responseText,
                    };
                    reject({
                        status: "error",
                        code: 500,
                        message: kesalahan,
                    });
                }
            } else {
                reject({
                    status: "error",
                    code: 503,
                    message: "Gagal terhubung ke server",
                });
            }
        };
    });
}

// Request dokumen
function reqDocs(nis) {
    const xhr = new XMLHttpRequest();
    xhr.open(
        "GET",
        "../../backend/kelola-calon.php?nis=" + nis + "&berkas=true",
        true
    );
    xhr.setRequestHeader("Content-Type", "application/json");

    xhr.send();

    xhr.onload = () => {
        if (xhr.status === 200) {
            try {
                let respon = JSON.parse(xhr.responseText);

                if (respon.status === "success") {
                    let sumberFile = respon.data;

                    // Loop data
                    for (let i = 0; i < sumberFile.length; i++) {
                        previewFile(
                            sumberFile[i].file_path,
                            sumberFile[i].jenis_dokumen
                        );
                    }
                } else {
                    if (respon.code !== 404) {
                        showPopup("Kesalahan", "Terjadi kesalahan", "", "Oke");
                        console.log(respon.message);
                    }
                }
            } catch (errMsg) {
                showPopup("Kesalahan", "Terjadi kesalahan", "", "Oke");
                console.dir({
                    Kesalahan: errMsg,
                    "XMLHttpRequest Respon": xhr.responseText,
                });
            }
        } else {
            showPopup("Kesalahan", "Terjadi kesalahan", "", "Oke");
        }
    };
}

// atur link
function aturLink(nis) {
    let linkBtn = document.querySelectorAll("a.btn-ubah");
    linkBtn.forEach((element) => {
        let hrefAttr = element.getAttribute("href");
        element.setAttribute("href", hrefAttr + nis);
    });
}

// Sidebar Responsive
// Menu Mobile 480px
const menuToggle = document.querySelector(".menu-btn");
const menuClose = document.querySelector(".menu-close");
const menuMobile = document.querySelector(".menu-mobile");

try {
    // Tampilkan menu saat tombol menu ditekan
    menuToggle.addEventListener("click", () => {
        menuMobile.classList.add("active");
    });
} catch (errMsg) {
    alert(
        "Benerin tombol menu nya itu Reza. Cek console kalo gk tau salahnya dimana."
    );
    console.log(errMsg);
}

// Sembunyikan menu saat tombol close ditekan
menuClose.addEventListener("click", () => {
    menuMobile.classList.remove("active");
});

// Konversi tanggal
function konversiTanggal(tanggal) {
    const tglObj = new Date(tanggal);
    const bulan = [
        "Januari",
        "Februari",
        "Maret",
        "April",
        "Mei",
        "Juni",
        "Juli",
        "Agustus",
        "September",
        "Oktober",
        "November",
        "Desember",
    ];

    const hari = tglObj.getDate();
    const namaBulan = bulan[tglObj.getMonth()];
    const tahun = tglObj.getFullYear();

    const hasilKonversi = `${hari} ${namaBulan} ${tahun}`;
    return hasilKonversi;
}

// Preview file
function previewInputFile(parentClass) {
    let inputFile = parentClass.querySelector(".upload-btn input");
    let previewFile = parentClass.querySelector(".preview");

    if (inputFile.files.length > 0) {
        const file = inputFile.files[0];
        const fileType = file.type;
        const maxSize = 2 * 1024 * 1024;

        // cek ukuran file
        if (file.size > maxSize) {
            showPopup("Kesalahan", "Ukuran file terlalu besar", "", "Oke");
            const icon = document.createElement("span");
            icon.setAttribute("class", "material-symbols-outlined");
            icon.innerText = "broken_image";
            previewFile.innerHTML = "";
            previewFile.appendChild(icon);
        } else {
            // Cek tipe file
            if (fileType.startsWith("image/")) {
                // Jika file adalah gambar, tampilkan menggunakan <img>
                const reader = new FileReader();
                reader.onload = function (e) {
                    const img = document.createElement("img");
                    img.src = e.target.result;
                    previewFile.innerHTML = "";
                    previewFile.appendChild(img);
                };
                reader.readAsDataURL(file);
            } else if (fileType === "application/pdf") {
                // Jika file adalah PDF, tampilkan menggunakan <iframe>
                const reader = new FileReader();
                reader.onload = function (e) {
                    const iframe = document.createElement("iframe");
                    iframe.src = e.target.result;
                    iframe.width = "100%";
                    iframe.height = "500px";
                    previewFile.innerHTML = "";
                    previewFile.appendChild(iframe);
                };
                reader.readAsDataURL(file);
            } else {
                showPopup("Kesalahan", "Format file tidak didukung", "", "Oke");
                const icon = document.createElement("span");
                icon.setAttribute("class", "material-symbols-outlined");
                icon.innerText = "broken_image";
                previewFile.innerHTML = "";
                previewFile.appendChild(icon);
            }
        }
    }
}

function previewFile(filepath, jenis) {
    let previewElement = document.querySelector("#view-" + jenis);

    // cek tipe file
    let indeksFile = filepath.lastIndexOf(".");
    let jenisFile =
        indeksFile !== -1 ? filepath.slice(indeksFile + 1).toLowerCase() : "";

    if (jenisFile === "pdf") {
        const iframe = document.createElement("iframe");
        iframe.src = filepath;
        iframe.width = "100%";
        iframe.height = "500px";
        previewElement.innerHTML = "";
        previewElement.appendChild(iframe);
    } else {
        const img = document.createElement("img");
        img.src = filepath;
        previewElement.innerHTML = "";
        previewElement.appendChild(img);
    }
}
