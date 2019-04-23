<?php

namespace App\Repository;

use App\Entity\User;
use App\Entity\Company;
use App\Entity\UserRole;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, User::class);
    }

    public function findSalesRoles()
    {
        return $this->createQueryBuilder('u')
            ->join('u.userRoles', 'r')
            ->addSelect('r')
            ->join('u.person', 'p')
            ->addSelect('r')
            ->andWhere('r.code = :val')
            ->setParameter('val', 'ROLE_SALES')
            ->orderBy('p.lastname', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }

    public function findWherePersonIsActive()
    {
        return $this->createQueryBuilder('u')
            ->join('u.person', 'p')
            ->addSelect('p')
            ->andWhere('p.isActive = true')
            ->getQuery()
            ->getResult()
        ;
    }

    public function findByIsActiveAndOrderedByField($isActive = true, $table = 'p', $field = 'lastname', $order = 'ASC')
    {
        return $this->createQueryBuilder('u')
            ->join('u.userRoles', 'r')
            ->addSelect('r')
            ->join('u.person', 'p')
            ->addSelect('p')
            ->andWhere('p.isActive IN (:isActive)')
            ->setParameter('isActive', $isActive)
            ->orderBy($table . '.' . $field, $order)
            ->getQuery()
            ->getResult()
        ;
    }

    public function findByIsActiveAndByUserRole($isActive = true, $userRoleTitle, $table = 'p', $field = 'lastname', $order = 'ASC')
    {
        return $this->createQueryBuilder('u')
            ->join('u.userRoles', 'r')
            ->addSelect('r')
            ->join('u.person', 'p')
            ->addSelect('p')
            ->andWhere('r.title = :userRoleTitle')
            ->setParameter('userRoleTitle', $userRoleTitle)
            ->andWhere('p.isActive IN (:isActive)')
            ->setParameter('isActive', $isActive)
            ->orderBy($table . '.' . $field, $order)
            ->getQuery()
            ->getResult()
        ;
    }

    public function findByIsActiveAndByCompany($isActive = true, $companyName, $table = 'p', $field = 'lastname', $order = 'ASC')
    {
        return $this->createQueryBuilder('u')
            ->join('u.userRoles', 'r')
            ->addSelect('r')
            ->join('u.person', 'p')
            ->addSelect('p')
            ->join('u.companies', 'c')
            ->addSelect('c')
            ->andWhere('c.name = :companyName')
            ->setParameter('companyName', $companyName)
            ->andWhere('p.isActive IN (:isActive)')
            ->setParameter('isActive', $isActive)
            ->orderBy($table . '.' . $field, $order)
            ->getQuery()
            ->getResult()
        ;
    }

    public function findOneByEmailAndNotById($email, $id): ?User
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.email = :email')
            ->setParameter('email', $email)
            ->andWhere('u.id != :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

    // /**
    //  * @return User[] Returns an array of User objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('u.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?User
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
