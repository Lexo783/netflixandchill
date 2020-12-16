<?php

namespace App\Controller;

use App\Repository\OrderRepository;
use App\Services\StripeApi;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class StripeController extends AbstractController
{
    /**
     * @Route("/order/create-session/{reference}", name="create-session-order")
     * @param $reference
     * @param OrderRepository $orderRepository
     * @param EntityManagerInterface $entityManager
     * @param StripeApi $stripeApi
     * @return Response
     */
    public function index($reference,OrderRepository $orderRepository,EntityManagerInterface $entityManager, StripeApi $stripeApi): Response
    {
        $order = $orderRepository->findOneBy(['reference' => $reference]);
        if (!$order){
            $response = new JsonResponse(['error' => 'order']);
            return $response;
        }
        $productStripe = [];
        foreach ($order->getOrderDetails()->getValues() as $product){
            $productStripe[] = [
                'price_data' => [
                    'currency' => 'eur',
                    'unit_amount' => $product->getPrice(),
                    'product_data' => [
                        'name' => $product->getProduct(),
                    ],
                ],
                'quantity' => $product->getQuantity(),
            ];
        }
        $checkoutStripe = $stripeApi->intent($this->getUser()->getEmail(),$productStripe);
        $order->setStripSessionId($checkoutStripe->id);
        $entityManager->flush();

        return new JsonResponse(['id' => $checkoutStripe->id]);
    }
}
