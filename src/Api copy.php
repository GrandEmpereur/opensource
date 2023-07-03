<?php

declare(strict_types=1);

namespace Empereur\Opensource;

require_once __DIR__ . '/../vendor/autoload.php';

use Google_Client;
use Google_Service_Calendar;
use Google_Service_Calendar_Event;
use Dotenv\Dotenv;
use Empereur\Opensource\CurlClient;
use Empereur\Opensource\DomParser;
use function \json_encode;

class Api {
    private $googleClient;
    private $curlClient;
    private $domParser;

    public function __construct(GoogleClient $googleClient, CurlClient $curlClient, DomParser $domParser) {
        $this->googleClient = $googleClient;
        $this->curlClient = $curlClient;
        $this->domParser = $domParser;
    }

    public function getEvents(): array|object {
        return $this->googleClient->getEvents();
    }

    public function getAllLine(): array {
        return $this->curlClient->getAllLine();
    }
}
