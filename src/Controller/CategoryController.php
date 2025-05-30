<?php

namespace App\Controller;

use App\Entity\Category;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\Persistence\ManagerRegistry;

final class CategoryController extends AbstractController
{
    #[Route('/category', name: 'app_category')]
    public function index(ManagerRegistry $doctrine): Response
    { $entityManager = $doctrine->getManager();
        $repo=$doctrine->getRepository(Category::class);
        $category=new category();
        $category->setName("Chien");
        $category1=new category();
        $category1->setName("Rongeurs");
        $category2=new category();
        $category2->setName("chats");
        $category3=new category();
        $category3->setName("Oiseau");
        $category4=new category();
        $category4->setName("poisson");
        $category5=new category();
        $category5->setName("Aliments");
        $category6=new category();
        $category6->setName("Habitats");
        $category7=new category();
        $category7->setName("3alloush el 3id");

        $entityManager->persist($category);

      $entityManager->persist($category1);
      $entityManager->persist($category2);
      $entityManager->persist($category3);
      $entityManager->persist($category4);

      $entityManager->persist($category5);
      $entityManager->persist($category6);
      $entityManager->persist($category7);
       $entityManager->flush();
       return new Response("category created");
    }
}
