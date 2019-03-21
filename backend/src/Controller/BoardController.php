<?php

namespace App\Controller;


use App\Repository\UserRepository;
use App\Repository\CompanyRepository;
use App\Repository\RequestRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class BoardController extends AbstractController
{
    /**
     * @Route("/", name="board_index", methods={"GET"})
     */
    public function index(Request $request, CompanyRepository $companyRepo, RequestRepository $requestRepo, UserRepository $userRepo)
    {
        $index = $request->query->get('index', 1);

        $orphanCompanies = $companyRepo->findOrphans();
        $companiesWithUnhandledRequests = $companyRepo->findByUnhandledRequests();
        $unhandledRequests = $requestRepo->findUnhandled();
        $salesUsers = $userRepo->findSalesRoles();

        dump($companiesWithUnhandledRequests );

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
