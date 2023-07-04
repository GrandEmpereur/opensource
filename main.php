<?php

use Empereur\Opensource\Api;

require_once __DIR__ . '/vendor/autoload.php';

$api = new Api();

// Pour obtenir les événements Google Calendar
$events = $api->getGoogleCalendarEvents();
$jsonEvents = json_encode($events, JSON_PRETTY_PRINT);
echo $jsonEvents;

// Pour obtenir toutes les lignes de bus
$linesJson = $api->getTransdevAllLines();
echo $linesJson, PHP_EOL;

$choice = readline("Enter the number of your choice: ");
$chosenLine = json_decode($linesJson, true)[$choice - 1] ?? null;

if ($chosenLine === null) {
    echo "Invalid choice. Please try again.\n";
    exit;
}

// Pour obtenir toutes les stations de bus d'une ligne spécifique
$stationsJson = $api->getTransdevBusStations($chosenLine['url']);
echo $stationsJson, PHP_EOL;
