<?php

namespace App\Controller\Admin;

use App\Entity\RequestType;
use App\Form\RequestTypeFormType;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\RequestTypeRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/** 
 *  @Route("/admin/requestType", name="admin_requestType_") 
*/
class RequestTypeController extends AbstractController
{
    /**
     * @Route("/index", name="index", methods={"GET"})
     */
    public function index(Request $request, RequestTypeRepository $requestTypeRepo)
    {
        $isActive = [true,false];
        $field = $request->query->get('field', 'title');
        $order = $request->query->get('order', 'ASC');

        $requestTypes = $requestTypeRepo->findByIsActiveOrderedByField($isActive, $field , $order);

        return $this->render('admin/request_type/index.html.twig', [
            'page_title' => 'Liste des types de contact',
            'requestTypes' => $requestTypes,
        ]);
    }

    /**
     * @Route("/new", name="new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager)
    {
        $requestType = new RequestType();

        $form = $this->createForm(RequestTypeFormType::class, $requestType);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($requestType);
            $entityManager->flush();

            $this->addFlash(
                'success',
                'Le type ' . $requestType->getTitle() . ' a bien été ajouté !'
            );
            return $this->redirectToRoute('admin_requestType_index');
        }


        return $this->render('admin/request_type/new.html.twig', [
            'page_title' => 'Ajouter un nouveau type de contact',
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/{id}/edit", name="edit", methods={"GET", "POST"})
     */
    public function edit(RequestType $requestType, Request $request, EntityManagerInterface $entityManager)
    {
        if (!$requestType) {
            throw $this->createNotFoundException("Le type indiqué n'existe pas"); 
        }

        $form = $this->createForm(RequestTypeFormType::class, $requestType);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            $this->addFlash(
                'warning',
                'Le type ' . $requestType->getTitle() . ' a bien été mis à jour !'
            );
            return $this->redirectToRoute('admin_requestType_index');
        }

        return $this->render('admin/request_type/edit.html.twig', [
            'page_title' => 'Mettre à jour le type ' . $requestType->getTitle(),
            'form' => $form->createView(),
            'requestType' => $requestType
        ]);
    }

    /**
     * @Route("/{id}/archive", name="archive", methods={"PATCH"}, requirements={"id"="\d+"})
     */
    public function archive(RequestType $requestType, Request $request, EntityManagerInterface $entityManager)
    {
        if (!$requestType) {
            throw $this->createNotFoundException("Le type indiqué n'existe pas"); 
        }

        $requestType->setIsActive(!$requestType->getIsActive());
        $notification = ($requestType->getIsActive() ? ' a été désarchivé' : ' a été archivé !');
        $this->addFlash(
            'warning',
            'Le type ' . $requestType->getTitle() . $notification
        );
        $entityManager->flush();

        $referer = $request->headers->get('referer');

        return $this->redirect($referer);;
    }

        /**
     * @Route("/{id}/delete", name="delete", methods={"DELETE"}, requirements={"id"="\d+"})
     */
    public function delete(RequestType $requestType, Request $request, EntityManagerInterface $entityManager)
    {
        if (!$requestType) {
            throw $this->createNotFoundException("Le type indiqué n'existe pas"); 
        }

        if (!$requestType->getRequests()) {
            $entityManager->remove($requestType);;
            $this->addFlash(
                'danger',
                'Le type ' . $requestType->getTitle() . ' a été supprimé ! '
            );
            $entityManager->flush();
        } else {
            $this->addFlash(
                'danger',
                'Le type ' . $requestType->getTitle() . ' ne peut être supprimé tant que des demandes y sont liées ! '
            );
        }
        

        $referer = $request->headers->get('referer');

        return $this->redirect($referer);;
    }
}
