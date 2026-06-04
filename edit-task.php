<?php

session_start();

include 'includes/db.php';

$id = $_GET['id'];

$getTask = mysqli_query($conn,
"SELECT * FROM tasks WHERE id='$id'");

$task = mysqli_fetch_assoc($getTask);

if(isset($_POST['updateTask'])){

    $subject = $_POST['subject'];

    $task_title = $_POST['task_title'];

    $deadline = $_POST['deadline'];

    $priority = $_POST['priority'];

    $status = $_POST['status'];

    $query = "UPDATE tasks SET

              subject='$subject',
              task_title='$task_title',
              deadline='$deadline',
              priority='$priority',
              status='$status'

              WHERE id='$id'";

    mysqli_query($conn, $query);

    header("Location: dashboard.php");

}

?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport"
      content="width=device-width, initial-scale=1.0">

<title>Edit Task</title>

<link rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

<style>

*{
    margin:0;
    padding:0;
    box-sizing:border-box;
    font-family:Arial, sans-serif;
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

/* FORM CARD */

.form-container{
    width:750px;
    background:#f5f5f5;
    border-radius:30px;
    padding:40px;
}

.form-group{
    margin-bottom:22px;
}

.form-group input,
.form-group select{
    width:100%;
    padding:18px;
    border:2px solid #ddd;
    border-radius:16px;
    font-size:18px;
    outline:none;
}

/* BUTTON */

.btn{
    width:100%;
    padding:18px;
    border:none;
    border-radius:16px;
    font-size:22px;
    font-weight:bold;
    cursor:pointer;
    color:white;
    background:linear-gradient(to right,#5b6ee1,#7a4dbb);
    transition:0.3s;
}

.btn:hover{
    opacity:0.9;
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

            <li class="active">
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
        ✏ Edit Task
    </h1>

    <div class="form-container">

        <form method="POST">

            <!-- SUBJECT -->

            <div class="form-group">

                <input type="text"
                       name="subject"
                       value="<?php echo $task['subject']; ?>"
                       required>

            </div>

            <!-- TASK TITLE -->

            <div class="form-group">

                <input type="text"
                       name="task_title"
                       value="<?php echo $task['task_title']; ?>"
                       required>

            </div>

            <!-- DEADLINE -->

            <div class="form-group">

                <input type="date"
                       name="deadline"
                       value="<?php echo $task['deadline']; ?>"
                       required>

            </div>

            <!-- PRIORITY -->

            <div class="form-group">

                <select name="priority">

                    <option value="High"
                    <?php if($task['priority']=="High") echo "selected"; ?>>
                    High Priority
                    </option>

                    <option value="Medium"
                    <?php if($task['priority']=="Medium") echo "selected"; ?>>
                    Medium Priority
                    </option>

                    <option value="Low"
                    <?php if($task['priority']=="Low") echo "selected"; ?>>
                    Low Priority
                    </option>

                </select>

            </div>

            <!-- STATUS -->

            <div class="form-group">

                <select name="status">

                    <option value="Pending"
                    <?php if($task['status']=="Pending") echo "selected"; ?>>
                    Pending
                    </option>

                    <option value="In Progress"
                    <?php if($task['status']=="In Progress") echo "selected"; ?>>
                    In Progress
                    </option>

                    <option value="Completed"
                    <?php if($task['status']=="Completed") echo "selected"; ?>>
                    Completed
                    </option>

                </select>

            </div>

            <!-- BUTTON -->

            <button type="submit"
                    name="updateTask"
                    class="btn">

                Update Task

            </button>

        </form>

    </div>

</div>

</body>
</html>