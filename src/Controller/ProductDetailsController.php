<?php

namespace App\Controller;

use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\AnimalProduct;
use App\Entity\Category;
use App\Entity\Product;

final class ProductDetailsController extends AbstractController
{
    #[Route('/product/details/{productId}', name: 'app_product_details')]
    public function index(ManagerRegistry $doctrine, integer $productId): Response
    {   $entityManager = $doctrine->getManager();
        $repo = $doctrine->getRepository(Product::class);






        return $this->render('product_details/index.html.twig', [
            'controller_name' => 'ProductDetailsController',
        ]);
    }
}
