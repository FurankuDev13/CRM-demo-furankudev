<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\User;
use Unirest\Request;
use App\Entity\Person;
use App\Entity\Company;
use App\Entity\Contact;
use App\Entity\Product;
use App\Entity\Category;
use App\Entity\Discount;
use App\Entity\UserRole;
use App\Entity\ContactType;
use App\Entity\RequestType;
use App\Entity\CompanyAddress;
use App\Entity\HandlingStatus;
use Faker\ORM\Doctrine\Populator;
use App\Entity\CompanyAddressType;
use App\DataFixtures\Faker\DataProvider;
use App\Entity\Request as ClientRequest;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Validator\Constraints\DateTime;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;


class AppFixtures extends Fixture
{
    private $passwordEncoder;
    private $manager;
    private $categories;
    private $userRoles = [];

    public function __construct(UserPasswordEncoderInterface $passwordEncoder, ObjectManager $manager)
    {
        $this->passwordEncoder = $passwordEncoder;
        $this->manager = $manager;
    }

    

    public function load(ObjectManager $manager)
    {
        $this->getProductsAndCategories();

        $this->getMainContactAndUsers();

        $this->getPopulatedData();        
    }

    private function getProductsAndCategories()
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
            $product->setIsOnHomePage(false);
            $product->setRank(10);
            
            //dd($this->completeCategory($category));
            $product->addCategory($this->completeCategory($category));

            $url = $product->getPicture();
            $file_headers = @get_headers($url);
            if($file_headers[0] != 'HTTP/1.1 404 Not Found') {
                $this->manager->persist($product);
            }
        }

        foreach ($this->categories as $category) {
            $category->setDescription('Lorem ipsum dolor sit amet consectetur adipisicing elit.'); 
            $category->setPicture('https://picsum.photos/200/300/?random');
            $this->manager->persist($category);
        }
        $this->manager->flush();
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

    private function getMainContactAndUsers()
    {
        //Company
        $company = new Company();
        $company->setName('Tartenpion SAS');
        $company->setDescription('La force est en toi');
        $company->setPicture('https://static.thespicehouse.com/images/file/1454/large_square_Hungarian_Half-Sharp_Paprika__close.jpg');
        $this->manager->persist($company);

        //===== supprimé car traité dans Populator
        // $contactType = new ContactType();
        // $contactType->setTitle('Gérant');
        // $this->manager->persist($contactType);

        //User Role
        $salesRole = new UserRole();
        $salesRole->setTitle('Commercial');
        $salesRole->setCode('ROLE_SALES');
        $this->manager->persist($salesRole);
        $this->userRoles[] = $salesRole;

        $salesMgrRole = new UserRole();
        $salesMgrRole->setTitle('Responsable Commercial');
        $salesMgrRole->setCode('ROLE_SALESMGR');
        $this->manager->persist($salesMgrRole);
        $this->userRoles[] = $salesMgrRole;

        $supportRole = new UserRole();
        $supportRole->setTitle('Support');
        $supportRole->setCode('ROLE_SUPPORT');
        $this->manager->persist($supportRole);
        $this->userRoles[] = $supportRole;

        $managerRole = new UserRole();
        $managerRole->setTitle('Responsable');
        $managerRole->setCode('ROLE_MANAGER');
        $this->manager->persist($managerRole);
        $this->userRoles[] = $managerRole;

        $adminRole = new UserRole();
        $adminRole->setTitle('Administrateur');
        $adminRole->setCode('ROLE_ADMIN');
        $this->manager->persist($adminRole);

        //Person
        $person1 = new Person();
        $person1->setFirstname('Franck');
        $person1->setLastname('TANUKI');
        $person1->setBusinessPhone('0102030405');
        $person1->setCellPhone('0102030405');
        $this->manager->persist($person1);

        $person2 = new Person();
        $person2->setFirstname('Phil');
        $person2->setLastname('PAGAN');
        $person2->setBusinessPhone('0102030405');
        $person2->setCellPhone('0102030405');
        $this->manager->persist($person2);

        $person3 = new Person();
        $person3->setFirstname('PF');
        $person3->setLastname('ECCHI');
        $person3->setBusinessPhone('0102030405');
        $person3->setCellPhone('0102030405');
        $this->manager->persist($person3);

        //User
        $user1 = new User();
        $user1->setPerson($person1);
        $user1->addUserRole($salesRole);
        $user1->addUserRole($supportRole);
        $user1->setEmail('franck_t@oclock.io');
        $encodedPassword = $this->passwordEncoder->encodePassword($user1, 'franck');
        $user1->setPassword($encodedPassword);
        $this->manager->persist($user1);

        $user2 = new User();
        $user2->setPerson($person2);
        $user2->addUserRole($adminRole);
        $user2->setEmail('phil_p@oclock.io');
        $encodedPassword = $this->passwordEncoder->encodePassword($user2, 'phil');
        $user2->setPassword($encodedPassword);
        $this->manager->persist($user2);

        //Contact
        $contact1 = new Contact();
        $contact1->setPerson($person3);
        $contact1->setContactType($contactType);
        $contact1->setCompany($company);
        $contact1->setEmail('pf_e@oclock.io');
        $encodedPassword = $this->passwordEncoder->encodePassword($contact1, 'pf');
        $contact1->setPassword($encodedPassword);
        $this->manager->persist($contact1);
    }

    public function getPopulatedData()
    {
        //Populator
        $generator = Factory::create('fr_FR');
        $generator->seed(1234);
        $generator->addProvider(new DataProvider($generator));
        $populator = new Populator($generator, $this->manager);

        //Contact Type
        $populator->addEntity(ContactType::class,5, array(
            'title' => function() use ($generator) { return $generator->unique()->SetContactType(); },
        ));

        //Discounts
        $populator->addEntity(Discount::class,6, array(
            'rate' => function() use ($generator) { return $generator->unique()->SetDiscount(); },
        ), array(
            function($discount) { 
                $discount->setTitle($discount->getRate() . '%');
            },
        ));

        //Handling Status des Demandes
        $populator->addEntity(HandlingStatus::class,4, array(
            'title' => function() use ($generator) { return $generator->unique()->SetHandlingStatus(); },
        ));

        //Types des Demandes        
        $populator->addEntity(RequestType::class,4, array(
            'title' => function() use ($generator) { return $generator->unique()->SetRequestType(); },
        ));

        //Additional Person puis Users -> Commercial
        $populator->addEntity(Person::class,20, array(
            'firstName' => function() use ($generator) { return $generator->firstName(); },
            'lastName' => function() use ($generator) { return $generator->unique()->lastName(); },
            'businessPhone' => function() use ($generator) { return $generator->unique()->phoneNumber(); },
            'cellPhone' => function() use ($generator) { return $generator->unique()->phoneNumber(); },
            'createdAt' => function() use ($generator) { return $generator->dateTimeBetween($startDate = '-1 years', $endDate = 'now', $timezone = null) ; },
            'updatedAt' => null,
        ));

        $populator->addEntity(User::class,10, array(
            'email' => function() use ($generator) { return $generator->unique()->email(); },
        ), array(
            function($user) { 
                $user->fakerConstruct();
                $encodedPassword = $this->passwordEncoder->encodePassword($user, 'user');
                $user->setPassword($encodedPassword);
                $randomIndex = array_rand($this->userRoles);
                $user->addUserRole($this->userRoles[$randomIndex]);
            },
        ));

        //Company
        $populator->addEntity(Company::class,20, array(
            'name' => function() use ($generator) { return $generator->unique()->company(); },
            'description' => function() use ($generator) { return $generator->unique()->catchPhrase(); },
            'sirenNumber' => function() use ($generator) { return $generator->unique()->siren(); },
            'picture' => function() use ($generator) { return $generator->imageUrl(320,240); },
            'createdAt' => function() use ($generator) { return $generator->dateTimeBetween($startDate = '-1 years', $endDate = 'now', $timezone = null) ; },
            'updatedAt' => null,

        ));

        //AddressType
        $populator->addEntity(CompanyAddressType::class,4, array(
            'title' => function() use ($generator) { return $generator->unique()->setAddressType(); },
        ));

        //CompanyAddress
        $populator->addEntity(CompanyAddress::class, 40, array(
            'firstAddressField' => function() use ($generator) { return $generator->streetAddress(); },
            'secondAddressField' => null,
            'postalCode' => function() use ($generator) { return $generator->postcode(); },
            'city' => function() use ($generator) { return $generator->city(); },
            'country' => 'France',            
        ));

        //Contact
        $populator->addEntity(Contact::class,10, array(
            'email' => function() use ($generator) { return $generator->unique()->email(); },
        ), array(
            function($contact) { 
                $encodedPassword = $this->passwordEncoder->encodePassword($contact, 'contact');
                $contact->setPassword($encodedPassword);
            },
        ));

        //Request
        $populator->addEntity(ClientRequest::class, 30, array(
            'title' => function() use ($generator) { return $generator->realText(50); },
            'body' => function() use ($generator) { return $generator->realText(500); },
            'createdAt' => function() use ($generator) { return $generator->dateTimeBetween($startDate = '-1 years', $endDate = 'now', $timezone = null) ; },
            'updatedAt' => null,
        ));
        
        return  $insertedEntities = $populator->execute();
    }
}
