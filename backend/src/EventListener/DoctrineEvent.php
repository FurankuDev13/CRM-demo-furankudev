<?php

namespace App\EventListener;

use DateTime;
use Doctrine\ORM\Events;
use Doctrine\Common\EventSubscriber;
use Doctrine\Common\Persistence\Event\LifecycleEventArgs;
use App\Entity\HandlingStatus;

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
        $this->setCreatedAt($args);
    }
    
    public function preUpdate(LifecycleEventArgs $args) 
    {
        $this->setUpdatedAt($args);
    }

    public function setCreatedAt(LifecycleEventArgs $args)
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

    public function setUpdatedAt(LifecycleEventArgs $args)
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
}
