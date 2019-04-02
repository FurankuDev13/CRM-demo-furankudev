<?php

namespace App\Repository;

use App\Entity\EmailType;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method EmailType|null find($id, $lockMode = null, $lockVersion = null)
 * @method EmailType|null findOneBy(array $criteria, array $orderBy = null)
 * @method EmailType[]    findAll()
 * @method EmailType[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EmailTypeRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, EmailType::class);
    }

    public function findByIsActiveOrderedByField($isActive = true, $field = 'title', $order = 'ASC')
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.isActive IN (:isActive)')
            ->setParameter('isActive', $isActive)
            ->orderBy('e.' . $field, $order)
            ->getQuery()
            ->getResult()
        ;
    }

    // /**
    //  * @return EmailType[] Returns an array of EmailType objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('e.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?EmailType
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
