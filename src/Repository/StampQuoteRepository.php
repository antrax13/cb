<?php

namespace App\Repository;

use App\Entity\StampQuote;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method StampQuote|null find($id, $lockMode = null, $lockVersion = null)
 * @method StampQuote|null findOneBy(array $criteria, array $orderBy = null)
 * @method StampQuote[]    findAll()
 * @method StampQuote[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class StampQuoteRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, StampQuote::class);
    }

    // /**
    //  * @return StampQuote[] Returns an array of StampQuote objects
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
    public function findOneBySomeField($value): ?StampQuote
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
