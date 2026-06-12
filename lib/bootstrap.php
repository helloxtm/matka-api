<?php
declare(strict_types=1);

date_default_timezone_set('Asia/Kolkata');

$configPath = dirname(__DIR__) . '/config.php';
if (!is_file($configPath)) {
    http_response_code(500);
    die('Missing config.php — copy config.example.php to config.php and set domain_key.');
}

$config = require $configPath;
if (!is_array($config)) {
    die('config.php must return an array.');
}

require_once __DIR__ . '/MapiClient.php';
require_once __DIR__ . '/Response.php';
