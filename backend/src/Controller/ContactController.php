<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Repository\CompanyRepository;
use App\Repository\ContactRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\ContactTypeRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

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
    public function show(Contact $contact)
    {
        if (!$contact) {
            throw $this->createNotFoundException("Le contact indiqué n'existe pas"); 
        }

        return $this->render('contact/show.html.twig', [
            'page_title' => 'Contact: ' . $contact->getPerson()->getFirstname() . ' ' . $contact->getPerson()->getLastname(),
            'contact' => $contact,
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


}
