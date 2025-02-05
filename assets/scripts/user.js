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
                        alert(respon.message);
                        // showPopup("Kesalahan", "Internal Server Error", "", "Oke");
                        console.log(respon.message);
                    }
                } else {
                    alert(respon.message);
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
