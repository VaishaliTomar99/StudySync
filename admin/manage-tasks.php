<?php

session_start();

include '../includes/db.php';

if(!isset($_SESSION['admin_id'])){
    header("Location: admin-login.php");
}

$tasks =
mysqli_query($conn,
"SELECT tasks.*, users.name
 FROM tasks
 JOIN users
 ON tasks.user_id = users.id");

?>

<!DOCTYPE html>
<html>

<head>

<title>Manage Tasks</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

</head>

<body style="background:#f5f7fb;">

<div class="container mt-5">

<div class="card p-4">

<h2 class="mb-4">
Manage Tasks
</h2>

<table class="table">

<thead>

<tr>

<th>User</th>
<th>Subject</th>
<th>Task</th>
<th>Status</th>

</tr>

</thead>

<tbody>

<?php while($task=mysqli_fetch_assoc($tasks)){ ?>

<tr>

<td>
<?php echo $task['name']; ?>
</td>

<td>
<?php echo $task['subject']; ?>
</td>

<td>
<?php echo $task['task_title']; ?>
</td>

<td>
<?php echo $task['status']; ?>
</td>

</tr>

<?php } ?>

</tbody>

</table>

</div>

</div>

</body>
</html>