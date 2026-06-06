<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

header("Content-Type: application/json");

require_once __DIR__ . '/vendor/autoload.php';

use Smalot\PdfParser\Parser;


// =====================
// API KEY
// =====================

$apiKey = getenv('GROQ_API_KEY') ?: $_ENV['GROQ_API_KEY'] ?? '';

$text = "";


// =====================
// GET NOTES
// =====================

if(isset($_POST['notes'])){

    $text .= trim($_POST['notes']);

}


// =====================
// FILE UPLOAD
// =====================

if(isset($_FILES['file']) && $_FILES['file']['error'] == 0){

    $tmpName = $_FILES['file']['tmp_name'];

    $fileName = $_FILES['file']['name'];

    $extension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));


    // TXT FILE

    if($extension == "txt"){

        $text .= "\n\n" . file_get_contents($tmpName);

    }


    // PDF FILE

    elseif($extension == "pdf"){

        try{

            $parser = new Parser();

            $pdf = $parser->parseFile($tmpName);

            $pdfText = $pdf->getText();

            $text .= "\n\n" . $pdfText;

        }

        catch(Exception $e){

            echo json_encode([
                "summary" => "PDF Read Error: " . $e->getMessage()
            ]);

            exit();

        }

    }

}


// =====================
// EMPTY CHECK
// =====================

if(trim($text) == ""){

    echo json_encode([
        "summary" => "Please enter notes or upload a valid file."
    ]);

    exit();

}


// =====================
// GROQ REQUEST
// =====================

$data = [

    "model" => "llama-3.3-70b-versatile",

    "messages" => [

        [
            "role" => "system",
            "content" =>
            "Summarize these notes in simple student-friendly language with headings and bullet points."
        ],

        [
            "role" => "user",
            "content" => $text
        ]

    ]

];



$ch = curl_init("https://api.groq.com/openai/v1/chat/completions");

curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

curl_setopt($ch, CURLOPT_POST, true);

curl_setopt($ch, CURLOPT_HTTPHEADER, [

    "Content-Type: application/json",

    "Authorization: Bearer " . $apiKey

]);

curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

$response = curl_exec($ch);


// =====================
// CURL ERROR
// =====================

if(curl_errno($ch)){

    echo json_encode([
        "summary" => "CURL Error: " . curl_error($ch)
    ]);

    exit();

}


$result = json_decode($response, true);


// =====================
// SUCCESS
// =====================

if(isset($result['choices'][0]['message']['content'])){

    echo json_encode([

        "summary" => nl2br(
            $result['choices'][0]['message']['content']
        )

    ]);

}

else{

    echo json_encode([

        "summary" => "Groq API Error",

        "debug" => $result

    ]);

}

curl_close($ch);

?>
