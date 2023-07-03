<?php

declare(strict_types=1);

namespace Empereur\Opensource;

class Api
{
    public function getRandomNumber(): int
    {
        return rand(0, 100);
    }

    public function getDate(): \DateTime
    {
        return new \DateTime();
    }

    public function getDateAsString(): string
    {
        return $this->getDate()->format('Y-m-d H:i:s');
    }
}