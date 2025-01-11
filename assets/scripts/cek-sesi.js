function cekSesi(lokasi) {
    let xhrSesi = new XMLHttpRequest();
    xhrSesi.open("GET", "../../backend/cek-sesi.php", true);
    xhrSesi.setRequestHeader(
        "Content-Type",
        "application/x-www-form-urlencoded"
    );

    xhrSesi.onload = function () {
        if (xhrSesi.status === 200) {
            const respon = JSON.parse(xhrSesi.responseText);
            if (respon.diotentikasi && respon.role === "Admin") {
                if (lokasi === "login") {
                    window.location.href = "/views/admin/";
                }
            } else if (respon.diotentikasi && respon.role === "Calon") {
                if (lokasi === "login") {
                    window.location.href = "/views/user/";
                }
            } else {
                if (lokasi !== "login") {
                    window.location.href = "/views/login/";
                }
            }
        }
    };

    xhrSesi.send();
}
