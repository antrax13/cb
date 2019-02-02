<?php

namespace App\Repository;

use App\Entity\HandleColor;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method HandleColor|null find($id, $lockMode = null, $lockVersion = null)
 * @method HandleColor|null findOneBy(array $criteria, array $orderBy = null)
 * @method HandleColor[]    findAll()
 * @method HandleColor[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class HandleColorRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, HandleColor::class);
    }

    // /**
    //  * @return HandleColor[] Returns an array of HandleColor objects
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
    public function findOneBySomeField($value): ?HandleColor
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
