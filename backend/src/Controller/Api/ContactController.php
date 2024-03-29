<?php
namespace App\Controller\Api;
use App\Entity\Person;
use App\Entity\Company;
use App\Entity\Contact;
use App\Entity\Product;
use App\Entity\CompanyAddress;
use Swagger\Annotations as SWG;
use App\Repository\CompanyRepository;
use App\Repository\ContactRepository;
use App\Repository\ProductRepository;
use App\Repository\DiscountRepository;
use App\Entity\Request as DemandRequest;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Request as ContactRequest;
use App\Repository\ContactTypeRepository;
use App\Repository\RequestTypeRepository;
use Nelmio\ApiDocBundle\Annotation\Model;
use App\Repository\EmailTemplateRepository;
use App\Repository\HandlingStatusRepository;
use Nelmio\ApiDocBundle\Annotation\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\CompanyAddressTypeRepository;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;


/** 
 *  @Route("/api", name="api_contact_") 
*/
class ContactController extends AbstractController
{
    /**
     * @Route("/contact/login", name="login", methods={"POST", "OPTIONS"})
     * 
     * @SWG\Response(
     *     response=200,
     *     description="Checks the credentials of a contact user for his login, returns his information",
     *     @SWG\Schema(
     *         type="array",
     *         @SWG\Items(ref=@Model(type=Contact::class, groups={"contact_group"}))
     *     )
     * )
     *  @SWG\Parameter(
     *     name="email",
     *     in="formData",
     *     type="string",
     *     description="email address of the contact user"
     * )
     *  @SWG\Parameter(
     *     name="password",
     *     in="formData",
     *     type="string",
     *     description="password of the contact user"
     * )
     * @SWG\Tag(name="contacts")
     * @Security(name="Bearer")
     * 
     */
    public function login(Request $request, ContactRepository $contactRepo, SerializerInterface $serializer, UserPasswordEncoderInterface $passwordEncoder)
    {
        $responseCode = 400 ;
        $errorCode = 'no_data_sent';
        $errorDescription = "Les données transmises sont insuffisantes, la demande ne peut être traitée";

        $data = $request->getContent();
        $decodedData = json_decode($data, true);

        $email = array_key_exists('email', $decodedData) ? $decodedData['email'] : null;
        $password = array_key_exists('password', $decodedData) ? $decodedData['password'] : null;
        
        if ($email) {
            $errorCode = 'no_user_found';
            $errorDescription = "Les données transmises ne correspondent à aucun utilisateur, la demande ne peut être traitée";

            $contact = $contactRepo->findOneByEmail($email);
            if ($contact && $password) {
                $validPassword = $passwordEncoder->isPasswordValid($contact,$password);
                if ($validPassword) {
                    $responseCode = 200;
                    $jsonObject = $serializer->serialize($contact, 'json',['groups' => 'contact_group']);
                } 
            } 
        } 

        if ($responseCode == 400) {
            $jsonObject = $serializer->serialize(
                [
                "error" => $errorCode,
                "error_description"  => $errorDescription
                ], 
                'json'
            );
        }
        
        $response = new Response($jsonObject, $responseCode);

        $response->headers->set('Content-Type', 'application/json');
        $response->headers->set('Access-Control-Allow-Origin', '*');

        return $response; 
    }

    /**
     * @Route("/contact/{id}", name="show", methods={"GET"})
     * 
     * @SWG\Response(
     *     response=200,
     *     description="Returns one contact user information by his id",
     *     @SWG\Schema(
     *         type="array",
     *         @SWG\Items(ref=@Model(type=Contact::class, groups={"contact_group"}))
     *     )
     * )
     * @SWG\Tag(name="contacts")
     * @Security(name="Bearer")
     * 
     */
    public function show(Contact $contact = null, ContactRepository $contactRepo, SerializerInterface $serializer)
    {
        if (!is_null($contact) && $contact->getPerson()->getIsActive()) {
            $responseCode = 200 ;
            $jsonObject = $serializer->serialize($contact, 'json', ['groups' => 'contact_group']);
        } else {
            $responseCode = 400 ;
            $jsonObject = $serializer->serialize(
                [
                "error" => "no_user_found",
                "error_description"  => "Les données transmises ne correspondent à aucun utilisateur, la demande ne peut être traitée"
                ], 
                'json'
            );
        }

        $response = new Response($jsonObject, $responseCode);
        
        $response->headers->set('Content-Type', 'application/json');
        $response->headers->set('Access-Control-Allow-Origin', '*');

        return $response;
    }

    /**
     * @Route("/contact", name="new", methods={"POST"})
     * 
     * @SWG\Response(
     *     response=200,
     *     description="Creates a new contact user, returns the contact user information",
     *     @SWG\Schema(
     *         type="array",
     *         @SWG\Items(ref=@Model(type=Contact::class, groups={"contact_group"}))
     *     )
     * )
     *  @SWG\Parameter(
     *     name="companyName",
     *     in="formData",
     *     type="string",
     *     description="name of the contact user's company"
     * )
     *  @SWG\Parameter(
     *     name="companySiren",
     *     in="formData",
     *     type="integer",
     *     description="siren of the contact user's company"
     * )
     *  @SWG\Parameter(
     *     name="companyAddressField",
     *     in="formData",
     *     type="string",
     *     description="address of the contact user's company"
     * )
     *  @SWG\Parameter(
     *     name="companyPostalCode",
     *     in="formData",
     *     type="string",
     *     description="postal code of the contact user's company"
     * )
     *  @SWG\Parameter(
     *     name="companyCity",
     *     in="formData",
     *     type="string",
     *     description="city of the contact user's company"
     * )
     *  @SWG\Parameter(
     *     name="contactLastname",
     *     in="formData",
     *     type="string",
     *     description="lastname of the contact user"
     * )
     *  @SWG\Parameter(
     *     name="contactFirstname",
     *     in="formData",
     *     type="string",
     *     description="firstname of the contact user"
     * )
     *  @SWG\Parameter(
     *     name="contactBusinessPhone",
     *     in="formData",
     *     type="string",
     *     description="phonenumber of the contact user"
     * )
     *  @SWG\Parameter(
     *     name="contactEmail",
     *     in="formData",
     *     type="string",
     *     description="email address of the contact user"
     * )
     *  @SWG\Parameter(
     *     name="contactPassword",
     *     in="formData",
     *     type="string",
     *     description="password of the contact user"
     * )
     *  @SWG\Parameter(
     *     name="contactPasswordRepeat",
     *     in="formData",
     *     type="string",
     *     description="password repeat of the contact user"
     * )
     *  @SWG\Parameter(
     *     name="contactRequest",
     *     in="formData",
     *     type="string",
     *     description="information request of the contact user"
     * )
     * @SWG\Tag(name="contacts")
     * @Security(name="Bearer")
     * 
     */
    public function new(Request $request, SerializerInterface $serializer, EntityManagerInterface $entityManager, UserPasswordEncoderInterface $passwordEncoder, CompanyAddressTypeRepository $companyAddressTypeRepo, ContactTypeRepository $contactTypeRepo, RequestTypeRepository $requestTypeRepo, HandlingStatusRepository $handlingStatusRepo, CompanyRepository $companyRepo, ContactRepository $contactRepo, \Swift_Mailer $mailer, EmailTemplateRepository $emailTemplateRepo, DiscountRepository $discountRepo)
    {
        // $data = $serializer->deserialize($request->getContent(), Contact::class, 'json');

        $responseCode = 400 ;
        $errorCode = 'no_data_sent';
        $errorDescription = "Aucune des données requises n'a été transmise, la demande ne peut être traitée";

        $jsonObject = null;
        $data = json_decode($request->getContent(), true);

        $companyName = array_key_exists('companyName', $data) ? $data['companyName'] : null;
        $companySiren = array_key_exists('companySiren', $data) ? $data['companySiren'] : null;
        $companyAddressField = array_key_exists('companyAddressField', $data) ? $data['companyAddressField'] : null;
        $companyPostalCode = array_key_exists('companyPostalCode', $data) ? $data['companyPostalCode'] : null;
        $companyCity = array_key_exists('companyCity', $data) ? $data['companyCity'] : null;
        $contactLastname = array_key_exists('contactLastname', $data) ? $data['contactLastname'] : null;
        $contactFirstname = array_key_exists('contactFirstname', $data) ? $data['contactFirstname'] : null;
        $contactBusinessPhone = array_key_exists('contactBusinessPhone', $data) ? $data['contactBusinessPhone'] : null;
        $contactEmail = array_key_exists('contactEmail', $data) ? $data['contactEmail'] : null;
        $contactPassword = array_key_exists('contactPassword', $data) ? $data['contactPassword'] : null;
        $contactPasswordRepeat = array_key_exists('contactPasswordRepeat', $data) ? $data['contactPasswordRepeat'] : false;
        $contactRequest = array_key_exists('contactRequest', $data) ? $data['contactRequest'] : null;
        
        if ($contactPassword == $contactPasswordRepeat) {
            $errorCode = 'notenough_data_sent';
            $errorDescription = "Les données transmises sont insuffisantes, la demande ne peut être traitée";

            if ($companyName && $companySiren && $companyAddressField && $companyPostalCode && $companyCity && $contactLastname && $contactFirstname && $contactBusinessPhone && $contactEmail && $contactPassword) {
                
                $company = $companyRepo->findOneBySirenNumber($companySiren);

                if (!$company) {
                    $companyAddressType = $companyAddressTypeRepo->findOneByTitle('contact');
                    $companyAddress = new CompanyAddress();
                    $companyAddress->setFirstAddressField($companyAddressField);
                    $companyAddress->setPostalCode($companyAddressField);
                    $companyAddress->setPostalCode($companyPostalCode);
                    $companyAddress->setCity($companyCity);
                    $companyAddress->setCountry('France');
                    $companyAddress->setCompanyAddressType($companyAddressType);
                    $entityManager->persist($companyAddress);

                    $company = new Company();
                    $company->setName($companyName);
                    $company->setSirenNumber($companySiren);
                    $company->addCompanyAddress($companyAddress);

                    if (!$company->getDiscount()) {
                        $noDiscount = $discountRepo->findOneByRate(0);
                        $company->setDiscount($noDiscount);
                    }

                    $entityManager->persist($company);

                } 

                $contact = $contactRepo->findOneByEmail($contactEmail);

                if (!$contact) {
                    $contactType = $contactTypeRepo->findOneByTitle('Commercial');
                
                    $person = new Person();
                    $person->setFirstname($contactFirstname);
                    $person->setLastname($contactLastname);
                    $person->setBusinessPhone($contactBusinessPhone);
                    $entityManager->persist($person);

                    $contact = new Contact();
                    $contact->setEmail($contactEmail);
                    $encodedPassword = $passwordEncoder->encodePassword($contact, $contactPassword);
                    $contact->setPassword($encodedPassword);
                    $contact->setPerson($person);
                    $contact->setContactType($contactType);
                    $contact->setCompany($company);
                    $entityManager->persist($contact);

                    $responseCode = 200;

                } else {
                    $errorCode = 'data_already_exists';
                    $errorDescription = "Un contact avec cette adresse email existe déjà, la demande ne peut être traitée";
                }
                
                if ($contactRequest) {
                    $requestType = $requestTypeRepo->findOneByTitle('Informations');
                    $handlingStatus = $handlingStatusRepo->findOneByTitle('Initiée');
                    $currentRequest = new ContactRequest();
                    $currentRequest->setTitle('Demande internet - nouveau contact');
                    $currentRequest->setBody($contactRequest);
                    $currentRequest->setRequestType($requestType);
                    $currentRequest->setHandlingStatus($handlingStatus);
                    $currentRequest->setContact($contact);
                    $entityManager->persist($currentRequest);
                }
            }

            if ($responseCode == 200) {
                $entityManager->flush();
                $jsonObject = $serializer->serialize($contact, 'json',['groups' => 'contact_group']);

                $emailTemplate = $emailTemplateRepo->findOneByEmailTypeTitle('Inscription - Internet');

                if ($emailTemplate) {
                    $message = (new \Swift_Message("Bienvenue chez Beer'oClock"))
                    ->setFrom('cerberus.crm.mailer@gmail.com')
                    ->setTo([$contact->getEmail(), 'cerberus.crm.mailer@gmail.com', 'sith13160@gmail.com'])
                    ->setBody(
                        $this->renderView(
                            'emails/notification.html.twig',
                            [
                                'emailTemplate' => $emailTemplate,
                                'contact' => $contact,
                                'password' => $contactPassword,
                            ]
                        ),
                        'text/html'
                    );
                    $mailer->send($message);
                }
            }
        }

        if ($responseCode == 400) {
            $jsonObject = $serializer->serialize(
                [
                "error" => $errorCode,
                "error_description"  => $errorDescription
                ], 
                'json'
            );
        }
        
        $response = new Response($jsonObject, $responseCode);

        $response->headers->set('Content-Type', 'application/json');
        $response->headers->set('Access-Control-Allow-Origin', '*');

        return $response; 
    }

    /**
     * @Route("/contact/{id}", name="edit", methods={"PATCH"})
     * 
     * @SWG\Response(
     *     response=200,
     *     description="Updates one contact user information by his id, returns the contact user information",
     *     @SWG\Schema(
     *         type="array",
     *         @SWG\Items(ref=@Model(type=Contact::class, groups={"contact_group"}))
     *     )
     * )
     *  @SWG\Parameter(
     *      name="body",
     *      in="body",
     *      @SWG\Schema(
     *          type="array",
     *          @Model(type=Contact::class, groups={"contact_group"})
     *      )
     *  )
     * @SWG\Tag(name="contacts")
     * @Security(name="Bearer")
     * 
     */
    public function edit(Contact $contact = null, Request $request, SerializerInterface $serializer, EntityManagerInterface $entityManager, UserPasswordEncoderInterface $passwordEncoder, ContactRepository $contactRepo)
    {   
        if (!is_null($contact)) {
            
            $savedPassword = $contact->getPassword();

            $data = $serializer->deserialize($request->getContent(), Contact::class, 'json', ['object_to_populate' => $contact]);

            if (!$contactRepo->findOneByEmailAndNotById($data->getEmail(), $contact->getId())) {
                if ($data->getPassword()) {
                    $encodedPassword = $passwordEncoder->encodePassword($data, $data->getPassword());
                    $data->setPassword($encodedPassword);
                } else {
                    $data->setPassword($savedPassword);
                }

                $entityManager->flush();

                $responseCode = 200 ;
                $jsonObject = $serializer->serialize($data, 'json', ['groups' => 'contact_group']);

            } else {
                $responseCode = 400 ;
                $errorCode = 'email_already_exists';
                $errorDescription = "Un utilisateur avec cette adresse email existe déjà, cette donnée doit être unique, la demande ne peut être traitée";
            }

        } else {
            $responseCode = 400 ;
            $errorCode = 'no_user_found';
            $errorDescription = "Les données transmises ne correspondent à aucun utilisateur, la demande ne peut être traitée";
    
        }

        if ($responseCode == 400) {
            $jsonObject = $serializer->serialize(
                [
                "error" => $errorCode,
                "error_description"  => $errorDescription
                ], 
                'json'
            );
        }
        $response = new Response($jsonObject, $responseCode);
        
        $response->headers->set('Content-Type', 'application/json');
        $response->headers->set('Access-Control-Allow-Origin', '*');

        return $response;
    }
    /**
     * @Route("/contact/{id}/product", name="product_index", methods={"GET"})
     * 
     * @SWG\Response(
     *     response=200,
     *     description="Returns the list of the catalog products with contact user's company discounted prices",
     *     @SWG\Schema(
     *         type="array",
     *         @SWG\Items(ref=@Model(type=Product::class, groups={"product_group"}))
     *     )
     * )
     * @SWG\Tag(name="contacts")
     * @Security(name="Bearer")
     * 
     */
    public function productIndex(Contact $contact, ProductRepository $productRepo, SerializerInterface $serializer)
    {
        $isActive = true;
        $isAvailable = true;

        $products = $productRepo->findByIsActiveAndIsAvailable($isActive, $isAvailable);

        if ($products) {
            if ($contact->getCompany()->getDiscount() == null) {
                $discountGranted = 0;
            } else {
                $discountGranted = $contact->getCompany()->getDiscount()->getRate();
            }
            foreach ($products as $product) {
                $maxDiscount = $product->getMaxDiscountRate();
                $discount = min($discountGranted, $maxDiscount);
                $listPrice = $product->getListPrice();
                $sellingPrice = round($listPrice * (100-$discount)/100, 0);
                $product->setListPrice($sellingPrice);
            }

            $responseCode = 200 ;
            $jsonObject = $serializer->serialize($products, 'json', ['groups' => 'product_group']);

        } else {
            $responseCode = 400 ;
            $jsonObject = $serializer->serialize(
                [
                "error" => "no_product_found",
                "error_description"  => "Aucun produit n'a pu être trouvé"
                ], 
                'json'
            );
        }

        $response = new Response($jsonObject, $responseCode);
        
        $response->headers->set('Content-Type', 'application/json');
        $response->headers->set('Access-Control-Allow-Origin', '*');

        return $response;
    }
    
    /**
     * @Route("/contact/{id}/request", name="request_new", methods={"POST"})
     * 
     * @SWG\Response(
     *     response=200,
     *     description="Creates a new contact user's request, returns the contact user's request",
     *     @SWG\Schema(
     *         type="array",
     *         @SWG\Items(ref=@Model(type=DemandRequest::class, groups={"request_group"}))
     *     )
     * )
     *  @SWG\Parameter(
     *     name="request_type",
     *     in="formData",
     *     type="string",
     *     description="type of the request"
     * )
     *  @SWG\Parameter(
     *     name="request_title",
     *     in="formData",
     *     type="string",
     *     description="title of the request"
     * )
     *  @SWG\Parameter(
     *     name="request_body",
     *     in="formData",
     *     type="string",
     *     description="body of the request"
     * )
     * @SWG\Tag(name="contacts")
     * @Security(name="Bearer")
     * 
     */
    public function requestNew(Contact $contact, Request $request, EntityManagerInterface $entityManager, HandlingStatusRepository $handlingStatusRepo, RequestTypeRepository $requestTypeRepo, EmailTemplateRepository $emailTemplateRepo, DiscountRepository $discountRepo, SerializerInterface $serializer, \Swift_Mailer $mailer)
    {
        $responseCode = 400 ;
        $errorCode = 'no_data_sent';
        $errorDescription = "Aucune des données requises n'a été transmise, la demande ne peut être traitée";

        $jsonObject = null;
        $data = json_decode($request->getContent(), true);

        $requestTypeTitle = array_key_exists('request_type', $data) ? $data['request_type'] : null;
        $requestTitle = array_key_exists('request_title', $data) ? $data['request_title'] : null;
        $requestBody = array_key_exists('request_body', $data) ? $data['request_body'] : null;

        if ($requestTypeTitle && $requestTitle && $requestBody) {
            $errorCode = 'notenough_data_sent';
            $errorDescription = "Les données transmises sont insuffisantes, la demande ne peut être traitée";

            $requestType = $requestTypeRepo->findOneByTitle($requestTypeTitle);

            if ($requestType) {
                $handlingStatus = $handlingStatusRepo->findOneByTitle("Initiée");

                $contactRequest = new ContactRequest();
                $contactRequest->setTitle($requestTitle);
                $contactRequest->setBody($requestBody);
                $contactRequest->setHandlingStatus($handlingStatus);
                $contactRequest->setRequestType($requestType);
                $contactRequest->setContact($contact);
                $entityManager->persist($contactRequest);

                $company = $contactRequest->getContact()->getCompany();
                if (!$company->getDiscount()) {
                    $noDiscount = $discountRepo->findOneByRate(0);
                    $company->setDiscount($noDiscount);
                }

                $responseCode = 200;
            }
            
            if ($responseCode == 200) {
                $entityManager->flush();
                $jsonObject = $serializer->serialize($contactRequest, 'json', ['groups' => 'request_group']);

                $emailTemplate = $emailTemplateRepo->findOneByEmailTypeTitle('Nouvelle demande - Internet');

                if ($emailTemplate) {
                    $message = (new \Swift_Message("Votre demande a été prise en compte"))
                    ->setFrom('cerberus.crm.mailer@gmail.com')
                    ->setTo([$contact->getEmail(), 'cerberus.crm.mailer@gmail.com', 'sith13160@gmail.com'])
                    ->setBody(
                        $this->renderView(
                            'emails/notification.html.twig',
                            [
                                'emailTemplate' => $emailTemplate,
                                'contact' => $contact,
                                'request' => $contactRequest,
                            ]
                        ),
                        'text/html'
                    );
                    $mailer->send($message);
                }
                
            }
        }

        if ($responseCode == 400) {
            $jsonObject = $serializer->serialize(
                [
                "error" => $errorCode,
                "error_description"  => $errorDescription
                ], 
                'json'
            );
        }

        $response = new Response($jsonObject, $responseCode);
        
        $response->headers->set('Content-Type', 'application/json');
        $response->headers->set('Access-Control-Allow-Origin', '*');

        return $response;
    }
}