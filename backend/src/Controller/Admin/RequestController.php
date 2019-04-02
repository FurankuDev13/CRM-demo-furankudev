<?php
namespace App\Controller\Admin;

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
*  @Route("/admin/request", name="admin_request_") 
*/
class RequestController extends AbstractController
{


    /**
     * @Route("/index", name="index", methods={"GET"})
     * Index des Demandes en mode Gestion/ADMIN : seulement celles qui sont archivées
     */
    public function index(Request $request, RequestRepository $requestRepo, CompanyRepository $companyRepo, HandlingStatusRepository $handlingStatusRepo, RequestTypeRepository $requestTypeRepo)
    {
        $demandRequests = $requestRepo->findIsActiveOrderedByField('r', 'createdAt', 'DESC',false);

        return $this->render('admin/request/index.html.twig', [
            'page_title' => 'Demandes',
            'requests' => $demandRequests,
        ]);
    }

    /**
     * @Route("/{id}/delete", name="delete", methods={"DELETE"}, requirements={"id"="\d+"})
     */
    public function delete(DemandRequest $demandRequest, Request $request, EntityManagerInterface $entityManager)
    {
        if (!$demandRequest) {
            throw $this->createNotFoundException("La demande indiquée n'existe pas"); 
        }

        $entityManager->remove($demandRequest);
        $entityManager->flush();
        $notification = " a été supprimée !";
        $this->addFlash(
            'danger',
            'La demande ' . $demandRequest->getTitle() . $notification
        );

        $referer = $request->headers->get('referer');
        return $this->redirect($referer);;
    }

}
