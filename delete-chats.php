<?php

session_start();

header("Content-Type: application/json");


// DATABASE CONNECTION
$conn = mysqli_connect(
    "localhost",
    "root",
    "",
    "studyplanner_db"
);


// CHECK CONNECTION
if(!$conn){

    echo json_encode([
        "success" => false,
        "error" => "Database connection failed"
    ]);

    exit();

}


// CHECK USER SESSION
if(!isset($_SESSION['user_id'])){

    echo json_encode([
        "success" => false,
        "error" => "User session not found"
    ]);

    exit();

}


$user_id = $_SESSION['user_id'];


// DELETE CHATS
$sql =
"DELETE FROM chat_history WHERE user_id='$user_id'";


if(mysqli_query($conn, $sql)){

    echo json_encode([
        "success" => true
    ]);

}
else{

    echo json_encode([
        "success" => false,
        "error" => mysqli_error($conn)
    ]);

}

?>