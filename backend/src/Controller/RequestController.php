<?php

namespace App\Controller;

use App\Repository\CompanyRepository;
use App\Repository\RequestRepository;
use App\Entity\Request as DemandRequest;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\RequestTypeRepository;
use App\Repository\HandlingStatusRepository;
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
    public function index(Request $request, RequestRepository $requestRepo, CompanyRepository $companyRepo, HandlingStatusRepository $handlingStatusRepo, RequestTypeRepository $requestTypeRepo)
    {
        $filter = $request->query->get('filter', null);
        $filterId = $request->query->get('filterId', null);
        $table = $request->query->get('table', 'r');
        $field = $request->query->get('field', 'createdAt');
        $order = $request->query->get('order', 'DESC');

        $handlingStatuses = $handlingStatusRepo->findByIsActive(true);
        $requestTypes = $requestTypeRepo->findByIsActive(true);

        if ($filter == 'handlingStatus') {
            $handlingStatus = $handlingStatusRepo->find($filterId);
            $demandRequests = $requestRepo->findIsActiveByHandlingStatus($handlingStatus, $table, $field, $order);

        } elseif ($filter == 'requestType') {
            $requestType = $requestTypeRepo->find($filterId);
            $demandRequests = $requestRepo->findIsActiveByRequestType($requestType, $table, $field, $order);
        } else {
            $demandRequests = $requestRepo->findIsActiveOrderedByField($table, $field, $order);
        }

        return $this->render('request/index.html.twig', [
            'page_title' => 'Demandes',
            'requests' => $demandRequests,
            'requestTypes' => $requestTypes,
            'handlingStatuses' => $handlingStatuses,
            'filter' => $filter,
            'filterId' => $filterId,
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
    public function editHandlingStatus(DemandRequest $demandRequest, Request $request, EntityManagerInterface $entityManager, HandlingStatusRepository $handlingStatusRepo)
    {
        if (!$demandRequest) {
            throw $this->createNotFoundException("La demande indiquée n'existe pas"); 
        }
        
        $handlingStatusId = $request->request->get('handlingStatusId');
        $handlingStatus = $handlingStatusRepo->find($handlingStatusId);
        $demandRequest->setHandlingStatus($handlingStatus);
        $entityManager->flush();

        $this->addFlash(
            'success',
            'Le status ' . $handlingStatus->getTitle() . ' a bien été attribué à la demande ' . $demandRequest->getTitle() . ' !'
        );

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

        $demandRequest->setIsActive(!$demandRequest->getIsActive());
        $this->addFlash(
            'success',
            'La Demande ' . $demandRequest->getTitle() . ' a été archivée !'
        );
        $entityManager->flush();

        $referer = $request->headers->get('referer');

        return $this->redirect($referer);;
    }
}
