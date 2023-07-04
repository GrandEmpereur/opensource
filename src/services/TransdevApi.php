<?php

declare(strict_types=1);

namespace Empereur\Opensource;

class TransdevApi {
    public function getAllLines(): string {
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

    public function getBusStations(string $chosenUrl): string {
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

    private function makeCurlRequest(string $url): string {
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
