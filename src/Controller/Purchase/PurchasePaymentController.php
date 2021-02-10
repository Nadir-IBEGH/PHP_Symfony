<?php

namespace App\Controller\Purchase;

use App\Entity\Purchase;
use App\Repository\PurchaseRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PurchasePaymentController extends AbstractController
{

    /**
     * @Route("/purchase/pay/{id}", name="purchase_payment_form")
     * @param $id
     * @param PurchaseRepository $purchaseRepository
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @throws \Stripe\Exception\ApiErrorException
     */

    public function showCardForm($id, PurchaseRepository $purchaseRepository) {

        /** @var Purchase $purchase */
        $purchase = $purchaseRepository->find($id);

        if(!$purchase){
            return $this->redirectToRoute('cart_show');
        }

        $stripe = [
            "secret_key"      => "sk_test_4eC39HqLyjWDarjtT1zdp7dc",
            "publishable_key" => "pk_test_TYooMQauvdEDq54NiTphI7jx",
        ];

        \Stripe\Stripe::setApiKey($stripe['secret_key']);
        $intent = \Stripe\PaymentIntent::create([
          'amount'=> $purchase->getTotal(),
          'currency' =>'eur'
        ]);

        return $this->render('purchase/payment.html.twig', [
            'clientSecret'=>$intent->client_secret,
            'purchase'=>$purchase
        ]);

    }
}