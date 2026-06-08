<?php
function sendEmail($to, $subject, $message) {
    $apiKey = getenv('BREVO_API_KEY');
    
    $data = [
        'sender' => ['name' => 'StudySync AI', 'email' => getenv('BREVO_SMTP_FROM')],
        'to' => [['email' => $to]],
        'subject' => $subject,
        'textContent' => $message
    ];

    $ch = curl_init('https://api.brevo.com/v3/smtp/email');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'api-key: ' . $apiKey
    ]);
    
    $response = curl_exec($ch);
    curl_close($ch);
    return true;
}
?>
