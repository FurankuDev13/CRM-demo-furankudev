<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }
    public function load(ObjectManager $manager)
    {
        // $product = new Product();
        // $manager->persist($product);

        //Request sur API ontariobeer
        // Request::verifyPeer(false);
        // $headers = array('Accept' => 'application/json');
        // $response = Request::get('https://api.themoviedb.org/3/movie/popular?api_key=8dcaebc51851c87be731768fea30529b&language=en-FR&page=1',$headers);
        // dd($response->body->results);


        // $salesRole = new UserRole();
        // $salesRole->setTitle('Commercial');
        // $salesRole->setCode('ROLE_SALES');
        // $manager->persist($salesRole);
        // $salesMgrRole = new UserRole();
        // $salesMgrRole->setTitle('Responsable Commercial');
        // $salesMgrRole->setCode('ROLE_SALESMGR');
        // $manager->persist($salesMgrRole);
        // $supportRole = new UserRole();
        // $supportRole->setTitle('Support');
        // $supportRole->setCode('ROLE_SUPPORT');
        // $manager->persist($supportRole);
        // $managerRole = new UserRole();
        // $managerRole->setTitle('Responsable Commercial');
        // $managerRole->setCode('ROLE_MANAGER');
        // $manager->persist($managerRole);
        // $adminRole = new UserRole();
        // $adminRole->setTitle('Administrateur');
        // $adminRole->setCode('ROLE_ADMIN');
        // $manager->persist($adminRole);

        // $person = new Person();
        // $person->setFirstname('Franck');
        // $person->setLastname('TANUKI');
        // $person->setEmail('franck_t@oclock.io');
        // $encodedPassword = $this->passwordEncoder->encodePassword($person, 'franck');
        // $person->setPassword($encodedPassword);
        // $person->setBusinessPhone('0102030405');
        // $person->setCellPhone('0102030405');

        // $person = new Person();
        // $person->setFirstname('Phil');
        // $person->setLastname('POIRON');
        // $person->setEmail('franck_t@oclock.io');
        // $encodedPassword = $this->passwordEncoder->encodePassword($person, 'phil');
        // $person->setPassword($encodedPassword);
        // $person->setBusinessPhone('0102030405');
        // $person->setCellPhone('0102030405');


        //$manager->flush();
    }
}
