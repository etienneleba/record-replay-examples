<?php

namespace App\Hexa\Domain;

readonly class RoomSnapshot
{

    public function __construct(
        public string $id,
        public string $number,
        public string $isFree
    )
    {
    }
}
