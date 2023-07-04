<?php

declare(strict_types=1);

namespace Empereur\Opensource;

use Google_Service_Calendar;
use Google_Service_Calendar_Event;

require_once __DIR__ . '/../config.php';

class CalendarEvents {
    private $client;

    public function __construct(GoogleClient $client) {
        $this->client = $client->getClient();
    }

    public function getEvents(): array {
        $service = new Google_Service_Calendar($this->client);
        $calendarId = CALENDAR_ID;
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
