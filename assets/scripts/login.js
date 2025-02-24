const container = document.querySelector(".container");
const resgisterBtn = document.querySelector(".register-btn");
const loginBtn = document.querySelector(".login-btn");

resgisterBtn.addEventListener("click", () => {
    container.classList.add("active");
});

loginBtn.addEventListener("click", () => {
    container.classList.remove("active");
});

// Sistem registrasi
function registrasi(elm, event, tombol) {
    event.preventDefault();

    let requiredField = elm.querySelectorAll(
        "input[required], select[required]"
    );
    let validasi = true;

    requiredField.forEach((element) => {
        if (!element.checkValidity()) {
            validasi = false;
        }
    });

    if (validasi) {
        tombol.innerText = "Memproses";

        // Ambil hasil input pengguna
        let nama = elm.querySelector("#regis-nama").value;
        let email = elm.querySelector("#regis-email").value;
        let sandi = elm.querySelector("#regis-passwd").value;

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
                try {
                    tombol.innerText = "Daftar";
                    let respon = JSON.parse(xhr.responseText);
                    if (respon.status === "success") {
                        showPopup(
                            "Berhasil",
                            "Akun berhasil dibuat, silahkan pindah ke menu login",
                            "",
                            "Lanjut"
                        );

                        // bersihkan kolom isian
                        let field = elm.querySelectorAll("input");
                        field.forEach((element) => {
                            element.value = "";
                        });

                        // pindahkan pengguna ke halaman login
                        // window.location.href = "index.html";
                    } else {
                        console.log(respon.message);
                        showPopup(
                            "Kesalahan",
                            "Kesalahan dalam membuat akun",
                            "",
                            "Lanjut"
                        );
                    }
                } catch (errMsg) {
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
                    "Lanjut"
                );
            }
        };

        // Kirim request ke back-end
        xhr.send(
            "createCalon=true&name=" +
                encodeURIComponent(nama) +
                "&email=" +
                encodeURIComponent(email) +
                "&password=" +
                encodeURIComponent(sandi)
        );
    } else {
        showPopup("Kesalahan", "Mohon isi kolom dengan benar", "", "Lanjut");
    }
}

// Sistem login
function login(elm, event, tombol) {
    event.preventDefault();

    let requiredField = elm.querySelectorAll(
        "input[required], select[required]"
    );
    let validasi = true;

    requiredField.forEach((element) => {
        if (!element.checkValidity()) {
            validasi = false;
        }
    });

    if (validasi) {
        tombol.innerText = "Memperoses";
        const email = elm.querySelector("#login-email").value;
        const password = elm.querySelector("#login-password").value;

        let xhr = new XMLHttpRequest();
        xhr.open("POST", "../../backend/login.php", true);
        xhr.setRequestHeader(
            "Content-Type",
            "application/x-www-form-urlencoded"
        );

        xhr.onload = function () {
            if (xhr.status === 200) {
                tombol.innerText = "Login";
                try {
                    let respon = JSON.parse(xhr.responseText);
                    if (respon.status === "success") {
                        pindahHalaman(respon.redirect);
                    } else {
                        showPopup("Kesalahan", respon.message, "", "Lanjut");
                    }
                } catch (errMsg) {
                    console.dir({
                        Kesalahan: errMsg,
                        "XMLHttpRequest Respon": xhr.responseText,
                    });
                }
            }
        };

        xhr.send(
            "login=true&email=" +
                encodeURIComponent(email) +
                "&password=" +
                encodeURIComponent(password)
        );
    } else {
        showPopup("Kesalahan", "Mohon isi kolom dengan benar", "", "Lanjut");
    }
}

// Sistem reset sandi
function resetPass(elm, event, tombol) {
    event.preventDefault();

    let requiredField = elm.querySelectorAll(
        "input[required], select[required]"
    );
    let validasi = true;

    requiredField.forEach((element) => {
        if (!element.checkValidity()) {
            validasi = false;
        }
    });

    let passfield = document.querySelectorAll("input[type='password']");
    if (passfield[0].value !== passfield[1].value) {
        validasi = false;
    }

    if (validasi) {
        tombol.innerText = "Memperoses";
        const email = elm.querySelector("#login-email").value;
        const newPassword = elm.querySelector("#new-password").value;
        const retypePassword = elm.querySelector("#new-password").value;

        let xhr = new XMLHttpRequest();
        xhr.open("POST", "../../backend/login.php", true);
        xhr.setRequestHeader(
            "Content-Type",
            "application/x-www-form-urlencoded"
        );

        xhr.onload = function () {
            if (xhr.status === 200) {
                tombol.innerText = "Reset Sandi";
                try {
                    let respon = JSON.parse(xhr.responseText);
                    if (respon.status === "success") {
                        showPopup(
                            "Berhasil",
                            "Sandi Berhasil di Ubah Silahkan Kehalaman Login.",
                            "",
                            "Lanjut"
                        );
                    } else {
                        if (respon.code === 404 || respon.code === 403) {
                            showPopup(
                                "Kesalahan",
                                respon.message,
                                "",
                                "Lanjut"
                            );
                        } else {
                            console.dir(respon.message);
                            showPopup(
                                "Kesalahan",
                                "Terjadi kesalahan",
                                "",
                                "Lanjut"
                            );
                        }
                    }
                } catch (errMsg) {
                    console.dir({
                        Kesalahan: errMsg,
                        "XMLHttpRequest Respon": xhr.responseText,
                    });
                }
            }
        };

        xhr.send(
            "reset=true&email=" +
                encodeURIComponent(email) +
                "&newpassword=" +
                encodeURIComponent(newPassword) +
                "&retypepassword=" +
                encodeURIComponent(retypePassword)
        );
    } else {
        showPopup("Kesalahan", "Mohon isi kolom dengan benar", "", "Lanjut");
    }
}

function pindahHalaman(url) {
    window.location.href = url;
}
