<?php

namespace App\Repository;

use DateTime;
use App\Entity\Company;
use App\Entity\Contact;
use App\Entity\Request;
use App\Entity\RequestType;
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

    public function findisActiveOrderedByField($table = 'r', $field = 'createdAt', $order = 'DESC', $isActive = true)
    {
        return $this->createQueryBuilder('r')
            ->join('r.contact', 'co')
            ->addSelect('co')
            ->join('co.person', 'p')
            ->addSelect('p')
            ->join('co.company', 'c')
            ->addSelect('c')
            ->andWhere('r.isActive = :isActive')
            ->setParameter('isActive', $isActive)
            ->orderBy($table . '.' . $field, $order)
            ->getQuery()
            ->getResult()
        ;
    }

    public function findIsActiveByHandlingStatus(HandlingStatus $handlingStatus, $table = 'r', $field = 'createdAt', $order = 'DESC')
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
            ->andWhere('r.isActive = :isActive')
            ->setParameter('isActive', true)
            ->orderBy($table . '.' . $field, $order)
            ->getQuery()
            ->getResult()
        ;
    }

    public function findIsActiveByRequestType(RequestType $requestType, $table = 'r', $field = 'createdAt', $order = 'DESC')
    {
        return $this->createQueryBuilder('r')
            ->join('r.contact', 'co')
            ->addSelect('co')
            ->join('co.person', 'p')
            ->addSelect('p')
            ->join('co.company', 'c')
            ->addSelect('c')
            ->andWhere('r.requestType = :val')
            ->setParameter('val', $requestType)
            ->andWhere('r.isActive = :isActive')
            ->setParameter('isActive', true)
            ->orderBy($table . '.' . $field, $order)
            ->getQuery()
            ->getResult()
        ;
    }

    //collection des demandes des contacts d'une société
    public function findAllByOneCompany(Company $company)
    {
        return $this->createQueryBuilder('r')
            ->join('r.contact', 'co')
            ->addSelect('co')
            ->join('co.company', 'c')
            ->addSelect('c')
            ->join('r.handlingStatus', 's')
            ->addSelect('s')
            ->where('c = :company')
            ->setParameter('company', $company)
            ->getQuery()
            ->getResult()
        ;
    }

    public function findIsActiveByCompany(Company $company)
    {
        return $this->createQueryBuilder('r')
            ->join('r.contact', 'co')
            ->addSelect('co')
            ->join('co.company', 'c')
            ->addSelect('c')
            ->join('r.handlingStatus', 's')
            ->addSelect('s')
            ->andWhere('c = :company')
            ->setParameter('company', $company)
            ->andWhere('r.isActive = :isActive')
            ->setParameter('isActive', true)
            ->getQuery()
            ->getResult()
        ;
    }

    public function findIsActiveByContact(Contact $contact)
    {
        return $this->createQueryBuilder('r')
            ->join('r.contact', 'co')
            ->addSelect('co')
            ->join('r.handlingStatus', 's')
            ->addSelect('s')
            ->andWhere('co = :contact')
            ->setParameter('contact', $contact)
            ->andWhere('r.isActive = :isActive')
            ->setParameter('isActive', true)
            ->getQuery()
            ->getResult()
        ;
    }

    public function findIsActiveAndIsFinishedByTypeFromDateToDate(RequestType $requestType, DateTime $minDate, DateTime $maxDate)
    {
        return $this->createQueryBuilder('r')
            ->join('r.handlingStatus', 's')
            ->addSelect('s')
            ->join('r.requestType', 'rt')
            ->addSelect('rt')
            ->andWhere('rt = :requestType')
            ->setParameter('requestType', $requestType)
            ->andWhere('s.title = :handlingStatusTitle')
            ->setParameter('handlingStatusTitle', 'Terminée')
            ->andWhere('r.isActive = :isActive')
            ->setParameter('isActive', true)
            ->andWhere('r.createdAt > :last')
            ->setParameter('last', $minDate)
            ->andWhere('r.createdAt < :first')
            ->setParameter('first', $maxDate)
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
