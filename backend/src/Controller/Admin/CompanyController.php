<?php
namespace App\Controller\Admin;

use App\Entity\Company;
use App\Repository\UserRepository;
use App\Repository\PersonRepository;
use App\Repository\CompanyRepository;
use App\Repository\ContactRepository;
use App\Repository\RequestRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\CompanyAddressRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


/** 
 *  @Route("/admin/company", name="admin_company_") 
*/
class CompanyController extends AbstractController
{

    /**
     * @Route("/index", name="index", methods={"GET"})
     * Index des Sociétés en mode Gestion/ADMIN : seulement celles qui sont archivées
     */
    public function index(Request $request, CompanyRepository $companyRepo, UserRepository $userRepo, PersonRepository $personRepo)
    {
        $companies = $companyRepo->findIsACtiveOrderedByField('name', 'ASC', false);

        return $this->render('admin/company/index.html.twig', [
            'page_title' => 'Sociétés',
            'companies' => $companies,
        ]);
    }

    /**
     * @Route("/{id}/delete", name="delete", methods={"DELETE"}, requirements={"id"="\d+"})
     */
    public function delete(Company $company, Request $request, EntityManagerInterface $entityManager)
    {
        if (!$company) {
            throw $this->createNotFoundException("La société indiquée n'existe pas"); 
        }

        $entityManager->remove($company);
        $entityManager->flush();
        $notification = " a été supprimée !";
        $this->addFlash(
            'danger',
            'La Société ' . $company->getName() . $notification
        );

        $referer = $request->headers->get('referer');
        return $this->redirect($referer);;
    }

}
