<?php

namespace App\Controller;

use App\Repository\PostRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

class PostController extends AbstractController
{


    public function __construct(
        private readonly PostRepository $postRepository,
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
