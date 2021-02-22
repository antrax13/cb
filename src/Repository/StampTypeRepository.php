<?php

namespace App\Repository;

use App\Entity\StampType;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method StampType|null find($id, $lockMode = null, $lockVersion = null)
 * @method StampType|null findOneBy(array $criteria, array $orderBy = null)
 * @method StampType[]    findAll()
 * @method StampType[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class StampTypeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, StampType::class);
    }

    // /**
    //  * @return StampType[] Returns an array of StampType objects
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
    public function findOneBySomeField($value): ?StampType
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
