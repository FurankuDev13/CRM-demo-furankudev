<?php

namespace App\Controller\Api;

use App\Entity\Contact;
use App\Repository\ContactRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
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
        $data = $request->getContent();
        // $data = json_decode($data, true);
        // $data = $serializer->deserialize($data, Contact::class, 'json');
        // echo (gettype($maVariable));
        // echo($maVariable['email']);

        
        $decodedData = json_decode($data, true);
        $email = $decodedData['email'];
        $password = $decodedData['password'];

        if ($email && $password) {
            
            $contact = $contactRepo->findByEmail($email);
            echo($contact->getEmail());
        
            //$validPassword = $passwordEncoder->isPasswordValid($contact->getPassword(),$password);
            $validPassword = true;
            if ($validPassword) {
                echo('password ok');
                // $jsonObject = $serializer->serialize($contact, 'json', [
                //     'circular_reference_handler' => function ($object) {
                //         return $object->getId();
                //     }
                // ]);
            // } else {
            //     echo('password NOK');
            //     //$jsonObject = null;
            }
        }

        $jsonObject = $data;


        $response = new Response($jsonObject, 200);
        
        $response->headers->set('Content-Type', 'application/json');
        $response->headers->set('Access-Control-Allow-Origin', '*');

        return $response; 
    }

    /**
     * @Route("/contact", name="new", methods={"POST"})
     */
    public function new(Request $request, SerializerInterface $serializer, EntityManagerInterface $entityManager, UserPasswordEncoderInterface $passwordEncoder)
    {
        $email = $request->request->get('email', null);
        $password = $request->request->get('password', null);

        if ($email && $password) {
            $contact = new Contact();
            $contact->setEmail($email);
            $encodedPassword = $this->passwordEncoder->encodePassword($contact, $password);
            $contact->setPassword($encodedPassword);
            $entityManager->persist($contact);
            $entityManager->flush();

            if ($contact) {
                $jsonObject = $serializer->serialize($contact, 'json', [
                    'circular_reference_handler' => function ($object) {
                        return $object->getId();
                    }
                ]);
            } else {
                $jsonObject = null;
            }
        }

        return new Response($jsonObject, 200, ['Content-Type' => 'application/json']);
    }

    /**
     * @Route("/contact/{id}", name="edit", methods={"PATCH"})
     */
    public function edit(Contact $contact, Request $request, SerializerInterface $serializer, EntityManagerInterface $entityManager, UserPasswordEncoderInterface $passwordEncoder)
    {
        if (!$contact) {
            throw $this->createNotFoundException("Le contact indiqué n'existe pas"); 
        }

        $email = $request->request->get('email', null);
        $password = $request->request->get('password', null);

        if ($email && $password) {
            $contact->setEmail($email);
            $encodedPassword = $this->passwordEncoder->encodePassword($contact, $password);
            $contact->setPassword($encodedPassword);
            $entityManager->flush();

            if ($contact) {
                $jsonObject = $serializer->serialize($contact, 'json', [
                    'circular_reference_handler' => function ($object) {
                        return $object->getId();
                    }
                ]);
            } else {
                $jsonObject = null;
            }
        }

        return new Response($jsonObject, 200, ['Content-Type' => 'application/json']);
    }
}
