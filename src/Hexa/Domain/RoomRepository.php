<?php

namespace App\Hexa\Domain;

interface RoomRepository
{
    public function get(string $roomId): Room;

    public function save(Room $room): void;
}
