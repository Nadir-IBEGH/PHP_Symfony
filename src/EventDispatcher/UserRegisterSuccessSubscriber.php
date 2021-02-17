<?php

namespace App\EventDispatcher;

use App\Entity\User;
use App\Event\RegisterSuccessEvent;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;

class UserRegisterSuccessSubscriber implements EventSubscriberInterface
{

    protected $mailer;

    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    public static function getSubscribedEvents()
    {
        return [
            'register.success' => 'sendSuccessMailRegister'
        ];
    }

    /**
     * @param RegisterSuccessEvent $registerSuccessEvent
     * @throws \Symfony\Component\Mailer\Exception\TransportExceptionInterface
     */
    public function sendSuccessMailRegister(RegisterSuccessEvent $registerSuccessEvent)
    {
        /** @var User $registerUser */
        $registerUser = $registerSuccessEvent->getUser();

        $email = new TemplatedEmail();
        $email
            ->from(new Address($registerUser->getEmail(), $registerUser->getFullName()))
            ->to($registerUser->getEmail())
            ->subject("Bravo, votre incriptiobn a bien été confirmée")
            ->htmlTemplate('emails/register_confirmation.html.twig')
            ->context([
                'user' => $registerUser
            ]);

        $this->mailer->send($email);
    }

}
