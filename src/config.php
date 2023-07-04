<?php

declare(strict_types=1);

use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();

define('APPLICATION_NAME', $_ENV['APPLICATION_NAME']);
define('DEVELOPER_KEY', $_ENV['DEVELOPER_KEY']);
define('CALENDAR_ID', $_ENV['CALENDAR_ID']);
