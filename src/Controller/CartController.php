<?php

namespace App\Controller;

use App\Entity\Cart;
use App\Entity\CartItem;
use App\Entity\Product;
use App\Entity\User;
use Doctrine\ORM\EntityManager;
use Doctrine\Persistence\ManagerRegistry;
use http\Client\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Attribute\Route;
#[Route('/cart', name: 'app_cart')]
final class CartController extends AbstractController
{
    #[Route('/show_cart', name: 'show_cart')]
    public function showCart(ManagerRegistry $doctrine)
    {
        $user=$this->getUser();
        if(!$user) {
            return $this->redirectToRoute('app_login');
        }
        $entityManager = $doctrine->getManager();
        $cart=$doctrine->getRepository(Cart::class)->findOneBy(['user' => $user->getId()]);
        if(!$cart) {
            $cart = new Cart();
            $user = $doctrine->getRepository(User::class)->find($user->getId());
            $cart->setUser($user);
            $entityManager->persist($cart);
            $entityManager->flush();
        }
        $cartItems= $doctrine->getRepository(CartItem::class)->findBy(['cart' => $cart]);
        $products=array();
        $quantity=[];
        $isAnimal=[];
        $totalPrice=0;
        for ($i = 0; $i < count($cartItems); $i++) {
            $cartItem = $cartItems[$i];
            $totalPrice+=$cartItem->getProduct()->getPrice()*$cartItem->getQuantity();
            $product = $cartItem->getProduct();
            $quantity[$product->getId()]=$cartItem->getQuantity();
            if( (new \ReflectionClass($product))->getShortName()=="AnimalProduct") {
                $isAnimal[$product->getId()]=true;
            } else {
                $isAnimal[$product->getId()]=false;
            }
            $products[] = $product;
        }
        return $this->render('cart/cart.html.twig', ['products' => $products,'quantity' => $quantity,'totalPrice' => $totalPrice,'cart_id'=>$cart->getId(),'isAnimal'=>$isAnimal]);
    }









    #[Route('/add/{id}', name: 'add_to_cart',methods: ['POST'])]
    public function addToCart(ManagerRegistry $doctrine, int $id)
    {
        $user=$this->getUser();
        if(!$user) {
            return $this->redirectToRoute('app_login');
        }
        $entityManager = $doctrine->getManager();
        $product = $doctrine->getRepository(Product::class)->find($id);
        if (!$product) {
            throw $this->createNotFoundException('Product not found');
        }
        $userId=$user->getId();
        $cart=$doctrine->getRepository(Cart::class)->findOneBy(['user' => $userId]);
        if(!$cart) {
            $cart = new Cart();
            $user = $doctrine->getRepository(User::class)->find($userId);
            $cart->setUser($user);
            $entityManager->persist($cart);
            $entityManager->flush();
        }
        $cartItem = $doctrine->getRepository(CartItem::class)->findOneBy(['product' => $product, 'cart' => $cart]);
        if(isset($cartItem)&&((new \ReflectionClass($product))->getShortName()=="Product")) {
            $cartItem->setQuantity($cartItem->getQuantity() + 1);
            $entityManager->persist($cartItem);
        } else if (isset($cartItem)&&((new \ReflectionClass($product))->getShortName()=="AnimalProduct")){
            $cartItem->setQuantity(1);
            $entityManager->persist($cartItem);
        }else{
            $cartItem = new CartItem();
            $cartItem->setProduct($product);
            $cartItem->setQuantity(1);
            $cartItem->setCart($cart);
            $entityManager->persist($cartItem);
        }
        $entityManager->flush();

        return $this->redirectToRoute('app_cartshow_cart');
    }






    #[Route('/remove/{id}', name: 'remove_from_cart', methods: ['DELETE'])]
    public function removeFromCart(ManagerRegistry $doctrine, int $id)
    {
        $product = $doctrine->getRepository(Product::class)->find($id);
        if (!$product) {
            throw $this->createNotFoundException('Product not found');
        }
        $user=$this->getUser();
        $entityManager = $doctrine->getManager();
        $userId=$user->getId();
        $cart=$doctrine->getRepository(Cart::class)->findOneBy(['user' => $userId]);
        if(!$cart) {
            $cart = new Cart();
            $user = $doctrine->getRepository(User::class)->find($userId);
            $cart->setUser($user);
            $entityManager->persist($cart);
            $entityManager->flush();
        }

        $cartItem = $doctrine->getRepository(CartItem::class)->findOneBy(['product' => $product, 'cart' => $cart]);
        if (!$cartItem) {
            throw $this->createNotFoundException('Cart item not found');
        }
        else {
            $entityManager->remove($cartItem);
        }
        $entityManager->flush();

        return $this->redirectToRoute('app_cartshow_cart');
    }









    #[Route('/changeQuantity/{id}/{quantity}', name: 'change_quantity', methods: ['POST'])]
    public function changeQuantity($id,$quantity,ManagerRegistry $doctrine)
    {
        $entityManager = $doctrine->getManager();
        $product = $doctrine->getRepository(Product::class)->find($id);
        if (!$product) {
            throw $this->createNotFoundException('Product not found');
        }
        $user=$this->getUser();
        $userId=$user->getId();
        $cart=$doctrine->getRepository(Cart::class)->findOneBy(['user' => $userId]);
                if(!$cart) {
                    $cart = new Cart();
                    $user = $doctrine->getRepository(User::class)->find($userId);
                    $cart->setUser($user);
                    $entityManager->persist($cart);
                    $entityManager->flush();
                }


        $cartItem = $doctrine->getRepository(CartItem::class)->findOneBy(['product' => $product, 'cart' => $cart]);


        if($quantity>=1) {
            $cartItem->setQuantity($quantity);
            $entityManager->persist($cartItem);
        }
        $entityManager->flush();

        return new Response("Quantity updated successfully");
    }

}
