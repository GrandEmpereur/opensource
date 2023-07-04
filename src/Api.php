<?php

declare(strict_types=1);

namespace Empereur\Opensource;

require_once __DIR__ . '/GoogleClient.php';
require_once __DIR__ . '/CalendarEvents.php';
require_once __DIR__ . '/TransdevApi.php';

class Api {
    private $googleClient;
    private $calendarEvents;
    private $transdevApi;

    public function __construct() {
        $this->googleClient = new GoogleClient();
        $this->calendarEvents = new CalendarEvents($this->googleClient);
        $this->transdevApi = new TransdevApi();
    }

    public function getGoogleCalendarEvents() {
        return $this->calendarEvents->getEvents();
    }

    public function getTransdevAllLines() {
        return $this->transdevApi->getAllLines();
    }

    public function getTransdevBusStations(string $chosenUrl) {
        return $this->transdevApi->getBusStations($chosenUrl);
    }
}
