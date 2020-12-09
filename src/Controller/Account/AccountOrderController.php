<?php

namespace App\Controller\Account;

use App\Repository\OrderRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AccountOrderController extends AbstractController
{
    /**
     * @Route("/account/order", name="account_order")
     */
    public function index(OrderRepository $orderRepository): Response
    {
        $order = $orderRepository->findSuccessOrders($this->getUser());
        return $this->render('account/order.html.twig', [
            'orders' => $order,
        ]);
    }

    /**
     * @Route("/account/order/{reference}", name="account_order_show")
     */
    public function show($reference,OrderRepository $orderRepository): Response
    {
        $order = $orderRepository->findOneBy(['reference' => $reference]);
        if (!$order || $order->getUser() != $this->getUser()) {
            return $this->redirectToRoute('account_order');
        }

        return $this->render('account/order_show.html.twig', [
            'order' => $order,
        ]);
    }
}
