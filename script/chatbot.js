const chatInput = document.querySelector(".chat-input textarea");
const sendChatBtn = document.querySelector(".chat-input span");
const chatbox = document.querySelector(".chatbox");

let userMessage; 
const API_KEY = "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";
let databaseChatbot;

const createChatLi = (message, className) => {
    // Creata a chat <li> element width passed message and className 
    const chatLi = document.createElement("li");
    chatLi.classList.add("chat", className);
    let chatContent = className === "outgoing" ? `<p>${message}</p>` : `<span class="material-symbols-outlined">smart_toy</span><p>${message}</p>`;
    chatLi.innerHTML = chatContent;
    return chatLi;
}
// Proses JSON
fetch("../json/chatbot.json").then(response => {
    // Memastikan respons berhasil
    if (!response) {
        throw new Error(`HTTP error! status: ${response.status}`);
    
    }
    // Mengubah respons menjadi JavaScript
    return response.json(); 
}).then(data => {
    databaseChatbot = data;

}).catch(error => {
    // Menangani error
    console.error("Error fetching JSON", error)
});

const generateResponse = (chat) => {
//     const API_URL = "https://api.openai.com/v1/chat/completions";

//     const requestOptions = {
//         method: "POST",
//         headers: {
//             "Content-Type": "Application/json",
//             "Authorization": `Bearer ${API_KEY}`
//         },
//         body: JSON.stringify({
//             model: "gpt-3.5-turbo",
//             messages:[{role: "user", content: userMessage}]
//         })
//     }
// // Send POST request to API, get response
//     fetch(API_URL, requestOptions).then(res => res.json()).then(data =>{
//         console.log(data);
//     }).catch((error) => {
//         console.log(error);
//     })

    // jawab pertanyaan
    let messageElement = chat.querySelector("p");
    databaseChatbot.forEach((jawaban, index, array) => {
        let pertanyaan = jawaban["pertanyaan"];
        if (Array.isArray(pertanyaan)) {
            pertanyaan.forEach((katakunci) => {
                if (userMessage.toLowerCase().includes(katakunci.toLowerCase())) {
                    prosesJawaban(array.indexOf(jawaban));
                }
            });
        }else if (userMessage.toLowerCase().includes(pertanyaan.toLowerCase())) {
            prosesJawaban(array.indexOf(jawaban));
        }
    });

    function prosesJawaban(index) {
        let responJawaban = databaseChatbot[index]["jawaban"];
        if (Array.isArray(responJawaban)) {
            messageElement.innerHTML = responJawaban.map((item, index) => `${index + 1}. ${item}`).join("<br>");
        }else if (typeof responJawaban == "object" && responJawaban !== null) {
            messageElement.innerHTML = Object.entries(responJawaban).map(([key, value]) => `<b>${key}</b>: ${value}`).join("<br>");
        }else {
            messageElement.innerHTML = responJawaban; 
        }
    }
}

const handleChat = () => {
    userMessage = chatInput.value.trim();
    if(!userMessage) return;

    //Append the user's message to the chatbox 
    chatbox.appendChild(createChatLi(userMessage, "outgoing"));

    setTimeout(() => {
        //Display "Thinking..." message while waiting for the response 
        let incomingChatLi = chatbox.appendChild(createChatLi("Maaf saya tidak tahu", "incoming"));
        generateResponse(incomingChatLi);
    }, 600);
}
sendChatBtn.addEventListener("click", handleChat);