<?php

namespace App\Controller;

use App\Entity\Product;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/product', name: 'app_product')]
final class ProductController extends AbstractController
{
    #[Route('/list', name: 'product_list')]
    public function show_products(ManagerRegistry $doctrine)
    {
        $repo = $doctrine->getRepository(Product::class);
        $products = $repo->findAll();
        return $this->render('product/list.html.twig', [
            'products' =>$products  ,
        ]);
    }

}
