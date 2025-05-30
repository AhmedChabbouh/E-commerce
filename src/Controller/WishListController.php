<?php

namespace App\Controller;

use App\Entity\Product;
use App\Entity\User;
use App\Entity\Wishlist;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/wish_list', name: 'app_wish_list')]
final class WishListController extends AbstractController
{
    #[Route('/show_list', name: 'app_wish_list_show')]
    public function showList(ManagerRegistry $doctrine): Response
    {
        $user = $this->getUser();
        $entityManager = $doctrine->getManager();
        $wishlist = $entityManager->getRepository(Wishlist::class)->findOneBy(['user'=>$user->getId()]);
        if(!$wishlist){
            $wishlist = new Wishlist();
            $user = $doctrine->getRepository(User::class)->find($user->getId());
            $wishlist->setUser($user);
            $entityManager->persist($wishlist);
            $entityManager->flush();
        }
        $products = $wishlist->getProducts()->toArray();
        /*
        $isAnimal = [];
        foreach ($products as $product) {
            $isAnimal[$product->getId()] = (new \ReflectionClass($product))->getShortName() === "AnimalProduct";
        }
        */
        return $this->render('wish_list/index.html.twig', [
            'products' => $products,
            'wishlist_id' => $wishlist->getId()
        ]);

    }

    #[Route('/add/{id}', name: 'app_wishlist_add', methods: ['POST'])]
    public function addToWishlist(int $id, ManagerRegistry $doctrine,Request $request): Response
    {
        $user = $this->getUser();
        $entityManager = $doctrine->getManager();

        $product = $doctrine->getRepository(Product::class)->find($id);
        if (!$product) {
            throw $this->createNotFoundException('Product not found');
        }

        $userId = $user->getId();
        $wishlist = $doctrine->getRepository(Wishlist::class)->findOneBy(['user' => $userId]);
        if (!$wishlist) {
            $wishlist = new Wishlist();
            $user = $doctrine->getRepository(User::class)->find($userId);
            $wishlist->setUser($user);
            $entityManager->persist($wishlist);
            $entityManager->flush();
        }

        if ($wishlist->getProducts()->contains($product)) {
            $wishlist->removeProduct($product);
            $message = 'Product removed from wishlist';
        } else {
            $wishlist->addProduct($product);
            $message = 'Product added to wishlist';
        }

        $entityManager->flush();

        $this->addFlash('success', $message);
        return $this->redirect($request->headers->get('referer'));



    }

}
