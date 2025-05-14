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
        $repo = $doctrine->getRepository(CartItem::class);
        $cartItems = $repo->findAll();
        $products=array();
        $quantity=[];
        $totalPrice=0;
        for ($i = 0; $i < count($cartItems); $i++) {
            $cartItem = $cartItems[$i];
            $totalPrice+=$cartItem->getProduct()->getPrice()*$cartItem->getQuantity();
            $product = $cartItem->getProduct();
            $quantity[$product->getId()]=$cartItem->getQuantity();
            $products[] = $product;
        }
        return $this->render('cart/cart.html.twig', ['products' => $products,'quantity' => $quantity,'totalPrice' => $totalPrice]);
    }
    #[Route('/add/{id}', name: 'add_to_cart',methods: ['POST'])]
    public function addToCart(ManagerRegistry $doctrine,SessionInterface $session, int $id)
    {
        $session->start();
        $session->set('user', 1);
        $entityManager = $doctrine->getManager();
        $product = $doctrine->getRepository(Product::class)->find($id);
        if (!$product) {
            throw $this->createNotFoundException('Product not found');
        }
        $cartId = $session->get('cart');
        $userId=$session->get('user');
        if(!isset($cartId)) {
            $cart = new Cart();
            $user=$doctrine->getRepository(User::class)->find($userId);
            $cart->setUser($user);
            $entityManager->persist($cart);
            $entityManager->flush();
            $cart=$doctrine->getRepository(Cart::class)->findOneBy(['user' => $userId]);
            $cartId=$cart->getId();
            $session->set('cart',$cartId);
        }

        $cart=$doctrine->getRepository(Cart::class)->find($cartId);
        $cartItem = $doctrine->getRepository(CartItem::class)->findOneBy(['product' => $product, 'cart' => $cartId]);
        if(isset($cartItem)) {
            $cartItem->setQuantity($cartItem->getQuantity() + 1);
            $entityManager->persist($cartItem);
        } else {
            $cartItem = new CartItem();
            $cartItem->setProduct($product);
            $cartItem->setQuantity(1);
            $cartItem->setCart($cart);
            $entityManager->persist($cartItem);
        }
        $entityManager->flush();

        return $this->redirectToRoute('app_cartshow_cart');
    }

#[Route('/change_quantity/{typeOfChange}/{id}', name: 'change_quantity')]
public function changeQuantity(string $typeOfChange,int $id,EntityManager $doctrine)
{
    $cartItem = $doctrine->getRepository(CartItem::class)->findOneBy(["product" =>$id]);
  switch($typeOfChange)
  {case "add":
    $cartItem->setQuantity($cartItem->getQuantity() + 1);
    break;
    case "remove":
        if($cartItem->getQuantity()>1)
        {
            $cartItem->setQuantity($cartItem->getQuantity() - 1);
        }
        else
        {
            $doctrine->remove($cartItem);
        }
        break;
  }
    $doctrine->flush();
    return $this->redirectToRoute('app_cart_show_cart');
  }

}
