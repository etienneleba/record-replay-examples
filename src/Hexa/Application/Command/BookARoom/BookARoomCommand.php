<?php

namespace App\Hexa\Application\Command\BookARoom;

readonly class BookARoomCommand
{

    public function __construct(
        public string $roomId
    )
    {
    }
}
