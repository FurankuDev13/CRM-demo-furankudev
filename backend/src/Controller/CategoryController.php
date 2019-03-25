<?php

namespace App\Controller;

use App\Entity\Category;
use App\Form\CategoryType;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/** 
 *  @Route("/category", name="category_") 
*/
class CategoryController extends AbstractController
{
    /**
     * @Route("/index", name="index", methods={"GET"})
     */
    public function index(CategoryRepository $categoryRepo)
    {
        $categories = $categoryRepo->findByIsActive(true);

        return $this->render('category/index.html.twig', [
            'page_title' => 'Catalogue Catégories',
            'categories' => $categories,
        ]);
    }

    /**
     * @Route("/{id}/show", name="show", methods={"GET"}, requirements={"id"="\d+"})
     */
    public function show(Category $category)
    {
        if (!$category) {
            throw $this->createNotFoundException("La catégorie indiquée n'existe pas"); 
        }

        return $this->render('category/show.html.twig', [
            'page_title' => 'Catégorie: ' . $category->getName(),
            'category' => $category,
        ]);
    }

    /**
     * @Route("/new", name="new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager)
    {
        $category = new Category();

        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($category);
            $entityManager->flush();

            $this->addFlash(
                'success',
                'La catégorie ' . $category->getName() . ' a bien été ajoutée !'
            );
            return $this->redirectToRoute('category_show', ['id' => $category->getId()]);
        }


        return $this->render('category/new.html.twig', [
            'page_title' => 'Ajouter une nouvelle catégorie',
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/{id}/edit", name="edit", methods={"GET", "POST"}, requirements={"id"="\d+"})
     */
    public function edit(Category $category, Request $request, EntityManagerInterface $entityManager)
    {
        if (!$category) {
            throw $this->createNotFoundException("La catégorie indiquée n'existe pas"); 
        }

        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            $this->addFlash(
                'success',
                'La catégorie ' . $category->getName() . ' a bien été mise à jour !'
            );
            return $this->redirectToRoute('category_show', ['id' => $category->getId()]);
        }

        return $this->render('category/edit.html.twig', [
            'page_title' => 'Mettre à jour la catégorie: ' . $category->getName(),
            'category' => $category,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/{id}/archive", name="archive", methods={"PATCH"}, requirements={"id"="\d+"})
     */
    public function archive(Category $category, Request $request, EntityManagerInterface $entityManager)
    {
        if (!$category) {
            throw $this->createNotFoundException("La catégorie indiquée n'existe pas"); 
        }

        $category->setIsActive(!$category->getIsActive());
        $this->addFlash(
            'success',
            'La catégorie ' . $category->getName() . ' a été archivée !'
        );
        $entityManager->flush();

        $referer = $request->headers->get('referer');

        return $this->redirect($referer);;
    }
}
