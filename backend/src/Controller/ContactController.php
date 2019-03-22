<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Repository\CompanyRepository;
use App\Repository\ContactRepository;
use Doctrine\ORM\EntityManagerInterface;
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
    public function index(ContactRepository $contactRepo, CompanyRepository $companyRepo)
    {
        $contacts = $contactRepo->findWherePersonIsActive();
        $companies = $companyRepo->findByIsActive(true);

        return $this->render('contact/index.html.twig', [
            'page_title' => 'Liste des contacts (professionnels)',
            'contacts' => $contacts,
            'companies' => $companies,
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
        $this->addFlash(
            'success',
            'Le Contact ' . $contact->getPerson()->getFirstname() . " " . $contact->getPerson()->getLastname() . ' a été archivé !'
        );
        $entityManager->flush();

        $referer = $request->headers->get('referer');

        return $this->redirect($referer);;
    }
}
