<?php

namespace App\Controller\Api;

use App\Entity\Product;
use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/** 
 *  @Route("/api", name="api_product_") 
*/
class ProductController extends AbstractController
{
    /**
     * @Route("/products", name="index", methods={"GET"})
     */
    public function index(Request $request, ProductRepository $productRepo, SerializerInterface $serializer)
    {
        $isActive = true;
        $isAvailable = $request->query->get('isAvailable', true);
        $isOnHomePage = $request->query->get('isOnHomePage', false);

        $products = $productRepo->findByIsActiveAndIsAvailableAndIsOnHomePage($isActive, $isAvailable, $isOnHomePage);

        if ($products) {
            $responseCode = 200 ;
            $jsonObject = $serializer->serialize($products, 'json', ['groups' => 'product_group']);

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

    /**
     * @Route("/product/{id}", name="show", methods={"GET"})
     */
    public function show(Product $product = null, ProductRepository $productRepo, SerializerInterface $serializer)
    {
        if (!is_null($product) && $product->getIsActive()) {
            $responseCode = 200 ;
            $jsonObject = $serializer->serialize($product, 'json', ['groups' => 'product_group']);
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
