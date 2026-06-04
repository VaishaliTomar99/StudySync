<?php

include 'includes/db.php';

$today = date('Y-m-d');

$tasks =
mysqli_query($conn,
"SELECT * FROM tasks
 WHERE deadline='$today'");

while($task=mysqli_fetch_assoc($tasks)){

echo "
Reminder:
" . $task['task_title'] .
" deadline is today!<br>";

}

?>