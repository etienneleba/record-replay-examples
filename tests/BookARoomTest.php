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

        // retrieve the current repository implementation
        $postgresRoomRepository = $container->get(PostgresRoomRepository::class);

        static::ensureKernelShutdown();

        $recordReplay = new RecordReplay();

        // create a proxy around the current repository implementation
        /** @var PostgresRoomRepository $postgresRoomRepository */
        $postgresRoomRepository = $recordReplay->createProxy($postgresRoomRepository);

        $container = static::getContainer();

        // override the current repository implementation with the proxy in the container
        $container->set(PostgresRoomRepository::class, $postgresRoomRepository);

        /*
         * Arrange
         */
        // record all the call to any secondary adapters that have been proxied
        $recordReplay->start('./tests/records/test-that-it-should-book-a-room.json', Mode::REPLAY);

        $roomBuilder = new RoomBuilder();
        $roomId = '1';
        $room = $roomBuilder
            ->setId($roomId)
            ->setIsFree(true)
            ->build();

        $postgresRoomRepository->loadSnapshots([$room]);

        /*
         * Act
         */
        $bookARoomCommand = new BookARoomCommand($roomId);
        $bookARoomCommandHandler = new BookARoomCommandHandler($postgresRoomRepository);

        $bookARoomCommandHandler($bookARoomCommand);

        /*
         * Assert
         */
        $rooms = $postgresRoomRepository->getSnapshots();

        $expectedRoom = (new RoomBuilder)
            ->setId("1")
            ->setIsFree(false)
            ->build();

        $this->assertEquals($expectedRoom, $rooms[0]);

        $recordReplay->save();
    }
}
