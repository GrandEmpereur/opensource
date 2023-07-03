<?php

use Empereur\Opensource\Api;

require_once __DIR__ . '/vendor/autoload.php';

$api = new Api();
echo $api->getRandomNumber();