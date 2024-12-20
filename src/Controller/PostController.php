<?php

namespace App\Controller;

use App\Hexa\Domain\Room;
use App\Hexa\Domain\RoomSnapshot;
use App\Hexa\Infrastructure\Persistence\Postgres\PostgresRoomRepository;
use App\Repository\PostRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

class PostController extends AbstractController
{


    public function __construct(
        private readonly PostRepository $postRepository,
        private readonly PostgresRoomRepository $roomRepository,
        private readonly EntityManagerInterface $entityManager
    )
    {
    }

    #[Route('/', name: 'app_index')]
    public function index(): JsonResponse
    {


        $posts = $this->postRepository->findPublishedPosts();

        return $this->json($posts);
    }
}
