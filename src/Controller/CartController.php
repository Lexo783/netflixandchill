<?php

namespace App\Controller;

use App\Services\Cart;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CartController extends AbstractController
{
    private $cart;
    /**
     * CartController constructor.
     */
    public function __construct(Cart $cart)
    {
        $this->cart = $cart;
    }

    /**
     * @Route("/cart", name="cart")
     */
    public function index(): Response
    {
        return $this->render('cart/index.html.twig', [
            'cart' => $this->cart->getFull(),
        ]);
    }

    /**
     * @Route("/cart/add/{id}", name="addCart")
     */
    public function add($id): Response
    {
        $this->cart->addProduct($id);
        return $this->redirectToRoute('cart');
    }

    /**
     * @Route("/cart/remove", name="removeCart")
     */
    public function remove(): Response
    {
        $this->cart->removeCart();
        return $this->redirectToRoute('home');
    }

    /**
     * @Route("/cart/delete/{id}", name="deleteCart")
     */
    public function delete($id): Response
    {
        $this->cart->deleteProduct($id);
        return $this->redirectToRoute('cart');
    }

    /**
     * @Route("/cart/decrease/{id}", name="decreaseCart")
     */
    public function decrease($id): Response
    {
        $this->cart->decreaseProduct($id);
        return $this->redirectToRoute('cart');
    }
}
