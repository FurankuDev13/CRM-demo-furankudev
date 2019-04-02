<?php
namespace App\Controller\Admin;

use App\Entity\Product;
use App\Repository\ProductRepository;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/** 
 *  @Route("/admin/product", name="admin_product_") 
*/
class ProductController extends AbstractController
{
    /**
     * @Route("/index", name="index", methods={"GET"})
     * Index des produits en mode Gestion/ADMIN : seulement ceux qui sont archivés
     */
    public function index(Request $request, ProductRepository $productRepo, CategoryRepository $categoryRepo)
    {
        $field = $request->query->get('field', 'name');
        $order = $request->query->get('order', 'ASC');
        $products = $productRepo->findIsACtiveOrderedByField($field , $order, false);

        return $this->render('admin/product/index.html.twig', [
            'page_title' => 'Catalogue Produits Archivés',
            'products' => $products,
        ]);
    }

    /**
     * @Route("/{id}/delete", name="delete", methods={"DELETE"}, requirements={"id"="\d+"})
     */
    public function delete(Product $product, Request $request, EntityManagerInterface $entityManager)
    {
        if (!$product) {
            throw $this->createNotFoundException("Le produit indiqué n'existe pas"); 
        }

        $entityManager->remove($product);
        $entityManager->flush();
        $notification = " a été supprimé !";
        $this->addFlash(
            'danger',
            'Le Produit ' . $product->getName() . $notification
        );

        $referer = $request->headers->get('referer');
        return $this->redirect($referer);;
    }
}
