<?php


namespace App\Services;


use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class Cart
{
    private $session;
    private $productRepository;
    /**
     * Cart constructor.
     */
    public function __construct(SessionInterface $session,ProductRepository $productRepository)
    {
        $this->session = $session;
        $this->productRepository = $productRepository;
    }

    public function getCart()
    {
        return $this->session->get('cart',[]);
    }

    public function getFull()
    {
        $cartComplete = [];
        foreach ($this->getCart() as $key => $quantity)
        {
            $product = $this->productRepository->findOneBy(['id' => $key]);
            if (!$product)
            {
                $this->deleteProduct($key);
                continue;
            }
            $cartComplete[] = [
                'product' => $product,
                'quantity' => $quantity
            ];
        }
        return $cartComplete;
    }

    public function addProduct($id)
    {
        $cart = $this->session->get('cart', []);
        if (!empty($cart[$id])) {
            $cart[$id]++;
        }
        else{
            $cart[$id] = 1;
        }
        $this->session->set('cart',$cart);
    }

    public function decreaseProduct($id)
    {
        $cart = $this->session->get('cart', []);
        if ($cart[$id] > 1)
        {
            $cart[$id]--;
        }
        else{
            unset($cart[$id]);
        }
    }

    public function removeCart(){
        $this->session->remove('cart');
    }

    public function deleteProduct($id)
    {
        $cart = $this->session->get('cart', []);
        unset($cart[$id]);
        return $this->session->set('cart',$cart);
    }
}
