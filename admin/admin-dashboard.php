<?php

session_start();

include '../includes/db.php';

if(!isset($_SESSION['admin_id'])){
    header("Location: admin-login.php");
}

$totalUsers =
mysqli_num_rows(mysqli_query($conn,
"SELECT * FROM users"));

$totalTasks =
mysqli_num_rows(mysqli_query($conn,
"SELECT * FROM tasks"));

$completedTasks =
mysqli_num_rows(mysqli_query($conn,
"SELECT * FROM tasks
 WHERE status='Completed'"));

?>

<!DOCTYPE html>
<html>

<head>

<title>Admin Dashboard</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<style>

body{
    background:#f5f7fb;
    font-family:Poppins;
}

.admin-header{
    background:linear-gradient(135deg,#667eea,#764ba2);
    color:white;
    padding:25px;
    border-radius:20px;
}

.admin-card{
    background:white;
    padding:25px;
    border-radius:20px;
    box-shadow:0 10px 30px rgba(0,0,0,0.05);
}

</style>

</head>

<body>

<div class="container mt-5">

<div class="admin-header d-flex justify-content-between align-items-center">

<div>

<h2>
Welcome Admin,
<?php echo $_SESSION['admin_name']; ?>
</h2>

<p>
Manage your StudySync AI platform
</p>

</div>

<a href="admin-logout.php"
   class="btn btn-light">

Logout

</a>

</div>

<div class="row mt-4">

<div class="col-md-4">

<div class="admin-card">

<h3>
<?php echo $totalUsers; ?>
</h3>

<p>
Total Users
</p>

</div>

</div>

<div class="col-md-4">

<div class="admin-card">

<h3>
<?php echo $totalTasks; ?>
</h3>

<p>
Total Tasks
</p>

</div>

</div>

<div class="col-md-4">

<div class="admin-card">

<h3>
<?php echo $completedTasks; ?>
</h3>

<p>
Completed Tasks
</p>

</div>

</div>

</div>

<div class="row mt-4">

<div class="col-md-8">

<div class="admin-card">

<h4 class="mb-4">
Task Analytics
</h4>

<canvas id="taskChart"></canvas>

</div>

</div>

<div class="col-md-4">

<div class="admin-card">

<h4 class="mb-4">
Quick Actions
</h4>

<a href="manage-users.php"
   class="btn btn-primary w-100 mb-3">

Manage Users

</a>

<a href="manage-tasks.php"
   class="btn btn-dark w-100">

Manage Tasks

</a>

</div>

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
<?php echo $totalTasks - $completedTasks; ?>
],

backgroundColor:[
'#10b981',
'#f59e0b'
]

}]

}

});

</script>

</body>
</html>