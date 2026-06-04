<?php

session_start();

include 'includes/db.php';

if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
}

$message = "";

if(isset($_POST['addTask'])){

    $user_id = $_SESSION['user_id'];

    $subject = $_POST['subject'];
    $task_title = $_POST['task_title'];
    $deadline = $_POST['deadline'];
    $priority = $_POST['priority'];
    $status = $_POST['status'];

    $query = "INSERT INTO tasks(
        user_id,
        subject,
        task_title,
        deadline,
        priority,
        status
    )
    VALUES(
        '$user_id',
        '$subject',
        '$task_title',
        '$deadline',
        '$priority',
        '$status'
    )";
    $query = "INSERT INTO tasks(user_id,subject,task_title,deadline,priority,status)
              VALUES('$user_id','$subject','$task_title','$deadline','$priority','$status')";

    if(mysqli_query($conn, $query)){
        $message = "✅ Task Added Successfully!";
    }else{
        $message = "❌ Something went wrong!";
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

<title>Add Task</title>

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<style>

body{
    margin:0;
    font-family:'Poppins',sans-serif;
    background:linear-gradient(135deg,#667eea 0%,#764ba2 100%);
    min-height:100vh;
    display:flex;
}

/* SIDEBAR */

.sidebar{
    width:250px;
    background:linear-gradient(135deg,#667eea 0%,#764ba2 100%);
    padding:30px 20px;
    position:fixed;
    height:100vh;
}

.sidebar-brand{
    color:white;
    font-size:24px;
    font-weight:700;
    margin-bottom:40px;
}

.sidebar-nav a{
    display:flex;
    align-items:center;
    gap:15px;
    padding:15px;
    margin-bottom:10px;
    border-radius:10px;
    color:white;
    text-decoration:none;
    font-weight:600;
    transition:0.3s;
}

.sidebar-nav a:hover,
.sidebar-nav a.active{
    background:rgba(255,255,255,0.2);
}

.sidebar-logout{
    position:absolute;
    bottom:30px;
    width:80%;
}

/* MAIN */

.main-content{
    margin-left:250px;
    width:100%;
    padding:40px;
}

.page-title{
    color:white;
    font-size:40px;
    font-weight:700;
    margin-bottom:30px;
}

.task-card{
    background:white;
    max-width:700px;
    border-radius:20px;
    padding:40px;
    box-shadow:0 10px 30px rgba(0,0,0,0.15);
}

.form-control,
.form-select{
    border-radius:12px;
    padding:14px;
    margin-bottom:20px;
}

.btn-custom{
    background:linear-gradient(135deg,#667eea,#764ba2);
    color:white;
    border:none;
    border-radius:12px;
    padding:14px;
    width:100%;
    font-weight:600;
    transition:0.3s;
}

.btn-custom:hover{
    transform:translateY(-2px);
    opacity:0.9;
}

.alert{
    border-radius:10px;
}

</style>

</head>

<body>

<!-- SIDEBAR -->

<div class="sidebar">

    <div class="sidebar-brand">
        📘 StudySync AI
    </div>

    <div class="sidebar-nav">

        <a href="dashboard.php">
            <i class="fas fa-home"></i>
            Dashboard
        </a>

        <a href="add-task.php" class="active">
            <i class="fas fa-plus-circle"></i>
            Add Task
        </a>

        <a href="analytics.php">
            <i class="fas fa-chart-line"></i>
            Analytics
        </a>

        <a href="pomodoro.php">
            <i class="fas fa-clock"></i>
            Pomodoro Timer
        </a>

        <a href="chatbot.php">
            <i class="fas fa-robot"></i>
            AI Chatbot
        </a>

        <a href="calendar.php">
            <i class="fas fa-calendar"></i>
            Calendar
        </a>

    </div>

    <div class="sidebar-logout">

        <a href="logout.php">
            <i class="fas fa-sign-out-alt"></i>
            Logout
        </a>

    </div>

</div>

<!-- MAIN CONTENT -->

<div class="main-content">

    <div class="page-title">
        ➕ Add Study Task
    </div>

    <div class="task-card">

        <?php if($message != ""){ ?>

            <div class="alert alert-info">
                <?php echo $message; ?>
            </div>

        <?php } ?>

        <form method="POST">

            <input type="text"
                   name="subject"
                   class="form-control"
                   placeholder="Enter Subject Name"
                   required>

            <input type="text"
                   name="task_title"
                   class="form-control"
                   placeholder="Enter Task Title"
                   required>

            <input type="date"
                   name="deadline"
                   class="form-control"
                   required>

            <select name="priority"
                    class="form-select">

                <option value="High">High Priority</option>
                <option value="Medium">Medium Priority</option>
                <option value="Low">Low Priority</option>

            </select>
            <select name="status"
                    class="form-select">

                <option value="Pending">Pending</option>
                <option value="In Progress">In Progress</option>
                <option value="Completed">Completed</option>

            </select>

            <button type="submit"
                    name="addTask"
                    class="btn btn-custom">

                Add Task

            </button>

        </form>

    </div>

</div>

</body>
</html>