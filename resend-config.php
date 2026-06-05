<?php

require 'vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

use Resend;

$resend = Resend::client($_ENV['RESEND_API_KEY']);

?>