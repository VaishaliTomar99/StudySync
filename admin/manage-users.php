<?php

session_start();

include '../includes/db.php';

if(!isset($_SESSION['admin_id'])){
    header("Location: admin-login.php");
}

$users =
mysqli_query($conn,
"SELECT * FROM users");

?>

<!DOCTYPE html>
<html>

<head>

<title>Manage Users</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

</head>

<body style="background:#f5f7fb;">

<div class="container mt-5">

<div class="card p-4">

<h2 class="mb-4">
Manage Users
</h2>

<table class="table">

<thead>

<tr>

<th>ID</th>
<th>Name</th>
<th>Email</th>
<th>Role</th>

</tr>

</thead>

<tbody>

<?php while($user=mysqli_fetch_assoc($users)){ ?>

<tr>

<td>
<?php echo $user['id']; ?>
</td>

<td>
<?php echo $user['name']; ?>
</td>

<td>
<?php echo $user['email']; ?>
</td>

<td>
<?php echo $user['role']; ?>
</td>

</tr>

<?php } ?>

</tbody>

</table>

</div>

</div>

</body>
</html>