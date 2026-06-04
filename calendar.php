<?php

session_start();

include 'includes/db.php';

$user_id = $_SESSION['user_id'];

/* FETCH TASKS */

$getTasks =
mysqli_query($conn,
"SELECT * FROM tasks
 WHERE user_id='$user_id'");

/* STORE EVENTS */

$events = [];

while($task = mysqli_fetch_assoc($getTasks)){

    $events[] = [

        'title' =>
        $task['task_title'],

        'start' =>
        $task['deadline']

    ];
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport"
      content="width=device-width, initial-scale=1.0">

<title>Study Calendar</title>

<link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/main.min.css"
rel="stylesheet">

<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.js"></script>

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
    color:white;
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
    padding:35px;
}

/* TITLE */

.page-title{
    color:white;
    font-size:42px;
    font-weight:800;
    margin-bottom:25px;
}

/* CARD */

.calendar-card{
    background:#f5f5f5;
    border-radius:30px;
    padding:25px;
    box-shadow:0 15px 40px rgba(0,0,0,0.15);
}

/* CALENDAR */

.fc .fc-toolbar-title{
    font-size:32px;
    font-weight:700;
    color:#1f2937;
}

.fc .fc-button{
    background:#6a5af9 !important;
    border:none !important;
    padding:10px 18px !important;
    border-radius:12px !important;
    font-weight:600;
}

.fc .fc-button:hover{
    background:#5848e5 !important;
}

.fc-day-today{
    background:#efe9ff !important;
}

.fc-event{
    background:#6a5af9 !important;
    border:none !important;
    border-radius:8px;
    padding:4px;
    font-size:13px;
}

.fc-daygrid-day-number{
    color:#1f2937;
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

            <li>
                <a href="pomodoro.php">
                    ⏰ Pomodoro Timer
                </a>
            </li>

            <li>
                <a href="chatbot.php">
                    🤖 AI Chatbot
                </a>
            </li>

            <li class="active">
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

    <h1 class="page-title">
        📅 Study Calendar
    </h1>

    <div class="calendar-card">

        <div id="calendar"></div>

    </div>

</div>

<script>

document.addEventListener('DOMContentLoaded', function(){

    var calendarEl =
    document.getElementById('calendar');

    var calendar =
    new FullCalendar.Calendar(calendarEl, {

        initialView: 'dayGridMonth',

        height: 'auto',

        headerToolbar: {

            left: 'prev,next today',

            center: 'title',

            right: 'dayGridMonth,timeGridWeek,timeGridDay'
        },

        events:
        <?php echo json_encode($events); ?>

    });

    calendar.render();

});

</script>

</body>
</html>