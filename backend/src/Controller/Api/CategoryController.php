<?php

namespace App\Controller\Api;

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
     * @Route("/categories", name="index", methods={"GET"})
     */
    public function index(CategoryRepository $categoryRepo, SerializerInterface $serializer)
    {
        $categories = $categoryRepo->findByIsActive(true);

        $jsonObject = $serializer->serialize($categories, 'json', [
            'circular_reference_handler' => function ($object) {
                return $object->getId();
            }
        ]);

        return new Response($jsonObject, 200, ['Content-Type' => 'application/json']);
    }
}
