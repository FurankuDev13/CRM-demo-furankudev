<?php

namespace App\Repository;

use App\Entity\EmailTemplate;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method EmailTemplate|null find($id, $lockMode = null, $lockVersion = null)
 * @method EmailTemplate|null findOneBy(array $criteria, array $orderBy = null)
 * @method EmailTemplate[]    findAll()
 * @method EmailTemplate[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EmailTemplateRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, EmailTemplate::class);
    }

    public function findByIsActiveOrderedByField($isActive = true, $field = 'title', $order = 'ASC')
    {
        return $this->createQueryBuilder('et')
            ->andWhere('et.isActive IN (:isActive)')
            ->setParameter('isActive', $isActive)
            ->orderBy('et.' . $field, $order)
            ->getQuery()
            ->getResult()
        ;
    }

    // /**
    //  * @return EmailTemplate[] Returns an array of EmailTemplate objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('e.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?EmailTemplate
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
