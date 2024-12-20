<?php

namespace App\Tests\Builder;

use App\Hexa\Domain\Room;
use App\Hexa\Domain\RoomSnapshot;
use phpDocumentor\Reflection\Types\Boolean;

class RoomBuilder
{
    private string $id = '1';
    private string $number = '1';
    private bool $isFree = true;

    public function setId(string $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function setNumber(string $number): self
    {
        $this->number = $number;

        return $this;
    }

    public function setIsFree(bool $value): self
    {
        $this->isFree = $value;

        return $this;
    }

    public function build(): Room
    {
        return Room::fromSnapshot(new RoomSnapshot(
            $this->id,
            $this->number,
            $this->isFree
        ));
    }
}
