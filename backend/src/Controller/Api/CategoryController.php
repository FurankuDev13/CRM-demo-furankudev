<?php

namespace App\Controller\Api;

use App\Entity\Category;
use App\Repository\CategoryRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/** 
 *  @Route("/api", name="api_category_") 
*/
class CategoryController extends AbstractController
{
    /**
     * @Route("/category", name="index", methods={"GET"})
     */
    public function index(CategoryRepository $categoryRepo, SerializerInterface $serializer)
    {
        $categories = $categoryRepo->findByIsActive(true);

        if ($categories) {
            $responseCode = 200 ;
            $jsonObject = $serializer->serialize($categories, 'json', ['groups' => 'category_group']);
        } else {
            $jsonObject = $serializer->serialize(
                [
                "error" => "no_category_found",
                "error_description"  => "Aucune catégorie n'a pu être trouvée"
                ], 
                'json'
            );
        }
        return new Response($jsonObject, $responseCode, ['Content-Type' => 'application/json']);
    }

    /**
     * @Route("/category/{id}", name="show", methods={"GET"})
     */
    public function show(Category $category = null, CategoryRepository $categoryRepo, SerializerInterface $serializer)
    {
        if (!is_null($category) && $category->getIsActive()) {
            $responseCode = 200 ;
            $jsonObject = $serializer->serialize($category, 'json', ['groups' => 'category_group']);
        } else {
            $responseCode = 400 ;
            $jsonObject = $serializer->serialize(
                [
                "error" => "no_product_found",
                "error_description"  => "Aucun produit n'a pu être trouvé"
                ], 
                'json'
            );
        }

        $response = new Response($jsonObject, $responseCode);
        
        $response->headers->set('Content-Type', 'application/json');
        $response->headers->set('Access-Control-Allow-Origin', '*');

        return $response;
    }
}
