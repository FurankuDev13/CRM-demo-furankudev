<?php
namespace App\Controller\Admin;

use App\Entity\Contact;
use App\Repository\CompanyRepository;
use App\Repository\ContactRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\ContactTypeRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


/** 
 *  @Route("/admin/contact", name="admin_contact_") 
*/
class ContactController extends AbstractController
{

    /**
     * @Route("/index", name="index", methods={"GET"})
     * Index des Contacts en mode Gestion/ADMIN : seulement ceux qui sont archivés
     */
    public function index(Request $request, ContactRepository $contactRepo)
    {
        //$table = 'p', $field = 'lastname', $order = 'ASC', $isActive = true
        $contacts = $contactRepo->findIsActiveOrderedByField('p', 'lastname', 'ASC', false);

        return $this->render('admin/contact/index.html.twig', [
            'page_title' => 'Contacts',
            'contacts' => $contacts,
        ]);
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
            'Le Contact ' . $contact->getPerson()->getFirstname() . ' ' . $contact->getPerson()->getLastname() . $notification
        );

        $referer = $request->headers->get('referer');
        return $this->redirect($referer);;
    }

}
