<?php

namespace App\Controller\Purchase;

use App\Cart\CartService;
use App\Entity\Purchase;
use App\Event\PurchaseSuccessEvent;
use App\Repository\PurchaseRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

class PurchasePaymentSuccessController extends AbstractController
{


    /**
     * @Route("/purchase/terminate/{id}", name="purchase_payment_success")
     * @IsGranted("ROLE_USER")
     * @param $id
     * @param PurchaseRepository $purchaseRepository
     * @param EntityManagerInterface $entityManager
     * @param CartService $carteService
     * @param EventDispatcherInterface $dispatcher
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function success(
        $id,
        PurchaseRepository $purchaseRepository,
        EntityManagerInterface $entityManager,
        CartService $carteService,
        EventDispatcherInterface $dispatcher
    )
    {

        $purchase = $purchaseRepository->find($id);
        if (
            !$purchase ||
            ($purchase && $purchase->getUser() != $this->getUser()) ||
            $purchase::STATUS_PEND == $purchase->getStatus()
        ) {
            $this->addFlash('warning', "La commande n'existe pas");
            return $this->redirectToRoute('purchases_index');
        }

        $purchase->setStatus(Purchase::STATUS_PEND);
        $entityManager->flush();
        $carteService->empty();

        $purchaseEvent = new PurchaseSuccessEvent($purchase);
        $dispatcher->dispatch($purchaseEvent, 'purchase.success');

        $this->addFlash('success', 'La commande a été payée et confirmée !');
        return $this->redirectToRoute('purchases_index');

    }

}
