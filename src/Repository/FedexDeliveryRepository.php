<?php

namespace App\Repository;

use App\Entity\FedexDelivery;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method FedexDelivery|null find($id, $lockMode = null, $lockVersion = null)
 * @method FedexDelivery|null findOneBy(array $criteria, array $orderBy = null)
 * @method FedexDelivery[]    findAll()
 * @method FedexDelivery[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FedexDeliveryRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, FedexDelivery::class);
    }

    // /**
    //  * @return FedexDelivery[] Returns an array of FedexDelivery objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('f.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?FedexDelivery
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
