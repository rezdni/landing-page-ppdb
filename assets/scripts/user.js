// Data formulir
function kirimForm(postId, event) {
    event.preventDefault();

    let requiredField = document.querySelectorAll(
        "input[required], select[required]"
    );
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

        kirimData(isiForm);
    } else {
        alert("Mohon isi kolom yang dibutuhkan");
        // showPopup("Info", "Mohon isi kolom yang dibutuhkan", "", "Oke");
    }
}

// Kirim formulir
function kirimData(isi) {
    const xhr = new XMLHttpRequest();
    xhr.open("POST", "../../backend/menu-calon.php", true);

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
                        alert(respon.message);
                        // showPopup("Kesalahan", "Internal Server Error", "", "Oke");
                        console.log(respon.message);
                    } else if (respon.code === 500) {
                        // Debuging
                        console.dir(respon.message);
                        // Jika NISN sudah dimiliki siswa lain
                        respon.message.errorInfo[1] === 1062
                            ? alert(
                                  "Nomor NISN yang anda masukan sudah dimiliki siswa lain"
                              )
                            : "";
                    }
                } else {
                    alert(respon.message);

                    // Pindahkan pengguna ke halaman biodata
                    window.location.href.includes("biodata")
                        ? muatData()
                        : (window.location.href = "biodata.html");
                    // showPopup("Berhasil", respon.pesan, "", "Oke");
                }
            } catch (errMsg) {
                console.dir({
                    Kesalahan: errMsg,
                    "XMLHttpRequest Respon": xhr.responseText,
                });
            }
        }
    };

    xhr.send(isi);
}

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

// data siswa
function muatDataCalon() {
    // Ajax
    let dataCalon = new XMLHttpRequest();
    dataCalon.open("GET", "../../backend/menu-calon.php?data-diri=true", true);
    dataCalon.setRequestHeader("Content-Type", "application/json");

    dataCalon.send();

    dataCalon.onload = function () {
        if (dataCalon.status === 200) {
            let data = JSON.parse(dataCalon.responseText);
            if (data.code === 404) {
                window.location.href = "data-diri.html";
            } else {
                try {
                    if (data.status === "success") {
                        // Tabel biodata
                        let tabel = document.querySelectorAll("#siswa table");
                        let indeks = 0;

                        tabel.forEach((elmTabel) => {
                            let tdIsi =
                                elmTabel.querySelectorAll("td:nth-child(2)");
                            tdIsi.forEach((elmIsi) => {
                                elmIsi.innerText = ": " + data.data[indeks];
                                indeks++;
                            });
                        });

                        // Konversi tanggal
                        let tglLahir = document.getElementById("tgl-lahir");
                        tglLahir.innerText =
                            ": " + konversiTanggal(tglLahir.innerText);
                    }
                } catch (kesalahan) {
                    alert("Terjadi kesalahan");
                    console.log(kesalahan);
                }
            }
        }
    };
}

// Data orangtua
function muatDataOrtu() {
    // Ajax
    let dataOrtu = new XMLHttpRequest();
    dataOrtu.open("GET", "../../backend/menu-calon.php?data-ortu=true", true);
    dataOrtu.setRequestHeader("Content-Type", "application/json");

    dataOrtu.send();

    dataOrtu.onload = function () {
        if (dataOrtu.status === 200) {
            try {
                let data = JSON.parse(dataOrtu.responseText);
                if (data.code === 200) {
                    try {
                        // Tabel biodata
                        let tabel = document.querySelectorAll("#ortu table");
                        let indeks = 0;

                        tabel.forEach((elmTabel) => {
                            let tdIsi =
                                elmTabel.querySelectorAll("td:nth-child(2)");
                            tdIsi.forEach((elmIsi) => {
                                elmIsi.innerText = ": " + data.data[indeks];
                                indeks++;
                            });
                        });
                    } catch (kesalahan) {
                        alert("Terjadi kesalahan");
                        console.log(kesalahan);
                    }
                }
            } catch (errMsg) {
                console.dir({
                    Kesalahan: errMsg,
                    "XMLHttpRequest Respon": dataOrtu.responseText,
                });
            }
        }
    };
}

// Dokumen pendukung
function muatDokumen() {
    // Ajax
    let dokumen = new XMLHttpRequest();
    dokumen.open("GET", "../../backend/menu-calon.php?berkas=true", true);
    dokumen.setRequestHeader("Content-Type", "application/json");

    dokumen.send();

    dokumen.onload = function () {
        if (dokumen.status === 200) {
            try {
                let data = JSON.parse(dokumen.responseText);
                if (data.code === 200) {
                    try {
                        let sumberFile = data.data;

                        for (let i = 0; i < sumberFile.length; i++) {
                            previewFile(
                                sumberFile[i].path,
                                sumberFile[i].jenis
                            );
                        }
                    } catch (kesalahan) {
                        alert("Terjadi kesalahan");
                        console.log(kesalahan);
                    }
                }
            } catch (errMsg) {
                console.dir({
                    Kesalahan: errMsg,
                    "XMLHttpRequest Respon": dokumen.responseText,
                });
            }
        }
    };
}

// Preview file
function previewInputFile(parentClass) {
    let inputFile = parentClass.querySelector(".upload-btn input");
    let previewFile = parentClass.querySelector(".preview");

    if (inputFile.files.length > 0) {
        const file = inputFile.files[0];
        const fileType = file.type;

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
            // Jika file bukan gambar atau PDF, tampilkan pesan
            // previewFile.textContent =
            //     "Preview tidak tersedia untuk tipe file ini.";
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

// Muat semua data
function muatData() {
    muatDataCalon();
    muatDataOrtu();
    muatDokumen();
}
