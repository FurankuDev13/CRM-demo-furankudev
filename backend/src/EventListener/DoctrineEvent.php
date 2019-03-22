<?php

namespace App\EventListener;

use DateTime;
use App\Entity\Person;
use App\Entity\Company;
use App\Entity\Product;
use App\Entity\Request;
use App\Entity\Category;
use App\Entity\Discount;
use App\Entity\UserRole;
use Doctrine\ORM\Events;
use App\Entity\ContactType;
use App\Entity\RequestType;
use App\Entity\CompanyAddress;
use App\Entity\HandlingStatus;
use App\Entity\CompanyAddressType;
use Doctrine\Common\EventSubscriber;
use Doctrine\Common\Persistence\Event\LifecycleEventArgs;

class DoctrineEvent implements EventSubscriber
{
    public function getSubscribedEvents()
    {
        return array(
            Events::preUpdate,
            Events::prePersist,
        );
    }

    public function prePersist(LifecycleEventArgs $args) 
    {
        $this->setIsActive($args);
        $this->setCreatedAt($args);
        $this->setCompanyDefaultIsCustomer($args);
        $this->setProductDefaultIsAvailable($args);
    }
    
    public function preUpdate(LifecycleEventArgs $args) 
    {
        $this->setUpdatedAt($args);
    }

    private function setCreatedAt(LifecycleEventArgs $args)
    {
        $entity = $args->getObject();
        $entityManager = $args->getObjectManager();

        if (
            $entity instanceof Category 
            || $entity instanceof Company
            || $entity instanceof CompanyAddress
            || $entity instanceof CompanyAddressType
            || $entity instanceof ContactType
            || $entity instanceof Discount
            || $entity instanceof HandlingStatus
            || $entity instanceof Person
            || $entity instanceof Product
            || $entity instanceof Request
            || $entity instanceof RequestType
            || $entity instanceof UserRole
            ) {

            $entity->setCreatedAt(new DateTime);
        } 
    }

    private function setIsActive(LifecycleEventArgs $args)
    {
        $entity = $args->getObject();
        $entityManager = $args->getObjectManager();

        if (
            $entity instanceof Category 
            || $entity instanceof Company
            || $entity instanceof CompanyAddress
            || $entity instanceof CompanyAddressType
            || $entity instanceof ContactType
            || $entity instanceof Discount
            || $entity instanceof HandlingStatus
            || $entity instanceof Person
            || $entity instanceof Product
            || $entity instanceof Request
            || $entity instanceof RequestType
            || $entity instanceof UserRole
            ) {

            $entity->setIsActive(true);
        } 
    }

    private function setUpdatedAt(LifecycleEventArgs $args)
    {
        $entity = $args->getObject();
        $entityManager = $args->getObjectManager();

        if (
            $entity instanceof Category 
            || $entity instanceof Company
            || $entity instanceof CompanyAddress
            || $entity instanceof CompanyAddressType
            || $entity instanceof ContactType
            || $entity instanceof Discount
            || $entity instanceof HandlingStatus
            || $entity instanceof Person
            || $entity instanceof Product
            || $entity instanceof Request
            || $entity instanceof RequestType
            || $entity instanceof UserRole
            ) {
            $entity->setUpdatedAt(new DateTime);
        } 
    }

    private function setCompanyDefaultIsCustomer(LifecycleEventArgs $args)
    {
        $entity = $args->getObject();
        $entityManager = $args->getObjectManager();

        if ($entity instanceof Company) {
            $entity->setIsCustomer(false);
        } 
    }

    private function setProductDefaultIsAvailable(LifecycleEventArgs $args)
    {
        $entity = $args->getObject();
        $entityManager = $args->getObjectManager();

        if ($entity instanceof Product) {
            $entity->setIsAvailable(false);
        } 
    }
}
