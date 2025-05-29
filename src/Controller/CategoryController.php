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

       $entityManager->persist($category);
       $entityManager->persist($category1);
       $entityManager->persist($category2);
       $entityManager->persist($category3);
       $entityManager->persist($category4);
       $entityManager->flush();
       return new Response("category created");
    }
}
