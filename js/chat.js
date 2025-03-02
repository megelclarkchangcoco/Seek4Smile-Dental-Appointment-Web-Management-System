const form = document.querySelector(".typing-area");
const sendBtn = form.querySelector(".send-btn");
const inputField = form.querySelector(".input-field");
const chatBox = document.querySelector(".chat-box");
const imageInput = form.querySelector("input[name='send_image']");

// Prevent default form submission
form.onsubmit = (e) => e.preventDefault();

// Enable send button when typing
inputField.onkeyup = () => {
    sendBtn.classList.toggle("active", inputField.value.trim() !== "");
};

// Enable send button when selecting an image
imageInput.onchange = () => {
    sendBtn.classList.add("active");
};

// Send message function
sendBtn.onclick = () => {
    let formData = new FormData(form);

    fetch('php/insert_chat.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.text())
    .then(() => {
        inputField.value = "";
        imageInput.value = "";
        sendBtn.classList.remove("active");
        updateChat(); // Refresh chat messages
    })
    .catch(console.error);
};

// Fetch chat messages periodically
function updateChat() {
    let incoming_id = document.querySelector("input[name='incoming_id']").value;
    let outgoing_id = document.querySelector("input[name='outgoing_id']").value;

    fetch('php/get_message.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: `incoming_id=${incoming_id}&outgoing_id=${outgoing_id}`
    })
    .then(response => response.text())
    .then(data => {
        chatBox.innerHTML = data;
        chatBox.scrollTop = chatBox.scrollHeight;
    })
    .catch(console.error);
}

// Update chat every 500ms
setInterval(updateChat, 500);
