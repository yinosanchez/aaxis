<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Product;
use App\Entity\User;

class ProductController extends AbstractController
{
    #[Route('/product', name: 'my_product')]
    public function index(#[CurrentUser] ?User $user, EntityManagerInterface $entityManager): Response
    {
        $products = $entityManager->getRepository(Product::class)->findBy(['owner' => ['id' => $user->getId()]]);

        return $this->json($products, Response::HTTP_OK, [], [AbstractNormalizer::ATTRIBUTES => ['id', 'sku', 'product_name', 'description', 'created_at', 'owner' => ['username']]]);
    }

    #[Route('/product/all', name: 'all_product')]
    public function all(EntityManagerInterface $entityManager): Response
    {
        $products = $entityManager->getRepository(Product::class)->findAll();

        return $this->json($products, Response::HTTP_OK, [], [AbstractNormalizer::ATTRIBUTES => ['id', 'sku', 'product_name', 'description', 'created_at', 'owner' => ['username']]]);
    }

    #[Route('/product/{id}', name: 'update_product')]
    public function update(Request $request, EntityManagerInterface $entityManager, #[CurrentUser] ?User $user, int $id): Response
    {
        $product = $entityManager->getRepository(Product::class)->findOneBy(['id' => $id]);
        if(!$product){
            return $this->json([
                'message' => 'Product not found',
              ], Response::HTTP_NOT_FOUND);
        }

        if($product->getOwner()->getId() != $user->getId()){
            return $this->json([
                'message' => 'You can\'t see nor edit this product',
              ], Response::HTTP_UNAUTHORIZED);
        }

        if($request->getContent())
        {
            $data = json_decode($request->getContent(), true);

            if(isset($data['sku'])){
                $product->setSku($data['sku']);
            }

            if(isset($data['product_name'])){
                $product->setProductName($data['product_name']);
            }

            if(isset($data['description'])){
                $product->setDescription($data['description']);
            }

            $entityManager->flush();    
        }
        

        return $this->json($product);
    }
}


