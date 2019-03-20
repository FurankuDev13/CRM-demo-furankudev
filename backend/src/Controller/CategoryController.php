<?php

namespace App\Controller;

use App\Entity\Category;
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
            'page_title' => 'Liste des catégories du catalogue',
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
        return $this->render('category/new.html.twig', [
            'page_title' => 'Ajouter une nouvelle catégorie',
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

        return $this->render('category/edit.html.twig', [
            'page_title' => 'Mettre à jour la catégorie: ' . $category->getName(),
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

        $referer = $request->headers->get('referer');

        return $this->redirect($referer);;
    }
}
