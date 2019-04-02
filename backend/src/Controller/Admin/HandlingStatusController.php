<?php

namespace App\Controller\Admin;

use App\Entity\HandlingStatus;
use App\Form\HandlingStatusFormType;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\HandlingStatusRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/** 
 *  @Route("/admin/handlingStatus", name="admin_handlingStatus_") 
*/
class HandlingStatusController extends AbstractController
{
    /**
     * @Route("/index", name="index", methods={"GET"})
     */
    public function index(Request $request, HandlingStatusRepository $handlingStatusRepo)
    {
        $isActive = [true,false];
        $field = $request->query->get('field', 'title');
        $order = $request->query->get('order', 'ASC');

        $handlingStatuses = $handlingStatusRepo->findByIsActiveOrderedByField($isActive, $field , $order);

        return $this->render('admin/handling_status/index.html.twig', [
            'page_title' => 'Liste des statuts des demandes',
            'handlingStatuses' => $handlingStatuses,
        ]);
    }

    /**
     * @Route("/new", name="new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager)
    {
        $handlingStatus = new HandlingStatus();

        $form = $this->createForm(HandlingStatusFormType::class, $handlingStatus);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($handlingStatus);
            $entityManager->flush();

            $this->addFlash(
                'success',
                'Le statut ' . $handlingStatus->getTitle() . ' a bien été ajouté !'
            );
            return $this->redirectToRoute('admin_handlingStatus_index');
        }


        return $this->render('admin/handling_status/new.html.twig', [
            'page_title' => 'Ajouter un nouveau statut de demande',
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/{id}/edit", name="edit", methods={"GET", "POST"})
     */
    public function edit(HandlingStatus $handlingStatus, Request $request, EntityManagerInterface $entityManager)
    {
        if (!$handlingStatus) {
            throw $this->createNotFoundException("Le statut indiqué n'existe pas"); 
        }

        $form = $this->createForm(HandlingStatusFormType::class, $handlingStatus);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            $this->addFlash(
                'warning',
                'Le statut ' . $handlingStatus->getTitle() . ' a bien été mis à jour !'
            );
            return $this->redirectToRoute('admin_handlingStatus_index');
        }

        return $this->render('admin/handling_status/edit.html.twig', [
            'page_title' => 'Mettre à jour le statut ' . $handlingStatus->getTitle(),
            'form' => $form->createView(),
            'handlingStatus' => $handlingStatus
        ]);
    }

    /**
     * @Route("/{id}/archive", name="archive", methods={"PATCH"}, requirements={"id"="\d+"})
     */
    public function archive(HandlingStatus $handlingStatus, Request $request, EntityManagerInterface $entityManager)
    {
        if (!$handlingStatus) {
            throw $this->createNotFoundException("Le statut indiqué n'existe pas"); 
        }

        $handlingStatus->setIsActive(!$handlingStatus->getIsActive());
        $notification = ($handlingStatus->getIsActive() ? ' a été désarchivé' : ' a été archivé !');
        $this->addFlash(
            'warning',
            'Le statut ' . $handlingStatus->getTitle() . $notification
        );
        $entityManager->flush();

        $referer = $request->headers->get('referer');

        return $this->redirect($referer);;
    }

        /**
     * @Route("/{id}/delete", name="delete", methods={"DELETE"}, requirements={"id"="\d+"})
     */
    public function delete(HandlingStatus $handlingStatus, Request $request, EntityManagerInterface $entityManager)
    {
        if (!$handlingStatus) {
            throw $this->createNotFoundException("Le statut indiqué n'existe pas"); 
        }

        $entityManager->remove($handlingStatus);;
        $this->addFlash(
            'danger',
            'Le statut ' . $handlingStatus->getTitle() . ' a été supprimé ! '
        );
        $entityManager->flush();

        $referer = $request->headers->get('referer');

        return $this->redirect($referer);;
    }
}
