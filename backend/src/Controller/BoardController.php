<?php

namespace App\Controller;


use App\Repository\UserRepository;
use App\Repository\CommentRepository;
use App\Repository\CompanyRepository;
use App\Repository\RequestRepository;
use App\Repository\HandlingStatusRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class BoardController extends AbstractController
{
    /**
     * @Route("/", name="board_index", methods={"GET"})
     */
    public function index(Request $request, CompanyRepository $companyRepo, RequestRepository $requestRepo, UserRepository $userRepo, HandlingStatusRepository $handlingStatusRepo, CommentRepository $commentRepo)
    {
        $index = $request->query->get('index', 1);
        $table = $request->query->get('table');
        $field = $request->query->get('field');
        $order = $request->query->get('order');

        $unhandledStatus = $handlingStatusRepo->findOneByTitle('Initiée');

        if ($index == 1) {
            $orphanCompanies = $companyRepo->findIsActiveWithoutUserOrderedByField($table?:'c', $field?:'name', $order?:'ASC'); 
        } else {
            $orphanCompanies = $companyRepo->findIsActiveWithoutUserOrderedByField(); 
        }

        if ($index == 2) {
            $companiesWithUnhandledRequests = $companyRepo->findIsActiveByHandlingStatus($unhandledStatus, $table?:'r', $field?:'createdAt', $order?:'DESC');
        } else {
            $companiesWithUnhandledRequests = $companyRepo->findIsActiveByHandlingStatus($unhandledStatus);
        }

        if ($index == 3) {
            $unhandledRequests = $requestRepo->findIsActiveByHandlingStatus($unhandledStatus, $table?:'r', $field?:'createdAt', $order?:'DESC'); 
        } else {
            $unhandledRequests = $requestRepo->findIsActiveByHandlingStatus($unhandledStatus); 
        } 

        if ($index == 4) {
            $comments = $commentRepo->findCommentIsActiveByUpdatedAt($table?:'com', $field?:'updatedAt', $order?:'DESC');
        } else {
            $comments = $commentRepo->findCommentIsActiveByUpdatedAt(); 
        } 

        if ($index != 1 && $index != 2 && $index != 3 && $index != 4) {
            throw $this->createNotFoundException("L'index indiqué n'existe pas");
        }
               
        $salesUsers = $userRepo->findSalesRoles();

        return $this->render('board/index.html.twig', [
            'page_title' => 'Tableau de bord',
            'index' => $index,
            'orphanCompanies' => $orphanCompanies,
            'companiesWithUnhandledRequests' => $companiesWithUnhandledRequests,
            'unhandledRequests' => $unhandledRequests,
            'salesUsers' => $salesUsers,
            'comments' => $comments,
        ]);
    }
}
