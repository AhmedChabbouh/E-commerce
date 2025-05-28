<?php

namespace App\Controller;

use App\Entity\Cart;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use function Symfony\Component\DependencyInjection\Loader\Configurator\env;

final class PaymentController extends AbstractController
{
    #[Route('/payment/{cartId}', name: 'app_payment')]
    public function index(int $cartId,EntityManagerInterface $em): Response
    {
        $cart = $em->getRepository(Cart::class)->find($cartId);
        if(!$cart){
            throw $this->createNotFoundException('cart not found');
        }
        $items = [];
        foreach($cart->getCartItems() as $cartItem){
            $product = $cartItem->getProduct();
            $items[] = ['item_quantity' => $cartItem->getQuantity(), 'product_name' => $product->getName()
            ,'product_price' => $product->getPrice()
            ,'product_image' => $product->getImage()
            ];
        }
        return $this->render('payment/index.html.twig', [
            'cart' => $cart,'items'=>$items
        ]);
    }

    #[Route('/checkout/{cartId}', name: 'checkout', methods:'GET')]
    public function checkout(int $cartId,$stripeSK, EntityManagerInterface $em): Response
    {
        $cart = $em->getRepository(Cart::class)->find($cartId);
        if(!$cart){
            throw $this->createNotFoundException('cart is not found');
        }
        $items = [];
        foreach($cart->getCartItems() as $cartItem){
            $product = $cartItem->getProduct();
            $items[] = [
                'price_data' => [
                    'currency' => 'usd',
                    'product_data' => [
                        'name' => $product->getName(),
                        'description' => $product->getDescription(),
                        'image' => $product->getImage(),
                    ],
                    'unit_amount' => $product->getStockQuantity(),
                ],
                'quantity' => $cartItem->getQuantity(),
            ];
        }
        \Stripe\Stripe::setApiKey($stripeSK);
        $session = \Stripe\Checkout\Session::create([
            'line_items' => $items,
            'mode' => 'payment',
            'success_url' => $this->generateUrl('success_url', [], UrlGeneratorInterface::ABSOLUTE_URL),
            'cancel_url' => $this->generateUrl('cancel_url', [], UrlGeneratorInterface::ABSOLUTE_URL),
        ]);


        return $this->redirect($session->url,303);


    }

    #[Route('/success-url', name: 'success_url')]
    public function successUrl(): Response
    {
        return $this->render('payment/success.html.twig', []);
    }

    #[Route('/cancel-url', name: 'cancel_url')]
    public function failureUrl(): Response
    {
        return $this->render('payment/failure.html.twig', []);
    }

}
