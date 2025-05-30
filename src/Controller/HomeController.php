<?php

namespace App\Controller;

use Doctrine\ORM\EntityManager;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\AnimalProduct;
use App\Entity\Category;
use App\Entity\Product;
final class HomeController extends AbstractController
{
    #[Route('/home', name: 'app_home')]
    public function index(ManagerRegistry $doctrine): Response
    {
        $entityManager = $doctrine->getManager();
        $categoryRepository = $doctrine->getRepository(Category::class);
        $productRepository = $doctrine->getRepository(Product::class);

        $selectedProducts = [];

        // Get all categories

        $products = $productRepository->findAll();
        for ($i=0 ;$i<6; $i++) {
            $selectedProducts[] = $products[$i];
    }















        return $this->render('home/index.html.twig', [
            'products' => $selectedProducts,
        ]);
    }
}
