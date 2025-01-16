function kirimForm(postId, event) {
    event.preventDefault();

    const formulir = document.querySelector("form");
    const isiForm = new FormData(formulir);
    isiForm.append(postId, "true");

    kirimData(isiForm);
}

function kirimData(isi) {
    const xhr = new XMLHttpRequest();
    xhr.open("POST", "../../backend/menu-calon.php", true);

    xhr.onload = function () {
        if (xhr.status === 200) {
            console.log(xhr.responseText);
            const respon = JSON.parse(xhr.responseText);
            if (respon.status === "error") {
                showPopup("Kesalahan", "Internal Server Error", "", "Oke");
                console.log(respon.pesan);
            } else if (respon.status === "gagal") {
                showPopup("Gagal", respon.pesan, "", "Oke");
            } else {
                showPopup("Berhasil", respon.pesan, "", "Oke");
            }
        }
    };

    xhr.send(isi);
}
