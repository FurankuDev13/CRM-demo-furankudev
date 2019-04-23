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
    public function index(Request $request, CompanyRepository $companyRepo, RequestRepository $requestRepo, UserRepository $userRepo, HandlingStatusRepository $handlingStatusRepo, RequestTypeRepository $requestTypeRepo, CommentRepository $commentRepo)
    {
        $index = $request->query->get('index', 0);
        $table = $request->query->get('table');
        $field = $request->query->get('field');
        $order = $request->query->get('order');
        $yearRequests = 0;
       
        if ($index > 4) {
            throw $this->createNotFoundException("L'index indiqué n'existe pas"); 
        }

        // Statistiques
        if (!$index) {
            $infoType = $requestTypeRepo->findOneByTitle('Informations');
            $quotationType = $requestTypeRepo->findOneByTitle('Devis Détaillé');
            $detailedQuotationType = $requestTypeRepo->findOneByTitle('Devis simple');
            $orderType = $requestTypeRepo->findOneByTitle('Commande');

            $currentdDate = new DateTime();
            $pastDate = new DateTime();
            $pastDate->modify('-1 year');

            $informations = $requestRepo->findIsActiveAndIsFinishedByTypeFromDateToDate($infoType, $pastDate, $currentdDate);
            $quotations = $requestRepo->findIsActiveAndIsFinishedByTypeFromDateToDate($quotationType, $pastDate, $currentdDate);
            $detailedQuotations = $requestRepo->findIsActiveAndIsFinishedByTypeFromDateToDate($detailedQuotationType, $pastDate, $currentdDate);
            $orders = $requestRepo->findIsActiveAndIsFinishedByTypeFromDateToDate($orderType, $pastDate, $currentdDate);
            $yearRequests = count($informations) + count($quotations) + count($detailedQuotations) + count($orders);

            $pieChart = new PieChart();
            $pieChart->getData()->setArrayToDataTable(
                [['Demandes', $pastDate->format('d-m-Y') . ' - ' . $currentdDate->format('d-m-Y')],
                ['Informations', count($informations)],
                ['Devis', count($quotations) + count($detailedQuotations)],
                ['Commandes', count($orders)],
                ]
            );
            $pieChart->getOptions()->setTitle('Répartition des demandes du ' . $pastDate->format('d-m-Y') . ' au ' . $currentdDate->format('d-m-Y'));
            $pieChart->getOptions()->setHeight(400);
/*             $pieChart->getOptions()->setWidth(500); */
            $pieChart->getOptions()->getTitleTextStyle()->setBold(true);
            $pieChart->getOptions()->getTitleTextStyle()->setColor('#009900');
            $pieChart->getOptions()->getTitleTextStyle()->setItalic(true);
            $pieChart->getOptions()->getTitleTextStyle()->setFontName('Arial');
            $pieChart->getOptions()->getTitleTextStyle()->setFontSize(20);
            
            $amountPerMonth = [];

            for ($i = 1; $i <= 12; $i++) {

                $maxDate = new DateTime();
                $maxDate->modify('-' . $i + 1 . ' month');
                $minDate = new DateTime();
                $minDate->modify('-' . $i . ' month');

                $monthOrders = $requestRepo->findIsActiveAndIsFinishedByTypeFromDateToDate($orderType, $minDate, $maxDate);

                $amount = 0;
                foreach ($monthOrders as $monthOrder) {
                    foreach ($monthOrder->getRequestDetails() as $detail) {
                        $productMaxDiscount = $detail->getProduct()->getMaxDiscountRate();
                        $productListPrice = $detail->getProduct()->getListPrice();
                        $productDiscount = min($detail->getRequest()->getContact()->getCompany()->getDiscount()->getRate(), $productMaxDiscount);
                        $productSellingPrice = $productListPrice * (100 - $productDiscount)/100;
                        $amount += ($detail->getQuantity() * $productSellingPrice);
                    }
                }
                $amountPerMonth[] = [
                    'dates' => $minDate->format('m-Y') . ' - ' . $maxDate->format('m-Y'),
                    'amount' => $amount
                ];
            }

            $oldColumnChart = new ColumnChart();
            $oldColumnChart->getData()->setArrayToDataTable(
                [
                    ['Période', 'CA'],
                    [$amountPerMonth[11]['dates'], $amountPerMonth[11]['amount']],
                    [$amountPerMonth[10]['dates'], $amountPerMonth[10]['amount']],
                    [$amountPerMonth[9]['dates'], $amountPerMonth[9]['amount']],
                    [$amountPerMonth[8]['dates'], $amountPerMonth[8]['amount']],
                    [$amountPerMonth[7]['dates'], $amountPerMonth[7]['amount']],
                    [$amountPerMonth[6]['dates'], $amountPerMonth[6]['amount']],
                    [$amountPerMonth[5]['dates'], $amountPerMonth[5]['amount']],
                    [$amountPerMonth[4]['dates'], $amountPerMonth[4]['amount']],
                    [$amountPerMonth[3]['dates'], $amountPerMonth[3]['amount']],
                    [$amountPerMonth[2]['dates'], $amountPerMonth[2]['amount']],
                    [$amountPerMonth[1]['dates'], $amountPerMonth[1]['amount']],
                    [$amountPerMonth[0]['dates'], $amountPerMonth[0]['amount']],
                ]
            );
            $oldColumnChart->getOptions()->setTitle('Répartition du CA du ' . $pastDate->format('d-m-Y') . ' au ' . $currentdDate->format('d-m-Y'));
            $oldColumnChart->getOptions()->getLegend()->setPosition('top');
            $oldColumnChart->getOptions()->setHeight(400);
            /* $oldColumnChart->getOptions()->setWidth(800); */
            $oldColumnChart->getOptions()->getTitleTextStyle()->setBold(true);
            $oldColumnChart->getOptions()->getTitleTextStyle()->setColor('#009900');
            $oldColumnChart->getOptions()->getTitleTextStyle()->setItalic(true);
            $oldColumnChart->getOptions()->getTitleTextStyle()->setFontName('Arial');
            $oldColumnChart->getOptions()->getTitleTextStyle()->setFontSize(20);

        } else {
            $pieChart = null;
            $oldColumnChart = null;
        }

        // Tableaux

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
               
        $salesUsers = $userRepo->findSalesRoles();

        return $this->render('board/index.html.twig', [
            'page_title' => 'Tableau de bord',
            'index' => $index,
            'orphanCompanies' => $orphanCompanies,
            'companiesWithUnhandledRequests' => $companiesWithUnhandledRequests,
            'unhandledRequests' => $unhandledRequests,
            'salesUsers' => $salesUsers,
            'comments' => $comments,
            'piechart' => $pieChart,
            'oldColumnChart' => $oldColumnChart,
            'yearRequests' => $yearRequests
        ]);
    }
}
