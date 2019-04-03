<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\User;
use Unirest\Request;
use App\Entity\Person;
use App\Entity\Comment;
use App\Entity\Company;
use App\Entity\Contact;
use App\Entity\Product;
use App\Entity\Category;
use App\Entity\Discount;
use App\Entity\UserRole;
use App\Entity\EmailType;
use App\Entity\ContactType;
use App\Entity\RequestType;
use App\Entity\EmailTemplate;
use App\Entity\RequestDetail;
use App\Entity\CompanyAddress;
use App\Entity\HandlingStatus;
use Faker\ORM\Doctrine\Populator;
use App\Entity\CompanyAddressType;
use App\DataFixtures\Faker\CategoryData;
use App\DataFixtures\Faker\DataProvider;
use App\Entity\Request as ClientRequest;
use Doctrine\Bundle\FixturesBundle\Fixture;
use App\DataFixtures\Faker\EmailTemplateData;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Validator\Constraints\DateTime;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;


class AppFixtures extends Fixture
{
    use CategoryData;
    use EmailTemplateData;

    private $passwordEncoder;
    private $manager;
    private $categories;
    private $request  = [];
    private $products  = [];
    private $userRoles = [];

    public function __construct(UserPasswordEncoderInterface $passwordEncoder, ObjectManager $manager)
    {
        $this->passwordEncoder = $passwordEncoder;
        $this->manager = $manager;
    }

    

    public function load(ObjectManager $manager)
    {
        $this->getEmailTypesAndTemplates();

        $this->getProductsAndCategories();

        $this->getMainContactAndUsers();

        $data = $this->getPopulatedData();  
        
        $this->getRequestDetails(); 
    
    }

    private function getProductsAndCategories()
    {
        //Request sur API ontariobeer
        Request::verifyPeer(false);
        $headers = array('Accept' => 'application/json');
        $response = Request::get('http://ontariobeerapi.ca/products/',$headers);

        //dd($response->body[0]->name);
        $dateToSet = new \DateTime();
        for ($i=0; $i < min(200, count($response->body)); $i++) {
            $url = $response->body[$i]->image_url;
            $file_headers = @get_headers($url);

            if($file_headers[0] != 'HTTP/1.1 404 Not Found') {
                $product = new Product();
                $category = new Category();
                $category->setName($response->body[$i]->category);
                $category->setCreatedAt($dateToSet);
                $category->setUpdatedAt($dateToSet);

                $category2 = new Category();
                $category2->setName($response->body[$i]->type);
                $category2->setCreatedAt($dateToSet);
                $category2->setUpdatedAt($dateToSet);

                $product->setReference($response->body[$i]->product_id);
                $product->setName($response->body[$i]->name);
                
                $categories = $this->categoryList;
                shuffle($categories);

                $product->setDescription($response->body[$i]->size . " - " . $response->body[$i]->country . " - " . $response->body[$i]->type . " - by " . $response->body[$i]->brewer . '. ' . $categories[0]['description']);

                $product->setPicture($response->body[$i]->image_url);
                $product->setListPrice($response->body[$i]->price);
                $product->setMaxDiscountRate(15);
                $product->setIsAvailable(true);
                $product->setIsOnHomePage(false);
                $product->setRank(random_int(1, 10));
                
                //dd($this->completeCategory($category));
                $product->addCategory($this->completeCategory($category));
                $product->addCategory($this->completeCategory($category2));
                
                $this->manager->persist($product);

                $this->products[] = $product;
            }
        }

        foreach ($this->categories as $category) {
            $data = $this->categoryList[$category->getName()];
            $category->setName($data['name']);
            $category->setDescription($data['description']); 
            $category->setPicture($data['picture']);
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

    private function getEmailTypesAndTemplates() {
        foreach($this->emailTemplatesList as $emailTypeTitle => $emailTemplateData) {
            $emailTemplate = new EmailTemplate();
            $emailTemplate->setTitle($emailTypeTitle . ' par défaut');
            $emailTemplate->setMessageTitle($emailTemplateData['messageTitle']);
            $emailTemplate->setMessageBody($emailTemplateData['messageBody']);
            $emailTemplate->setMessageSignature($emailTemplateData['messageSignature']);
            $this->manager->persist($emailTemplate);

            $emailType = new EmailType();
            $emailType->setTitle($emailTypeTitle);
            $emailType->setEmailTemplate($emailTemplate);
            $this->manager->persist($emailType);

        }

        $this->manager->flush();
    }

    private function getMainContactAndUsers()
    {
        //Company
        $company = new Company();
        $company->setName('Tartenpion SAS');
        $company->setDescription('La force est en toi');
        $company->setPicture('https://static.thespicehouse.com/images/file/1454/large_square_Hungarian_Half-Sharp_Paprika__close.jpg');
        $company->setSirenNumber('527726046');
        $this->manager->persist($company);

        $contactType = new ContactType();
        $contactType->setTitle('Contact');
        $this->manager->persist($contactType);

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

        $apiRole = new UserRole();
        $apiRole->setTitle('Frontoffice');
        $apiRole->setCode('ROLE_APIUSER');
        $this->manager->persist($apiRole);

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
        $person3->setLastname('PICOLO');
        $person3->setBusinessPhone('0102030405');
        $person3->setCellPhone('0102030405');
        $this->manager->persist($person3);

        $person4 = new Person();
        $person4->setFirstname('API');
        $person4->setLastname('JWT');
        $person4->setBusinessPhone('0102030405');
        $person4->setCellPhone('0102040405');
        $person4->setIsActive(false);
        $this->manager->persist($person4);

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
        $this->manager->persist($user2);;

        $user3 = new User();
        $user3->setPerson($person4);
        $user3->addUserRole($apiRole);
        $user3->setEmail('cerberus.crm.mailer@gmail.com');
        $encodedPassword = $this->passwordEncoder->encodePassword($user3, 'alabiere13');
        $user3->setPassword($encodedPassword);
        $this->manager->persist($user3);

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
            'createdAt' => function() use ($generator) { return $generator->dateTimeBetween($startDate = '-1 years', $endDate = 'now', $timezone = null) ; },
            'updatedAt' => null,
        ));

        //Discounts
        $populator->addEntity(Discount::class,6, array(
            'rate' => function() use ($generator) { return $generator->unique()->SetDiscount(); },
            'createdAt' => function() use ($generator) { return $generator->dateTimeBetween($startDate = '-1 years', $endDate = 'now', $timezone = null) ; },
            'updatedAt' => null,
        ), array(
            function($discount) { 
                $discount->setTitle($discount->getRate() . '%');
            },
        ));

        //Handling Status des Demandes
        $populator->addEntity(HandlingStatus::class,4, array(
            'title' => function() use ($generator) { return $generator->unique()->SetHandlingStatus(); },
            'createdAt' => function() use ($generator) { return $generator->dateTimeBetween($startDate = '-1 years', $endDate = 'now', $timezone = null) ; },
            'updatedAt' => null,
        ));

        //Types des Demandes        
        $populator->addEntity(RequestType::class,4, array(
            'title' => function() use ($generator) { return $generator->unique()->SetRequestType(); },
            'createdAt' => function() use ($generator) { return $generator->dateTimeBetween($startDate = '-1 years', $endDate = 'now', $timezone = null) ; },
            'updatedAt' => null,
        ));

        //Additional Person puis Users -> Commercial
        $populator->addEntity(Person::class,40, array(
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
            'createdAt' => function() use ($generator) { return $generator->dateTimeBetween($startDate = '-1 years', $endDate = 'now', $timezone = null) ; },
            'updatedAt' => null,
        ));

        //CompanyAddress
        $populator->addEntity(CompanyAddress::class, 40, array(
            'firstAddressField' => function() use ($generator) { return $generator->streetAddress(); },
            'secondAddressField' => null,
            'postalCode' => function() use ($generator) { return $generator->postcode(); },
            'city' => function() use ($generator) { return $generator->city(); },
            'country' => 'France', 
            'createdAt' => function() use ($generator) { return $generator->dateTimeBetween($startDate = '-1 years', $endDate = 'now', $timezone = null) ; },
            'updatedAt' => null,           
        ));

        //Contact
        $populator->addEntity(Contact::class,30, array(
            'email' => function() use ($generator) { return $generator->unique()->email(); },
        ), array(
            function($contact) { 
                $encodedPassword = $this->passwordEncoder->encodePassword($contact, 'contact');
                $contact->setPassword($encodedPassword);
            },
        ));

        //Request
        $populator->addEntity(ClientRequest::class, 60, array(
            'title' => function() use ($generator) { return $generator->realText(50); },
            'body' => function() use ($generator) { return $generator->realText(500); },
            'createdAt' => function() use ($generator) { return $generator->dateTimeBetween($startDate = '-1 years', $endDate = 'now', $timezone = null) ; },
            'updatedAt' => null,
        ), array(
            function($request) { 
                if($request->getRequestType()->getTitle() == 'Devis Détaillé' || $request->getRequestType()->getTitle() == 'Commande') {
                    $this->requests[] = $request;
                }
            },
        ));

        //Comment
        $populator->addEntity(Comment::class, 30, array(
            'title' => function() use ($generator) { return $generator->realText(50); },
            'body' => function() use ($generator) { return $generator->realText(200); },
            'createdAt' => function() use ($generator) { return $generator->dateTimeBetween($startDate = '-1 years', $endDate = 'now', $timezone = null) ; },
        ), array(
            function($comment) { 
                $comment->setUpdatedAt($comment->getCreatedAt());
            },
        ));
        
        return  $insertedEntities = $populator->execute();
    }

    private function getRequestDetails() {
        for ($i = 0; $i < 500; $i ++) {
            $requestDetail = new RequestDetail();
            $requestDetail->setQuantity(random_int(10, 30));

            $products = $this->products;
            shuffle($products);
            $requestDetail->setProduct($products[0]);

            $requests = $this->requests;
            shuffle($requests);
            $requestDetail->setRequest($requests[0]);

            $this->manager->persist($requestDetail);
        }

        $this->manager->flush();
    }
}
