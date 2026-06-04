<?php

session_start();

include 'includes/db.php';

$user_id = $_SESSION['user_id'];

header('Content-Type:text/csv');
header('Content-Disposition:attachment;filename=tasks.csv');

$output = fopen("php://output", "w");

fputcsv($output,
['Subject','Task','Deadline','Priority','Status']);

$tasks =
mysqli_query($conn,
"SELECT * FROM tasks
 WHERE user_id='$user_id'");

while($row=mysqli_fetch_assoc($tasks)){

    fputcsv($output,[
        $row['subject'],
        $row['task_title'],
        $row['deadline'],
        $row['priority'],
        $row['status']
    ]);

}

fclose($output);

?>