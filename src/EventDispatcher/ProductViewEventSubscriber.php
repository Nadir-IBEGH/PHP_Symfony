<?php

namespace App\EventDispatcher;

use App\Event\ProductViewEvent;
use Psr\Log\LoggerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email;

class ProductViewEventSubscriber implements EventSubscriberInterface
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
            'product.view' => 'showProductMail'
        ];
    }


    /**
     * @param ProductViewEvent $event
     */
    public function showProductMail(ProductViewEvent $event)
    {
 /*       $email = new TemplatedEmail();
        $email
            ->from(new Address("contact@gmail.com", "Infos de la boutique"))
            ->to("admin@gmail.com")
            ->text("Un visiteur entrain de voir le produit n° " . $event->getProduct()->getId())
            ->htmlTemplate('emails/product_view.html.twig')
            ->subject("Visite du produit n° " . $event->getProduct()->getId())
            ->context(['product' => $event->getProduct()]);

        $this->mailer->send($email);*/
    }

}