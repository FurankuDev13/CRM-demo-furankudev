<?php

namespace App\Controller;


use App\Repository\UserRepository;
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
    public function index(Request $request, CompanyRepository $companyRepo, RequestRepository $requestRepo, UserRepository $userRepo, HandlingStatusRepository $handlingStatusRepo)
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
            $unhandledRequests = $requestRepo->findByHandlingStatus($unhandledStatus, $table?:'r', $field?:'createdAt', $order?:'DESC'); 
        } else {
            $unhandledRequests = $requestRepo->findByHandlingStatus($unhandledStatus); 
        }

        if ($index != 1 && $index != 2 && $index != 3) {
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
        ]);
    }

    /**
     * @Route("/list-test", name="list-test")
     */
    public function listTest()
    {
        return $this->render('tests/list.html.twig', [
            'page_title' => 'Produit',
        ]);
    }

    /**
     * @Route("/show-test", name="show-test")
     */
    public function showTest()
    {
        return $this->render('tests/show.html.twig', [
            'page_title' => 'Produit',
        ]);
    }
}
