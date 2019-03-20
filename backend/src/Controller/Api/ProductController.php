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
     * @Route("/products", name="index")
     */
    public function index(ProductRepository $productRepo, SerializerInterface $serializer)
    {
        $products = findByIsActive(true);

        $jsonObject = $serializer->serialize($products, 'json', [
            'circular_reference_handler' => function ($object) {
                return $object->getId();
            }
        ]);

        return new Response($jsonObject, 200, ['Content-Type' => 'application/json']);
    }
}
