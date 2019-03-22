<?php

namespace App\Controller;

use App\Entity\Company;
use App\Entity\CompanyAddress;
use App\Repository\UserRepository;
use App\Repository\CompanyRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/** 
 *  @Route("/company", name="company_") 
*/
class CompanyController extends AbstractController
{
    /**
     * @Route("/index", name="index", methods={"GET"})
     */
    public function index(CompanyRepository $companyRepo)
    {
        $companies = $companyRepo->findByIsActive(true);

        return $this->render('company/index.html.twig', [
            'page_title' => 'Liste des sociétés',
            'companies' => $companies,
        ]);
    }

    /**
     * @Route("/{id}/show", name="show", methods={"GET"}, requirements={"id"="\d+"})
     */
    public function show(Company $company, CompanyRepository $companyRepo)
    {
        if (!$company) {
            throw $this->createNotFoundException("La société indiquée n'existe pas"); 
        }

        return $this->render('company/show.html.twig', [
            'page_title' => 'Société: ' . $company->getName(),
            'company' => $company,
        ]);
    }

    /**
     * @Route("/new", name="new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager)
    {
        return $this->render('company/new.html.twig', [
            'page_title' => 'Ajouter une nouvelle société',
        ]);
    }

    /**
     * @Route("/{id}/edit", name="edit", methods={"GET", "POST"}, requirements={"id"="\d+"})
     */
    public function edit(Company $company, Request $request, EntityManagerInterface $entityManager)
    {
        if (!$company) {
            throw $this->createNotFoundException("La société indiquée n'existe pas"); 
        }

        return $this->render('company/edit.html.twig', [
            'page_title' => 'Mettre à jour la société: ' . $company->getName(),
        ]);
    }

    /**
     * @Route("/{id}/toggle-is-customer", name="toggleIsCustomer", methods={"PATCH"}, requirements={"id"="\d+"})
     */
    public function toggleIsCustomer(Company $company, Request $request, EntityManagerInterface $entityManager)
    {
        if (!$company) {
            throw $this->createNotFoundException("La société indiquée n'existe pas"); 
        }

        $referer = $request->headers->get('referer');

        return $this->redirect($referer);
    }

    /**
     * @Route("/{id}/user/set", name="user_set", methods={"PATCH"}, requirements={"id"="\d+"})
     */
    public function setUser(Company $company, Request $request, EntityManagerInterface $entityManager, UserRepository $userRepo)
    {
        if (!$company) {
            throw $this->createNotFoundException("La société indiquée n'existe pas"); 
        }

        $userId = $request->request->get('userId');
        $user = $userRepo->find($userId);
        $company->setUser($user);
        $entityManager->flush();

        $this->addFlash(
            'success',
            $user->getPerson()->getFirstname() . ' ' . $user->getPerson()->getLastname() . ' a bien été attribué à la société ' . $company->getName() . ' !'
        );

        $referer = $request->headers->get('referer');

        return $this->redirect($referer);
    }

    /**
     * @Route("/{id}/archive", name="archive", methods={"PATCH"}, requirements={"id"="\d+"})
     */
    public function archive(Company $company, Request $request, EntityManagerInterface $entityManager)
    {
        if (!$company) {
            throw $this->createNotFoundException("La société indiquée n'existe pas"); 
        }

        $company->setIsActive(!$company->getIsActive());
        $this->addFlash(
            'success',
            'La Société ' . $company->getName() . ' a été archivée !'
        );
        $entityManager->flush();

        $referer = $request->headers->get('referer');

        return $this->redirect($referer);;
    }

    /**
     * @Route("/new", name="address_new", methods={"GET", "POST"})
     */
    public function newAddress(Request $request, EntityManagerInterface $entityManager)
    {
        return $this->render('company/new_address.html.twig', [
            'page_title' => 'Ajouter une nouvelle adresse',
        ]);
    }

    /**
     * @Route("/{companyId}/address/{id}/edit", name="address_edit", methods={"GET", "POST"}, requirements={"companyId"="\d+", "id"="\d+"})
     */
    public function editAddress(CompanyAddress $companyAddress, Request $request, EntityManagerInterface $entityManager)
    {
        if (!$companyAddress) {
            throw $this->createNotFoundException("L'adresse' indiquée n'existe pas"); 
        }

        return $this->render('company/edit_address.html.twig', [
            'page_title' => "Mettre à jour l'adresse de la société: " . $company->getName(),
        ]);
    }
}
