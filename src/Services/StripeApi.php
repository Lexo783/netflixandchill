<?php

namespace App\Services;

use Stripe\Stripe;

class StripeApi
{
    private $stripePublicKey;
    private $stripeSecretKey;
    private $stripeClient;

    function __construct($stripePublicKey, $stripeSecretKey)
    {
        $this->stripePublicKey = $stripePublicKey;
        $this->stripeSecretKey = $stripeSecretKey;
        $this->stripeClient = new \Stripe\StripeClient($this->stripeSecretKey);
        \Stripe\Stripe::setApiKey($this->stripeSecretKey);
    }

    public function intent($user,$lineItem)
    {
        $domaine = 'http://127.0.0.1:8000/';
        return $this->stripeClient->checkout->sessions->create(
            [
                'customer_email' => $user,
                'payment_method_types' => ['card'],
                'line_items' => [
                    $lineItem,
                ],
                'mode' => 'payment',
                'success_url' => $domaine.'order/success/{CHECKOUT_SESSION_ID}',
                'cancel_url' =>  $domaine.'order/cancel/{CHECKOUT_SESSION_ID}',
            ]
        );
    }


}
