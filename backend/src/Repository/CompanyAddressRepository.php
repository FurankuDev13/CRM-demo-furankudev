<?php

namespace App\Repository;

use App\Entity\Company;
use App\Entity\CompanyAddress;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @method CompanyAddress|null find($id, $lockMode = null, $lockVersion = null)
 * @method CompanyAddress|null findOneBy(array $criteria, array $orderBy = null)
 * @method CompanyAddress[]    findAll()
 * @method CompanyAddress[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CompanyAddressRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, CompanyAddress::class);
    }

    public function findAddressIsActiveByCompany(Company $company)
    {
        return $this->createQueryBuilder('a')
            ->join('a.company', 'c')
            ->addSelect('c')
            ->where('c.id = :companyId')
            ->setParameter('companyId', $company->getId())
            ->andWhere('a.isActive IN (:isActive)')
            ->setParameter('isActive', true)
            ->orderBy('c.name', 'ASC')
            ->getQuery()
            ->getResult();
        ;
    }

        //collection des adresses d'une société
        public function findAllByOneCompany(Company $company)
        {
            return $this->createQueryBuilder('a')
                ->join('a.company', 'c')
                ->addSelect('c')
                ->where('c = :company')
                ->setParameter('company', $company)
                ->getQuery()
                ->getResult()
            ;
        }
    

    // /**
    //  * @return CompanyAddress[] Returns an array of CompanyAddress objects
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
    public function findOneBySomeField($value): ?CompanyAddress
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
