<?php

namespace App\DataFixtures;

use Unirest\Request;
use App\Entity\Person;
use App\Entity\Product;
use App\Entity\Category;
use App\Entity\UserRole;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Validator\Constraints\DateTime;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    private $passwordEncoder;
    private $categories;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    private function completeCategory(Category $category)
    {
        if (empty($this->categories) OR !in_array($category, $this->categories)) {
            $this->categories[] = $category;
        } else {
            foreach ($this->categories as $cat) {
                $category = ($category == $cat) ? $cat : $category;
            }
        }
        return $category;
    }

    public function load(ObjectManager $manager)
    {

        //Request sur API ontariobeer
        Request::verifyPeer(false);
        $headers = array('Accept' => 'application/json');
        $response = Request::get('http://ontariobeerapi.ca/products/',$headers);
        //dd($response->body[0]->name);
        $dateToSet = new \DateTime();
        for ($i=0; $i < min(100, count($response->body)); $i++) { 
            $product = new Product();
            $category = new Category();
            $category->setName($response->body[$i]->category);
            $category->setIsActive(true);
            $category->setCreatedAt($dateToSet);
            $category->setUpdatedAt($dateToSet);
    
            $product->setReference($response->body[$i]->product_id);
            $product->setName($response->body[$i]->name);
            $product->setDescription($response->body[$i]->size . " - " . $response->body[$i]->country . " - " . $response->body[$i]->type . " - by " . $response->body[$i]->brewer);
            $product->setPicture($response->body[$i]->image_url);
            $product->setListPrice($response->body[$i]->price);
            $product->setMaxDiscountRate(15);
            $product->setIsOnHomePage(true);
            $product->setRank(10);
            $product->setIsActive(true);
            $product->setCreatedAt(new \DateTime());
            $product->setUpdatedAt(new \DateTime());
            
            //dd($this->completeCategory($category));
            $product->addCategory($this->completeCategory($category));

            $manager->persist($product);
        }
        foreach ($this->categories as $category) {
            $manager->persist($category);
        }

        $salesRole = new UserRole();
        $salesRole->setTitle('Commercial');
        $salesRole->setCode('ROLE_SALES');
        $salesRole->setIsActive(true);
        $salesRole->setCreatedAt(new \DateTime());
        $salesRole->setUpdatedAt(new \DateTime());
        $manager->persist($salesRole);
        $salesMgrRole = new UserRole();
        $salesMgrRole->setTitle('Responsable Commercial');
        $salesMgrRole->setCode('ROLE_SALESMGR');
        $salesMgrRole->setIsActive(true);
        $salesMgrRole->setCreatedAt(new \DateTime());
        $salesMgrRole->setUpdatedAt(new \DateTime());
        $manager->persist($salesMgrRole);
        $supportRole = new UserRole();
        $supportRole->setTitle('Support');
        $supportRole->setCode('ROLE_SUPPORT');
        $supportRole->setIsActive(true);
        $supportRole->setCreatedAt(new \DateTime());
        $supportRole->setUpdatedAt(new \DateTime());
        $manager->persist($supportRole);
        $managerRole = new UserRole();
        $managerRole->setTitle('Responsable Commercial');
        $managerRole->setCode('ROLE_MANAGER');
        $managerRole->setIsActive(true);
        $managerRole->setCreatedAt(new \DateTime());
        $managerRole->setUpdatedAt(new \DateTime());
        $manager->persist($managerRole);
        $adminRole = new UserRole();
        $adminRole->setTitle('Administrateur');
        $adminRole->setCode('ROLE_ADMIN');
        $adminRole->setIsActive(true);
        $adminRole->setCreatedAt(new \DateTime());
        $adminRole->setUpdatedAt(new \DateTime());
        $manager->persist($adminRole);

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


        $manager->flush();
    }
}
