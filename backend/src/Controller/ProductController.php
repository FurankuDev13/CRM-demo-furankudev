<?php

namespace App\Controller;

use App\Entity\Product;
use App\Repository\ProductRepository;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/** 
 *  @Route("/product", name="product_") 
*/
class ProductController extends AbstractController
{
    /**
     * @Route("/index", name="index", methods={"GET"})
     */
    public function index(ProductRepository $productRepo, CategoryRepository $categoryRepo)
    {
        $products = $productRepo->findByIsActive(true);
        $categories = $categoryRepo->findByIsActive(true);

        return $this->render('product/index.html.twig', [
            'page_title' => 'Liste des produits du catalogue',
            'products' => $products,
            'categories' => $categories,
        ]);
    }

    /**
     * @Route("/{id}/show", name="show", methods={"GET"}, requirements={"id"="\d+"})
     */
    public function show(Product $product)
    {
        if (!$product) {
            throw $this->createNotFoundException("Le produit indiqué n'existe pas"); 
        }

        return $this->render('product/show.html.twig', [
            'page_title' => 'Produit: ' . $product->getName(),
            'product' => $product,
        ]);
    }

    /**
     * @Route("/new", name="new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager)
    {
        return $this->render('product/new.html.twig', [
            'page_title' => 'Ajouter un nouveau produit',
        ]);
    }

    /**
     * @Route("/{id}/edit", name="edit", methods={"GET", "POST"}, requirements={"id"="\d+"})
     */
    public function edit(Product $product, Request $request, EntityManagerInterface $entityManager)
    {
        if (!$product) {
            throw $this->createNotFoundException("Le produit indiqué n'existe pas"); 
        }

        return $this->render('product/edit.html.twig', [
            'page_title' => 'Mettre à jour le produit: ' . $product->getName(),
        ]);
    }

    /**
     * @Route("/{id}/archive", name="archive", methods={"PATCH"}, requirements={"id"="\d+"})
     */
    public function archive(Product $product, Request $request, EntityManagerInterface $entityManager)
    {
        if (!$product) {
            throw $this->createNotFoundException("Le produit indiqué n'existe pas"); 
        }

        $product->setIsActive(!$product->getIsActive());
        $this->addFlash(
            'success',
            'Le Produit ' . $product->getName() . ' a été archivé !'
        );
        $entityManager->flush();

        $referer = $request->headers->get('referer');

        return $this->redirect($referer);;
    }
}
