<?php

include 'includes/db.php';
include 'resend-config.php';

date_default_timezone_set('Asia/Kolkata');

$today = date('Y-m-d');

$tomorrow = date('Y-m-d', strtotime('+1 day'));


// =======================================
// 1 DAY BEFORE DEADLINE
// =======================================

$query1 = mysqli_query($conn,

"SELECT tasks.*, users.email, users.name

FROM tasks

JOIN users ON tasks.user_id = users.id

WHERE deadline = '$tomorrow'

AND reminder_sent_before = 0

AND status != 'Completed'");

while($task = mysqli_fetch_assoc($query1)){

    $email = $task['email'];

    $name = $task['name'];

    $subject = "📚 Reminder: Task Due Tomorrow";

    $message = "

Hello $name,

Your task deadline is tomorrow.

--------------------------------

Subject: {$task['subject']}

Task: {$task['task_title']}

Priority: {$task['priority']}

Deadline: {$task['deadline']}

--------------------------------

Please complete it on time.

- StudySync AI

";

    try {

        $resend->emails->send([

            'from' => 'StudySync <onboarding@resend.dev>',

            'to' => [$email],

            'subject' => $subject,

            'text' => $message

        ]);

        mysqli_query($conn,

        "UPDATE tasks

        SET reminder_sent_before = 1

        WHERE id='{$task['id']}'");

    } catch (Exception $e) {

        echo $e->getMessage();

    }

}



// =======================================
// DEADLINE TODAY
// =======================================

$query2 = mysqli_query($conn,

"SELECT tasks.*, users.email, users.name

FROM tasks

JOIN users ON tasks.user_id = users.id

WHERE deadline = '$today'

AND reminder_sent_today = 0

AND status != 'Completed'");

while($task = mysqli_fetch_assoc($query2)){

    $email = $task['email'];

    $name = $task['name'];

    $subject = "🚨 Deadline Today";

    $message = "

Hello $name,

Your task deadline is TODAY.

--------------------------------

Subject: {$task['subject']}

Task: {$task['task_title']}

Priority: {$task['priority']}

Deadline: {$task['deadline']}

--------------------------------

Please complete it before end of day.

- StudySync AI

";

    try {

        $resend->emails->send([

            'from' => 'StudySync <onboarding@resend.dev>',

            'to' => [$email],

            'subject' => $subject,

            'text' => $message

        ]);

        mysqli_query($conn,

        "UPDATE tasks

        SET reminder_sent_today = 1

        WHERE id='{$task['id']}'");

    } catch (Exception $e) {

        echo $e->getMessage();

    }

}

echo "Reminder System Executed Successfully";

?>