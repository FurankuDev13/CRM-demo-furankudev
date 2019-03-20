<?php

namespace App\DataFixtures;

use App\Entity\User;
use Unirest\Request;
use App\Entity\Person;
use App\Entity\Company;
use App\Entity\Contact;
use App\Entity\Product;
use App\Entity\Category;
use App\Entity\UserRole;
use App\Entity\ContactType;
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
            
            //dd($this->completeCategory($category));
            $product->addCategory($this->completeCategory($category));

            $manager->persist($product);
        }

        foreach ($this->categories as $category) {
            $manager->persist($category);
        }

        $company = new Company();
        $company->setName('Tartenpion SAS');
        $manager->persist($company);

        $salesRole = new UserRole();
        $salesRole->setTitle('Commercial');
        $salesRole->setCode('ROLE_SALES');
        $manager->persist($salesRole);

        $salesMgrRole = new UserRole();
        $salesMgrRole->setTitle('Responsable Commercial');
        $salesMgrRole->setCode('ROLE_SALESMGR');
        $manager->persist($salesMgrRole);

        $supportRole = new UserRole();
        $supportRole->setTitle('Support');
        $supportRole->setCode('ROLE_SUPPORT');
        $manager->persist($supportRole);

        $managerRole = new UserRole();
        $managerRole->setTitle('Responsable Commercial');
        $managerRole->setCode('ROLE_MANAGER');
        $manager->persist($managerRole);

        $adminRole = new UserRole();
        $adminRole->setTitle('Administrateur');
        $adminRole->setCode('ROLE_ADMIN');
        $manager->persist($adminRole);

        $ownerType = new ContactType();
        $ownerType->setTitle('Gérant');
        $manager->persist($ownerType);

        $person1 = new Person();
        $person1->setFirstname('Franck');
        $person1->setLastname('TANUKI');
        $person1->setBusinessPhone('0102030405');
        $person1->setCellPhone('0102030405');
        $manager->persist($person1);

        $person2 = new Person();
        $person2->setFirstname('Phil');
        $person2->setLastname('PAGAN');
        $person2->setBusinessPhone('0102030405');
        $person2->setCellPhone('0102030405');
        $manager->persist($person2);

        $person3 = new Person();
        $person3->setFirstname('PF');
        $person3->setLastname('ECCHI');
        $person3->setBusinessPhone('0102030405');
        $person3->setCellPhone('0102030405');
        $manager->persist($person3);

        $user1 = new User();
        $user1->setPerson($person1);
        $user1->addUserRole($salesRole);
        $user1->addUserRole($supportRole);
        $user1->setEmail('franck_t@oclock.io');
        $encodedPassword = $this->passwordEncoder->encodePassword($user1, 'franck');
        $user1->setPassword($encodedPassword);
        $manager->persist($user1);

        $user2 = new User();
        $user2->setPerson($person2);
        $user2->addUserRole($adminRole);
        $user2->setEmail('phil_p@oclock.io');
        $encodedPassword = $this->passwordEncoder->encodePassword($user2, 'phil');
        $user2->setPassword($encodedPassword);
        $manager->persist($user2);

        $contact1 = new Contact();
        $contact1->setPerson($person3);
        $contact1->setContactType($ownerType);
        $contact1->setCompany($company);
        $contact1->setEmail('pf_e@oclock.io');
        $encodedPassword = $this->passwordEncoder->encodePassword($contact1, 'pf');
        $contact1->setPassword($encodedPassword);
        $manager->persist($contact1);

        $manager->flush();
        
    }
}
