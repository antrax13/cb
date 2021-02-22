<?php

namespace App\Repository;

use App\Entity\HandleShape;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method HandleShape|null find($id, $lockMode = null, $lockVersion = null)
 * @method HandleShape|null findOneBy(array $criteria, array $orderBy = null)
 * @method HandleShape[]    findAll()
 * @method HandleShape[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class HandleShapeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, HandleShape::class);
    }

    // /**
    //  * @return HandleShape[] Returns an array of HandleShape objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('h')
            ->andWhere('h.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('h.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?HandleShape
    {
        return $this->createQueryBuilder('h')
            ->andWhere('h.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
