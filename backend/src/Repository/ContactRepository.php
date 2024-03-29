<?php

namespace App\Repository;

use App\Entity\Company;
use App\Entity\Contact;
use App\Entity\ContactType;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @method Contact|null find($id, $lockMode = null, $lockVersion = null)
 * @method Contact|null findOneBy(array $criteria, array $orderBy = null)
 * @method Contact[]    findAll()
 * @method Contact[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ContactRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Contact::class);
    }

    public function findWherePersonIsActive()
    {
        return $this->createQueryBuilder('c')
            ->join('c.person', 'p')
            ->addSelect('p')
            ->andWhere('p.isActive = true')
            ->getQuery()
            ->getResult()
        ;
    }

    public function findisActiveOrderedByField($table = 'p', $field = 'lastname', $order = 'ASC', $isActive = true)
    {
        return $this->createQueryBuilder('co')
            ->join('co.company', 'c')
            ->addSelect('c')
            ->join('co.contactType', 'ct')
            ->addSelect('ct')
            ->join('co.person', 'p')
            ->addSelect('p')
            ->andWhere('p.isActive = :isActive')
            ->setParameter('isActive', $isActive)
            ->orderBy($table . '.' . $field, $order)
            ->getQuery()
            ->getResult()
        ;
    }
    

    public function findIsActiveByContactType(ContactType $contactType, $table = 'p', $field = 'lastname', $order = 'ASC')
    {
        return $this->createQueryBuilder('co')
            ->join('co.company', 'c')
            ->addSelect('c')
            ->join('co.contactType', 'ct')
            ->addSelect('ct')
            ->join('co.person', 'p')
            ->addSelect('p')
            ->andWhere('co.contactType = :contactType')
            ->setParameter('contactType', $contactType)
            ->andWhere('p.isActive = :isActive')
            ->setParameter('isActive', true)
            ->orderBy($table . '.' . $field, $order)
            ->getQuery()
            ->getResult()
        ;
    }

    public function findIsActiveByCompany(Company $company, $table = 'p', $field = 'lastname', $order = 'ASC')
    {
        return $this->createQueryBuilder('co')
            ->join('co.company', 'c')
            ->addSelect('c')
            ->join('co.contactType', 'ct')
            ->addSelect('ct')
            ->join('co.person', 'p')
            ->addSelect('p')
            ->andWhere('co.company = :company')
            ->setParameter('company', $company)
            ->andWhere('p.isActive = :isActive')
            ->setParameter('isActive', true)
            ->orderBy($table . '.' . $field, $order)
            ->getQuery()
            ->getResult()
        ;
    }

    //collection des contacts d'une société
    public function findAllByOneCompany(Company $company)
    {
        return $this->createQueryBuilder('co')
            ->join('co.company', 'c')
            ->addSelect('c')
            ->join('co.person', 'p')
            ->addSelect('p')
            ->where('c = :company')
            ->setParameter('company', $company)
            ->getQuery()
            ->getResult()
        ;
    }

    public function findOneByEmailAndNotById($email, $id): ?Contact
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.email = :email')
            ->setParameter('email', $email)
            ->andWhere('c.id != :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }


    // /**
    //  * @return Contact[] Returns an array of Contact objects
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
    public function findOneBySomeField($value): ?Contact
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
