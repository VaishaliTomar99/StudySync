<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require 'vendor/autoload.php';

function sendEmail($to, $subject, $message) {
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host       = 'smtp-relay.brevo.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = getenv('BREVO_SMTP_USER');
        $mail->Password   = getenv('BREVO_SMTP_PASS');
        $mail->SMTPSecure = 'tls';
        $mail->Port       = 587;
        $mail->setFrom(getenv('BREVO_SMTP_FROM'), 'StudySync AI');
        $mail->addAddress($to);
        $mail->Subject = $subject;
        $mail->Body    = $message;
        $mail->send();
        return true;
    } catch (Exception $e) {
        echo "Email Error: {$mail->ErrorInfo}";
        return false;
    }
}
?>
