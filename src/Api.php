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

    public function getAllLines(): string
    {
        $busLine = readline("Enter the bus line number: ");
        $url = 'https://www.transdev-idf.com';
        $urlLines = $url . '/plan-et-horaires/' . $busLine;
        $response = $this->makeCurlRequest($urlLines);
        $dom = new \DOMDocument;
        @$dom->loadHTML($response);
        $xpath = new \DOMXPath($dom);
        $lines = $xpath->query('//div[@id="lines"]//a');
        $data = [];

        foreach ($lines as $line) {
            $data[] = [
                'name' => $line->nodeValue,
                'url' => $line->getAttribute('href')
            ];
        }

        return json_encode($data, JSON_PRETTY_PRINT);
    }

    public function getBusStations(string $chosenUrl): string
    {
        $url = 'https://www.transdev-idf.com';
        $busStation = $url . $chosenUrl;
        $response = $this->makeCurlRequest($busStation);
        $dom = new \DOMDocument;
        @$dom->loadHTML($response);
        $xpath = new \DOMXPath($dom);
        $stations = $xpath->query('//div[@class="station"]');
        $stationsData = [];

        foreach ($stations as $station) {
            $stationsData[] = [
                'name' => $station->nodeValue,
                'url' => $station->getAttribute('href')
            ];
        }

        return json_encode($stationsData, JSON_PRETTY_PRINT);
    }

    private function makeCurlRequest(string $url): string
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_FAILONERROR, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $response = curl_exec($ch);

        if ($response === false) {
            echo 'Erreur cURL : ' . curl_error($ch);
        }

        curl_close($ch);
        return $response;
    }
}
