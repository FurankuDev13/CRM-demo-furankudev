<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\ProductType;
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
    public function index(Request $request, ProductRepository $productRepo, CategoryRepository $categoryRepo)
    {
        $field = $request->query->get('field', 'name');
        $order = $request->query->get('order', 'ASC');
        $categoryName = $request->query->get('category', false);

        $category = $categoryRepo->findOneByName($categoryName);

        if ($category) {
            $products = $productRepo->findIsACtiveByCategoryNameOrderedByField($categoryName ,$field , $order);
        } else {
            $products = $productRepo->findIsACtiveOrderedByField($field , $order);
        }
        
        $categories = $categoryRepo->findByIsActive(true);

        return $this->render('product/index.html.twig', [
            'page_title' => 'Catalogue Produits',
            'products' => $products,
            'categories' => $categories,
            'category' => $category,
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
        $product = new Product();

        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($product);
            $entityManager->flush();

            $this->addFlash(
                'success',
                'Le produit ' . $product->getName() . ' a bien été ajouté !'
            );
            return $this->redirectToRoute('product_show', ['id' => $product->getId()]);
        }


        return $this->render('product/new.html.twig', [
            'page_title' => 'Ajouter un nouveau produit',
            'form' => $form->createView()
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

        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            $this->addFlash(
                'success',
                'Le produit ' . $product->getName() . ' a bien été mis à jour !'
            );
            return $this->redirectToRoute('product_show', ['id' => $product->getId()]);
        }

        return $this->render('product/edit.html.twig', [
            'page_title' => 'Mettre à jour le produit: ' . $product->getName(),
            'product' => $product,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/{id}/available", name="available", methods={"PATCH"}, requirements={"id"="\d+"})
     */
    public function available(Product $product, Request $request, EntityManagerInterface $entityManager)
    {
        if (!$product) {
            throw $this->createNotFoundException("Le produit indiqué n'existe pas"); 
        }

        $product->setIsAvailable(!$product->getIsAvailable());

        if(!$product->getIsAvailable()) {
            //si le produit n'est pas dispo à la vente, on ne l'affiche pas en home page front
            $product->setIsOnHomePage(false);
        }

        $entityManager->flush();

        $referer = $request->headers->get('referer');
        return $this->redirect($referer);;
    }

    /**
     * @Route("/{id}/on_home_page", name="on_home_page", methods={"PATCH"}, requirements={"id"="\d+"})
     */
    public function onHomePage(Product $product, Request $request, EntityManagerInterface $entityManager)
    {
        if (!$product) {
            throw $this->createNotFoundException("Le produit indiqué n'existe pas"); 
        }

        //si le produit n'est pas dispo à la vente, on ne l'affiche pas en home page front
        if (!$product->getIsOnHomePage()) {
            if($product->getIsAvailable()) {
                $product->setIsOnHomePage(true);
            }
        } else {
            $product->setIsOnHomePage(!$product->getIsOnHomePage());
        }

        $entityManager->flush();

        $referer = $request->headers->get('referer');
        return $this->redirect($referer);;
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
