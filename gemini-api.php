<?php

session_start();

header("Content-Type: application/json");


// DATABASE CONNECTION

include 'includes/db.php';

if ($conn->connect_error) {

    echo json_encode([
        "reply" => "Database Connection Failed"
    ]);

    exit();

}


// GROQ API KEY

require 'vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$apiKey = $_ENV['GROQ_API_KEY'];;


// CHECK REQUEST METHOD

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {

    echo json_encode([
        "reply" => "Invalid request method"
    ]);

    exit();

}


// CHECK MESSAGE

if (!isset($_POST['message'])) {

    echo json_encode([
        "reply" => "No message received"
    ]);

    exit();

}


// USER MESSAGE

$message = trim($_POST['message']);


// USER ID




$user_id = $_SESSION['user_id'];


// IF LOGIN SYSTEM EXISTS USE:
// $user_id = $_SESSION['user_id'];


// API DATA

$data = [

    "model" => "llama-3.3-70b-versatile",

    "messages" => [

        [
            "role" => "system",
            "content" => "You are an AI Study Assistant. Help students with DBMS, OS, CN, DSA, Web Development, Interview Preparation, Notes and Quizzes."
        ],

        [
            "role" => "user",
            "content" => $message
        ]

    ]

];


// CURL REQUEST

$ch = curl_init(
    "https://api.groq.com/openai/v1/chat/completions"
);

curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

curl_setopt($ch, CURLOPT_POST, true);

curl_setopt($ch, CURLOPT_HTTPHEADER, [

    "Content-Type: application/json",

    "Authorization: Bearer " . $apiKey

]);

curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));


// RESPONSE

$response = curl_exec($ch);


// CURL ERROR

if (curl_errno($ch)) {

    echo json_encode([

        "reply" => "CURL Error: " . curl_error($ch)

    ]);

    exit();

}


// DECODE RESPONSE

$result = json_decode($response, true);


// CHECK AI REPLY

if (isset($result['choices'][0]['message']['content'])) {

    $reply =
    $result['choices'][0]['message']['content'];


    // SAVE CHAT

    $stmt = $conn->prepare(

        "INSERT INTO chat_history
        (user_id, message, reply)
        VALUES (?, ?, ?)"

    );

    $stmt->bind_param(

        "iss",

        $user_id,

        $message,

        $reply

    );

    $stmt->execute();


    // SEND REPLY

    echo json_encode([

        "reply" => $reply

    ]);

} else {

    echo json_encode([

        "reply" => "API Error",

        "full_response" => $result

    ]);

}


curl_close($ch);

$conn->close();

?>