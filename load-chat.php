<?php

session_start();

$conn = new mysqli(
    "localhost",
    "root",
    "",
    "studyplanner_db"
);

$user_id = $_SESSION['user_id'];

$result = $conn->query(
"SELECT * FROM chat_history
WHERE user_id = '$user_id'
ORDER BY id ASC"
);

while($row = $result->fetch_assoc()){

    echo "

    <div class='message user-message'>
        <div class='message-content'>
            {$row['message']}
        </div>
    </div>

    <div class='message bot-message'>
        <div class='message-content'>
            {$row['reply']}
        </div>
    </div>

    ";

}

?>