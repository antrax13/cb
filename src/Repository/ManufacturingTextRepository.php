<?php

namespace App\Repository;

use App\Entity\ManufacturingText;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ManufacturingText|null find($id, $lockMode = null, $lockVersion = null)
 * @method ManufacturingText|null findOneBy(array $criteria, array $orderBy = null)
 * @method ManufacturingText[]    findAll()
 * @method ManufacturingText[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ManufacturingTextRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ManufacturingText::class);
    }

    // /**
    //  * @return ManufacturingText[] Returns an array of ManufacturingText objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('m.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ManufacturingText
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
