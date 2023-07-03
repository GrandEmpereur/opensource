<?php

namespace Empereur\Opensource;

class CurlClient {
    private $domParser;

    public function __construct(DomParser $domParser) {
        $this->domParser = $domParser;
    }

    public function getAllLine(): array {
        // Ask the user for the bus line number
        $busLine = readline("Enter the bus line number: ");
    
        // Construct the URL for the bus line
        $url = 'https://www.transdev-idf.com/plan-et-horaires/' . $busLine;
    
        echo "Fetching data from $url...\n";
    
        // Initialise the cURL session
        $ch = curl_init();
    
        echo "Initialising cURL session...\n";
    
        // Set the options for the cURL session
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_FAILONERROR, true); // cURL shows HTTP errors
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true); // cURL follows redirects
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false); // Disable SSL verification
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // Disable SSL verification
    
        // Execute the cURL session and store the response
        $response = curl_exec($ch);
    
        if($response === false) {
            echo 'Erreur cURL : ' . curl_error($ch);
        }
    
        // Close the cURL session
        curl_close($ch);
    
        // Create a new DOMDocument and load the HTML response
        $dom = new \DOMDocument;
        @$dom->loadHTML($response);
    
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
    
        // Display the choices to the user
        foreach ($data as $index => $line) {
            echo ($index+1) . ". " . $line['name'] . "\n";
        }
    
        // Ask the user for their choice
        $choice = readline("Enter the number of your choice: ");
    
        // Validate the user's choice
        if (!isset($data[$choice-1])) {
            echo "Invalid choice. Please try again.\n";
            return $this->getAllLine();
        }
    
        // Construct the URL for the chosen line
        $chosenUrl = $data[$choice-1]['url'];
    
        echo "Fetching data from $chosenUrl...\n";
    
        // Initialise the cURL session
        $ch = curl_init();
    
        echo "Initialising cURL session...\n";
    
        // Set the options for the cURL session
        curl_setopt($ch, CURLOPT_URL, $chosenUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    
        // Execute the cURL session and store the response
        $response = curl_exec($ch);
    
        // Close the cURL session
        curl_close($ch);
    
        // Create a new DOMDocument and load the HTML response
        $dom = new \DOMDocument;
        @$dom->loadHTML($response);
    
        // Use XPath to parse the DOMDocument
        $xpath = new \DOMXPath($dom);
    
        // Extract the bus stop information from the DOMDocument
        $stops = $xpath->query('//a[contains(@class, "stop_name")]');
    
        $stopsData = [];
    
        foreach ($stops as $stop) {
            $stopsData[] = [
                'name' => $stop->nodeValue,
                'url' => $stop->getAttribute('href')
            ];
        }
    
        return $stopsData;
    }
}
