<?php

namespace App\Hexa\Domain;

class Room
{
    private bool $isFree = true;

    public function __construct(
        public readonly string $id,
        private readonly string $number
    )
    {
    }


    public function book(): void
    {
        $this->isFree = false;
    }

    public static function fromSnapshot(RoomSnapshot $snapshot): self
    {
        $room = new self(
            $snapshot->id,
            $snapshot->number,

        );
        $room->isFree = $snapshot->isFree;

        return $room;
    }

    public function toSnapshot(): RoomSnapshot
    {
        return new RoomSnapshot(
            $this->id,
            $this->number,
            $this->isFree
        );
    }

}
