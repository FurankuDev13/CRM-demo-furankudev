<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\User;
use App\Entity\Person;
use App\Entity\Company;
use App\Entity\Contact;
use App\Entity\Product;
use App\Entity\Category;
use App\Entity\Discount;
use App\Entity\UserRole;
use Unirest\Request;
use App\Entity\ContactType;
use App\Entity\RequestType;
use App\Entity\CompanyAddress;
use App\Entity\HandlingStatus;
use Faker\ORM\Doctrine\Populator;
use App\Entity\CompanyAddressType;
use App\Entity\Request as ClientRequest;
use App\DataFixtures\Faker\DataForFaker;
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

        //Contact Type
        $contactTypeList = [
            'Directeur',
            'Gérant',
            'Responsable Commercial',
            'Commercial',
            'Responsable des Achats'
        ];
        $contactTypes = []; //collection
        foreach ($contactTypeList as $value) {
            $contactType = new ContactType();
            $contactType->setTitle($value);
            $contactTypes[$contactType->getTitle()] = $contactType;
            $manager->persist($contactType);
        };
        //dump($contactTypes);

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
        $contact1->setContactType($contactType);
        $contact1->setCompany($company);
        $contact1->setEmail('pf_e@oclock.io');
        $encodedPassword = $this->passwordEncoder->encodePassword($contact1, 'pf');
        $contact1->setPassword($encodedPassword);
        $manager->persist($contact1);

        //Discount
        $discountList = [
            '5%' => 5,
            '10%' => 10,
            '15%'=> 15,
            '20%' => 20,
            '25%' => 25,
            '30%' => 30
        ];
        $discounts = []; //collection
        foreach ($discountList as $key => $value) {
            $discount = new Discount();
            $discount->setTitle($key);
            $discount->setRate($value);
            $discounts[$key] = $discount;
            $manager->persist($discount);
        }

        //Handling Status
        $handlingStatusList = [
            'Initiée',
            'En cours',
            'En attente',
            'Terminée'
        ];
        $handlingStatusArray = []; //collection
        foreach ($handlingStatusList as $value) {
            $handlingStatus = new HandlingStatus();
            $handlingStatus->setTitle($value);
            $handlingStatusArray[$handlingStatus->getTitle()] = $handlingStatus;
            $manager->persist($handlingStatus);
        }

        //Request Type
        $requestTypeList = [
            'Demande Simple',
            'Devis Détaillé',
        ];
        $requestTypes = []; //collection
        foreach ($requestTypeList as $value) {
            $requestType = new RequestType();
            $requestType->setTitle($value);
            $requestTypes[$requestType->getTitle()] = $requestType;
            $manager->persist($requestType);
        }

        
        //Populator
        $generator = Factory::create('fr_FR');
        $generator->addProvider(new DataForFaker($generator));
        $populator = new Populator($generator, $manager);

        //Company
        $populator->addEntity(Company::class,4, array(
            'name' => function() use ($generator) { return $generator->unique()->company(); },
            'description' => function() use ($generator) { return $generator->unique()->catchPhrase(); },
            'sirenNumber' => function() use ($generator) { return $generator->unique()->randomNumber(9); },
            'picture' => function() use ($generator) { return $generator->imageUrl(320,240); },
            'isCustomer' => false
        ));

        //AddressType
        $populator->addEntity(CompanyAddressType::class,4, array(
            'title' => function() use ($generator) { return $generator->unique()->setAddressType(); },
        ));

        //CompanyAddress
        $populator->addEntity(CompanyAddress::class, 20, array(
            'firstAddressField' => function() use ($generator) { return $generator->streetAddress(); },
            'secondAddressField' => null,
            'postalCode' => function() use ($generator) { return $generator->postcode(); },
            'city' => function() use ($generator) { return $generator->city(); },
            'country' => 'France',            
        ));

        //Request
        $populator->addEntity(ClientRequest::class, 20, array(
            'title' => function() use ($generator) { return $generator->realText(50); },
            'body' => function() use ($generator) { return $generator->realText(500); },
            'handlingStatus' => $handlingStatusArray['Initiée'],
            'requestType' => $requestTypes['Demande Simple'],
            'contact' => $contact1
        ));

        $insertedEntities = $populator->execute();
        //dump($insertedEntities['App\Entity\Request']);


        $manager->flush();
        
    }
}
