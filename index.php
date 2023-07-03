<?php

use Empereur\Opensource\Api;

require_once __DIR__ . '/vendor/autoload.php';

$api = new Api();
$events = $api->getEvents();

$jsonEvents = json_encode($events, JSON_PRETTY_PRINT);
echo $jsonEvents;

$ratpData = $api->getAllLine();

print_r($ratpData);