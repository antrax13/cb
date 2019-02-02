<?php

namespace App\Repository;

use App\Entity\StampShape;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method StampShape|null find($id, $lockMode = null, $lockVersion = null)
 * @method StampShape|null findOneBy(array $criteria, array $orderBy = null)
 * @method StampShape[]    findAll()
 * @method StampShape[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class StampShapeRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, StampShape::class);
    }

    // /**
    //  * @return StampShape[] Returns an array of StampShape objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?StampShape
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
