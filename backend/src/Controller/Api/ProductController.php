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
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use Swagger\Annotations as SWG;

/** 
 *  @Route("/api", name="api_product_") 
*/
class ProductController extends AbstractController
{
    /**
     * @Route("/product", name="index", methods={"GET"})
     * @SWG\Response(
     *     response=200,
     *     description="Returns the list of the catalog products",
     *     @SWG\Schema(
     *         type="array",
     *         @SWG\Items(ref=@Model(type=Product::class, groups={"product_group"}))
     *     )
     * )
     *  @SWG\Parameter(
     *     name="isAvailable",
     *     in="query",
     *     type="boolean",
     *     description="filter the available products"
     * )
     *  @SWG\Parameter(
     *     name="isOnHomepage",
     *     in="query",
     *     type="boolean",
     *     description="filter the top products meant to be displayed on the homepage"
     * )
     * @SWG\Tag(name="products")
     * @Security(name="Bearer")
     * 
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
     * 
     * @SWG\Response(
     *     response=200,
     *     description="Returns one product by its id",
     *     @SWG\Schema(
     *         type="array",
     *         @SWG\Items(ref=@Model(type=Product::class, groups={"product_group"}))
     *     )
     * )
     * @SWG\Tag(name="products")
     * @Security(name="Bearer")
     * 
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
