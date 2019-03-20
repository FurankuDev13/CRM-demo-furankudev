<?php

namespace App\Controller\Api;

use App\Entity\Contact;
use App\Repository\ContactRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/** 
 *  @Route("/api", name="api_contact_") 
*/
class ContactController extends AbstractController
{
    /**
     * @Route("/login", name="find", methods={"POST"})
     */
    public function find(Request $request, ContactRepository $contactRepo, SerializerInterface $serializer, UserPasswordEncoderInterface $passwordEncoder)
    {
        $email = $request->request->get('email', null);
        $password = $request->request->get('password', null);

        if ($email && $password) {
            $contact = $contactRepo->findByEmail($email);
            $validPassword = $passwordEncoder->isPasswordValid($contact->getPassword(),$password);

            if ($validPassword) {
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
