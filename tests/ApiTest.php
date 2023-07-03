<?php

use Empereur\Opensource\Api;
use PHPUnit\Framework\TestCase;

class ApiTest extends TestCase
{
    public function testGetEvents(): void
    {
        $api = new Api();
        $events = $api->getEvents();

        $this->assertIsArray($events);
        $this->assertNotEmpty($events);

        foreach ($events as $event) {
            $this->assertArrayHasKey('name', $event);
            $this->assertArrayHasKey('attendees', $event);
            $this->assertArrayHasKey('location', $event);
            $this->assertArrayHasKey('start', $event);
            $this->assertArrayHasKey('end', $event);
        }
    }

    public function testGetRATPData(): void
    {
        $api = new Api();
        $data = $api->getRATPData();

        $this->assertIsArray($data);
        $this->assertNotEmpty($data);

        foreach ($data as $lineName => $stations) {
            $this->assertIsString($lineName);
            $this->assertNotEmpty($lineName);

            $this->assertIsArray($stations);
            $this->assertNotEmpty($stations);

            foreach ($stations as $stationName) {
                $this->assertIsString($stationName);
                $this->assertNotEmpty($stationName);
            }
        }
    }
}