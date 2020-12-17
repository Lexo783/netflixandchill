<?php

namespace App\Controller\Order;

use App\Entity\Order;
use App\Entity\OrderDetails;
use App\Form\OrderType;
use App\Services\Cart;
use App\Services\StripeApi;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class OrderController extends AbstractController
{
    private $entityManager;
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/order", name="order")
     */
    public function index(Cart $cart): Response
    {
        if(!$this->getUser()->getAddresses()->getValues())
        {
            return $this->redirectToRoute('add_account_address');
        }
        if(!$cart->getFull())
        {
            return $this->redirectToRoute('subscriber');
        }

        $form = $this->createForm(OrderType::class, null, [
            'user' => $this->getUser(),
        ]);

        return $this->render('order/index.html.twig', [
            'form' => $form->createView(),
            'cart' => $cart->getFull(),
        ]);
    }

    /**
     * @Route("/order/add", name="order-show", methods={"POST"})
     */
    public function add(Cart $cart, Request $request, StripeApi $api): Response
    {

        $form = $this->createForm(OrderType::class, null, [
            'user' => $this->getUser(),
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $date = new \DateTime();
            //on stock dans une variable l'adresse de livraison
            $delivery = $form->get('addresses')->getData();
            $delivery_content = $delivery->getFirstName().' '.$delivery->getLastName();
            $delivery_content .= '<br/>'.$delivery->getPhone();
            if ($delivery->getCompany()){
                $delivery_content .= '<br/>'.$delivery->getCompany();
            }
            $delivery_content .= '<br/>'.$delivery->getAddress();
            $delivery_content .= '<br/>'.$delivery->getPostal().' '.$delivery->getCity();
            $delivery_content .= '<br/>'.$delivery->getCountry();


            $order = new Order();
            $reference = $date->format('dmY').'-'.uniqid();
            $order->setReference($reference);
            $order->setUser($this->getUser());
            $order->setCreatedAt($date);
            $order->setDelivery($delivery_content);
            $order->setIsPayed(false);

            $this->entityManager->persist($order);

            foreach ($cart->getFull() as $product){
                $orderDetails = new OrderDetails();
                $orderDetails->setOrderId($order);
                $orderDetails->setProduct($product['product']->getName());
                $orderDetails->setQuantity($product['quantity']);
                $orderDetails->setPrice($product['product']->getPrice());
                $orderDetails->setTotal($product['product']->getPrice() * $product['quantity']);

                $this->entityManager->persist($orderDetails);
            }
            $this->entityManager->flush();

            return $this->render('order/show.html.twig', [
                'cart' => $cart->getFull(),
                'delivery' => $delivery_content,
                'reference' => $order->getReference(),
            ]);
        }
        return $this->redirectToRoute('cart');
    }
}
