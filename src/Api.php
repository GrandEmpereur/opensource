<?php

declare(strict_types=1);

namespace Empereur\Opensource;

require_once __DIR__ . '/../vendor/autoload.php';

use Google_Client;
use Google_Service_Calendar;
use Google_Service_Calendar_Event;
use function \json_encode;

class Api {
    private $client;
    private $httpClient;

    public function __construct() {
        $this->client = new Google_Client();
        $this->client->setApplicationName('Calendar x RATP');
        //$this->client->setClientId('536453816458-ajmiqhr1uc137pn8q3159i12djvnoe51.apps.googleusercontent.com');
        //$this->client->setClientSecret('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');
        $this->client->setDeveloperKey('AIzaSyDfkCR7zb1Vy55fB7uP8OpKA151rs1Urjo');
        // $this->client->setAccessToken('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');
        $this->client->setScopes(Google_Service_Calendar::CALENDAR);
        $this->client->setHttpClient(new \GuzzleHttp\Client(['verify' => false]));
    }

    public function getEvents(): array|object {
        $service = new Google_Service_Calendar($this->client);
        $calendarId = 'bartosikpatrick1@gmail.com';
        $currentDate = date('Y-m-d');
    
        $optParams = array(
            'orderBy' => 'startTime',
            'singleEvents' => true,
            'timeMin' => $currentDate . 'T00:00:00Z',
            'timeMax' => date('Y-m-d', strtotime($currentDate . ' + 1 day')) . 'T00:00:00Z',
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
