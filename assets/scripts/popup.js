// popup
let popup = document.querySelector(".popup");
let popupTitle = document.querySelector(".popup-title");
let popupMsg = document.querySelector(".popup-msg");
let popupClose = document.querySelector(".controls .close-btn");
let popupSubmit = document.querySelector(".controls .submit-btn");

function showPopup(title, msg, closeBtn, submitBtn) {
    popupTitle.innerHTML = title;
    popupMsg.innerHTML = msg;
    popupClose.innerHTML = closeBtn;
    popupSubmit.innerHTML = submitBtn;

    if (popupClose.innerHTML == "") {
        popupClose.classList.add = "hidden";
        popupSubmit.addEventListener("click", togglePopup);
    }

    togglePopup();
}

function togglePopup() {
    if (popup.classList.contains("active")) {
        popup.classList.remove("show");
        setTimeout(() => {
            popup.classList.remove("active");
            if (popupClose.classList.contains("hidden")) {
                popupClose.classList.remove("hidden");
            }
        }, 300);
    } else {
        popup.classList.add("active");
        setTimeout(() => {
            popup.classList.add("show");
        }, 50);
    }
}

popupClose.addEventListener("click", togglePopup);
