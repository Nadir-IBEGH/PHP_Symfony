<?php

namespace App\EventDispatcher;

use App\Event\PurchaseSuccessEvent;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class PurchaseSuccessEmailSubscriber implements EventSubscriberInterface
{

    protected $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public static function getSubscribedEvents()
    {
        return [
            'purchase.success' => 'sendSuccessMail'
        ];
    }

    public function sendSuccessMail(PurchaseSuccessEvent $purchaseSuccessEvent)
    {
        $this->logger->info('Email envoyé pour la commande n° '. $purchaseSuccessEvent->getPurchase()->getId());
    }

}
