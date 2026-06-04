<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport"
      content="width=device-width, initial-scale=1.0">

<title>Pomodoro Timer</title>

<link rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

<style>

*{
    margin:0;
    padding:0;
    box-sizing:border-box;
    font-family:Arial,sans-serif;
}

body{
    display:flex;
    min-height:100vh;
    background:linear-gradient(135deg,#5b6ee1,#7a4dbb);
    color:white;
}

/* SIDEBAR */

.sidebar{
    width:270px;
    background:rgba(255,255,255,0.08);
    padding:30px 20px;
    display:flex;
    flex-direction:column;
    justify-content:space-between;
}

.logo{
    font-size:18px;
    font-weight:bold;
    margin-bottom:40px;
}

.sidebar ul{
    list-style:none;
}

.sidebar ul li{
    margin:18px 0;
}

.sidebar ul li a{
    text-decoration:none;
    color:white;
    display:flex;
    align-items:center;
    gap:12px;
    padding:14px 18px;
    border-radius:14px;
    transition:0.3s;
    font-size:16px;
    font-weight:600;
}

.sidebar ul li a:hover,
.sidebar ul li.active a{
    background:rgba(255,255,255,0.15);
}

.logout-btn{
    text-decoration:none;
    color:white;
    font-size:18px;
}

/* MAIN */

.main{
    flex:1;
    display:flex;
    justify-content:center;
    align-items:center;
    padding:40px;
}

/* CARD */

.timer-card{
    width:520px;
    background:#f5f5f5;
    border-radius:35px;
    padding:50px;
    text-align:center;
    box-shadow:0 15px 40px rgba(0,0,0,0.2);
}

.timer-title{
    font-size:42px;
    font-weight:bold;
    color:#1f2937;
    margin-bottom:25px;
}

/* TIMER */

#timer{
    font-size:90px;
    font-weight:800;
    color:#6a5af9;
    margin:25px 0;
}

/* BUTTONS */

.button-group{
    display:flex;
    justify-content:center;
    gap:15px;
    flex-wrap:wrap;
    margin-top:20px;
}

.timer-btn{
    border:none;
    padding:14px 24px;
    border-radius:14px;
    color:white;
    font-size:17px;
    font-weight:600;
    cursor:pointer;
    transition:0.3s;
}

.timer-btn:hover{
    transform:translateY(-3px);
}

.start{
    background:#10b981;
}

.pause{
    background:#f59e0b;
}

.reset{
    background:#ef4444;
}

/* QUICK BUTTONS */

.quick-buttons{
    margin-top:20px;
}

.quick-btn{
    border:none;
    background:#6a5af9;
    color:white;
    padding:10px 18px;
    border-radius:10px;
    margin:5px;
    cursor:pointer;
    font-weight:600;
}

.quick-btn:hover{
    opacity:0.9;
}

/* INPUT */

.custom-time{
    margin-top:20px;
}

.custom-time input{
    width:120px;
    padding:12px;
    border-radius:12px;
    border:1px solid #ccc;
    text-align:center;
    font-size:18px;
}

.custom-time button{
    padding:12px 20px;
    border:none;
    border-radius:12px;
    background:#6a5af9;
    color:white;
    font-weight:bold;
    margin-left:10px;
    cursor:pointer;
}

/* TEXT */

.session-text{
    margin-top:25px;
    color:#6b7280;
    font-size:18px;
    font-weight:600;
}

</style>

</head>

<body>

<!-- SIDEBAR -->

<div class="sidebar">

    <div>

        <div class="logo">
            📘 StudySync AI
        </div>

        <ul>

            <li>
                <a href="dashboard.php">
                    🏠 Dashboard
                </a>
            </li>

            <li>
                <a href="add-task.php">
                    ➕ Add Task
                </a>
            </li>

            <li>
                <a href="analytics.php">
                    📈 Analytics
                </a>
            </li>

            <li class="active">
                <a href="pomodoro.php">
                    ⏰ Pomodoro Timer
                </a>
            </li>

            <li>
                <a href="chatbot.php">
                    🤖 AI Chatbot
                </a>
            </li>

            <li>
                <a href="calendar.php">
                    📅 Calendar
                </a>
            </li>

        </ul>

    </div>

    <a href="logout.php"
       class="logout-btn">

       ↩ Logout

    </a>

</div>

<!-- MAIN -->

<div class="main">

    <div class="timer-card">

        <h1 class="timer-title">
            Pomodoro Timer
        </h1>

        <!-- TIMER -->

        <div id="timer">
            25:00
        </div>

        <!-- QUICK TIME BUTTONS -->

        <div class="quick-buttons">

            <button class="quick-btn"
                    onclick="setQuickTime(25)">

                25 Min

            </button>

            <button class="quick-btn"
                    onclick="setQuickTime(15)">

                15 Min

            </button>

            <button class="quick-btn"
                    onclick="setQuickTime(5)">

                5 Min

            </button>

        </div>

        <!-- CUSTOM TIME -->

        <div class="custom-time">

            <input type="number"
                   id="customMinutes"
                   placeholder="Minutes">

            <button onclick="setCustomTime()">

                Set Time

            </button>

        </div>

        <!-- CONTROL BUTTONS -->

        <div class="button-group">

            <button class="timer-btn start"
                    onclick="startTimer()">

                ▶ Start

            </button>

            <button class="timer-btn pause"
                    onclick="pauseTimer()">

                ⏸ Pause

            </button>

            <button class="timer-btn reset"
                    onclick="resetTimer()">

                🔄 Reset

            </button>

        </div>


    </div>

</div>

<script>

/* DEFAULT = 25 MIN */

let defaultTime = 1500;

let time = defaultTime;

let timer;

let running = false;

/* DISPLAY */

function updateDisplay(){

    let minutes =
    Math.floor(time / 60);

    let seconds =
    time % 60;

    seconds =
    seconds < 10
    ? "0" + seconds
    : seconds;

    document.getElementById("timer").innerText =
    minutes + ":" + seconds;
}

/* START */

function startTimer(){

    if(running) return;

    running = true;

    timer = setInterval(() => {

        if(time > 0){

            time--;

            updateDisplay();

        }

        else{

            clearInterval(timer);

            running = false;

            alert("🍅 Pomodoro Session Completed!");

        }

    },1000);
}

/* PAUSE */

function pauseTimer(){

    clearInterval(timer);

    running = false;
}

/* RESET */

function resetTimer(){

    clearInterval(timer);

    running = false;

    time = defaultTime;

    updateDisplay();
}

/* QUICK BUTTONS */

function setQuickTime(minutes){

    clearInterval(timer);

    running = false;

    defaultTime = minutes * 60;

    time = defaultTime;

    updateDisplay();
}

/* CUSTOM TIME */

function setCustomTime(){

    let minutes =
    document.getElementById("customMinutes").value;

    if(minutes <= 0){

        alert("Enter valid minutes");

        return;
    }

    clearInterval(timer);

    running = false;

    defaultTime = minutes * 60;

    time = defaultTime;

    updateDisplay();
}

/* INITIAL */

updateDisplay();

</script>

</body>
</html>