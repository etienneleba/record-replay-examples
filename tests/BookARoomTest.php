<?php

namespace App\Tests;

use App\Hexa\Application\Command\BookARoom\BookARoomCommand;
use App\Hexa\Application\Command\BookARoom\BookARoomCommandHandler;
use App\Hexa\Infrastructure\Persistence\Postgres\PostgresRoomRepository;
use App\RecordReplay\Mode;
use App\RecordReplay\RecordReplay;
use App\Tests\Builder\RoomBuilder;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class BookARoomTest extends KernelTestCase
{
    public function testThatItShouldBookARoom()
    {
        $container = static::getContainer();

        $postgresRoomRepository = $container->get(PostgresRoomRepository::class);

        static::ensureKernelShutdown();

        $recordReplay = new RecordReplay();

        /** @var PostgresRoomRepository $postgresRoomRepository */
        $postgresRoomRepository = $recordReplay->createProxy($postgresRoomRepository);

        $container = static::getContainer();

        $container->set(PostgresRoomRepository::class, $postgresRoomRepository);

        // Given the room 1 is free
        $roomBuilder = new RoomBuilder();
        $roomId = '1';
        $room = $roomBuilder
            ->setId($roomId)
            ->setIsFree(true)
            ->build();

        $recordReplay->start('./tests/records/test-that-it-should-book-a-room.json', Mode::REPLAY);

        $postgresRoomRepository->loadSnapshots([$room]);

        // When I book the room 1
        $bookARoomCommand = new BookARoomCommand($roomId);
        $bookARoomCommandHandler = new BookARoomCommandHandler($postgresRoomRepository);

        $bookARoomCommandHandler($bookARoomCommand);

        // Then the room 1 should not free anymore
        $rooms = $postgresRoomRepository->getSnapshots();

        $expectedRoom = (new RoomBuilder)
            ->setId("1")
            ->setIsFree(false)
            ->build();

        $this->assertEquals($expectedRoom, $rooms[0]);

        $recordReplay->save();
    }
}
