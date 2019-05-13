<?php

if (! file_exists(__DIR__ . '/.env')) {
	throw new Exception('The .env file is missing');
}

require_once __DIR__ . '/vendor/autoload.php';

$dotenv = Dotenv\Dotenv::create(__DIR__);
$dotenv->load();
