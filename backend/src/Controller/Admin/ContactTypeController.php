<?php

namespace App\Controller\Admin;

use App\Entity\ContactType;
use App\Form\ContactTypeFormType;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\ContactTypeRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/** 
 *  @Route("/admin/contactType", name="admin_contactType_") 
*/
class ContactTypeController extends AbstractController
{
    /**
     * @Route("/index", name="index", methods={"GET"})
     */
    public function index(Request $request, ContactTypeRepository $contactTypeRepo)
    {
        $isActive = [true,false];
        $field = $request->query->get('field', 'title');
        $order = $request->query->get('order', 'ASC');

        $contactTypes = $contactTypeRepo->findByIsActiveOrderedByField($isActive, $field , $order);

        return $this->render('admin/contact_type/index.html.twig', [
            'page_title' => 'Liste des types de contact',
            'contactTypes' => $contactTypes,
        ]);
    }

    /**
     * @Route("/new", name="new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager)
    {
        $contactType = new ContactType();

        $form = $this->createForm(ContactTypeFormType::class, $contactType);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($contactType);
            $entityManager->flush();

            $this->addFlash(
                'success',
                'Le type ' . $contactType->getTitle() . ' a bien été ajouté !'
            );
            return $this->redirectToRoute('admin_contactType_index');
        }


        return $this->render('admin/contact_type/new.html.twig', [
            'page_title' => 'Ajouter un nouveau type de contact',
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/{id}/edit", name="edit", methods={"GET", "POST"})
     */
    public function edit(ContactType $contactType, Request $request, EntityManagerInterface $entityManager)
    {
        if (!$contactType) {
            throw $this->createNotFoundException("Le type indiqué n'existe pas"); 
        }

        $form = $this->createForm(ContactTypeFormType::class, $contactType);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            $this->addFlash(
                'warning',
                'Le type ' . $contactType->getTitle() . ' a bien été mis à jour !'
            );
            return $this->redirectToRoute('admin_contactType_index');
        }

        return $this->render('admin/contact_type/edit.html.twig', [
            'page_title' => 'Mettre à jour le type ' . $contactType->getTitle(),
            'form' => $form->createView(),
            'contactType' => $contactType
        ]);
    }

    /**
     * @Route("/{id}/archive", name="archive", methods={"PATCH"}, requirements={"id"="\d+"})
     */
    public function archive(ContactType $contactType, Request $request, EntityManagerInterface $entityManager)
    {
        if (!$contactType) {
            throw $this->createNotFoundException("Le type indiqué n'existe pas"); 
        }

        $contactType->setIsActive(!$contactType->getIsActive());
        $notification = ($contactType->getIsActive() ? ' a été désarchivé' : ' a été archivé !');
        $this->addFlash(
            'warning',
            'Le type ' . $contactType->getTitle() . $notification
        );
        $entityManager->flush();

        $referer = $request->headers->get('referer');

        return $this->redirect($referer);;
    }

        /**
     * @Route("/{id}/delete", name="delete", methods={"DELETE"}, requirements={"id"="\d+"})
     */
    public function delete(ContactType $contactType, Request $request, EntityManagerInterface $entityManager)
    {
        if (!$contactType) {
            throw $this->createNotFoundException("Le type indiqué n'existe pas"); 
        }

        $entityManager->remove($contactType);;
        $this->addFlash(
            'danger',
            'Le type ' . $contactType->getTitle() . ' a été supprimé ! '
        );
        $entityManager->flush();

        $referer = $request->headers->get('referer');

        return $this->redirect($referer);;
    }

}
