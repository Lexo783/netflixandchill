<?php


namespace App\Services;

use Stripe\Stripe;
use Stripe\StripeClient;

class ApiStripe
{
    private $stripePublicKey;
    private $stripeSecretKey;
    private $stripeClient;
    private $databaseMovement;

    /**
     * ApiStripe constructor.
     * @param $stripePublicKey
     * @param $stripeSecretKey
     * @param DatabaseMovement $databaseMovement
     */
    function __construct($stripePublicKey, $stripeSecretKey, DatabaseMovement $databaseMovement)
    {
        $this->stripePublicKey = $stripePublicKey;
        $this->stripeSecretKey = $stripeSecretKey;
        $this->stripeClient = new StripeClient($this->stripeSecretKey);
        \Stripe\Stripe::setApiKey($this->stripeSecretKey);

        $this->databaseMovement = $databaseMovement;
    }

    /// Section Customer

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
     * is the current customer
     * @param $customer
     * @throws \Stripe\Exception\ApiErrorException
     */
    public function retrieveCustomer($customer)
    {
        return $this->stripeClient->customers->retrieve(
            $customer,
            []
        );
    }

    /**
     * @param $customer
     * @param null $name
     * @param null $description
     * @param null $phone
     * @return \Stripe\Customer
     * @throws \Stripe\Exception\ApiErrorException
     */
    public function updateCustomer($customer, $name = null, $description = null, $phone = null )
    {
        return $this->stripeClient->customers->update(
            $customer,
            [
                'name' => $name,
                'description' => $description,
                'phone' => $phone,
            ]);
    }

    /**
     * @param $customer
     * @return \Stripe\Customer
     * @throws \Stripe\Exception\ApiErrorException
     */
    public function deleteCustomer($customer)
    {
        return $this->stripeClient->customers->delete(
            $customer,
            []
        );
    }

    /**
     * @param null $limit
     * @return \Stripe\Collection
     * @throws \Stripe\Exception\ApiErrorException
     */
    public function listAllCustomers($limit = null)
    {
        if($limit)
        {
            return $this->stripeClient->customers->all(['limit' => $limit]);
        }
        else{
            return $this->stripeClient->customers->all();
        }
    }

    /// End Section Customer

    /// Section CheckOut

    /**
     * @return \Stripe\Checkout\Session
     * @throws \Stripe\Exception\ApiErrorException
     */
    public function checkOut($price)
    {
        $user = $this->databaseMovement->getCurrentUserData();
        $checkout = \Stripe\Checkout\Session::create([
            'customer' => $user->getCustomerId(),
            'payment_method_types' => ['card'],
            'line_items' => [[
                // Replace `price_...` with the actual price ID for your subscription
                // you created in step 2 of this guide.
                'price' => $price,
                'quantity' => 1,
            ]],
            'mode' => 'subscription',
            'subscription_data' => [
                'default_tax_rates' => ['txr_1HcotQEvRX2FljL9n9KB6bv9'],
            ],
            'billing_address_collection' => 'required',
            'success_url' => 'http://localhost:8888/wonboarding/public/payment/successpayment/{CHECKOUT_SESSION_ID}',
            'cancel_url' => 'http://localhost:8888/wonboarding/public/payment/cancelpayment',
        ]);
        return $checkout;
    }

    /// End Section CheckOut

    /**
     * @param $sessionId
     * @throws \Stripe\Exception\ApiErrorException
     */
    public function retrieveSession($sessionId)
    {
        return $this->stripeClient->checkout->sessions->retrieve(
            $sessionId,
            []
        );
    }

    /// Section Billing Portal

    /**
     * @return \Stripe\BillingPortal\Session
     * @throws \Stripe\Exception\ApiErrorException
     */
    public function billingPortal($customer)
    {
        return \Stripe\BillingPortal\Session::create([
            'customer' => 'cus_ICKIlFNdCenqTq',
            'return_url' => 'https://example.com/account',
        ]);
    }

    /// End Section Billing Portal

    public function createTaxe(string $display_name, $description, bool $inclusiveBool, float $percentage, string $jurisdiction)
    {
        $this->stripeClient->taxRates->create([
            'display_name' => $display_name,
            'inclusive' => $inclusiveBool,
            'percentage' => $percentage,
            'jurisdiction' => $jurisdiction,
            'description' => $description,
        ]);
    }

    /**
     * @throws \Stripe\Exception\ApiErrorException
     * not working now
     */
    public function updateTaxe()
    {
        $this->stripeClient->taxRates->update(
            'txr_1HcU9sEvRX2FljL9GeKRSsMc',
            ['active' => false]
        );
    }

    /**
     * @param $customer
     * @param $taxeId
     * @throws \Stripe\Exception\ApiErrorException
     */
    public function deleteTaxeCustomer($customer,$taxeId)
    {
        $this->stripeClient->customers->deleteTaxId(
            $customer,
            $taxeId,
            []
        );
    }

    public function listProduct()
    {
        return $this->stripeClient->products->all();
    }

    public function listPrice()
    {
        return $this->stripeClient->prices->all(['product' => 'prod_IC9ph4zKjFYsQY']);
    }
}