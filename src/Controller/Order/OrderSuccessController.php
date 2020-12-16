<?php

namespace App\Controller\Order;

use App\Repository\OrderRepository;
use App\Services\Cart;
use App\Services\MailJetApi;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class OrderSuccessController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/order/success/{stripeSessionId}", name="order_success")
     *
     * @param $stripeSessionId
     * @param OrderRepository $orderRepository
     * @param Cart $cart
     * @param MailJetApi $mailJetApi
     * @return Response
     */
    public function index($stripeSessionId, OrderRepository $orderRepository,Cart $cart,MailJetApi $mailJetApi): Response
    {
        $order = $orderRepository->findOneBy(['stripeSessionId' => $stripeSessionId]);
        if (!$order || $order->getUser() != $this->getUser()){
            return $this->redirectToRoute('home');
        }

        if(!$order->getIsPaid())
        {
            //vider le panier
            $cart->removeCart();
            //modifier le status
            $order->setIsPaid(true);
            $this->entityManager->flush();

            //Send email for customer
            $contentCustomer = "Bonjour ".$order->getUser()->getFirstName()."<br/> Merci de votre commande";
            $mailJetApi->send($order->getUser()->getEmail(),$order->getUser()->getFirstName(),'Votre commande est validé',$contentCustomer);

            /*
            // send email for the admin
            $contentAdmin = "Bonjour vous avez une nouvelle demande d'accés a cet email <br/>".$order->getUser()->getEmail();
            $mailJetApi->send($order->getUser()->getEmail(),$order->getUser()->getFirstName(),'Votre commande est validé',$contentCustomer);
            */

        }



        return $this->render('order_success/index.html.twig', [
            'order' => $order
        ]);
    }
}
