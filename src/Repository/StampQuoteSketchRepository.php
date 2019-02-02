<?php

namespace App\Repository;

use App\Entity\StampQuoteSketch;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method StampQuoteSketch|null find($id, $lockMode = null, $lockVersion = null)
 * @method StampQuoteSketch|null findOneBy(array $criteria, array $orderBy = null)
 * @method StampQuoteSketch[]    findAll()
 * @method StampQuoteSketch[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class StampQuoteSketchRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, StampQuoteSketch::class);
    }

    // /**
    //  * @return StampQuoteSketch[] Returns an array of StampQuoteSketch objects
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
    public function findOneBySomeField($value): ?StampQuoteSketch
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
