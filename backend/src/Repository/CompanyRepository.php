<?php

namespace App\Repository;

use App\Entity\Company;
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

    public function findOrphans()
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.user IS NULL')
            ->orderBy('c.createdAt', 'DESC')
            ->getQuery()
            ->getResult()
        ;
    }

    public function findByUnhandledRequests()
    {
        return $this->createQueryBuilder('c')
            ->join('c.contacts', 'co')
            ->addSelect('co')
            ->join('co.requests', 'r')
            ->addSelect('r')
            ->join('r.handlingStatus', 'h')
            ->addSelect('h')
            ->andWhere('h.title LIKE :val')
            ->setParameter('val', 'Initiée')
            ->orderBy('r.createdAt', 'ASC')
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
