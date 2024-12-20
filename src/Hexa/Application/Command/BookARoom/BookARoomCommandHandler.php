<?php

namespace App\Hexa\Application\Command\BookARoom;

use App\Hexa\Domain\RoomRepository;

class BookARoomCommandHandler
{


    public function __construct(
        private readonly RoomRepository $roomRepository,
    )
    {
    }

    public function __invoke(BookARoomCommand $command) {
        $room = $this->roomRepository->get($command->roomId);
        $room->book();
        $this->roomRepository->save($room);
    }
}
