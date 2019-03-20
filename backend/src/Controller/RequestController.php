<?php

namespace App\Controller;

use App\Entity\Request as DemandRequest;
use App\Repository\RequestRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/** 
 *  @Route("/request", name="request_") 
*/
class RequestController extends AbstractController
{
    /**
     * @Route("/index", name="index", methods={"GET"})
     */
    public function index(RequestRepository $requestRepo)
    {
        $demandRequests = $requestRepo->findByIsActive(true);

        return $this->render('request/index.html.twig', [
            'page_title' => 'Liste des demandes',
            'requests' => $demandRequests,
        ]);
    }

    /**
     * @Route("/{id}/show", name="show", methods={"GET"}, requirements={"id"="\d+"})
     */
    public function show(DemandRequest $demandRequest)
    {
        if (!$demandRequest) {
            throw $this->createNotFoundException("La demande indiquée n'existe pas"); 
        }

        return $this->render('request/show.html.twig', [
            'page_title' => 'Demande: ' . $demandRequest->getTitle(),
            'request' => $demandRequest,
        ]);
    }

    /**
     * @Route("/{id}/edit-handling-status", name="editHandlingStatus", methods={"PATCH"}, requirements={"id"="\d+"})
     */
    public function editHandlingStatus(DemandRequest $demandRequest, Request $request, EntityManagerInterface $entityManager)
    {
        if (!$demandRequest) {
            throw $this->createNotFoundException("La société indiquée n'existe pas"); 
        }

        $referer = $request->headers->get('referer');

        return $this->redirect($referer);
    }

    /**
     * @Route("/{id}/archive", name="archive", methods={"PATCH"}, requirements={"id"="\d+"})
     */
    public function archive(DemandRequest $demandRequest, Request $request, EntityManagerInterface $entityManager)
    {
        if (!$demandRequest) {
            throw $this->createNotFoundException("La demande indiquée n'existe pas"); 
        }

        $referer = $request->headers->get('referer');

        return $this->redirect($referer);;
    }
}
