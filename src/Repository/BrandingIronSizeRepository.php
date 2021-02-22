<?php

namespace App\Repository;

use App\Entity\BrandingIronSize;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method BrandingIronSize|null find($id, $lockMode = null, $lockVersion = null)
 * @method BrandingIronSize|null findOneBy(array $criteria, array $orderBy = null)
 * @method BrandingIronSize[]    findAll()
 * @method BrandingIronSize[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BrandingIronSizeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BrandingIronSize::class);
    }

    // /**
    //  * @return BrandingIronSize[] Returns an array of BrandingIronSize objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('b.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?BrandingIronSize
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
