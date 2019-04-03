<?php

namespace App\Controller\Admin;

use App\Entity\CompanyAddressType;
use App\Form\CompanyAddressTypeFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\CompanyAddressTypeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/** 
 *  @Route("/admin/addressType", name="admin_addressType_") 
*/
class AddressTypeController extends AbstractController
{
    /**
     * @Route("/index", name="index", methods={"GET"})
     */
    public function index(Request $request, CompanyAddressTypeRepository $companyAddressTypeRepo)
    {
        $isActive = [true,false];
        $field = $request->query->get('field', 'title');
        $order = $request->query->get('order', 'ASC');

        $companyAddressTypes = $companyAddressTypeRepo->findByIsActiveOrderedByField($isActive, $field , $order);

        return $this->render('admin/address_type/index.html.twig', [
            'page_title' => "Liste des types d'adresse",
            'addressTypes' => $companyAddressTypes,
        ]);
    }

    /**
     * @Route("/new", name="new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager)
    {
        $companyAddressType = new CompanyAddressType();

        $form = $this->createForm(CompanyAddressTypeFormType::class, $companyAddressType);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($companyAddressType);
            $entityManager->flush();

            $this->addFlash(
                'success',
                'Le type ' . $companyAddressType->getTitle() . ' a bien été ajouté !'
            );
            return $this->redirectToRoute('admin_addressType_index');
        }


        return $this->render('admin/address_type/new.html.twig', [
            'page_title' => "Ajouter un nouveau type d'adresse",
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/{id}/edit", name="edit", methods={"GET", "POST"})
     */
    public function edit(CompanyAddressType $companyAddressType, Request $request, EntityManagerInterface $entityManager)
    {
        if (!$companyAddressType) {
            throw $this->createNotFoundException("Le type indiqué n'existe pas"); 
        }

        $form = $this->createForm(CompanyAddressTypeFormType::class, $companyAddressType);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            $this->addFlash(
                'warning',
                'Le type ' . $companyAddressType->getTitle() . ' a bien été mis à jour !'
            );
            return $this->redirectToRoute('admin_addressType_index');
        }

        return $this->render('admin/address_type/edit.html.twig', [
            'page_title' => 'Mettre à jour le type ' . $companyAddressType->getTitle(),
            'form' => $form->createView(),
            'companyAddressType' => $companyAddressType
        ]);
    }

    /**
     * @Route("/{id}/archive", name="archive", methods={"PATCH"}, requirements={"id"="\d+"})
     */
    public function archive(CompanyAddressType $companyAddressType, Request $request, EntityManagerInterface $entityManager)
    {
        if (!$companyAddressType) {
            throw $this->createNotFoundException("Le type indiqué n'existe pas"); 
        }

        $companyAddressType->setIsActive(!$companyAddressType->getIsActive());
        $notification = ($companyAddressType->getIsActive() ? ' a été désarchivé' : ' a été archivé !');
        $this->addFlash(
            'warning',
            'Le type ' . $companyAddressType->getTitle() . $notification
        );
        $entityManager->flush();

        $referer = $request->headers->get('referer');

        return $this->redirect($referer);;
    }

        /**
     * @Route("/{id}/delete", name="delete", methods={"DELETE"}, requirements={"id"="\d+"})
     */
    public function delete(CompanyAddressType $companyAddressType, Request $request, EntityManagerInterface $entityManager)
    {
        if (!$companyAddressType) {
            throw $this->createNotFoundException("Le type indiqué n'existe pas"); 
        }

        $entityManager->remove($companyAddressType);;
        $this->addFlash(
            'danger',
            'Le type ' . $companyAddressType->getTitle() . ' a été supprimé ! '
        );
        $entityManager->flush();

        $referer = $request->headers->get('referer');

        return $this->redirect($referer);;
    }
}
