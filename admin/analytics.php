<?php

session_start();

include 'includes/db.php';

if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

<title>Analytics</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

</head>

<body style="background:#f5f7fb;">

<div class="container mt-5">

    <div class="card p-4">

        <h2 class="mb-4">
            Productivity Analytics
        </h2>

        <canvas id="taskChart"></canvas>

    </div>

</div>

<script>

const ctx = document.getElementById('taskChart');

new Chart(ctx, {

    type: 'doughnut',

    data: {

        labels: ['Completed', 'Pending'],

        datasets: [{

            data: [<?php echo $completedTasks; ?>,
                   <?php echo $pendingTasks; ?>],

            backgroundColor: [
                '#10b981',
                '#f59e0b'
            ]

        }]
    }
});

</script>

</body>
</html>