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
            console.log(xhr.responseText);
            const respon = JSON.parse(xhr.responseText);
            if (respon.status === "error") {
                alert(respon.pesan);
                // showPopup("Kesalahan", "Internal Server Error", "", "Oke");
                console.log(respon.pesan);
            } else if (respon.status === "gagal") {
                alert(respon.pesan);
                // showPopup("Gagal", respon.pesan, "", "Oke");
            } else {
                alert(respon.pesan);
                // showPopup("Berhasil", respon.pesan, "", "Oke");
            }
        }
    };

    xhr.send(isi);
}
