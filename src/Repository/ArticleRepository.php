<?php

namespace App\Repository;

use App\Entity\Article;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Article>
 */
class ArticleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Article::class);
    }

//    /**
//     * @return Article[] Returns an array of Article objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('a.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Article
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
/**
     * Recherche un mot-clé dans les articles
     */
/**
     * Recherche un mot-clé dans les articles
     */
    public function findBySearch(string $query): array
    {
        return $this->createQueryBuilder('a')
            ->where('a.title LIKE :q')
            ->orWhere('a.content LIKE :q')
            // Correction : on utilise les noms des propriétés PHP (CamelCase)
            ->andWhere('a.isPublished = true') 
            ->setParameter('q', '%' . $query . '%')
            ->orderBy('a.createdAt', 'DESC') // Correction : createdAt au lieu de created_at
            ->getQuery()
            ->getResult();
    }
}
