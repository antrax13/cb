<?php

namespace App\Repository;

use App\Entity\SketchReference;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method SketchReference|null find($id, $lockMode = null, $lockVersion = null)
 * @method SketchReference|null findOneBy(array $criteria, array $orderBy = null)
 * @method SketchReference[]    findAll()
 * @method SketchReference[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SketchReferenceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SketchReference::class);
    }

    // /**
    //  * @return SketchReference[] Returns an array of SketchReference objects
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
    public function findOneBySomeField($value): ?SketchReference
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
