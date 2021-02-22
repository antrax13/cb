<?php

namespace App\Repository;

use App\Entity\CustomProductInfo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method CustomProductInfo|null find($id, $lockMode = null, $lockVersion = null)
 * @method CustomProductInfo|null findOneBy(array $criteria, array $orderBy = null)
 * @method CustomProductInfo[]    findAll()
 * @method CustomProductInfo[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CustomProductInfoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CustomProductInfo::class);
    }

    // /**
    //  * @return CustomProductInfo[] Returns an array of CustomProductInfo objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?CustomProductInfo
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
