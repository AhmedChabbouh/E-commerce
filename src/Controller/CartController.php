<?php

namespace App\Controller;

use App\Entity\Cart;
use App\Entity\CartItem;
use App\Entity\Product;
use Doctrine\Persistence\ManagerRegistry;
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
        for ($i = 0; $i < count($cartItems); $i++) {
            $cartItem = $cartItems[$i];
            $product = $doctrine->getRepository(Product::class)->find($cartItem->getProductId());
            $products += $product;
        }
        return $this->render('cart/cart.html.twig', ['products' => $products]);
    }
    #[Route('/add/{id}', name: 'add_to_cart')]
    public function addToCart(ManagerRegistry $doctrine,SessionInterface $session, int $id)
    {
        $entityManager = $doctrine->getManager();
        $product = $doctrine->getRepository(Product::class)->find($id);
        if (!$product) {
            throw $this->createNotFoundException('Product not found');
        }
        $cartId = $session->get('cart',[]);
        if(!isset($cartId)) {
            $cart = new Cart();
            $cartId = $cart->getId();
        }
        $cart=$doctrine->getRepository(Cart::class)->find($cartId);
        $cartItem = $doctrine->getRepository(CartItem::class)->findOneBy(['product' => $product, 'cart' => $cartId]);
        if(isset($cartItem)) {
            $cartItem->setQuantity($cartItem->getQuantity() + 1);
            $entityManager->persist($cartItem);
        } else {
            $cartItem = new CartItem();
            $cartItem->setProduct($product);
            $cartItem->setCart($cart);
            $entityManager->persist($cartItem);
        }
        $entityManager->flush();

        return $this->redirectToRoute('/show_cart');
    }
}
