<?php

namespace App\Repository;

use App\Entity\Request;
use App\Entity\HandlingStatus;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @method Request|null find($id, $lockMode = null, $lockVersion = null)
 * @method Request|null findOneBy(array $criteria, array $orderBy = null)
 * @method Request[]    findAll()
 * @method Request[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RequestRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Request::class);
    }

    public function findByHandlingStatus(HandlingStatus $handlingStatus, $table = 'r', $field = 'createdAt', $order = 'DESC')
    {
        return $this->createQueryBuilder('r')
            ->join('r.contact', 'co')
            ->addSelect('co')
            ->join('co.person', 'p')
            ->addSelect('p')
            ->join('co.company', 'c')
            ->addSelect('c')
            ->andWhere('r.handlingStatus = :val')
            ->setParameter('val', $handlingStatus)
            ->orderBy($table . '.' . $field, $order)
            ->getQuery()
            ->getResult()
        ;
    }

    // /**
    //  * @return Request[] Returns an array of Request objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('r.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Request
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
