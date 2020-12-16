<?php

namespace App\Services;

use Stripe\Checkout\Session;
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

    /**
     * @param $price
     * @return Session
     * @throws \Stripe\Exception\ApiErrorException
     */
    public function checkOut($price)
    {
        return Session::create([
            'payment_method_types' => ['card'],
            'line_items' => [[
                'price' => '20',
                'quantity' => '1'
            ]],
            'mode' => 'subscription',
            'billing_address_collection' => 'required',
            'success_url' => 'http://127.0.0.1:8000/wonboarding/public/payment/successpayment/{CHECKOUT_SESSION_ID}',
            'cancel_url' => 'http://127.0.0.1:8000/wonboarding/public/payment/cancelpayment',
        ]);
    }

    /**
     * @param $email
     * @param $firstName
     * @param $lastName
     * @param $phone
     * @return \Stripe\Customer
     * @throws \Stripe\Exception\ApiErrorException
     */
    public function createCustomer($email, $firstName, $lastName, $phone)
    {
        return $this->stripeClient->customers->create([
            'email' => $email,
            'name' => $firstName . ' ' . $lastName,
            'phone' => $phone
        ]);
    }

    /**
     * @param $customer
     * @return \Stripe\Customer
     * @throws \Stripe\Exception\ApiErrorException
     */
    public function retrieveCustomer($customer)
    {
        return $this->stripeClient->customers->retrieve(
            $customer,
            []
        );
    }


}
