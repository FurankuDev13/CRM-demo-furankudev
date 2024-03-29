<?php

namespace App\Repository;

use App\Entity\ContactType;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method ContactType|null find($id, $lockMode = null, $lockVersion = null)
 * @method ContactType|null findOneBy(array $criteria, array $orderBy = null)
 * @method ContactType[]    findAll()
 * @method ContactType[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ContactTypeRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, ContactType::class);
    }

    public function findWherePersonIsActive()
    {
        return $this->createQueryBuilder('ct')
            ->join('ct.contacts', 'co')
            ->addSelect('co')
            ->join('co.person', 'p')
            ->addSelect('p')
            ->andWhere('p.isActive = :isActive')
            ->setParameter('isActive', true)
            ->orderBy('ct.title', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }

    public function findByIsActiveOrderedByField($isActive = true, $field = 'title', $order = 'ASC')
    {
        return $this->createQueryBuilder('ct')
            ->andWhere('ct.isActive IN (:isActive)')
            ->setParameter('isActive', $isActive)
            ->orderBy('ct.' . $field, $order)
            ->getQuery()
            ->getResult()
        ;
    }


    // /**
    //  * @return ContactType[] Returns an array of ContactType objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ContactType
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
