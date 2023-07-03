<?php

declare(strict_types=1);

namespace Empereur\Opensource;

require_once __DIR__ . '/../vendor/autoload.php';

use Google_Client;
use Google_Service_Calendar;
use Google_Service_Calendar_Event;
use Dotenv\Dotenv;
use function \json_encode;

class Api {
    private $client;
    private $httpClient;

    public function __construct() {
        $dotenv = Dotenv::createImmutable(__DIR__ . '/..');
        $dotenv->load();

        $this->client = new Google_Client();
        $this->client->setApplicationName($_ENV['APPLICATION_NAME']);
        $this->client->setDeveloperKey($_ENV['DEVELOPER_KEY']);
        $this->client->setScopes(Google_Service_Calendar::CALENDAR);
        $this->client->setHttpClient(new \GuzzleHttp\Client(['verify' => false]));
    }

    public function getEvents(): array|object {
        $service = new Google_Service_Calendar($this->client);
        $calendarId = $_ENV['CALENDAR_ID'];
        $currentDate = date('Y-m-d');
    
        $optParams = array(
            'orderBy' => 'startTime',
            'singleEvents' => true,
            'timeMin' => $currentDate . 'T00:00:00Z',
            'timeMax' => date('Y-m-d', strtotime($currentDate . ' + 7 days')) . 'T00:00:00Z',
        );
    
        $results = $service->events->listEvents($calendarId, $optParams);
        $events = $results->getItems();

        $formattedEvents = [];
        foreach ($events as $event) {
            $formattedEvent = [
                'name' => $event->getSummary(),
                'attendees' => $event->getAttendees(),
                'location' => $event->getLocation(),
                'start' => $event->getStart()->getDateTime(),
                'end' => $event->getEnd()->getDateTime(),
            ];
            $formattedEvents[] = $formattedEvent;
        }

        return $formattedEvents;
    }
}
