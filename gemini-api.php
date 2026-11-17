
<?php

session_start();
header("Content-Type: application/json");

include 'includes/db.php';

// Check database connection
if ($conn->connect_error) {
    echo json_encode(["reply" => "Database connection failed"]);
    exit();
}

// Get Groq API key
$apiKey = getenv('GROQ_API_KEY');

if (empty($apiKey)) {
    error_log("GROQ_API_KEY is missing");
    echo json_encode(["reply" => "Groq API key is missing on the server"]);
    exit();
}

// Check request method
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(["reply" => "Invalid request method"]);
    exit();
}

// Check message
$message = trim($_POST['message'] ?? '');

if ($message === '') {
    echo json_encode(["reply" => "Please enter a message"]);
    exit();
}

// Check login session
$user_id = $_SESSION['user_id'] ?? null;

if (!$user_id) {
    echo json_encode(["reply" => "Please log in again"]);
    exit();
}

// Groq API request
$data = [
    "model" => "openai/gpt-oss-120b",
    "messages" => [
        [
            "role" => "system",
            "content" => "You are an AI Study Assistant. Help students with DBMS, OS, CN, DSA, Web Development, Interview Preparation, Notes and Quizzes."
        ],
        [
            "role" => "user",
            "content" => $message
        ]
    ],
    "temperature" => 0.7,
    "max_tokens" => 1024
];

$ch = curl_init("https://api.groq.com/openai/v1/chat/completions");

curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST => true,
    CURLOPT_HTTPHEADER => [
        "Content-Type: application/json",
        "Authorization: Bearer " . $apiKey
    ],
    CURLOPT_POSTFIELDS => json_encode($data),
    CURLOPT_TIMEOUT => 60
]);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

// Check CURL error
if ($response === false) {
    error_log("Groq CURL Error: " . curl_error($ch));
    curl_close($ch);
    echo json_encode(["reply" => "Connection error while contacting Groq"]);
    exit();
}

curl_close($ch);

// Decode response
$result = json_decode($response, true);

// Check Groq response
if ($httpCode !== 200) {
    $errorMessage = $result['error']['message'] ?? 'Unknown Groq API error';

    error_log("Groq HTTP Error: " . $httpCode . " - " . $errorMessage);

    echo json_encode([
        "reply" => "Groq API Error",
        "details" => $errorMessage,
        "status" => $httpCode
    ]);
    exit();
}

// Extract AI response
$reply = $result['choices'][0]['message']['content'] ?? '';

if ($reply === '') {
    error_log("Groq returned an empty response");
    echo json_encode(["reply" => "AI returned an empty response"]);
    exit();
}

// Save chat history
$stmt = $conn->prepare(
    "INSERT INTO chat_history (user_id, message, reply) VALUES (?, ?, ?)"
);

if ($stmt) {
    $stmt->bind_param("iss", $user_id, $message, $reply);
    $stmt->execute();
    $stmt->close();
}

// Send response
echo json_encode(["reply" => $reply]);

$conn->close();

?>
