// popup
let popup = document.querySelector(".popup");

// elemen popup
let popupTitle = document.querySelector(".popup-title");
let popupMsg = document.querySelector(".popup-msg");
let popupClose = document.querySelector(".controls .close-btn");
let popupSubmit = document.querySelector(".controls .submit-btn");

// Tampilkan popup
function showPopup(judul, deskripsi, teksFalse, teksTrue) {
    // Teks
    popupTitle.innerText = judul;
    popupMsg.innerText = deskripsi;

    // Tombol batal
    if (teksFalse || teksFalse == "") {
        popupClose.innerText = teksFalse;
        popupClose.addEventListener("click", () => {
            togglePopup();
        });
    } else {
        popupClose.classList.add = "hidden";
    }

    // Tombol lanjut
    popupSubmit.innerText = teksTrue;
    popupSubmit.addEventListener("click", () => {
        togglePopup();
    });
    togglePopup();
}

// Toggle popup
function togglePopup() {
    if (popup.classList.contains("active")) {
        popup.classList.remove("show");
        setTimeout(() => {
            popup.classList.remove("active");
            resetPopup();
        }, 300);
    } else {
        popup.classList.add("active");
        setTimeout(() => {
            popup.classList.add("show");
        }, 50);
    }
}

// Reset
function resetPopup() {
    // Reset teks
    popupTitle.innerText = "This is popup title";
    popupMsg.innerText =
        "Lorem ipsum, dolor sit amet consectetur adipisicing elit.";

    // Reset tombol
    popupClose.innerText = "Close";
    popupSubmit.innerText = "Submit";

    popupClose.removeAttribute("onclick");
    popupSubmit.removeAttribute("onclick");
}
