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

        $jsonObject = null;
        $data = $request->getContent();
        $decodedData = json_decode($data, true);

        $email = $decodedData['email'];
        $password = $decodedData['password'];

        if ($email) {
            $contact = $contactRepo->findOneByEmail($email);
            if ($contact && $password) {
                $validPassword = $passwordEncoder->isPasswordValid($contact,$password);
                if ($validPassword) {
                    $jsonObject = $serializer->serialize($contact, 'json',['groups' => 'contact_group']);

                    $response = new Response($jsonObject, 200);
                }
            }
        }

        if (!isset($response)) {
            $response = new Response([], 404);
        }
        
        $response->headers->set('Content-Type', 'application/json');
        $response->headers->set('Access-Control-Allow-Origin', '*');

        return $response; 
    }
    /**
     * @Route("/contact", name="new", methods={"POST"})
     */
    public function new(Request $request, SerializerInterface $serializer, EntityManagerInterface $entityManager, UserPasswordEncoderInterface $passwordEncoder, CompanyAddressTypeRepository $companyAddressTypeRepo, ContactTypeRepository $contactTypeRepo, RequestTypeRepository $requestTypeRepo, HandlingStatusRepository $handlingStatusRepo)
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

        $products = $productRepo->findByIsActiveAndIsAvailable(true, false);
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
    public function requestCreate(Contact $contact, Request $request, EntityManagerInterface $entityManager, HandlingStatusRepository $handlingStatusRepo, RequestTypeRepository $requestTypeRepo, SerializerInterface $serializer)
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
            
            $jsonObject = $serializer->serialize($contactRequest, 'json', ['groups' => 'contact_group']);
        }

        $response = new Response($jsonObject, 200);
        
        $response->headers->set('Content-Type', 'application/json');
        $response->headers->set('Access-Control-Allow-Origin', '*');

        return $response;
    }


}
