<?php
namespace App\Controller\Api;
use App\Entity\Person;
use App\Entity\Company;
use App\Entity\Contact;
use App\Entity\CompanyAddress;
use App\Repository\ContactRepository;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Request as ContactRequest;
use App\Repository\ContactTypeRepository;
use App\Repository\RequestTypeRepository;
use App\Repository\HandlingStatusRepository;
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
     * @Route("/login", name="find", methods={"POST", "OPTIONS"})
     */
    public function find(Request $request, ContactRepository $contactRepo, SerializerInterface $serializer, UserPasswordEncoderInterface $passwordEncoder)
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
     * @Route("/contact", name="new", methods={"POST"})
     */
    public function new(Request $request, SerializerInterface $serializer, EntityManagerInterface $entityManager, UserPasswordEncoderInterface $passwordEncoder, CompanyAddressTypeRepository $companyAddressTypeRepo, ContactTypeRepository $contactTypeRepo, RequestTypeRepository $requestTypeRepo, HandlingStatusRepository $handlingStatusRepo, \Swift_Mailer $mailer)
    {
        // $data = $serializer->deserialize($request->getContent(), Contact::class, 'json');
        // var_dump($data);
        $jsonObject = null;
        $data = json_decode($request->getContent(), true);
        $companyName = $data['companyName'];
        $companySiren = $data['companySiren'];
        $companyAddressField = $data['companyAddressField'];
        $companyPostalCode = $data['companyPostalCode'];
        $companyCity = $data['companyCity'];
        $contactLastname = $data['contactLastname'];
        $contactFirstname = $data['contactFirstname'];
        $contactBusinessPhone = $data['contactBusinessPhone'];
        $contactEmail = $data['contactEmail'];
        $contactPassword = $data['contactPassword'];
        $contactPasswordRepeat = $data['contactPasswordRepeat'];
        $contactRequest = $data['contactRequest'];
        
        if ($contactPassword == $contactPasswordRepeat) {
            if ($companyName && $companySiren && $companyAddressField && $companyPostalCode && $companyCity) {
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
                $entityManager->persist($company);
            }
            if ($contactLastname && $contactFirstname && $contactBusinessPhone && $contactEmail && $contactPassword) {
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

                $message = (new \Swift_Message("Bienvenue chez Beer'oClock"))
                ->setFrom('sith13160@gmail.com')
                ->setTo('sith13160@gmail.com', $contact->getEmail())
                ->setBody(
                    $this->renderView(
                        'emails/registration_notification.html.twig',
                        ['contactFullName' => $contact->getPerson()->getFirstname() . $contact->getPerson()->getLastname()]
                    ),
                    'text/html'
                );
                $mailer->send($message);
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
            $entityManager->flush();
            $jsonObject = $serializer->serialize($contact, 'json',['groups' => 'contact_group']);
        }
        return new Response($jsonObject, 200, ['Content-Type' => 'application/json']);
    }
    /**
     * @Route("/contact/{id}", name="edit", methods={"PATCH"})
     */
    public function edit(Contact $contact, Request $request, SerializerInterface $serializer, EntityManagerInterface $entityManager, UserPasswordEncoderInterface $passwordEncoder, ContactRepository $contactRepo)
    {
        if (!$contact) {
            throw $this->createNotFoundException("Le contact indiqué n'existe pas"); 
        }
        $data = $serializer->deserialize($request->getContent(), Contact::class, 'json');
        
        if ($data) {
            /* if (!$data->getEmail()) {
                $savedContact = $contactRepo->findOneByEmail($data->getEmail());
            } */
            /* $entityManager->flush(); */
            $jsonObject = $serializer->serialize($contact, 'json',['groups' => 'user_group']);
        }
        return new Response($jsonObject, 200, ['Content-Type' => 'application/json']);
    }
    /**
     * @Route("/contact/{id}/products", name="productIndex", methods={"GET"})
     */
    public function productIndex(Contact $contact, ProductRepository $productRepo, SerializerInterface $serializer)
    {
        $products = $productRepo->findByIsActiveAndIsAvailable(true, true);
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
        
        //dd($products);
        
        $jsonObject = $serializer->serialize($products, 'json', ['groups' => 'product_group']);
        $response = new Response($jsonObject, 200);
        
        $response->headers->set('Content-Type', 'application/json');
        $response->headers->set('Access-Control-Allow-Origin', '*');
        return $response;
    }
    /**
     * @Route("/contact/{id}/request", name="requestCreate", methods={"POST"})
     */
    public function requestCreate(Contact $contact, Request $request, EntityManagerInterface $entityManager, HandlingStatusRepository $handlingStatusRepo, RequestTypeRepository $requestTypeRepo, SerializerInterface $serializer, \Swift_Mailer $mailer)
    {
        if (!$contact) {
            throw $this->createNotFoundException("Le contact indiqué n'existe pas"); 
        }
        $jsonObject = null;
        $data = json_decode($request->getContent(), true);
        $handlingStatus = $handlingStatusRepo->findOneByTitle("Initiée");
        $carryOn = false;
        if ($requestTypeRepo->findOneByTitle($data["request_type"])) {
            $carryOn = true;
        }
        if ($carryOn) {
            
            $contactRequest = new ContactRequest();
            $contactRequest->setTitle($data["request_title"]);
            $contactRequest->setBody($data["request_body"]);
            $contactRequest->setHandlingStatus($handlingStatus);
            $requestType = $requestTypeRepo->findOneByTitle($data["request_type"]);
            $contactRequest->setRequestType($requestType);
            $contactRequest->setContact($contact);
            $entityManager->persist($contactRequest);
            $entityManager->flush();

            $message = (new \Swift_Message("Votre demande a été prise en compte"))
                ->setFrom('sith13160@gmail.com')
                ->setTo('sith13160@gmail.com', $contact->getEmail())
                ->setBody(
                    $this->renderView(
                        'emails/request_notification.html.twig',
                        ['contactFullName' => $contact->getPerson()->getFirstname() . $contact->getPerson()->getLastname()]
                    ),
                    'text/html'
                );

            $mailer->send($message);
            
            $jsonObject = $serializer->serialize($contactRequest, 'json', ['groups' => 'contact_group']);
        }
        $response = new Response($jsonObject, 200);
        
        $response->headers->set('Content-Type', 'application/json');
        $response->headers->set('Access-Control-Allow-Origin', '*');
        return $response;
    }
}