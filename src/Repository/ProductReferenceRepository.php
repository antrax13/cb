<?php

namespace App\Repository;

use App\Entity\ProductReference;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method ProductReference|null find($id, $lockMode = null, $lockVersion = null)
 * @method ProductReference|null findOneBy(array $criteria, array $orderBy = null)
 * @method ProductReference[]    findAll()
 * @method ProductReference[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductReferenceRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, ProductReference::class);
    }

    /**
    * @return ProductReference[] Returns an array of ProductReference objects
    */
    public function findAllOrdered()
    {
        return $this->createQueryBuilder('p')
            ->orderBy('p.position', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }

    /*
    public function findOneBySomeField($value): ?ProductReference
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
