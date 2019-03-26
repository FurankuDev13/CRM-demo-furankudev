<?php

namespace App\Controller;

use App\Entity\Company;
use App\Entity\CompanyAddress;
use App\Repository\UserRepository;
use App\Form\CompanyAddressFormType;
use App\Repository\CompanyRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Form\CompanyType as CompanyFormType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

/** 
 *  @Route("/company", name="company_") 
*/
class CompanyController extends AbstractController
{
    /**
     * @Route("/index", name="index", methods={"GET"})
     */
    public function index(Request $request, CompanyRepository $companyRepo, UserRepository $userRepo)
    {
        $filter = $request->query->get('filter', null);
        $salesUser = $request->query->get('salesUser', null);
        $field = $request->query->get('field', 'name');
        $order = $request->query->get('order', 'ASC');

        $salesUsers = $userRepo->findSalesRoles();
        $prospects = $companyRepo->findIsActiveByIsCustomerOrderedByField(false, $field, $order);
        $customers = $companyRepo->findIsActiveByIsCustomerOrderedByField(true, $field, $order);

        if ($salesUser) {
            $user = $userRepo->find($salesUser);
            $companies = $companyRepo->findIsActiveByUserOrderedByField($user, $field, $order);
            $filterValue = $salesUser;
        } else {
            $companies = $companyRepo->findIsACtiveOrderedByField($field, $order);
            $filterValue = null;
        }

        if ($filter == "isCustomer") {
            $companies = $customers;
            $filterValue = "isCustomer";
        }

        if ($filter == "isNotCustomer") {
            $companies = $prospects;
            $filterValue = "isNotCustomer";
        }

        return $this->render('company/index.html.twig', [
            'page_title' => 'Sociétés',
            'companies' => $companies,
            'salesUsers' => $salesUsers,
            'prospects' => $prospects,
            'customers' => $customers,
            'filterType' => $filter,
            'filterValue' => $filterValue,
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

        $companyFiltered = $companyRepo->find($company);

        return $this->render('company/show.html.twig', [
            'page_title' => 'Société: ' . $companyFiltered->getName(),
            'company' => $companyFiltered,
        ]);
    }

    /**
     * @Route("/new", name="new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager)
    {
        $company = new Company();

        $form = $this->createForm(CompanyFormType::class, $company);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($company);
            $entityManager->flush();

            $this->addFlash(
                'success',
                'La société ' . $company->getName() . ' a bien été ajoutée !'
            );
            return $this->redirectToRoute('company_show', ['id' => $company->getId()]);
        }

        return $this->render('company/new.html.twig', [
            'page_title' => 'Ajouter une nouvelle société',
            'form' => $form->createView()
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

        $form = $this->createForm(CompanyFormType::class, $company);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            $this->addFlash(
                'success',
                'La société ' . $company->getName() . ' a bien été mise à jour !'
            );
            return $this->redirectToRoute('company_show', ['id' => $company->getId()]);
        }

        return $this->render('company/edit.html.twig', [
            'page_title' => 'Mettre à jour la société: ' . $company->getName(),
            'company' => $company,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/{id}/toggleIsCustomer", name="toggleIsCustomer", methods={"PATCH"}, requirements={"id"="\d+"})
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
     * @Route("/{id}/address/new", name="address_new", methods={"GET", "POST"})
     */
    public function newAddress(Request $request, EntityManagerInterface $entityManager, Company $company)
    {
        $companyAddress = new CompanyAddress();
        $companyAddressForm = $this->createForm(CompanyAddressFormType::class, $companyAddress);
        $companyAddressForm->handleRequest($request);

        if ($companyAddressForm->isSubmitted() && $companyAddressForm->isValid()) {
            $companyAddress->setCompany($company);
            $entityManager->persist($companyAddress);
            $entityManager->flush();

            $this->addFlash(
                'success',
                "La nouvelle adresse a bien été ajoutée et associée à " . $company->getName()
            );
            return $this->redirectToRoute('company_show', ['id' => $company->getId()]);
        }

        return $this->render('company/new_address.html.twig', [
            'page_title' => 'Ajouter une nouvelle adresse',
            'form' => $companyAddressForm->createView(),
            'company' => $company,
        ]);
    }

    /**
     * @Route("/{id}/address/{address_id}/edit", name="address_edit", methods={"GET", "POST"}, requirements={"id"="\d+", "id"="\d+"})
     * @ParamConverter("companyAddress", options={"id" = "address_id"})
     */
    public function editAddress(Request $request, EntityManagerInterface $entityManager, Company $company, CompanyAddress $companyAddress)
    {
        if (!$companyAddress) {
            throw $this->createNotFoundException("L'adresse' indiquée n'existe pas"); 
        }

        $companyAddressForm = $this->createForm(CompanyAddressFormType::class, $companyAddress);
        $companyAddressForm->handleRequest($request);

        if ($companyAddressForm->isSubmitted() && $companyAddressForm->isValid()) {
            $companyAddress->setCompany($company);
            $entityManager->flush();

            $this->addFlash(
                'success',
                "L'adresse a bien été mise à jour pour la société " . $company->getName()
            );
            return $this->redirectToRoute('company_show', ['id' => $company->getId()]);
        }

        return $this->render('company/edit_address.html.twig', [
            'page_title' => "Mettre à jour l'adresse de la société: " . $company->getName(),
            'form' => $companyAddressForm->createView(),
            'company' => $company
        ]);
    }

    /**
     * @Route("/address/{id}/archive", name="address_archive", methods={"PATCH"}, requirements={"id"="\d+", "id"="\d+"})
     */
    public function archiveAddress(Request $request, EntityManagerInterface $entityManager, CompanyAddress $companyAddress)
    {
        if (!$companyAddress) {
            throw $this->createNotFoundException("L'adresse' indiquée n'existe pas"); 
        }

        $companyAddress->setIsActive(!$companyAddress->getIsActive());
        $this->addFlash(
            'success',
            'L\'adresse de ' . $companyAddress->getCity() . ' a été archivée !'
        );
        $entityManager->flush();

        $referer = $request->headers->get('referer');

        return $this->redirect($referer);;
    }
}
