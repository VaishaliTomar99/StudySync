<!DOCTYPE html>
<html>
<head>
<title>AI Quiz Generator</title>

<style>

body{
    font-family:Arial;
    background:#764ba2;
    color:white;
    padding:40px;
}

input{
    width:100%;
    padding:15px;
    border:none;
    border-radius:10px;
}

button{
    margin-top:20px;
    padding:15px 30px;
    border:none;
    border-radius:10px;
    background:white;
    color:#764ba2;
}

#quiz{
    background:white;
    color:black;
    margin-top:20px;
    padding:20px;
    border-radius:10px;
}

</style>
</head>

<body>

<h1>🧠 AI Quiz Generator</h1>

<input type="text"
id="topic"
placeholder="Enter topic like DBMS">

<button onclick="generateQuiz()">
Generate Quiz
</button>

<div id="quiz"></div>

<script>

async function generateQuiz(){

    let topic =
    document.getElementById("topic").value;

    let response =
    await fetch("gemini-api.php",{

        method:"POST",

        headers:{
            "Content-Type":
            "application/x-www-form-urlencoded"
        },

        body:
        "message=Generate 5 MCQ quiz questions on "
        + encodeURIComponent(topic)

    });

    let data =
    await response.json();

    document.getElementById("quiz").innerHTML =
    data.reply.replace(/\n/g,"<br>");

}

</script>

</body>
</html>