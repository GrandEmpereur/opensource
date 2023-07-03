<?php 

use Empereur\Opensource\Api;
use PHPUnit\Framework\TestCase;

class ApiTest extends TestCase
{
    public function testGetRandomNumber()
    {
        $api = new Api();
        $this->assertIsInt($api->getRandomNumber());
    }

    public function testGetDate()
    {
        $api = new Api();
        $this->assertInstanceOf(\DateTime::class, $api->getDate());
        $this->assertIsString($api->getDate());
        $this->assertSame(date('Y-m-d H:i:s'), $api->getDateAsString());
    }
}
