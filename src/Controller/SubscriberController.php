<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SubscriberController extends AbstractController
{
    #[Route('/subscriber', name: 'subscriber')]
    public function index(ProductRepository $productRepository): Response
    {
        return $this->render('subscriber/index.html.twig', [
            'products' => $productRepository->findAll()
        ]);
    }
}
