<?php

namespace App\Controller\Purchase;

use App\Cart\CartService;
use App\Entity\Purchase;
use App\Purchase\PurchasePersister;
use App\Entity\PurchaseItem;
use App\Form\CartConfirmationType;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

class PurchaseConfirmationController extends AbstractController {


    protected $cartService;
    protected $entityManager;
    protected $purchasePersister;

    /**
     * PurchaseConfirmationController constructor.
     * @param CartService $cartService
     * @param EntityManagerInterface $entityManager
     * @param PurchasePersister $purchasePersister
     */
    public function __construct(CartService $cartService, EntityManagerInterface $entityManager, PurchasePersister $purchasePersister){
        $this->cartService = $cartService;
        $this->entityManager = $entityManager;
        $this->purchasePersister = $purchasePersister;
    }
/** @IsGranted("USER_ROLE", message="Vous devez être connecté pour confirmer votre commande")
*/
    /**
     * @Route("/purchase/confirm", name="purchase_confirm")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function confirm(Request $request){

        $form = $this->createForm(CartConfirmationType::class);
        $form->handleRequest($request);

        if(!$form->isSubmitted()){
            $this->addFlash('warning', "Vous devez remplir le formulaire de confirmation");
            $this->redirectToRoute('cart_show');
        }
        $user = $this->getUser();

        $cartItemps = $this->cartService->getDetailedCartItems();

        if(count($cartItemps) ===0 ){
            $this->addFlash('warning', 'Vous ne pouvez pas confirmer un panier 
            avec une commande vide');
            return $this->redirectToRoute('cart_show');
        }

        /** @var Purchase $purchase */
        $purchase = $form->getData();
        $this->purchasePersister->storePurchase($purchase);

  /*      $this->cartService->empty();

        $this->addFlash('success', "La commande a bien été enregistré");*/
        return $this->redirectToRoute('purchase_payment_form',
        [
            'id'=> $purchase->getId()
        ]);
    }













}