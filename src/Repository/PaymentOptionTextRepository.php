<?php

namespace App\Repository;

use App\Entity\PaymentOptionText;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method PaymentOptionText|null find($id, $lockMode = null, $lockVersion = null)
 * @method PaymentOptionText|null findOneBy(array $criteria, array $orderBy = null)
 * @method PaymentOptionText[]    findAll()
 * @method PaymentOptionText[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PaymentOptionTextRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, PaymentOptionText::class);
    }

    // /**
    //  * @return PaymentOptionText[] Returns an array of PaymentOptionText objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?PaymentOptionText
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
