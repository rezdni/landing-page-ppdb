function createPopup(id){
    let popupNode = document.querySelector(id);
    let overlay = popupNode.querySelector(".overlay");
    let closeBtn = popupNode.querySelector(".closeBtn");
    function openPopup(){
        popupNode.classList.add("active");
    }
    function closePopup(){
        popupNode.classList.remove("active");
    }
    overlay.addEvenListener("click", closePopup);
    closBtn.addEvenListener("click", closePopup);
    return openPopup;
};

let popup = createPopup(".popup");