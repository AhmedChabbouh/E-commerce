<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mercure\HubInterface;
use Symfony\Component\Mercure\Update;
use Symfony\Component\Routing\Attribute\Route;

final class PublisherController extends AbstractController
{
    #[Route('/publisher', name: 'app_publisher')]
    public function index(HubInterface $hub): Response
    {
        $this->publish($hub);
        return $this->render('publisher/index.html.twig', [
            'controller_name' => 'PublisherController',
        ]);
    }
    public function publish(HubInterface $hub): Response
    {
        $update = new Update(
            "http://localhost:8000/product/1",
            json_encode(['id' => 1, 'name' => 'Product 1'])
        );
        $hub->publish($update);
        return new Response("published");
    }
}
