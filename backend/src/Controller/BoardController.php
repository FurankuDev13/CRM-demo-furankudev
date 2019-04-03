<?php

namespace App\Controller;


use DateTime;
use App\Repository\UserRepository;
use App\Repository\CommentRepository;
use App\Repository\CompanyRepository;
use App\Repository\RequestRepository;
use App\Repository\RequestTypeRepository;
use App\Repository\HandlingStatusRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use CMEN\GoogleChartsBundle\GoogleCharts\Charts\PieChart;
use CMEN\GoogleChartsBundle\GoogleCharts\Charts\ColumnChart;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use CMEN\GoogleChartsBundle\GoogleCharts\Charts\Diff\DiffColumnChart;

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

    /**
     * @Route("/stats", name="board_stats", methods={"GET"})
     */
    public function stats(RequestRepository $requestRepo, RequestTypeRepository $requestTypeRepo)
    {
        $requestType = $requestTypeRepo->findOneByTitle('Informations');

        $date = new DateTime();
        $date->modify('-1 year');

        // dd($requestRepo->findIsActiveAndIsFinishedByTypeFromDate($requestType, $date));

        $pieChart = new PieChart();
        $pieChart->getData()->setArrayToDataTable(
            [['Task', 'Hours per Day'],
            ['Work',     11],
            ['Eat',      2],
            ['Commute',  2],
            ['Watch TV', 2],
            ['Sleep',    7]
            ]
        );
        $pieChart->getOptions()->setTitle('My Daily Activities');
        $pieChart->getOptions()->setHeight(500);
        $pieChart->getOptions()->setWidth(900);
        $pieChart->getOptions()->getTitleTextStyle()->setBold(true);
        $pieChart->getOptions()->getTitleTextStyle()->setColor('#009900');
        $pieChart->getOptions()->getTitleTextStyle()->setItalic(true);
        $pieChart->getOptions()->getTitleTextStyle()->setFontName('Arial');
        $pieChart->getOptions()->getTitleTextStyle()->setFontSize(20);
        

        $oldColumnChart = new ColumnChart();
        $oldColumnChart->getData()->setArrayToDataTable(
            [
                ['Name', 'Popularity'],
                ['Cesar', 250],
                ['Rachel', 4200],
                ['Patrick', 2900],
                ['Eric', 8200]
            ]
        );
        $oldColumnChart->getOptions()->getLegend()->setPosition('top');
        $oldColumnChart->getOptions()->setWidth(450);
        $oldColumnChart->getOptions()->setHeight(250);
        
        $newColumnChart = new ColumnChart();
        $newColumnChart->getData()->setArrayToDataTable(
            [
                ['Name', 'Popularity'],
                ['Cesar', 370],
                ['Rachel', 600],
                ['Patrick', 700],
                ['Eric', 1500]
            ]
        );
        $newColumnChart->setOptions($oldColumnChart->getOptions());
        
        $diffColumnChart = new DiffColumnChart($oldColumnChart, $newColumnChart);
        $diffColumnChart->getOptions()->getLegend()->setPosition('top');
        $diffColumnChart->getOptions()->setWidth(450);
        $diffColumnChart->getOptions()->setHeight(250);
        $diffColumnChart->getOptions()->getDiff()->getNewData()->setWidthFactor(0.1);  


        return $this->render('board/stats.html.twig', [
            'page_title' => 'Tableau de bord - Statistiques',
            'piechart' => $pieChart,
            'oldColumnChart' => $oldColumnChart,
            'newColumnChart' => $newColumnChart,
            'diffColumnChart' => $diffColumnChart
        ]);
    }
}
