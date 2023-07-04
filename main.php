<?php

use Empereur\Opensource\Api;

require_once __DIR__ . '/vendor/autoload.php';

$api = new Api();
$events = $api->getEvents();

$jsonEvents = json_encode($events, JSON_PRETTY_PRINT);
echo $jsonEvents;

$linesJson = $api->getAllLines();

echo $linesJson, PHP_EOL;

$choice = readline("Enter the number of your choice: ");
$chosenLine = json_decode($linesJson, true)[$choice - 1] ?? null;

if ($chosenLine === null) {
    echo "Invalid choice. Please try again.\n";
    exit;
}

$stationsJson = $api->getBusStations($chosenLine['url']);

echo $stationsJson, PHP_EOL;