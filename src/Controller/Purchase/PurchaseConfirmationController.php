<?php

namespace App\Controller\Purchase;

use App\Cart\CartService;
use App\Entity\Purchase;
use App\Entity\PurchaseItem;
use App\Form\CartConfirmationType;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class PurchaseConfirmationController extends AbstractController {


    protected $cartService;
    protected $entityManager;

    public function __construct(CartService $cartService, EntityManagerInterface $entityManager){
        $this->cartService = $cartService;
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/purchase/confirm", name="purchase_confirm")
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
        $purchase->setUser($user)
            ->setPurchasedAt(new DateTime())
            ->setTotal($this->cartService->getTotal());
        $this->entityManager->persist($purchase);

        foreach ($this->cartService->getDetailedCartItems() as $cartItem){
            $purchaseItem = new PurchaseItem();
            $purchaseItem->setPurchase($purchase)
                ->setProduct($cartItem->product)
                ->setProductName($cartItem->product->getName())
                ->setQuantity($cartItem->getTotal())
                ->setTotal($cartItem->getTotal())
                ->setProductPrice($cartItem->product->getPrice());
        $this->entityManager->persist($purchaseItem);
        }

        $this->entityManager->flush();
        $this->cartService->empty();

        $this->addFlash('success', "La commande a bien été enregistré");
        return $this->redirectToRoute('purchases_index');
    }













}