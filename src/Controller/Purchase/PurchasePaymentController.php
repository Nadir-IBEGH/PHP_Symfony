<?php

namespace App\Controller\Purchase;

use App\Entity\Purchase;
use App\Repository\PurchaseRepository;
use App\Stripe\StripeService;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

class PurchasePaymentController extends AbstractController
{

    /**
     * @Route("/purchase/pay/{id}", name="purchase_payment_form")
     * @IsGranted("ROLE_USER")
     * @param $id
     * @param PurchaseRepository $purchaseRepository
     * @param StripeService $stripeService
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */

    public function showCardForm($id, PurchaseRepository $purchaseRepository, StripeService $stripeService)
    {

        /** @var Purchase $purchase */
        $purchase = $purchaseRepository->find($id);

        if (
            !$purchase ||
            ($purchase && $purchase->getUser() != $this->getUser()) ||
            Purchase::STATUS_PEND == $purchase->getStatus()
        ) {
            return $this->redirectToRoute('cart_show');
        }


        $intent = $stripeService->getPaymentIntent($purchase);

        return $this->render('purchase/payment.html.twig', [
            'clientSecret' => $intent->client_secret,
            'purchase' => $purchase
        ]);

    }
}