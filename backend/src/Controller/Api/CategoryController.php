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

        if ($categories) {
            $responseCode = 200 ;
            $jsonObject = $serializer->serialize($categories, 'json', [
                'circular_reference_handler' => function ($object) {
                    return $object->getId();
                }
            ]);
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
}
