<?php

namespace App\Stripe;

use App\Entity\Purchase;

class StripeService
{

    protected $publicKey;
    protected $secretKey;

    public function __construct(string $secretKey, string $publicKey)
    {
        $this->secretKey=$secretKey;
        $this->publicKey=$publicKey;
    }

    public function getPaymentIntent(Purchase $purchase)
    {
  /*      dd($this->secretKey);

        $this->publicKey = 'pk_test_TYooMQauvdEDq54NiTphI7jx';
        $this->secretKey ='sk_test_4eC39HqLyjWDarjtT1zdp7dc';*/


        \Stripe\Stripe::setApiKey($this->secretKey);
        $intent = \Stripe\PaymentIntent::create([
            'amount' => $purchase->getTotal(),
            'currency' => 'eur'
        ]);
        return $intent;
    }
}