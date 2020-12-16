<?php

namespace App\Controller\Order;

use App\Repository\OrderRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class OrderCancelController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }
    /**
     * @Route("/order/cancel/{stripeSessionId}", name="order_cancel")
     */
    public function index($stripeSessionId,OrderRepository $orderRepository): Response
    {
        $order = $orderRepository->findOneBy(['stripeSessionId' => $stripeSessionId]);
        if (!$order || $order->getUser() != $this->getUser()){
            return $this->redirectToRoute('home');
        }

        // envoyez un mailJetApi en cas d'échec
        return $this->render('order_cancel/index.html.twig', [
            'order' => $order
        ]);
    }
}
