<?php

declare(strict_types=1);

namespace Empereur\Opensource;

use Google_Client;
use Google_Service_Calendar;

require_once __DIR__ . '/../config.php';

class GoogleClient {
    private $client;

    public function __construct() {
        $this->client = new Google_Client();
        $this->client->setApplicationName(APPLICATION_NAME);
        $this->client->setDeveloperKey(DEVELOPER_KEY);
        $this->client->setScopes(Google_Service_Calendar::CALENDAR);
        $this->client->setHttpClient(new \GuzzleHttp\Client(['verify' => false]));
    }

    public function getClient(): Google_Client {
        return $this->client;
    }
}
