<?php

namespace App\Repository;

use App\Entity\User;
use App\Entity\Company;
use App\Entity\HandlingStatus;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @method Company|null find($id, $lockMode = null, $lockVersion = null)
 * @method Company|null findOneBy(array $criteria, array $orderBy = null)
 * @method Company[]    findAll()
 * @method Company[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CompanyRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Company::class);
    }

    public function findIsACtiveWithoutUserOrderedByField($table = 'c', $field = 'name', $order = 'ASC')
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.user IS NULL')
            ->orderBy($table . '.' . $field, $order)
            ->getQuery()
            ->getResult()
        ;
    }

    public function findIsActiveByHandlingStatus(HandlingStatus $handlingStatus, $table = 'r', $field = 'createdAt', $order = 'DESC')
    {
        return $this->createQueryBuilder('c')
            ->join('c.contacts', 'co')
            ->addSelect('co')
            ->join('co.requests', 'r')
            ->addSelect('r')
            ->andWhere('r.handlingStatus = :val')
            ->setParameter('val', $handlingStatus)
            ->orderBy($table . '.' . $field, $order)
            ->getQuery()
            ->getResult()
        ;
    }

    public function findIsActiveOrderedByField($field = 'name', $order = 'ASC')
    {
        return $this->createQueryBuilder('c')
        ->andWhere('c.isActive = :val')
        ->setParameter('val', true)
        ->orderBy('c.' . $field, $order)
        ->getQuery()
        ->getResult()
    ;
    }

    public function findIsActiveByIsCustomerOrderedByField($isCustomer = true, $field = 'name', $order = 'ASC')
    {
        return $this->createQueryBuilder('c')
        ->andWhere('c.isCustomer = :isCustomer')
        ->setParameter('isCustomer', $isCustomer)
        ->andWhere('c.isActive = :val')
        ->setParameter('val', true)
        ->orderBy('c.' . $field, $order)
        ->getQuery()
        ->getResult()
    ;
    }

    public function findIsActiveByUserOrderedByField(User $user, $field = 'name', $order = 'ASC')
    {
        return $this->createQueryBuilder('c')
        ->andWhere('c.user = :user')
        ->setParameter('user', $user)
        ->andWhere('c.isActive = :val')
        ->setParameter('val', true)
        ->orderBy('c.' . $field, $order)
        ->getQuery()
        ->getResult()
    ;
    }

    // /**
    //  * @return Company[] Returns an array of Company objects
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
    public function findOneBySomeField($value): ?Company
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
