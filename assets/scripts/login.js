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
function registrasi(elm, event) {
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
                    let respon = JSON.parse(xhr.responseText);
                    if (respon.status === "success") {
                        alert(
                            "Akun berhasil dibuat, silahkan pindah ke menu login"
                        );

                        // bersihkan kolom isian
                        let field = elm.querySelectorAll("input");
                        field.forEach((element) => {
                            element.value = "";
                        });

                        // pindahkan pengguna ke halaman login
                        window.location.href = "index.html";
                    } else {
                        alert("Kesalahan dalam membuat akun");
                        console.log(respon.message);
                    }
                } catch (errMsg) {
                    console.dir({
                        Kesalahan: errMsg,
                        "XMLHttpRequest Respon": xhr.responseText,
                    });
                }
            } else {
                alert("Kesalahan dalam menghubungkan ke server");
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
        alert("Mohon isi kolom dengan benar");
    }
}

// Sistem login
function login(elm, event) {
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
                try {
                    let respon = JSON.parse(xhr.responseText);
                    if (respon.status === "success") {
                        pindahHalaman(respon.redirect);
                    } else {
                        alert(respon.message);
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
        alert("Mohon isi kolom dengan benar");
    }
}

// Sistem reset sandi
function resetPass(elm, event) {
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
                try {
                    let respon = JSON.parse(xhr.responseText);
                    if (respon.status === "success") {
                        alert(respon.message);
                        window.location.href = "index.html";
                    } else {
                        if (respon.code === 404 || respon.code === 403) {
                            alert(respon.message);
                        } else {
                            alert("Terjadi kesalahan");
                            console.dir(respon.message);
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
        alert("Mohon isi kolom dengan benar");
    }
}

function pindahHalaman(url) {
    window.location.href = url;
}
