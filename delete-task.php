<?php

include 'includes/db.php';

$id = $_GET['id'];

$query = "DELETE FROM tasks WHERE id='$id'";

mysqli_query($conn, $query);

header("Location: dashboard.php");

?>