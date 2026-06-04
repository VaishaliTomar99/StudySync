// ===================================
// SEARCH TASKS
// ===================================

document.addEventListener("DOMContentLoaded", function () {

const searchInput =
document.getElementById("taskSearch");

if (searchInput) {

    searchInput.addEventListener("keyup", function () {

        let value =
        this.value.toLowerCase();

        let rows =
        document.querySelectorAll(".task-row");

        rows.forEach(row => {

            row.style.display =

            row.innerText.toLowerCase().includes(value)

            ? ""

            : "none";

        });

    });

}

});

// ===================================
// AUTO SCROLL CHAT
// ===================================

window.onload = () => {

const chatBox =
document.getElementById("chatBox");

if(chatBox){

    chatBox.scrollTop =
    chatBox.scrollHeight;

}
};

// ===================================
// DARK MODE
// ===================================

const darkToggle =
document.getElementById("darkToggle");

if (darkToggle) {


darkToggle.addEventListener("click", () => {

    document.body.classList.toggle("dark-mode");

});


}

// ===================================
// AI CHATBOT FUNCTION
// ===================================

async function sendMessage() {


let input =
document.getElementById("userInput");

if (!input) return;

let message =
input.value.trim();

if (message === "") return;

let chatBox =
document.getElementById("chatBox");


// ==========================
// USER MESSAGE
// ==========================

chatBox.innerHTML += `

<div class="message user-message">

    <div class="message-content">
        ${message}
    </div>

</div>

`;

input.value = "";


// AUTO SCROLL

chatBox.scrollTop =
chatBox.scrollHeight;


// ==========================
// TYPING MESSAGE
// ==========================

chatBox.innerHTML += `

<div class="message bot-message" id="typing">

    <div class="message-content">

        Typing...

    </div>

</div>

`;

chatBox.scrollTop =
chatBox.scrollHeight;


// ==========================
// SEND DATA TO PHP
// ==========================

try {

    let formData =
    new FormData();

    formData.append("message", message);


    let response =
    await fetch("gemini-api.php", {

        method: "POST",

        body: formData

    });


    let data =
    await response.json();


    // REMOVE TYPING

    let typing =
    document.getElementById("typing");

    if (typing) {

        typing.remove();

    }


    // ==========================
    // GET AI REPLY
    // ==========================

    let botReply =
    data.reply || "No response from AI";


    // ==========================
    // SHOW BOT MESSAGE
    // ==========================

    chatBox.innerHTML += `

    <div class="message bot-message">

        <img src="https://cdn-icons-png.flaticon.com/512/4712/4712027.png"
        class="avatar">

        <div class="message-content">

            ${botReply.replace(/\n/g, "<br>")}

        </div>

    </div>

    `;

    // AUTO SCROLL

    chatBox.scrollTop =
    chatBox.scrollHeight;

}

catch (error) {

    let typing =
    document.getElementById("typing");

    if (typing) {

        typing.remove();

    }

    chatBox.innerHTML += `

    <div class="message bot-message">

        <div class="message-content">

            ❌ Connection Error

        </div>

    </div>

    `;

    chatBox.scrollTop =
    chatBox.scrollHeight;

}


}

// ===================================
// DELETE CHAT HISTORY
// ===================================

async function deleteChats(){

    if(!confirm("Delete all chats?")) return;

    try{

        const response =
        await fetch("delete-chats.php");

        const data =
        await response.json();

        if(data.success){

            const chatBox =
            document.getElementById("chatBox");

            // RESET CHAT AREA
            chatBox.innerHTML = `

            <div class="message bot-message">

                <img
                src="https://cdn-icons-png.flaticon.com/512/4712/4712027.png"
                class="avatar">

                <div class="message-content">

                    Hello 👋<br><br>

                    I am your AI Study Assistant.<br>

                    Ask me anything about:

                    <ul>
                        <li>DBMS</li>
                        <li>OS</li>
                        <li>CN</li>
                        <li>DSA</li>
                        <li>Web Development</li>
                        <li>Interview Preparation</li>
                        <li>Notes Summaries</li>
                        <li>Quiz Generation</li>
                    </ul>

                </div>

            </div>

            `;

        }

        else{

            alert("Failed to delete chats");

        }

    }

    catch(error){

        alert("Error deleting chats");

        console.log(error);

    }

}


// ===================================
// NOTES SUMMARIZER
// ===================================

async function summarizeNotes(){


const notes =
document.getElementById("notesInput").value;

const file =
document.getElementById("notesFile").files[0];

const output =
document.getElementById("summaryOutput");


output.innerHTML =
"Summarizing Notes...";


let formData =
new FormData();

formData.append("notes", notes);


if(file){

    formData.append("file", file);

}


try{

    const response =
    await fetch("summarize-notes.php", {

        method: "POST",

        body: formData

    });

    const data =
    await response.json();


    output.innerHTML = `

    <div class="summary-box">

        ${data.summary.replace(/\n/g, "<br>")}

    </div>

    `;

}

catch(error){

    output.innerHTML =

    "❌ Error summarizing notes";

}


}

// ===================================
// ENTER KEY SUPPORT
// ===================================

document.addEventListener("keypress",

function (event) {

    if (event.key === "Enter") {

    const userInput =
    document.getElementById("userInput");

    if(document.activeElement === userInput){

        sendMessage();

    }

}

});
