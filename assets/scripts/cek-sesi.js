function cekSesi(lokasi) {
    let currentUrl = window.location.href;

    let xhrSesi = new XMLHttpRequest();
    xhrSesi.open("GET", "../../backend/cek-sesi.php", true);
    xhrSesi.setRequestHeader(
        "Content-Type",
        "application/x-www-form-urlencoded"
    );

    xhrSesi.onload = function () {
        if (xhrSesi.status === 200) {
            const respon = JSON.parse(xhrSesi.responseText);

            if (currentUrl.includes("login")) {
                if (respon.diotentikasi && respon.role === "Admin") {
                    window.location.href = "/views/admin/";
                } else if (respon.diotentikasi && respon.role === "Calon") {
                    window.location.href = "/views/user/";
                }
            } else if (respon.diotentikasi === false) {
                if (
                    currentUrl.includes("admin") ||
                    currentUrl.includes("user")
                ) {
                    window.location.href = "/views/login/";
                }
            }

            if (
                respon &&
                respon.role === "Admin" &&
                !currentUrl.includes("admin")
            ) {
                window.location.href = "/views/admin/";
            } else if (
                respon &&
                respon.role === "Calon" &&
                !currentUrl.includes("user")
            ) {
                window.location.href = "/views/user/";
            }
        }
    };

    xhrSesi.send();
}
