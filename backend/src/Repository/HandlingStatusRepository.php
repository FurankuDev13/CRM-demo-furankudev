<?php

namespace App\Repository;

use App\Entity\HandlingStatus;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method HandlingStatus|null find($id, $lockMode = null, $lockVersion = null)
 * @method HandlingStatus|null findOneBy(array $criteria, array $orderBy = null)
 * @method HandlingStatus[]    findAll()
 * @method HandlingStatus[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class HandlingStatusRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, HandlingStatus::class);
    }

    public function findByIsActiveOrderedByField($isActive = true, $field = 'title', $order = 'ASC')
    {
        return $this->createQueryBuilder('hs')
            ->andWhere('hs.isActive IN (:isActive)')
            ->setParameter('isActive', $isActive)
            ->orderBy('hs.' . $field, $order)
            ->getQuery()
            ->getResult()
        ;
    }
    // /**
    //  * @return HandlingStatus[] Returns an array of HandlingStatus objects
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
    public function findOneBySomeField($value): ?HandlingStatus
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
