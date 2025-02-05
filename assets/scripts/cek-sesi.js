function cekSesi() {
    let currentUrl = window.location.href;

    // Request API
    let xhrSesi = new XMLHttpRequest();
    xhrSesi.open("GET", "../../backend/cek-sesi.php", true);
    xhrSesi.setRequestHeader(
        "Content-Type",
        "application/x-www-form-urlencoded"
    );

    // Kirim request
    xhrSesi.send();

    // Ambil respon
    xhrSesi.onload = function () {
        if (xhrSesi.status === 200) {
            let respon;
            if ((respon = JSON.parse(xhrSesi.responseText))) {
                // Cek sesi di halaman login
                if (currentUrl.includes("login")) {
                    if (
                        respon.details.authenticated &&
                        respon.details.role === "Admin"
                    ) {
                        window.location.href = "/views/admin/";
                    } else if (
                        respon.details.authenticated &&
                        respon.details.role === "Calon"
                    ) {
                        window.location.href = "/views/user/";
                    }
                } else if (respon.details.authenticated === false) {
                    if (
                        currentUrl.includes("admin") ||
                        currentUrl.includes("user")
                    ) {
                        window.location.href = "/views/login/";
                    }
                }

                // Cek sesi diluar halaman login
                if (
                    respon &&
                    respon.details.role === "Admin" &&
                    !currentUrl.includes("admin")
                ) {
                    window.location.href = "/views/admin/";
                } else if (
                    respon &&
                    respon.details.role === "Calon" &&
                    !currentUrl.includes("user")
                ) {
                    window.location.href = "/views/user/";
                }
            } else {
                alert("Kesalahaan dalam memparsing API");
                console.log(xhrSesi.responseText);
            }
        }
    };
}
