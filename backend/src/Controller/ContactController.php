<?php

namespace App\Controller;

use App\Entity\Person;
use App\Entity\Contact;
use App\Form\PersonType;
use App\Form\ContactFormType;
use App\Repository\CompanyRepository;
use App\Repository\ContactRepository;
use App\Repository\RequestRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\ContactTypeRepository;
use App\Repository\HandlingStatusRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/** 
 *  @Route("/contact", name="contact_") 
*/
class ContactController extends AbstractController
{
    /**
     * @Route("/index", name="index", methods={"GET"})
     */
    public function index(Request $request, ContactRepository $contactRepo, ContactTypeRepository $contactTypeRepo, CompanyRepository $companyRepo)
    {
        $filter = $request->query->get('filter', null);
        $filterName = $request->query->get('filterName', null);

        $table = $request->query->get('table', 'p');
        $field = $request->query->get('field', 'lastname');
        $order = $request->query->get('order', 'ASC');

        $contactTypes = $contactTypeRepo->findWherePersonIsActive();
        $contactTypeList = $contactTypeRepo->findByIsActive(true);
        $companies = $companyRepo->findWherePersonIsActive();

        if ($filter == 'contactType') {
            $contactType = $contactTypeRepo->findOneByTitle($filterName);
            $contacts = $contactRepo->findIsActiveByContactType($contactType, $table, $field, $order);
        } elseif ($filter == 'company') {
            $company = $companyRepo->findOneByName($filterName);
            $contacts = $contactRepo->findIsActiveByCompany($company, $table, $field, $order);
        } else {
            $contacts = $contactRepo->findIsActiveOrderedByField($table, $field, $order);
        }

        return $this->render('contact/index.html.twig', [
            'page_title' => 'Contacts',
            'contacts' => $contacts,
            'contactTypes' => $contactTypes,
            'contactTypeList' => $contactTypeList,
            'companies' => $companies,
            'filter' => $filter,
            'filterName' => $filterName,
        ]);
    }

    /**
     * @Route("/index/admin", name="index_admin", methods={"GET"})
     * Index des Contacs en mode Gestion/ADMIN : seulement ceux qui sont archivés
     */
    public function indexAdmin(Request $request, ContactRepository $contactRepo, ContactTypeRepository $contactTypeRepo, CompanyRepository $companyRepo)
    {
        //$table = 'p', $field = 'lastname', $order = 'ASC', $isActive = true
        $contacts = $contactRepo->findIsActiveOrderedByField('p', 'lastname', 'ASC', false);

        return $this->render('contact/indexAdmin.html.twig', [
            'page_title' => 'Contacts',
            'contacts' => $contacts,
        ]);
    }

    /**
     * @Route("/{id}/show", name="show", methods={"GET"}, requirements={"id"="\d+"})
     */
    public function show(Contact $contact, RequestRepository $requestRepo, HandlingStatusRepository $handlingStatusRepo)
    {
        if (!$contact) {
            throw $this->createNotFoundException("Le contact indiqué n'existe pas"); 
        }

        $demands = $requestRepo->findIsActiveByContact($contact);
        $handlingStatuses = $handlingStatusRepo->findByIsActive(true);

        return $this->render('contact/show.html.twig', [
            'page_title' => 'Contact: ' . $contact->getPerson()->getFirstname() . ' ' . $contact->getPerson()->getLastname(),
            'contact' => $contact,
            'demands' => $demands,
            'handlingStatuses' => $handlingStatuses,
        ]);
    }

    /**
     * @Route("/new", name="new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager, UserPasswordEncoderInterface $passwordEncoder)
    {
        $contact = new Contact();
        $person = new Person();

        $contactForm = $this->createForm(ContactFormType::class, $contact);
        $personForm = $this->createForm(PersonType::class, $person);

        $contactForm->handleRequest($request);
        $personForm->handleRequest($request);

        if ($contactForm->isSubmitted() && $contactForm->isValid()) {
            $entityManager->persist($person);
            $password = random_bytes(10);
            $encodedPassword = $passwordEncoder->encodePassword($contact, $password);
            $contact->setPassword($encodedPassword);
            $contact->setPerson($person);
            $entityManager->persist($contact);
            $entityManager->flush();

            $this->addFlash(
                'success',
                "Le contact " . $contact->getPerson()->getFirstname() . ' ' . $contact->getPerson()->getLastname() . ' a bien été ajouté !'
            );
            return $this->redirectToRoute('contact_show', ['id' => $contact->getId()]);
        }

        return $this->render('contact/new.html.twig', [
            'page_title' => 'Ajouter un nouvel utilisateur',
            'contactForm' => $contactForm->createView(),
            'personForm' => $personForm->createView()
        ]);
    }

    /**
     * @Route("/{id}/edit", name="edit", methods={"GET", "POST"}, requirements={"id"="\d+"})
     */
    public function edit(Contact $contact, Request $request, EntityManagerInterface $entityManager, UserPasswordEncoderInterface $passwordEncoder)
    {
        if (!$contact) {
            throw $this->createNotFoundException("Le contact indiqué n'existe pas"); 
        }

        $person = $contact->getPerson();

        $contactForm = $this->createForm(ContactFormType::class, $contact);
        $personForm = $this->createForm(PersonType::class, $person);

        $contactForm->handleRequest($request);
        $personForm->handleRequest($request);

        if ($contactForm->isSubmitted() && $contactForm->isValid()) {
            $entityManager->flush();

            $this->addFlash(
                'success',
                "Le contact " . $contact->getPerson()->getFirstname() . ' ' . $contact->getPerson()->getLastname() . ' a bien été mis à jour !'
            );
            return $this->redirectToRoute('contact_show', ['id' => $contact->getId()]);
        }

        return $this->render('contact/edit.html.twig', [
            'page_title' => "Mettre à jour le contact: " . $contact->getPerson()->getFirstname() . ' ' . $contact->getPerson()->getLastname(),
            'contact' => $contact,
            'contactForm' => $contactForm->createView(),
            'personForm' => $personForm->createView()
        ]);
    }

    /**
     * @Route("/{id}/archive", name="archive", methods={"PATCH"}, requirements={"id"="\d+"})
     */
    public function archive(Contact $contact, Request $request, EntityManagerInterface $entityManager)
    {
        if (!$contact) {
            throw $this->createNotFoundException("Le contact indiqué n'existe pas"); 
        }

        $contact->getPerson()->setIsActive(!$contact->getPerson()->getIsActive());
        $notification = ($contact->getPerson()->getIsActive() ? ' a été désarchivé' : ' a été archivé !');
        $this->addFlash(
            'success',
            'Le Contact ' . $contact->getPerson()->getFirstname() . " " . $contact->getPerson()->getLastname() . $notification
        );
        $entityManager->flush();

        $referer = $request->headers->get('referer');

        return $this->redirect($referer);;
    }

    /**
     * @Route("/{id}/edit-type", name="editType", methods={"PATCH"}, requirements={"id"="\d+"})
     */
    public function editType(Contact $contact, Request $request, EntityManagerInterface $entityManager, ContactTypeRepository $contactTypeRepo)
    {
        if (!$contact) {
            throw $this->createNotFoundException("Le contact indiqué n'existe pas"); 
        }
        
        $contactTypeId = $request->request->get('contactTypeId');
        $contactType = $contactTypeRepo->find($contactTypeId);
        $contact->setContactType($contactType);
        $entityManager->flush();

        $this->addFlash(
            'success',
            'Le titre ' . $contactType->getTitle() . ' a bien été attribué au contact ' . $contact->getPerson()->getLastName() . ' ' . $contact->getPerson()->getFirstName() . ' !'
        );

        $referer = $request->headers->get('referer');

        return $this->redirect($referer);
    }

    /**
     * @Route("/{id}/delete", name="delete", methods={"DELETE"}, requirements={"id"="\d+"})
     */
    public function delete(Contact $contact, Request $request, EntityManagerInterface $entityManager)
    {
        if (!$contact) {
            throw $this->createNotFoundException("Le contact indiqué n'existe pas"); 
        }

        $entityManager->remove($contact);
        $entityManager->flush();
        $notification = " a été supprimé !";
        $this->addFlash(
            'danger',
            'La Catégorie ' . $contact->getPerson()->getFirstname() . ' ' . $contact->getPerson()->getLastname() . $notification
        );

        $referer = $request->headers->get('referer');
        return $this->redirect($referer);;
    }



}
