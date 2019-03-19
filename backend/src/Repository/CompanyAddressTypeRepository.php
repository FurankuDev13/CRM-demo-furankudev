<?php

namespace App\Repository;

use App\Entity\CompanyAddressType;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method CompanyAddressType|null find($id, $lockMode = null, $lockVersion = null)
 * @method CompanyAddressType|null findOneBy(array $criteria, array $orderBy = null)
 * @method CompanyAddressType[]    findAll()
 * @method CompanyAddressType[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CompanyAddressTypeRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, CompanyAddressType::class);
    }

    // /**
    //  * @return CompanyAddressType[] Returns an array of CompanyAddressType objects
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
    public function findOneBySomeField($value): ?CompanyAddressType
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
