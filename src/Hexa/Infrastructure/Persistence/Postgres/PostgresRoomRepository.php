<?php

namespace App\Hexa\Infrastructure\Persistence\Postgres;

use App\Hexa\Domain\Room;
use App\Hexa\Domain\RoomRepository;
use App\Hexa\Domain\RoomSnapshot;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class PostgresRoomRepository extends ServiceEntityRepository implements RoomRepository
{

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Room::class);
    }

    public function get(string $roomId): Room
    {
        $room = $this->find($roomId);

        if (!$room) {
            throw new \RuntimeException(sprintf('Room with ID "%s" not found', $roomId));
        }

        return $room;
    }

    public function save(Room $room): void
    {
        $em = $this->getEntityManager();
        $em->persist($room);
        $em->flush();
    }


    public function loadSnapshots(array $snapshots): void
    {
        foreach ($snapshots as $snapshot) {
            $this->getEntityManager()->persist($snapshot);
        }
        $this->getEntityManager()->flush();
    }

    public function getSnapshots(): array
    {
        return $this->findAll();
    }


}
