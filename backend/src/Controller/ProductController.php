<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\ProductType;
use App\Repository\ProductRepository;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\RequestDetailRepository;
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
    public function show(Product $product, RequestDetailRepository $requestDetailRepo)
    {
        if (!$product) {
            throw $this->createNotFoundException("Le produit indiqué n'existe pas"); 
        }

        $requestDetails = $requestDetailRepo->findIsActiveByProductOrderedByField($product);

        return $this->render('product/show.html.twig', [
            'page_title' => 'Produit: ' . $product->getName(),
            'product' => $product,
            'details' => $requestDetails,
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
                'warning',
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

        if(!$product->getIsOnHomepage()) {
            if (!$product->getIsAvailable()) {
                $product->setIsAvailable(true);
                $this->addFlash(
                    'success',
                    'Le produit ' . $product->getName() . ' a bien été indiqué comme disponible !'
                );
            } else {
                $product->setIsAvailable(false);
                $this->addFlash(
                    'warning',
                    'Le produit ' . $product->getName() . ' a bien été indiqué comme indisponible !'
                );
            }
        } else {
            //si le produit n'est pas dispo à la vente, on ne l'affiche pas en home page front
            if (!$product->getIsAvailable()) {
                $product->setIsAvailable(true);
                $this->addFlash(
                    'success',
                    'Le produit ' . $product->getName() . ' a bien été indiqué comme disponible, il apparait en vitrine !'
                );
            } else {
                $product->setIsAvailable(false);
                $product->setIsOnHomepage(false);
                $this->addFlash(
                    'warning',
                    'Le produit ' . $product->getName() . ' a bien été indiqué comme indisponible et retiré de la vitrine  !'
                );
            }
        }

        $entityManager->flush();

        

        $referer = $request->headers->get('referer');
        return $this->redirect($referer);;
    }

    /**
     * @Route("/{id}/on_home_page", name="on_home_page", methods={"PATCH"}, requirements={"id"="\d+"})
     */
    public function onHomePage(Product $product, Request $request, EntityManagerInterface $entityManager, ProductRepository $productRepo)
    {
        if (!$product) {
            throw $this->createNotFoundException("Le produit indiqué n'existe pas"); 
        }

        $onHomepageProducts = $productRepo->findByIsActiveAndIsAvailableAndIsOnHomePage(true, true, true);

        //si le produit n'est pas dispo à la vente, on ne l'affiche pas en home page front
        if (!$product->getIsOnHomePage()) {
            if($product->getIsAvailable()) {
                if (count($onHomepageProducts) <= 10 ){
                    $product->setIsOnHomePage(true);

                    $this->addFlash(
                        'success',
                        'Le Produit ' . $product->getName() . 'a été ajouté en vitrine !'
                    );
                    $entityManager->flush();

                } else {
                    $this->addFlash(
                        'danger',
                        'Un maximum de 10 produits peuvent être ajoutés en vitrine, un autre ajout est impossible !'
                    );
                    $entityManager->flush();
                }
            }
        } else {
            $product->setIsOnHomePage(!$product->getIsOnHomePage());

            $this->addFlash(
                'warning',
                'Le Produit ' . $product->getName() . 'a été retiré de la vitrine !'
            );
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
        $notification = ($product->getIsActive() ? ' a été désarchivé' : ' a été archivé !');
        $this->addFlash(
            'warning',
            'Le Produit ' . $product->getName() . $notification
        );
        $entityManager->flush();

        $referer = $request->headers->get('referer');
        return $this->redirect($referer);;
    }

}
