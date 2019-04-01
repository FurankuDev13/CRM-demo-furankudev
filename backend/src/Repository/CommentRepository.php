<?php

namespace App\Repository;

use App\Entity\Comment;
use App\Entity\Company;
use App\Entity\Request;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @method Comment|null find($id, $lockMode = null, $lockVersion = null)
 * @method Comment|null findOneBy(array $criteria, array $orderBy = null)
 * @method Comment[]    findAll()
 * @method Comment[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CommentRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Comment::class);
    }

    public function findCommentIsActiveByCompany(Company $company)
    {
        return $this->createQueryBuilder('com')
            ->join('com.company', 'c')
            ->addSelect('c')
            ->where('c = :company')
            ->setParameter('company', $company)
            ->andWhere('com.isActive IN (:isActive)')
            ->setParameter('isActive', true)
            ->orderBy('com.updatedAt', 'DESC')
            ->getQuery()
            ->getResult();
        ;
    }

    public function findCommentIsActiveByRequest(Request $request)
    {
        return $this->createQueryBuilder('com')
            ->join('com.request', 'r')
            ->addSelect('r')
            ->where('r = :request')
            ->setParameter('request', $request)
            ->andWhere('com.isActive IN (:isActive)')
            ->setParameter('isActive', true)
            ->orderBy('com.updatedAt', 'DESC')
            ->getQuery()
            ->getResult();
        ;
    }

    public function findCommentIsActiveByUpdatedAt()
    {
        return $this->createQueryBuilder('com')
            // ->join('com.company', 'c')
            // ->addSelect('c')
            // ->join('com.request', 'r')
            // ->addSelect('r')
            ->where('com.isActive IN (:isActive)')
            ->setParameter('isActive', true)
            ->orderBy('com.updatedAt', 'DESC')
            ->getQuery()
            ->getResult();
        ;
    }

    // /**
    //  * @return Comment[] Returns an array of Comment objects
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
    public function findOneBySomeField($value): ?Comment
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
