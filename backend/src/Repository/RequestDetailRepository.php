<?php

namespace App\Repository;

use App\Entity\RequestDetail;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method RequestDetail|null find($id, $lockMode = null, $lockVersion = null)
 * @method RequestDetail|null findOneBy(array $criteria, array $orderBy = null)
 * @method RequestDetail[]    findAll()
 * @method RequestDetail[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RequestDetailRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, RequestDetail::class);
    }

    // /**
    //  * @return RequestDetail[] Returns an array of RequestDetail objects
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
    public function findOneBySomeField($value): ?RequestDetail
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
