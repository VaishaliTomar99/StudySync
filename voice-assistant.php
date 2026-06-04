<!DOCTYPE html>
<html>
<head>
<title>Voice Assistant</title>

<style>

body{
    background:#667eea;
    color:white;
    font-family:Arial;
    text-align:center;
    padding-top:100px;
}

button{
    padding:20px 40px;
    border:none;
    border-radius:15px;
    font-size:20px;
    cursor:pointer;
}

#output{
    margin-top:40px;
    background:white;
    color:black;
    padding:20px;
    width:70%;
    margin:auto;
    border-radius:10px;
}

</style>
</head>

<body>

<h1>🎤 Voice Assistant</h1>

<button onclick="startVoice()">
Start Speaking
</button>

<div id="output"></div>

<script>

function startVoice(){

    let recognition =
    new webkitSpeechRecognition();

    recognition.lang = "en-US";

    recognition.start();

    recognition.onresult =
    async function(event){

        let text =
        event.results[0][0].transcript;

        document.getElementById("output").innerHTML =
        "You said: " + text;

        let response =
        await fetch("gemini-api.php",{

            method:"POST",

            headers:{
                "Content-Type":
                "application/x-www-form-urlencoded"
            },

            body:
            "message=" +
            encodeURIComponent(text)

        });

        let data =
        await response.json();

        document.getElementById("output").innerHTML +=
        "<br><br><b>AI:</b><br>" +
        data.reply;

        let speech =
        new SpeechSynthesisUtterance(data.reply);

        speechSynthesis.speak(speech);

    };

}

</script>

</body>
</html>