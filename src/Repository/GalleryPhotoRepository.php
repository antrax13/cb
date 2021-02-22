<?php

namespace App\Repository;

use App\Entity\GalleryPhoto;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method GalleryPhoto|null find($id, $lockMode = null, $lockVersion = null)
 * @method GalleryPhoto|null findOneBy(array $criteria, array $orderBy = null)
 * @method GalleryPhoto[]    findAll()
 * @method GalleryPhoto[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GalleryPhotoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, GalleryPhoto::class);
    }

    /**
     * @return GalleryPhoto[] Returns an array of GalleryPhoto objects
     */
    public function findAllOrdered()
    {
        return $this->createQueryBuilder('g')
            ->orderBy('g.position', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }


    /*
    public function findOneBySomeField($value): ?GalleryPhoto
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
