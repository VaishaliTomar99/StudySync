<?php
require 'vendor/autoload.php';
use Resend;
$resend = Resend::client(getenv('RESEND_API_KEY') ?: $_ENV['RESEND_API_KEY'] ?? '');
?>
