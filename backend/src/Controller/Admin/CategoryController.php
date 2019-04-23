<?php
namespace App\Controller\Admin;

use App\Entity\Category;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;



/** 
*  @Route("/admin/category", name="admin_category_") 
*/
class CategoryController extends AbstractController
{

    /**
     * @Route("/index", name="index", methods={"GET"})
     * Index des catégories en mode Gestion/ADMIN : seulement ceux qui sont archivés
     */
    public function index(Request $request, CategoryRepository $categoryRepo)
    {
        $field = $request->query->get('field', 'name');
        $order = $request->query->get('order', 'ASC');

        $categories = $categoryRepo->findIsACtiveOrderedByField($field , $order, false);

        return $this->render('admin/category/index.html.twig', [
            'page_title' => 'Catalogue Catégories',
            'categories' => $categories,
        ]);
    }

    /**
     * @Route("/{id}/delete", name="delete", methods={"DELETE"}, requirements={"id"="\d+"})
     */
    public function delete(Category $category, Request $request, EntityManagerInterface $entityManager)
    {
        if (!$category) {
            throw $this->createNotFoundException("La catégorie indiquée n'existe pas"); 
        }

        $entityManager->remove($category);
        $entityManager->flush();
        $notification = " a été supprimée !";
        $this->addFlash(
            'danger',
            'La Catégorie ' . $category->getName() . $notification
        );

        $referer = $request->headers->get('referer');
        return $this->redirect($referer);;
    }

}
