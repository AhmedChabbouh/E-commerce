<?php

namespace App\Controller;

use App\Entity\Address;
use App\Form\AddressTypeForm;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class AddressController extends AbstractController
{
    #[Route('/address/{cartId}', name: 'address')]
    public function index(Request $request, EntityManagerInterface $entityManager,int $cartId): Response
    {
        $address = new Address();

        $form = $this->createForm(AddressTypeForm::class, $address);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($address);
            $entityManager->flush();
            $this->addFlash('success', 'Address successfully added!');
            return $this->redirectToRoute('app_address');
        }

        // Render the form
        return $this->render('address/index.html.twig', [
            'form' => $form->createView(),
            'controller_name' => 'AddressController',
            'cartId' => $cartId
        ]);

    }
}
