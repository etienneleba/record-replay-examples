<?php

namespace App\Repository;

use App\Entity\Post;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Post>
 */
class PostRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Post::class);
    }

        /**
         * @return Post[] Returns an array of Post objects
         */
        public function findPublishedPosts(): array
        {
            return $this->createQueryBuilder('p')
                ->andWhere('p.published = :val')
                ->setParameter('val', true)
                ->orderBy('p.id', 'ASC')
                ->getQuery()
                ->getResult()
            ;
        }

}
