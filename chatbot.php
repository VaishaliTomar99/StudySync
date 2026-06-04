<?php
session_start();

if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport"
content="width=device-width, initial-scale=1.0">

<title>AI Study Assistant</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
rel="stylesheet">

<link rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

<style>

*{
    margin:0;
    padding:0;
    box-sizing:border-box;
    font-family:'Poppins',sans-serif;
}

body{
    background:
    linear-gradient(135deg,#667eea,#764ba2);
    height:100vh;
    overflow:hidden;
}

.sidebar{
    width:260px;
    height:100vh;
    background:rgba(255,255,255,0.1);
    backdrop-filter:blur(20px);
    position:fixed;
    left:0;
    top:0;
    padding:25px;
    color:white;
}

.logo{
    font-size:28px;
    font-weight:700;
    margin-bottom:40px;
}

.new-chat-btn{
    width:100%;
    padding:14px;
    border:none;
    border-radius:12px;
    background:white;
    color:#6c63ff;
    font-weight:600;
    margin-bottom:30px;
    cursor:pointer;
    transition:0.3s;
}

.new-chat-btn:hover{
    transform:scale(1.03);
}

.menu{
    display:flex;
    flex-direction:column;
    gap:15px;
}

.menu a{
    text-decoration:none;
    color:white;
    padding:14px;
    border-radius:12px;
    transition:0.3s;
}

.menu a:hover{
    background:rgba(255,255,255,0.2);
}

.main{
    margin-left:260px;
    height:100vh;
    display:flex;
    flex-direction:column;
}

.chat-header{
    padding:25px;
    color:white;
    font-size:32px;
    font-weight:700;
}

.chat-area{
    flex:1;
    overflow-y:auto;
    padding:30px;
}

.message{
    display:flex;
    margin-bottom:25px;
    animation:fadeIn 0.3s ease;
}

.user-message{
    justify-content:flex-end;
}

.bot-message{
    justify-content:flex-start;
}

.message-content{
    max-width:70%;
    padding:18px;
    border-radius:18px;
    line-height:1.7;
    font-size:16px;
}

.user-message .message-content{
    background:#6c63ff;
    color:white;
    border-bottom-right-radius:5px;
}

.bot-message .message-content{
    background:white;
    color:#222;
    border-bottom-left-radius:5px;
    box-shadow:0 5px 15px rgba(0,0,0,0.1);
}

.avatar{
    width:45px;
    height:45px;
    border-radius:50%;
    margin-right:12px;
    object-fit:cover;
}

.chat-input{
    padding:25px;
    background:white;
    display:flex;
    gap:15px;
}

.chat-input input{
    flex:1;
    border:none;
    background:#f1f3f6;
    border-radius:14px;
    padding:18px;
    font-size:16px;
    outline:none;
}

.send-btn{
    width:60px;
    border:none;
    border-radius:14px;
    background:#6c63ff;
    color:white;
    font-size:20px;
    cursor:pointer;
}

.typing{
    display:flex;
    gap:6px;
    margin-left:60px;
    margin-bottom:20px;
}

.typing span{
    width:10px;
    height:10px;
    background:white;
    border-radius:50%;
    animation:bounce 1s infinite;
}

.typing span:nth-child(2){ animation-delay:0.2s; }
.typing span:nth-child(3){ animation-delay:0.4s; }

@keyframes bounce{
    0%,80%,100%{ transform:scale(0); }
    40%{ transform:scale(1); }
}

@keyframes fadeIn{
    from{ opacity:0; transform:translateY(10px); }
    to{ opacity:1; transform:translateY(0); }
}

.chat-area::-webkit-scrollbar{ width:8px; }
.chat-area::-webkit-scrollbar-thumb{ background:#999; border-radius:10px; }
.chat-header{

    display: flex;

    justify-content: space-between;

    align-items: center;

    margin-bottom: 20px;

}

.delete-btn{

    background: #ff4d6d;

    color: white;

    border: none;

    padding: 10px 18px;

    border-radius: 12px;

    cursor: pointer;

    font-size: 15px;

    font-weight: bold;

    transition: 0.3s;

}

.delete-btn:hover{

    background: #ff1f4b;

}

</style>

</head>

<body>

<div class="sidebar">

    <div class="logo">🤖 StudySync AI</div>

    <button class="new-chat-btn" onclick="location.reload()">
        + New Chat
    </button>
    <div class="menu">

        <a href="dashboard.php">
            🏠 Dashboard
        </a>

        <a href="chatbot.php">
            💬 AI Chat
        </a>

        <a href="notes-summarizer.php">
            📝 Notes Summarizer
        </a>

        <a href="quiz-generator.php">
            🧠 AI Quiz Generator
        </a>

        <a href="voice-assistant.php">
            🎤 Voice Assistant
        </a>

        <a href="logout.php">
            🚪 Logout
        </a>

    </div>
    

</div>

<div class="main">

    <div class="chat-header">

        <h2>AI Study Assistant ✨</h2>

        <button
        class="delete-btn"
        onclick="deleteChats()">

            🗑 Delete Chats

        </button>

    </div>

    <div class="chat-area" id="chatBox">
        <?php include "load-chat.php"; ?>

        <div class="message bot-message">

            <img src="https://cdn-icons-png.flaticon.com/512/4712/4712027.png"
            class="avatar">

            <div class="message-content">
                Hello 👋 <br><br>
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

    </div>

    <div class="chat-input">
        <input type="text"
        id="userInput"
        placeholder="Ask anything...">

        <button class="send-btn" id="sendBtn">
            <i class="fa-solid fa-paper-plane"></i>
        </button>

    </div>

</div>

<script>

// ✅ FIXED - No onclick, using addEventListener instead
document.getElementById("sendBtn")
.addEventListener("click", sendMessage);

document.getElementById("userInput")
.addEventListener("keypress", function(e){
    if(e.key === "Enter") sendMessage();
});

async function sendMessage(){

    let input = document.getElementById("userInput");
    let chatBox = document.getElementById("chatBox");
    let message = input.value.trim();

    if(message === "") return;

    // USER MESSAGE
    chatBox.innerHTML += `
    <div class="message user-message">
        <div class="message-content">${message}</div>
    </div>`;

    input.value = "";

    // TYPING INDICATOR
    chatBox.innerHTML += `
    <div class="typing" id="typing">
        <span></span>
        <span></span>
        <span></span>
    </div>`;

    chatBox.scrollTop = chatBox.scrollHeight;

    try {

        let response = await fetch("gemini-api.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/x-www-form-urlencoded"
            },
            body: "message=" + encodeURIComponent(message)
        });

        let data = await response.json();

        // REMOVE TYPING
        let typing = document.getElementById("typing");
        if(typing) typing.remove();

        let botReply = data.reply || "No response from AI";

        // BOT MESSAGE
        chatBox.innerHTML += `
        <div class="message bot-message">
            <img src="https://cdn-icons-png.flaticon.com/512/4712/4712027.png"
            class="avatar">
            <div class="message-content">
                ${botReply.replace(/\n/g,"<br>")}
            </div>
        </div>`;

        chatBox.scrollTop = chatBox.scrollHeight;

    } catch(error) {

        let typing = document.getElementById("typing");
        if(typing) typing.remove();

        chatBox.innerHTML += `
        <div class="message bot-message">
            <div class="message-content">
                ❌ Connection Error. Check your API key.
            </div>
        </div>`;

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

            chatBox.innerHTML = `

            <div class="message bot-message">

                <img src="https://cdn-icons-png.flaticon.com/512/4712/4712027.png"
                class="avatar">

                <div class="message-content">

                    Hello 👋 <br><br>

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

        console.log(error);

        alert(data.error || "Error deleting chats");
    }

}
</script>

</body>
</html>