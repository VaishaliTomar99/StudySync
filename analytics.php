<?php

session_start();

include 'includes/db.php';

if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
}

$user_id = $_SESSION['user_id'];

$completedTasks = mysqli_num_rows(mysqli_query($conn,
"SELECT * FROM tasks
 WHERE user_id='$user_id'
 AND status='Completed'"));

$pendingTasks = mysqli_num_rows(mysqli_query($conn,
"SELECT * FROM tasks
 WHERE user_id='$user_id'
 AND status='Pending'"));

?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport"
      content="width=device-width, initial-scale=1.0">

<title>Analytics</title>

<link rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

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
    backdrop-filter:blur(10px);
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
    padding:50px;
}

.page-title{
    font-size:42px;
    font-weight:bold;
    margin-bottom:40px;
}

/* CARD */

.analytics-card{
    background:#f5f5f5;
    border-radius:30px;
    padding:40px;
    width:700px;
}

/* CHART */

.chart-container{
    width:400px;
    height:400px;
    margin:auto;
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

            <li class="active">
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

    <h1 class="page-title">
        📊 Productivity Analytics
    </h1>

    <div class="analytics-card">

        <div class="chart-container">

            <canvas id="taskChart"></canvas>

        </div>

    </div>

</div>

<script>

const ctx =
document.getElementById('taskChart');

new Chart(ctx, {

    type:'doughnut',

    data:{

        labels:[
            'Completed',
            'Pending'
        ],

        datasets:[{

            data:[
                <?php echo $completedTasks; ?>,
                <?php echo $pendingTasks; ?>
            ],

            backgroundColor:[
                '#10b981',
                '#f59e0b'
            ],

            borderWidth:0

        }]
    },

    options:{

        responsive:true,

        maintainAspectRatio:false,

        plugins:{

            legend:{

                position:'top',

                labels:{
                    color:'#111827',
                    font:{
                        size:16
                    }
                }

            }

        }

    }

});

</script>

</body>
</html>