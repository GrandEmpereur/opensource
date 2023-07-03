<?php

declare(strict_types=1);

namespace App;

class Api 
{
    public function getRandomNumber(): int
    {
        return random_int(0, 100);
    }
}