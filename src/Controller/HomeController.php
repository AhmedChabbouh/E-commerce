<?php

namespace App\Controller;

use Couchbase\WildcardSearchQuery;
use Doctrine\ORM\EntityManager;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\AnimalProduct;
use App\Entity\Category;
use App\Entity\Product;
use App\Entity\Wishlist;
final class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(ManagerRegistry $doctrine): Response
    {
        $entityManager = $doctrine->getManager();
        $categoryRepository = $entityManager->getRepository(Category::class);
        $productRepository = $entityManager->getRepository(Product::class);

        $selectedProducts = [];

        // Get all categories

        $products = $productRepository->findAll();
        for ($i=0 ;$i<6; $i++) {
            $selectedProducts[] = $products[$i];
    }
        $wishList = $entityManager->getRepository(Wishlist::class)->findOneBy(['user' => $this->getUser()]);
        $wishListProducts = $wishList ? $wishList->getProducts()->toArray() : [];
        $wishlistProductIds = array_map(function($product) {
            return $product->getId();
        }, $wishListProducts);


        return $this->render('home/index.html.twig', [
            'products' => $selectedProducts,
            'wishlistProductIds' => $wishlistProductIds,
        ]);
    }
}
