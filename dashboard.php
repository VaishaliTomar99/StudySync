<?php

session_start();

if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <title>Dashboard</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet" href="assets/css/style.css">

</head>

<body>

<div class="container mt-5">

    <div class="card p-4">

        <h1>
            Welcome,
            <?php echo $_SESSION['user_name']; ?>
        </h1>

        <p>
            AI Study Planner Dashboard
        </p>

        <a href="logout.php"
           class="btn btn-danger">

           Logout

        </a>

    </div>

</div>

</body>
</html>