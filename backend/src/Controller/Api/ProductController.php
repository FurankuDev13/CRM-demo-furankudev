<?php

namespace App\Controller\Api;

use App\Repository\ProductRepository;
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
    public function index(ProductRepository $productRepo, SerializerInterface $serializer)
    {
        $products = $productRepo->findByIsActiveAndIsAvailable(true, false);

        $jsonObject = $serializer->serialize($products, 'json', [
            'circular_reference_handler' => function ($object) {
                return $object->getId();
            }
        ]);

        $response = new Response($jsonObject, 200);
        
        $response->headers->set('Content-Type', 'application/json');
        $response->headers->set('Access-Control-Allow-Origin', '*');

        return $response;
    }
}
