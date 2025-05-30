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

    public function index(ManagerRegistry $doctrine, String $productId): Response
    {   $entityManager = $doctrine->getManager();
        $repo = $doctrine->getRepository(Product::class);
          $repo2 = $doctrine->getRepository(AnimalProduct::class);
          $product = $repo->find($productId);

  if ($product instanceof AnimalProduct){
      $type="animal";
    }
  else{
      $type="product";
    }




        return $this->render('product_details/index.html.twig', [
            'type' => $type,'product' => $product,
        ]);
    }
}
