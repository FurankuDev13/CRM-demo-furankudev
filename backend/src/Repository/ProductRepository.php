<?php

namespace App\Repository;

use App\Entity\Product;
use App\Entity\Category;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @method Product|null find($id, $lockMode = null, $lockVersion = null)
 * @method Product|null findOneBy(array $criteria, array $orderBy = null)
 * @method Product[]    findAll()
 * @method Product[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Product::class);
    }


    public function findByIsActiveAndIsAvailable($isActive = true, $isAvailable = true)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.isActive = :isActive')
            ->setParameter('isActive', $isActive)
            ->andWhere('p.isAvailable = :isAvailable')
            ->setParameter('isAvailable', $isAvailable)
            ->orderBy('p.createdAt', 'DESC')
            ->getQuery()
            ->getResult()
        ;
    }

    public function findByIsActiveAndIsAvailableAndIsOnHomePage($isActive = true, $isAvailable = true, $isOnHomePage = true)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.isActive = :isActive')
            ->setParameter('isActive', $isActive)
            ->andWhere('p.isAvailable = :isAvailable')
            ->setParameter('isAvailable', $isAvailable)
            ->andWhere('p.isOnHomePage = :isOnHomePage')
            ->setParameter('isOnHomePage', $isOnHomePage)
            ->orderBy('p.createdAt', 'DESC')
            ->getQuery()
            ->getResult()
        ;
    }

    public function findIsACtiveOrderedByField($field = 'name', $order = 'ASC', $isActive = true)
    {
        return $this->createQueryBuilder('p')
        ->andWhere('p.isActive = :val')
        ->setParameter('val', $isActive)
        ->orderBy('p.' . $field, $order)
        ->getQuery()
        ->getResult()
    ;
    }

    public function findIsACtiveByCategoryNameOrderedByField($categoryName, $field = 'name', $order = 'ASC', $isActive = true)
    {
        return $this->createQueryBuilder('p')
        ->join('p.categories', 'c')
        ->addSelect('c')
        ->where('c.name = :categoryName')
        ->setParameter('categoryName', $categoryName)
        ->andWhere('p.isActive = :val')
        ->setParameter('val', $isActive)
        ->orderBy('p.' . $field, $order)
        ->getQuery()
        ->getResult()
    ;
    }


    // /**
    //  * @return Product[] Returns an array of Product objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Product
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
