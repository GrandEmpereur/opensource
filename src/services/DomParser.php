<?php

namespace Empereur\Opensource;

class DomParser {
    public function parse($html) {
        // Create a new DOMDocument and load the HTML response
        $dom = new \DOMDocument;
        @$dom->loadHTML($html);

        // Use XPath to parse the DOMDocument
        $xpath = new \DOMXPath($dom);

        // Extract the bus stop information from the DOMDocument
        $lines = $xpath->query('//div[@id="lines"]//a');

        $data = [];

        foreach ($lines as $line) {
            $data[] = [
                'name' => $line->nodeValue,
                'url' => $line->getAttribute('href')
            ];
        }

        return $data;
    }
}
