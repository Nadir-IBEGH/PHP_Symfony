<?php

namespace App\EventDispatcher;

use App\Event\PurchaseSuccessEvent;
use Psr\Log\LoggerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;

class PurchaseSuccessEmailSubscriber implements EventSubscriberInterface
{

    protected $logger;
    protected $mailer;

    public function __construct(LoggerInterface $logger, MailerInterface $mailer)
    {
        $this->logger = $logger;
        $this->mailer = $mailer;
    }

    public static function getSubscribedEvents()
    {
        return [
            'purchase.success' => 'sendSuccessMail'
        ];
    }

    public function sendSuccessMail(PurchaseSuccessEvent $purchaseSuccessEvent)
    {
        $currentUser = $purchaseSuccessEvent->getPurchase()->getUser();
        $purchase = $purchaseSuccessEvent->getPurchase();

        $email = new TemplatedEmail();
        $email
            ->from(new Address($currentUser->getEmail(), $currentUser->getFullName()))
            ->to('contact@gmail.com')
            ->subject("Bravo, votre commande n°  (".$purchase-> getId().") a bien été confirmée")
            ->htmlTemplate('emails/purchase_confirmation.html.twig')
            ->context([
                'purchase' => $purchase,
                'user' => $currentUser
            ]);

        $this->mailer->send($email);
        //  $this->logger->info('Email envoyé pour la commande n° '. $purchaseSuccessEvent->getPurchase()->getId());
    }

}
